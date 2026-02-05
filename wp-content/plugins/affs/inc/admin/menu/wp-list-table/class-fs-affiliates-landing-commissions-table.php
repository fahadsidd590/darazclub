<?php

/**
 * Landing Commissions Post Table
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Landing_Commissions_Post_Table')) {

	/**
	 * FS_Affiliates_Landing_Commissions_Post_Table Class.
	 * 
	 * @since 1.0.0
	 * */
	class FS_Affiliates_Landing_Commissions_Post_Table extends FS_Affiliates_List_Table {

		/**
		 * Order BY
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $orderby = 'ORDER BY ID DESC';

		/**
		 * Post type
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $post_type = 'fs-landingcommission';
		
		/**
		 * Prepare the table Data to display table based on pagination.
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'landing_commissions' ), admin_url('admin.php'));
		   
			parent::prepare_items();
					   
			$this->get_current_page_items();     
		}
			  
		/**
		 * Initialize the columns
		 * 
		 * @since 1.0.0
		 * */
		public function get_columns() {
			$columns = array(
				'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
				'shortcode' => __('Shortcode', FS_AFFILIATES_LOCALE),
				'commission_value' => __('Commission Value', FS_AFFILIATES_LOCALE),
				'status' => __('Status', FS_AFFILIATES_LOCALE),
				'usage_count' => esc_html__('Usage / Limit', FS_AFFILIATES_LOCALE),
				'date' => __('Created date', FS_AFFILIATES_LOCALE),
			);

			return $columns;
		}

		/**
		 * Initialize the hidden columns
		 * 
		 * @since 1.0.0
		 * */
		public function get_hidden_columns() {
			return array();
		}

		/**
		 * Initialize the bulk actions
		 * 
		 * @since 1.0.0
		 * */
		protected function get_bulk_actions() {
			$action = array();
			$action['fs_active'] = __('Active', FS_AFFILIATES_LOCALE);
			$action['fs_inactive'] = __('Inactive', FS_AFFILIATES_LOCALE);

			return $action;
		}

		/**
		 * Display the list of views available on this table.
		 * 
		 * @since 1.0.0
		 * */
		public function get_views() {
			$args = array();
			$status_link = array();

			$status_link_array = array(
				'' => __('All', FS_AFFILIATES_LOCALE),
				'fs_active' => __('Active', FS_AFFILIATES_LOCALE),
				'fs_inactive' => __('Inactive', FS_AFFILIATES_LOCALE),
			);

			foreach ($status_link_array as $status_name => $status_label) {
				$status_count = $this->get_total_item_for_status($status_name);

				if (!$status_count) {
					continue;
				}

				if ($status_name) {
					$args['status'] = $status_name;
				}

				if (isset($_REQUEST['s'])) {
					$args['s'] = wc_clean(wp_unslash($_REQUEST['s']));
				}

				$label = $status_label . ' (' . $status_count . ')';
				$class = ( isset($_GET['status']) && $_GET['status'] == $status_name ) ? 'current' : '';
				$class = ( !isset($_GET['status']) && '' == $status_name ) ? 'current' : $class;
				$status_link[$status_name] = $this->get_edit_link($args, $label, $class);
			}

			return $status_link;
		}

		/**
		 * Edit link for status 
		 * 
		 * @param string $args
		 * @param string label
		 * @param string $class
		 * @since 1.0.0
		 * */
		private function get_edit_link( $args, $label, $class = '' ) {
			$url = add_query_arg($args, $this->base_url);
			$class_html = '';
			if (!empty($class)) {
				$class_html = sprintf(
						' class="%s"', esc_attr($class)
				);
			}

			return sprintf(
					'<a href="%s"%s>%s</a>', esc_url($url), $class_html, $label
			);
		}
		
		/**
		 * Prepare shortcode column data
		 * 
		 * @param string $item
		 * @since 1.0.0
		 * */
		protected function column_shortcode( $item ) {
			$actions['edit'] = sprintf('<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __('Edit', FS_AFFILIATES_LOCALE) . '</a>', 'edit', $item->get_id());
			$actions ['delete'] = sprintf('<a class="fs_affiliates_delete" data-type="landingcommission" href="' . $this->current_url . '&action=%s&id=%s">' . __('Delete', FS_AFFILIATES_LOCALE) . '</a>', 'delete', $item->get_id());

			return sprintf('%1$s %2$s',
					/* $1%s */ "[fs-landing-commission id='" . $item->get_id() . "']",
					/* $2%s */ $this->row_actions($actions)
			);
		}       

		/**
		 * Prepare each column data
		 * 
		 * @param string $item
		 * @param string $column_name
		 * @since 1.0.0
		 * */
		protected function column_default( $item, $column_name ) {
			switch ($column_name) {
				case 'commission_value':
					return $item->commission_value;
					break;
				case 'status':
					return fs_affiliates_get_status_display($item->get_status());
					break;
				case 'usage_count':
					if ('2' == $item->usage_type) {
						$used_count = !empty(get_post_meta($item->get_id(), 'fs_affs_lc_used_count', true)) ? get_post_meta($item->get_id(), 'fs_affs_lc_used_count', true) : 0;
						return $used_count . ' / ' . $item->validity_count;
					} else {
						return '-';
					}
					break;
				case 'date':
					return fs_affiliates_local_datetime($item->date);
					break;
			}
		}

		/**
		 * Prepare item Object
		 * 
		 * @param array $items
		 * @since 1.0.0
		 * */
		public function prepare_item_object( $items ) {
			$prepare_items = array();
			if (fs_affiliates_check_is_array($items)) {
				foreach ($items as $item) {
					$prepare_items[] = new FS_Affiliates_Landing_Commission($item['ID']);
				}
			}

			$this->items = $prepare_items;
		}

		/**
		 * get total item from status
		 * 
		 * @param string $status
		 * @since 1.0.0
		 * */
		private function get_total_item_for_status( $status = '' ) {
			global $wpdb;
			$where = "WHERE post_type='" . $this->post_type . "' and post_status";
			$status = ( $status == '' ) ? "NOT IN('trash')" : "IN('" . $status . "')";
			$data = $wpdb->get_results('SELECT ID FROM ' . $wpdb->posts . " $where $status", ARRAY_A);

			return count($data);
		}
	}

}
