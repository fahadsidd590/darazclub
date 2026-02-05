<?php

/**
 * Affiliates Payouts Post Table
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Payouts_Batch_Post_Table')) {

	/**
	 * FS_Affiliates_Payouts_Batch_Post_Table Class.
	 * 
	 * @since 1.0.0
	 * */
	class FS_Affiliates_Payouts_Batch_Post_Table extends FS_Affiliates_List_Table {

		/**
		 * Post type
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $post_type = 'fs-payouts-batch';
				 
		/**
		 * Prepare the table Data to display table based on pagination.
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'payouts' ), admin_url('admin.php'));

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
				'batch_id' => __('PayPal Payouts Batch ID', FS_AFFILIATES_LOCALE),
				'status' => __('Status', FS_AFFILIATES_LOCALE),
				'check_status' => __('Check Payout Status', FS_AFFILIATES_LOCALE),
				'date' => __('Date', FS_AFFILIATES_LOCALE),
					);

			return $columns;
		}

		/**
		 * Initialize the sortable columns
		 * 
		 * @since 1.0.0
		 * */
		public function get_sortable_columns() {
			return array(
				'batch_id' => array( 'batch_id', false ),
				'status' => array( 'status', false ),
				'date' => array( 'date', false ),
					);
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
			if (isset($_GET['status']) && $_GET['status'] == 'trash') {
				$action['restore'] = __('Restore', FS_AFFILIATES_LOCALE);
				$action['delete'] = __('Delete', FS_AFFILIATES_LOCALE);
			} else {
				$action['trash'] = __('Move to Trash', FS_AFFILIATES_LOCALE);
			}

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
				'fs_paid' => __('Paid', FS_AFFILIATES_LOCALE),
				'trash' => __('Trash', FS_AFFILIATES_LOCALE),
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
			if (isset($_GET['status']) && $_GET['status'] == 'trash') {
				$actions = array(
					'delete' => sprintf('<a href="' . $this->current_url . '&action=%s&id=%s">' . __('Delete', FS_AFFILIATES_LOCALE) . '</a>', 'delete', $item->ID),
					'restore' => sprintf('<a href="' . $this->current_url . '&action=%s&id=%s">' . __('Restore', FS_AFFILIATES_LOCALE) . '</a>', 'restore', $item->ID),
						);
			} else {
				$actions['edit'] = sprintf('<a href="' . $this->base_url . '&section=%s&id=%s">' . __('Edit', FS_AFFILIATES_LOCALE) . '</a>', 'edit', $item->ID);
				$actions ['trash'] = sprintf('<a href="' . $this->current_url . '&action=%s&id=%s">' . __('Trash', FS_AFFILIATES_LOCALE) . '</a>', 'trash', $item->ID);
			}

			//Return the title contents
			return sprintf('%1$s %2$s',
					/* $1%s */ $item->ID,
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
			$batch_header = get_post_meta($item->get_id(), '_payout_batch_header', true);
			
			switch ($column_name) {
				case 'batch_id':
					return !empty($batch_header['payout_batch_id']) ? $batch_header['payout_batch_id'] : '--';
					break;
				case 'status':
					return fs_affiliates_get_status_display(get_post_status($item->get_id()));
					break;
				case 'check_status':
					printf(__('<a href=%1$s>%2$s</a>'), add_query_arg(array( 'action' => 'check_payout_status', 'sender_batch_id' => $item->get_id() ), $this->current_url), __('Check Payout Status', FS_AFFILIATES_LOCALE));
					break;
				case 'date':
					return !empty($batch_header['time_completed']) ? $batch_header['time_completed'] : '--';
					break;
			}
		}

		/**
		 * Initialize the columns
		 * */
		public function get_current_page_items() {
			global $wpdb;

			$status = isset($_GET['status']) ? ' IN("' . $_GET['status'] . '")' : ' NOT IN("trash")';
			$where = " where post_type='" . $this->post_type . "' and post_status" . $status;
			$where = apply_filters($this->table_slug . '_query_where', $where);
			$limit = apply_filters($this->table_slug . '_query_limit', $this->perpage);
			$offset = apply_filters($this->table_slug . '_query_offset', $this->offset);
			$orderby = apply_filters($this->table_slug . '_query_orderby', $this->orderby);
			$prepare_query = $wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . " $where $orderby LIMIT %d,%d", $offset, $limit);
			$this->items = $wpdb->get_results($prepare_query, ARRAY_A);
			$count_items = $wpdb->get_results('SELECT ID FROM ' . $wpdb->posts . " $where $orderby");
			$this->total_items = count($count_items);

			$this->prepare_item_object($count_items);
		}

		/**
		 * Prepare item Object
		 * */
		public function prepare_item_object( $items ) {
			$prepare_items = array();
			if (fs_affiliates_check_is_array($items)) {
				foreach ($items as $item) {
					$prepare_items[] = new FS_Affiliates_Payouts($item->ID);
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
