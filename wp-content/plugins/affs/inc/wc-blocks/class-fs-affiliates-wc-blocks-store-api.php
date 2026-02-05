<?php

/**
 * WooCommerce Blocks Store API.
 *
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema ;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema ;

/**
 * Class for extend store API of cart/checkout.
 *
 * @since 10.1.0
 */
class FS_Affiliates_WC_Blocks_Store_API {

	/**
	 * Plugin Identifier, unique to each plugin.
	 *
	 * @since 10.1.0
	 * @var string
	 */
	const IDENTIFIER = 'fs-affiliates' ;

	/**
	 * Bootstrap.
	 * 
	 * @since 10.1.0
	 */
	public static function init() {
		// Extend StoreAPI.
		self::extend_store() ;

		add_action( 'woocommerce_store_api_checkout_update_order_from_request' , array( __CLASS__, 'maybe_update_order_meta' ) , 10 , 2 ) ;
	}

	/**
	 * Register extensibility points.
	 * 
	 * @since 10.1.0
	 */
	protected static function extend_store() {
		if ( function_exists( 'woocommerce_store_api_register_endpoint_data' ) ) {
			woocommerce_store_api_register_endpoint_data(
					array(
						'endpoint'        => CartSchema::IDENTIFIER,
						'namespace'       => self::IDENTIFIER,
						'data_callback'   => array( 'FS_Affiliates_WC_Blocks_Store_API', 'extend_cart_data' ),
						'schema_callback' => array( 'FS_Affiliates_WC_Blocks_Store_API', 'extend_cart_schema' ),
						'schema_type'     => ARRAY_A,
					)
			) ;

			woocommerce_store_api_register_endpoint_data(
					array(
						'endpoint'        => CheckoutSchema::IDENTIFIER,
						'namespace'       => self::IDENTIFIER,
						'data_callback'   => array( 'FS_Affiliates_WC_Blocks_Store_API', 'extend_cart_data' ),
						'schema_callback' => array( 'FS_Affiliates_WC_Blocks_Store_API', 'extend_checkout_schema' ),
						'schema_type'     => ARRAY_A,
					)
			) ;
		}

		if ( function_exists( 'woocommerce_store_api_register_update_callback' ) ) {
			woocommerce_store_api_register_update_callback(
					array(
						'namespace' => self::IDENTIFIER,
						'callback'  => array( 'FS_Affiliates_WC_Blocks_Store_API', 'rest_handle_endpoint' ),
					)
			) ;
		}
	}

	/**
	 * Register sumo affiliates pro schema in the checkout schema.
	 * 
	 * @since 10.1.0
	 * @return array
	 */
	public static function extend_checkout_schema() {
		return array(
			'affiliates_fields' => array(
				'description' => __( 'Affiliate related fields' , FS_AFFILIATES_LOCALE ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit' ),
				'properties'  => array(
					'referral_code'      => array(
						'description' => __( 'Referral Code' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'website'            => array(
						'description' => __( 'Affiliates Website' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'promotion'          => array(
						'description' => __( 'Affiliates Promotion' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'uploaded_key'       => array(
						'description' => __( 'Affiliates File Uploaded Key' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'file_upload'        => array(
						'description' => __( 'Affiliates File Upload' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'iagree'             => array(
						'description' => __( 'Affiliates iagree' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
					'affiliate_referrer' => array(
						'description' => __( 'Affiliate Referrer' , FS_AFFILIATES_LOCALE ),
						'type'        => array( 'string', 'boolean' ),
						'context'     => array( 'view', 'edit' ),
						'required'    => true,
					),
				),
				'arg_options' => array(
					'validate_callback' => array( 'FS_Affiliates_WC_Blocks_Store_API', 'validate_callback' ),
				),
			),
				) ;
	}

	/**
	 * Register affiliates pro schema in the cart schema.
	 * 
	 * @since 10.1.0
	 * @return array
	 */
	public static function extend_cart_schema() {
		return array() ;
	}

	/**
	 * Register affiliates pro data in the cart API.
	 * 
	 * @since 10.1.0
	 * @return array
	 */
	public static function extend_cart_data() {
		/**
		 * This hook is used to alter the extend cart data.
		 * 
		 * @since 10.1.0
		 */
		return apply_filters( 'fs_affiliates_extend_cart_data' , array( 'notices'=> fs_affiliates_get_store_api_notices() )  ) ;
	}

	/**
	 * Validate the given address object.
	 *
	 * @since 10.1.0
	 * @param array            $affiliate Value being sanitized.
	 * @param \WP_REST_Request $request The Request.
	 * @param string           $param The param being sanitized.
	 * @return true|\WP_Error
	 */
	public static function validate_callback( $affiliate, $request, $param ) {

		if ( ! fs_affiliates_check_is_array( $affiliate ) ) {
			return true ;
		}

		$errors = new \WP_Error() ;
		
		return apply_filters( 'fs_affiliates_valid_block_checkout_fields' , $errors ) ;
	}

	/**
	 * Handles sumo affiliates pro rest endpoints.
	 * 
	 * @since 10.1.0
	 * @param array $args
	 */
	public static function rest_handle_endpoint( $args ) {
		do_action( 'fs_affiliates_rest_handle_endpoint' , $args ) ;
	}

	/**
	 * Update an sumo affiliates pro order meta.
	 * 
	 * @since 10.1.0
	 * @param object $order
	 * @param \WP_REST_Request $request The Request.
	 * 
	 */
	public static function maybe_update_order_meta( $order, $request ) {

		if ( ! is_object( $request ) ) {
			return ;
		}

		$extensions = $request->get_param( 'extensions' ) ;
		$params     = $extensions[ 'fs-affiliates' ] ? $extensions[ 'fs-affiliates' ] : array() ;

		if ( empty( $params ) ) {
			return ;
		}

		$_POST = array_merge( $_POST , $extensions[ 'fs-affiliates' ][ 'affiliates_fields' ] ) ;
		$_POST = array_merge( $_POST , ( array ) $request[ 'billing_address' ] ) ;
		do_action( 'fs_affiliates_block_update_order_meta' , $order , $extensions[ 'fs-affiliates' ][ 'affiliates_fields' ] ) ;
	}
}
