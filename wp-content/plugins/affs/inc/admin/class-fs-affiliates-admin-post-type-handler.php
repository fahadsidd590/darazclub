<?php
/*
 * Admin post type handler.
 * 
 * @since 10.4.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( !class_exists( 'FS_Affiliates_Admin_Post_Type_Handler' ) ) {

	/**
	 * FS_Affiliates_Admin_Post_Type_Handler Class
	 * 
	 * @since 10.4.0
	 */
	class FS_Affiliates_Admin_Post_Type_Handler {

		/**
		 * Class initialization
		 * 
		 * @since 10.4.0
		 */
		public static function init() {

			add_action( 'woocommerce_admin_order_items_after_line_items' , array( __CLASS__, 'add_commission_for_order_items' ), 10, 1 ) ;
			add_action( 'woocommerce_ajax_order_items_removed' , array( __CLASS__, 'remove_commission_for_order_items' ), 10, 4 ) ;
		}

		/**
		 * Add commission for order items.
		 * 
		 * @since 10.4.0
		 * @param int $order_id
		 */
		public static function add_commission_for_order_items( $order_id ) {
			$order = wc_get_order($order_id);
			if ( !is_object($order)) {
				return;
			}
			$affiliate_id = $order->get_meta('fs_affiliate_in_order');
			if (empty($affiliate_id)) {
				return;
			}

			if (!apply_filters('fs_affiliates_commission_from_same_ip', true, $order_id, $affiliate_id)) {
				return;
			}

			if (!FS_Affiliates_WC_Commission::is_restricted_own_commission($affiliate_id, $order->get_user_id())) {
				return;
			}
			$commissions = FS_Affiliates_WC_Commission::award_commission_for_product_purchase($order_id, $affiliate_id);
		}

		/**
		 * Remove commission for order items.
		 * 
		 * @since 10.4.0
		 * @param int $item_id
		 * @param object $item
		 * @param array $stock
		 * @param object $order
		 */
		public static function remove_commission_for_order_items( $item_id, $item, $stock, $order ) {
			$commissions = $order->get_meta('fs_awarded_commission');
			if ( isset( $commissions[ $item['product_id']] )) {
				unset ($commissions[ $item['product_id']]);
				$order->update_meta_data('fs_awarded_commission', $commissions);
				$order->save();
			}
		}
	}

	FS_Affiliates_Admin_Post_Type_Handler::init() ;
}
