<?php

/**
 * WooCommerce Blocks Integration.
 *
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface ;

/**
 * Class for integrating with WooCommerce Blocks scripts
 *
 * @since 10.1.0
 */
class FS_Affiliates_WC_Blocks_Integration implements IntegrationInterface {

	/**
	 * Whether the integration has been initialized.
	 *
	 * @since 10.1.0
	 * @var boolean
	 */
	protected $is_initialized ;

	/**
	 * The single instance of the class.
	 *
	 * @since 10.1.0
	 * @var FS_Affiliates_WC_Blocks_Integration
	 */
	protected static $_instance = null ;

	/**
	 * Main FS_Affiliates_WC_Blocks_Integration instance. Ensures only one instance of FS_Affiliates_WC_Blocks_Integration is loaded or can be loaded.
	 *
	 * @since 10.1.0
	 * @static
	 * @return FS_Affiliates_WC_Blocks_Integration
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self() ;
		}

		return self::$_instance ;
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @since 10.1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__ , esc_html__( 'Foul!' , FS_AFFILIATES_LOCALE ) , '10.1.0' ) ;
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @since 10.1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__ , esc_html__( 'Foul!' , FS_AFFILIATES_LOCALE ) , '10.1.0' ) ;
	}

	/**
	 * The name of the integration.
	 *
	 * @since 10.1.0
	 * @return string
	 */
	public function get_name() {
		return 'fs-affiliates-wc-blocks' ;
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 * 
	 * @since 10.1.0
	 */
	public function initialize() {
		if ( $this->is_initialized ) {
			return ;
		}

		// Enqueue block assets for the editor.
		add_action( 'enqueue_block_editor_assets' , array( $this, 'enqueue_block_editor_assets' ) ) ;
		// Enqueue block assets for the front-end.
		add_action( 'enqueue_block_assets' , array( $this, 'enqueue_block_assets' ) ) ;
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @since 10.1.0
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'fs-affiliates-wc-blocks' ) ;
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @since 10.1.0
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'fs-affiliates-wc-blocks' ) ;
	}

	/**
	 * Enqueue block assets for the editor.
	 *
	 * @since 10.1.0
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		// Load script.
		$script_asset_details = $this->get_script_asset_details( 'admin' ) ;

		wp_register_script(
				'fs-affiliates-wc-blocks' , FS_AFFILIATES_PLUGIN_URL . '/assets/blocks/admin/index.js' , $script_asset_details[ 'dependencies' ] , $script_asset_details[ 'version' ] , true
		) ;
	}

	/**
	 * Get the script asset details from the file if exists.
	 * 
	 * @since 10.1.0
	 * @param string $site
	 * @return array
	 */
	private function get_script_asset_details( $site = 'frontend' ) {
		$script_asset_path = FS_AFFILIATES_PLUGIN_PATH . '/assets/blocks/' . $site . '/index.asset.php' ;

		return file_exists( $script_asset_path ) ? require $script_asset_path : array(
			'dependencies' => array(),
			'version'      => FS_AFFILIATES_VERSION,
		) ;
	}

	/**
	 * Enqueue block assets for the front-end.
	 *
	 * @since 10.1.0
	 *
	 * @return void
	 */
	public function enqueue_block_assets() {
		// Load script.
		$script_asset_details = $this->get_script_asset_details() ;

		wp_register_script(
				'fs-affiliates-wc-blocks' , FS_AFFILIATES_PLUGIN_URL . '/assets/blocks/frontend/index.js' , $script_asset_details[ 'dependencies' ] , $script_asset_details[ 'version' ] , true
		) ;

		wp_enqueue_style(
				'fs-affiliates-wc-blocks' , FS_AFFILIATES_PLUGIN_URL . '/assets/blocks/frontend/index.css' , '' , $script_asset_details[ 'version' ]
		) ;
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @since 10.1.0
	 * @return array
	 */
	public function get_script_data() {

		if ( ! is_admin() ) {
			return array(
				'referal_code_added_message'           => get_option( 'fs_affiliates_referral_code_checkout_page_message' ),
				'redeem_wallet_amount_removed_message' => __( 'Wallet Amount Removed' , FS_AFFILIATES_LOCALE ),
				'redeemed_wallet_amount_message'       => __( 'Wallet amount applied successfully' , FS_AFFILIATES_LOCALE ),
			) ;
		} else {
			return array(
				'referal_code_form_title' => get_option( 'fs_affiliates_referral_code_field_label' ),
			) ;
		}
	}
}
