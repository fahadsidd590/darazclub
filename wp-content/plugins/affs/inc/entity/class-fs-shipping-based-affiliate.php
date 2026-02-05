<?php

/*
 * Shipping Based Affiliate
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Shipping_Based_Affiliate' ) ) {

	/**
	 * FS_Shipping_Based_Affiliate Class.
	 */
	class FS_Shipping_Based_Affiliate extends FS_Affiliates_Post {

		/**
		 * Post type
		 */
		protected $post_type = 'fs-shippingaffiliate' ;

		/**
		 * Post Status
		 */
		protected $post_status = 'publish' ;

		/**
		 * Affiliate ID
		 * 
		 * @since 10.5.0
		 * @var string
		 */
		public $affiliate_id ;

		/**
		 * Shipping Id.
		 *
		 * @since 10.5.0
		 * @var string
		 * */
		public $shipping_id;

		/**
		 * Meta data keys
		 */
		protected $meta_data_keys = array(
			'affiliate_id'  => array(),
			'shipping_id'  => array(),
				) ;

		/**
		 * Set Affiliate Id.
		 */
		public function set_affiliate_id( $value ) {
			$this->affiliate_id = $value;
		}

		/**
		 * Set Shipping Id.
		 */
		public function set_shipping_id( $value ) {
			$this->shipping_id = $value;
		}

		/**
		 * Get Affiliate Id.
		 */
		public function get_affiliate_id() {
			return $this->affiliate_id;
		}

		/**
		 * Get Shipping Id.
		 */
		public function get_shipping_id() {
			return $this->shipping_id;
		}
	}

}
