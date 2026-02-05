<?php

/**
 * WC Coupon Linking Post Table
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_WC_Coupon_Linking_Post_Table')) {

	/**
	 * FS_Affiliates_WC_Coupon_Linking_Post_Table Class.
	 * 
	 * @since 1.0.0
	 * */
	class FS_Affiliates_WC_Coupon_Linking_Post_Table extends FS_Affiliates_List_Table {
	   
		/**
		 * Post type
		 * 
		 * @since 1.0.0
		 * @var string
		 * */
		protected $post_type = 'fs-coupon-linking';

		/**
		 * Prepare the table Data to display table based on pagination.
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'wc_coupon_linking' ), admin_url('admin.php'));
			
			parent::prepare_items();                       
		}

		/**
		 * Initialize the columns
		 * 
		 * @since 1.0.0
		 * */
		public function get_columns() {
			$columns = array(
				'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
				'coupon_code' => __('Coupon Code', FS_AFFILIATES_LOCALE),
				'coupon_value' => __('Coupon Value', FS_AFFILIATES_LOCALE),
				'affiliate_id' => __('Linked Affiliate', FS_AFFILIATES_LOCALE),
				'action' => __('Action', FS_AFFILIATES_LOCALE),
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
			$action['fs_link'] = __('Link', FS_AFFILIATES_LOCALE);
			$action['fs_unlink'] = __('Unlink', FS_AFFILIATES_LOCALE);
			$action['delete'] = __('Delete', FS_AFFILIATES_LOCALE);

			return $action;
		}

		/**
		 * Prepare each column data
		 * 
		 * @param string $item
		 * @param string $column_name
		 * 
		 * @since 1.0.0
		 * */
		protected function column_default( $item, $column_name ) {
			$CouponId = get_post_meta($item->get_id(), 'coupon_data', true);
			$CouponCode = !empty($CouponId) ? get_the_title($CouponId) : '';
			$DiscountType = get_post_meta($CouponId, 'discount_type', true);
			$coupon_value = ( $DiscountType != 'percent' ) ? fs_affiliates_price(get_post_meta($CouponId, 'coupon_amount', true)) : get_post_meta($CouponId, 'coupon_amount', true) . ' %';

			switch ($column_name) {
				case 'coupon_code':
					return $CouponCode;
					break;
				case 'coupon_value':
					return $coupon_value;
					break;
				case 'affiliate_id':
					$AffiliateId = $item->post_author;
					$AffiliateObj = new FS_Affiliates_Data($AffiliateId);
					return $AffiliateObj->user_name;
					break;
				case 'action':
					$actions = array();
					if ($item->get_status() == 'fs_link') {
						$actions['fs_unlink'] = fs_affiliates_get_action_display('fs_unlink', $item->get_id(), $this->current_url);
					} else {
						$actions['fs_link'] = fs_affiliates_get_action_display('fs_link', $item->get_id(), $this->current_url);
					}
					$actions['edit'] = sprintf('<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __('Edit', FS_AFFILIATES_LOCALE) . '</a>', 'edit_linking', $item->get_id());
					$actions['delete'] = sprintf('<a class="fs-delete-data" style="color:red !important;" href="' . $this->base_url . '&action=%s&id=%s">' . __('Delete Permanantly', FS_AFFILIATES_LOCALE) . '</a>', 'delete', $item->get_id());
					end($actions);
					$last_key = key($actions);
					foreach ($actions as $key => $action) {
						echo $action;

						if ($last_key == $key) {
							break;
						}
						echo ' | ';
					}
					break;
			}
		}

		/**
		 * Prepare item Object
		 * 
		 * @param array $items
		 * 
		 * @since 1.0.0
		 * */
		public function prepare_item_object( $items ) {
			$prepare_items = array();
			if (fs_affiliates_check_is_array($items)) {
				foreach ($items as $item) {
					$prepare_items[] = new FS_Linked_Affiliates_Data($item['ID']);
				}
			}

			$this->items = $prepare_items;
		}
	}

}
