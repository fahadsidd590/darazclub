<?php

/**
 * Store API functions.
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'fs_affiliates_get_checkout_block_referal_code_html' ) ) {

	/**
	 * Get the checkout block referal code HTML.
	 *
	 * @since 10.1.0
	 * @return HTML
	 */
	function fs_affiliates_get_checkout_block_referal_code_html() {
		if ('yes' !== get_option('fs_affiliates_referral_code_checkout_page_visible') || '2' == get_option( 'fs_affiliates_referral_code_checkout_page_visible_type' ) ) {
			return ;
		}
		if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) || ! apply_filters( 'fs_affiliates_display_checkout_referral_code' , true ) ) {
			return ;
		}

		return fs_affiliates_get_template_html( 'block/checkout-referral-code-form.php' ) ;
	}

}

if ( ! function_exists( 'fs_affiliates_get_block_affiliate_field_html' ) ) {

	/**
	 * Get the checkout block affiliate field HTML.
	 *
	 * @since 10.1.0
	 * @return HTML
	 */
	function fs_affiliates_get_block_affiliate_field_html() {
		$cookie_affiliate_id = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

		if ( ! empty( $cookie_affiliate_id ) || ! apply_filters( 'fs_affiliates_display_checkout_affiliate' , true ) ) {
			return ;
		}

		$radio_default = 1 ;

		wp_localize_script(
				'fs-affiliates-checkout' , 'fs_affiliates_checkout_params' , array(
			'affs_selection' => get_option( 'fs_affiliates_checkout_affiliate_affs_selection' ),
			'radio_default'  => $radio_default,
			'checkout_affiliate'  => wp_create_nonce( 'fs-checkout-affiliate-nonce' ),
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'is_checkout'   => is_checkout() && empty( is_wc_endpoint_url('order-received') ),
				)
		) ;

		wp_enqueue_script( 'fs-affiliates-checkout' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/checkout.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;

		$affiliates = fs_affiliates_get_active_affiliates() ;

		if ( get_option( 'fs_affiliates_checkout_affiliate_allowed_affiliates_method' ) == '2' ) {
			$affiliates = array_filter( array_unique( array_intersect( get_option( 'fs_affiliates_checkout_affiliate_selected_affiliates' ) , $affiliates ) ) ) ;
		}

		if ( ! fs_affiliates_check_is_array( $affiliates ) ) {
			return ;
		}

		return fs_affiliates_get_template_html( 'block/checkout-affiliate-field.php' , array( 'affiliates' => $affiliates ) ) ;
	}

}

if ( ! function_exists( 'fs_affiliates_get_checkout_block_referral_code_field_html' ) ) {

	/**
	 * Get the checkout block referral code field HTML.
	 *
	 * @since 10.1.0
	 * @return HTML
	 */
	function fs_affiliates_get_checkout_block_referral_code_field_html() {

		if ( 'yes' !== get_option( 'fs_affiliates_referral_code_checkout_page_visible' ) ) {
			return ;
		}

		if ( '1' == get_option( 'fs_affiliates_referral_code_checkout_page_visible_type' ) ) {
			return ;
		}

		if ( isset( $_COOKIE[ 'fsaffiliateid' ] ) ) {
			return ;
		}

		return fs_affiliates_get_template_html( 'block/checkout-referral-code-field.php' ) ;
	}

}

if ( ! function_exists( 'fs_affiliates_get_store_api_notices' ) ) {

	/**
	 * Get the store API cart notices.
	 * 
	 * @since 10.1.0
	 *
	 */
	function fs_affiliates_get_store_api_notices() {
		/**
		 * This hook is used to alter the store API cart notices.
		 * 
		 * @since 10.1.0
		 */
		 $notices = apply_filters( 'fs_affiliates_store_api_notices' , array() ) ;

		return array_filter( $notices ) ;
	}

}

if ( ! function_exists( 'fs_affiliates_get_block_wallet_coupon_html' ) ) {

	/**
	 * Get the block wallet coupon HTML.
	 *
	 * @since 10.1.0
	 * @return HTML
	 */
	function fs_affiliates_get_block_wallet_coupon_html() {
		$UserId = get_current_user_id() ;
		if ( ! $UserId ) {
			return ;
		}

		global $woocommerce ;
		if ( ! $woocommerce->cart->get_applied_coupons() ) {
			return ;
		}

		$UserData   = get_user_by( 'id' , $UserId ) ;
		$CouponCode = $UserData->user_login ;
		$CouponName = 'fs_' . strtolower( $CouponCode ) ;

		return fs_affiliates_get_template_html( 'block/wallet-coupon-wrapper.php' , array( 'CouponName' => $CouponName, 'discount_amount' => WC()->cart->coupon_discount_amounts[ "$CouponName" ] ) ) ;
	}

}

if ( ! function_exists( 'fs_affiliates_get_block_register_affiliate_html' ) ) {

	/**
	 * Get the block register affiliate HTML.
	 *
	 * @since 10.1.0
	 * @return HTML
	 */
	function fs_affiliates_get_block_register_affiliate_html() {
		$UserId = get_current_user_id() ;

		if ( $UserId ) {
			return ;
		}

		return fs_affiliates_get_template_html( 'block/register-affiliate-form.php' , array( 'fields' => fs_affiliates_get_form_fields() ) ) ;
	}

}
