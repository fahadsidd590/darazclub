<?php

/**
 * Affiliates Wallet Post Table
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Wallet_Logs_Post_Table')) {

	/**
	 * FS_Affiliates_Wallet_Logs_Post_Table Class.
	 * 
	 * @since 1.0.0
	 * */
	class FS_Affiliates_Wallet_Logs_Post_Table extends FS_Affiliates_List_Table {

		/**
		 * Post type
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $post_type = 'fs-wallet-logs';

		/**
		 * Prepare the table Data to display table based on pagination.
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'affiliate_wallet' ), admin_url('admin.php'));

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
				'affiliate_id' => __('Affiliate Name', FS_AFFILIATES_LOCALE),
				'event' => __('Event', FS_AFFILIATES_LOCALE),
				'earned_balance' => __('Earned balance', FS_AFFILIATES_LOCALE),
				'used_balance' => __('Used balance', FS_AFFILIATES_LOCALE),
				'available_balance' => __('Available Balance', FS_AFFILIATES_LOCALE),
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
		 * Prepare each column data
		 * 
		 * @param string $item
		 * @param string $column_name
		 * @since 1.0.0
		 * */
		protected function column_default( $item, $column_name ) {
			$WalletObject = new FS_Affiliates_Wallet($item['ID']);
			switch ($column_name) {
				case 'affiliate_id':
					return $WalletObject->affiliate_id;
					break;
				case 'event':
					return $WalletObject->event;
					break;
				case 'earned_balance':
					return $WalletObject->earned_balance;
					break;
				case 'used_balance':
					return $WalletObject->used_balance;
					break;
				case 'available_balance':
					return $WalletObject->available_balance;
					break;
				case 'date':
					$date = $WalletObject->date;
					return fs_affiliates_local_datetime($date);
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
		}
	}

}
