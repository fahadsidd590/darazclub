<?php

/**
 * Affiliates Post Table
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_URL_Masking_Post_Table')) {

	/**
	 * FS_URL_Masking_Post_Table Class.
	 * 
	 * @since 1.0.0
	 * */
	class FS_URL_Masking_Post_Table extends FS_Affiliates_List_Table {
	 
		/**
		 * Post type
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $post_type = 'fs-url-masking';

		/**
		 * Prepare the table Data to display table based on pagination.
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'url_masking' ), admin_url('admin.php'));
		   
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
				'ID' => __('Affiliate ID', FS_AFFILIATES_LOCALE),
				'affs_name' => __('Affiliate Name', FS_AFFILIATES_LOCALE),
				'domain' => __('Domain', FS_AFFILIATES_LOCALE),
				'visits' => __('Visits', FS_AFFILIATES_LOCALE),
				'status' => __('Status', FS_AFFILIATES_LOCALE),
				'date' => __('Date', FS_AFFILIATES_LOCALE),
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
				'fs_pending_approval' => __('Pending Approval', FS_AFFILIATES_LOCALE),
				'fs_suspended' => __('Suspended', FS_AFFILIATES_LOCALE),
				'fs_rejected' => __('Rejected', FS_AFFILIATES_LOCALE),
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
		 * @param string $label
		 * @param string $class
		 * 
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
		 * add row actions
		 * 
		 * @param string $item
		 * @since 1.0.0
		 * */
		public function column_ID( $item ) {
			$actions = array();

			$actions['edit'] = sprintf('<a href="' . $this->base_url . '&subsection=%s&um_key=%s">' . __('Edit', FS_AFFILIATES_LOCALE) . '</a>', 'edit', $item->get_id());
			$actions ['delete'] = sprintf('<a class="fs_affiliates_delete" data-type="affiliate" href="' . $this->current_url . '&action=%s&id=%s">' . __('Delete', FS_AFFILIATES_LOCALE) . '</a>', 'delete', $item->get_id());

			//Return the title contents
			return sprintf('%1$s %2$s',
					/* $1%s */ '#' . $item->get_id(),
					/* $3%s */ $this->row_actions($actions)
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
				case 'affs_id':
					return $item->affs_id;
					break;
				case 'affs_name':
					$affs_data = new FS_Affiliates_Data($item->affs_id);
					$first_name = isset($affs_data->first_name) ? $affs_data->first_name : ' - ';
					$last_name = isset($affs_data->last_name) ? $affs_data->last_name : '';
					return $first_name . '' . $last_name;
					break;
				case 'domain':
					return $item->url_masking_domain;
					break;
				case 'visits':
					return (int) $item->domain_visit_count;
					break;
				case 'status':
					return fs_affiliates_get_status_display($item->get_status());
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
					$prepare_items[] = new FS_URL_Masking_Data($item['ID']);
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

		/**
		 * Search Functionality
		 * 
		 * @param string $where
		 * @since 1.0.0
		 * */
		public function custom_search( $where ) {
			global $wpdb;
			if (isset($_REQUEST['s'])) {
				$search_ids = array();
				$terms = explode(',', $_REQUEST['s']);

				foreach ($terms as $term) {
					$term = $wpdb->esc_like($term);
					$meta_array = array(
						'first_name',
						'last_name',
						'email',
					);
					$implode_array = implode("','", $meta_array);
					if (isset($_GET['post_status']) && $_GET['post_status'] != 'all') {
						$post_status = $_GET['post_status'];
					} else {
						$post_status_array = array( 'fs_active', 'fs_inactive', 'fs_rejected', 'fs_suspended', 'fs_pending_approval' );
						$post_status = implode("','", $post_status_array);
					}

					$search_ids = $wpdb->get_col($wpdb->prepare(
									"SELECT DISTINCT ID FROM {$wpdb->posts} as p "
									. "INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id "
									. "WHERE p.post_type=%s AND p.post_status IN ('$post_status') AND (("
									. "pm.meta_key IN ('$implode_array') "
									. 'AND pm.meta_value LIKE %s) OR (p.ID LIKE %s) OR (p.post_title LIKE %s))', $this->post_type, '%' . $term . '%', '%' . $term . '%', '%' . $term . '%')
					);
				}

				$search_ids = array_filter(array_unique(array_map('absint', $search_ids)));

				$search_ids = fs_affiliates_check_is_array($search_ids) ? $search_ids : array( 0 );

				$where .= " AND ({$wpdb->posts}.ID IN (" . implode(',', $search_ids) . '))';
			}

			return $where;
		}
	}

}
