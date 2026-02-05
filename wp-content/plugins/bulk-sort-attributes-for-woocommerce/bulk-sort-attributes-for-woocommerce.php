<?php
	/*
		Plugin Name: Bulk Sort Attributes for WooCommerce
		Description: Bulk sort WooCommerce attributes when they are too numerous for custom sorting by hand.
		Version: 1.2
		Author: Inbound Horizons
		Author URI: https://www.inboundhorizons.com
		License: GPLv2 or later
		Requires Plugins: woocommerce
	*/


	if (!defined('ABSPATH')) {
		exit; // Exit if accessed directly
	}
	
	class Bulk_Sort_Attributes_For_WooCommerce {
		
		private static $_instance = null;	// Get the static instance variable
		
		public static function Instantiate() {
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		private function __construct() {
			if (is_admin()) {
				add_action('woocommerce_init', array($this, 'WooCommerceHooks'));
			}
		}
		
		public function WooCommerceHooks() {
			
			// Ensure that the user has permission to be here
			if (!current_user_can('manage_product_terms') && !current_user_can('manage_woocommerce')) {
				return;
			}
			
			// Get all attribute taxonomies
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			
			// Check to make sure the $attribute_taxonomies is an array
			if (!empty($attribute_taxonomies) && is_array($attribute_taxonomies)) {
			
				// Loop over the attributes
				foreach ($attribute_taxonomies as $attribute) {
				
					// Set a hook for every attribute taxonomy to display the sorting dropdown
					add_filter('views_edit-' . 'pa_' . $attribute->attribute_name, function($views) use ($attribute) {
						$this->OutputSortingButtonsHtml($attribute);
						return $views;
					});
				}
			}
			
			// Listen for POST data
			add_action('admin_post_BSAFW_SORT_ATTRIBUTES', array($this, 'AJAX_CustomOrderWooCommerceAttributes'));
		}
		
		public function AJAX_CustomOrderWooCommerceAttributes() {
		
			// Ensure that the user has permission to be here
			if (!current_user_can('manage_product_terms') && !current_user_can('manage_woocommerce')) {
				wp_die(esc_html__('Insufficient permissions.', 'bulk-sort-attributes-for-woocommerce'), 403);
			}
			
			// Validate the nonce
			check_admin_referer('bsafw-sort-attributes', 'bsafw_nonce');
			
			// Get the redirect URL
			$redirect_url = wp_get_referer();
			
			// Get and sanitize POSTed data
			$taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
			$bsafw_sorting = isset($_POST['bsafw_sorting']) ? sanitize_text_field(wp_unslash($_POST['bsafw_sorting'])) : '';

			// Split the POSTed data into a sort by name and direction
			$sorting_array = explode(" ", $bsafw_sorting);

			// Get the sort by and direction
			$sort_by = isset($sorting_array[0]) ? strtolower($sorting_array[0]) : '';
			$direction = isset($sorting_array[1]) ? strtolower($sorting_array[1]) : '';

			// Sort the attribute terms
			$this->CustomOrderWooAttributes($taxonomy, $sort_by, $direction);
			
			wp_safe_redirect($redirect_url);
			exit;
		}
		
		public function OutputSortingButtonsHtml($attribute) {
		
			// Get information about the attribute
			$att_orderby = $attribute->attribute_orderby;
			$taxonomy = 'pa_' . $attribute->attribute_name;
			
			// Check if we can customize the ordering of attribute terms
			$is_custom_ordering = ($att_orderby === 'menu_order');
			
			// Wrap everything in a <div>
			echo '<div>';
			
			if ($is_custom_ordering) {
			
				echo '
					<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">
				';
				
				// Output the nonce field
				wp_nonce_field('bsafw-sort-attributes', 'bsafw_nonce', true);
				
				echo '
						<input type="hidden" name="action" value="BSAFW_SORT_ATTRIBUTES" />
						<input type="hidden" name="taxonomy" value="'.esc_attr($taxonomy).'" />
						
						<select name="bsafw_sorting" required>
							<option value="" selected disabled>- Bulk Sort Attributes for WooCommerce -</option>
							
							<optgroup label="Sort By: ID">
								<option value="id asc">ID - ASC</option>
								<option value="id desc">ID - DESC</option>
							</optgroup>
							
							<optgroup label="Sort By: Name">
								<option value="name asc">Name - ASC</option>
								<option value="name desc">Name - DESC</option>
							</optgroup>
							
							<optgroup label="Sort By: Name (Numeric)">
								<option value="name_num asc">Name (Numeric) - ASC</option>
								<option value="name_num desc">Name (Numeric) - DESC</option>
							</optgroup>
						</select>
						
						<button type="submit" class="button button-secondary">
							Bulk Sort Attributes
						</button>
					</form>
				';
			}
			else {
				echo '
					<div class="notice notice-warning is-dismissible inline">
						<p>
							<strong>'.esc_html__('Bulk Sort Attributes is Unavailable', 'bulk-sort-attributes-for-woocommerce').'</strong>: 
							Edit this attribute and set the default sort order to <code>' . esc_html__('Custom ordering', 'bulk-sort-attributes-for-woocommerce') . '</code> to enable bulk sorting.
							
						</p>
					</div>
				';
			}
			
			// End the wrapping <div>
			echo '</div>';
		}
		
		public function CustomOrderWooAttributes($taxonomy, $sort = '', $direction = 'asc') {
			
			// Normalize the sort and direction
			$sort = strtolower($sort);
			$direction = strtolower($direction);
			
			// Define the white-listed sorts and directions
			$valid_sorts = array(
				'id',
				'name',
				'name_num',
			);
			
			$valid_sort_directions = array(
				'asc',
				'desc',
			);
			
			// Check if the sort and direction are valid
			if (in_array($sort, $valid_sorts, true) && in_array($direction, $valid_sort_directions, true)) {
				
				// Ensure that the taxonomy is a product attribute
				if (!taxonomy_exists($taxonomy) || strpos($taxonomy, 'pa_') !== 0) {
					return;
				}
			
				// Get the terms in an array
				$terms = get_terms(array(
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
				));
				
				// Check if the terms are valid
				if (!is_wp_error($terms) && !empty($terms)) {
			
					// Sort the attribute terms
					usort($terms, function($a, $b) use($sort, $direction) {
					
						// Default to assuming we are sorting by 'id' (term ID)
						$comparator = (intval($a->term_id) < intval($b->term_id)) ? -1 : 1;
						if (intval($a->term_id) === intval($b->term_id)) {
							$comparator = 0;
						}
					
						if ($sort === 'name') {
							$comparator = strcmp($a->name, $b->name);
						}
						else if ($sort === 'name_num') {
							$comparator = (intval($a->name) < intval($b->name)) ? -1 : 1;
							if (intval($a->name) === intval($b->name)) {
								$comparator = 0;
							}
						}
						
						// Return the results based on the direction
						return ($direction === 'desc') ? -$comparator : $comparator;
					});
					
					// Commit the new order to the database
					foreach ($terms as $index => $term) {
						$term_id = intval($term->term_id);
						wc_set_term_order($term_id, $index, $taxonomy);
					}
					
				}
			}
			
			
		}

	
	}
	
	Bulk_Sort_Attributes_For_WooCommerce::Instantiate();	// Instantiate an instance of the class
	
	