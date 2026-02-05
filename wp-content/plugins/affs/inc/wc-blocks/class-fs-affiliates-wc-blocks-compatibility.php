<?php

/**
 * WooCommerce Blocks Compatibility.
 *
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_WC_Blocks_Compatibility' ) ) {

	/**
	 * Class FS_Affiliates_WC_Blocks_Compatibility
	 * 
	 * @since 10.1.0
	 */
	class FS_Affiliates_WC_Blocks_Compatibility {

		/**
		 * Class initialization.
		 * 
		 * @since 10.1.0
		 */
		public static function init() {
			add_action( 'woocommerce_blocks_loaded' , array( __CLASS__, 'register_integration' ) , 10 ) ;
		}

		/**
		 * Register integration.
		 * 
		 * @since 10.1.0
		 */
		public static function register_integration() {
			self::initialize() ;

			/**
			 * This hook is used to alter the compatible block names.
			 * 
			 * @since 10.1.0
			 */
			$compatible_block_names = apply_filters( 'fs_affiliates_compatible_block_names' , array( 'cart', 'checkout', 'mini-cart' ) ) ;

			foreach ( $compatible_block_names as $block_name ) {
				add_action(
						"woocommerce_blocks_{$block_name}_block_registration" , function ( $registry ) {
							$registry->register( FS_Affiliates_WC_Blocks_Integration::instance() ) ;
						}
				) ;
			}
		}

		/**
		 * Initialize require files and store API.
		 * 
		 * @since 10.1.0
		 */
		private static function initialize() {
			// Require files.
			include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/wc-blocks/class-fs-affiliates-wc-blocks-integration.php' ;
			include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/wc-blocks/class-fs-affiliates-wc-blocks-store-api.php' ;

			// Initialize the store API.
			FS_Affiliates_WC_Blocks_Store_API::init() ;
		}
	}

	FS_Affiliates_WC_Blocks_Compatibility::init() ;
}
