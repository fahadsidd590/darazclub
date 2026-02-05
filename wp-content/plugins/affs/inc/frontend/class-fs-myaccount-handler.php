<?php

/**
 * My Account Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Myaccount_Handler' ) ) {

	/**
	 * FS_Myaccount_Handler Class.
	 */
	class FS_Myaccount_Handler {

		/**
		 * Cashback endpoint.
		 */
		public static $custom_endpoint = 'fs-affiliates-section';

		/**
		 * Class Initialization.
		 */
		public static function init() {
			$allow_user = get_option('fs_affiliates_wc_account_management_allow_users');
			if ( 'yes' != $allow_user ) {
				return ;
			}

			// Add custom rewrite endpoint
			add_action( 'init', array( __CLASS__, 'fs_rewrite_endpoint' ) );
			// Flush rewrite rules
			add_action( 'wp_loaded', array( __CLASS__, 'flush_rewrite_rules' ) );
			// Add custom query vars
			add_filter( 'query_vars', array( __CLASS__, 'fs_query_vars' ), 0 );
			// Add custom Myaccount Menu
			add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'fs_myaccount_menu' ) );
			// Customize the myaccount menu title
			add_filter( 'the_title', array( __CLASS__, 'customize_menu_title' ) );
			// Display the My Coupon menu content
			add_action( 'woocommerce_account_' . self::$custom_endpoint . '_endpoint', array( __CLASS__, 'affiliate_menu_content' ), 11 );
		}

		/**
		 * Custom rewrite endpoint
		 */
		public static function fs_rewrite_endpoint() {
			if(!self::show_menu_in_my_account()){
				return;
			}

			add_rewrite_endpoint( self::$custom_endpoint, EP_ROOT | EP_PAGES );
		}

		/**
		 * Add custom Query variable
		 */
		public static function fs_query_vars( $vars ) {
			if(!self::show_menu_in_my_account()){
				return $vars;
			}

			$vars[] = self::$custom_endpoint;

			return $vars;
		}

		/**
		 * Flush Rewrite Rules
		 */
		public static function flush_rewrite_rules() {
			flush_rewrite_rules();
		}

		/**
		 * Custom My account Menus
		 */
		public static function fs_myaccount_menu( $menus ) {
			if ( ! is_user_logged_in() ) {
				return $menus ;
			}

			if(!self::show_menu_in_my_account()){
				return $menus;
			}

			$custom_items[ self::$custom_endpoint ] = get_option('fs_affiliates_wc_account_management_menu_label') ;

			$menu_position = get_option('fs_affiliates_wc_account_management_menu_position');
			$menus = array_slice( $menus , 0 , ( int ) $menu_position ) + $custom_items + array_slice( $menus , ( int ) $menu_position , count( $menus ) - 1 ) ;

			return $menus ;
		}

		/**
		 * Customize the My account menu title
		 */
		public static function customize_menu_title( $title ) {
			if(!self::show_menu_in_my_account()){
				return $title;
			}

			global $wp_query ;

			if ( is_main_query() && in_the_loop() && is_account_page() ) {
				if ( isset( $wp_query->query_vars[ self::$custom_endpoint ] ) ) {
					$title = get_option('fs_affiliates_wc_account_management_menu_label') ;
				}

				remove_filter( 'the_title' , array( __CLASS__, 'customize_menu_title' ) ) ;
			}

			return $title ;
		}

		/**
		 * Display the Affiliate menu content
		 */
		public static function affiliate_menu_content() {

			if(!self::show_menu_in_my_account()){
				return;
			}

			$user_id      = get_current_user_id() ;
			$fs_affiliate = get_user_meta( $user_id , 'fs_affiliates_enabled' , true ) == 'yes' ;
			if ( $fs_affiliate ) {
				echo __( 'To view your affiliate dashboard, click the link below' , FS_AFFILIATES_LOCALE ) . '<br>' ;
				$dashboard_page_id = fs_affiliates_get_page_id( 'dashboard' ) ;
				$dashboard_url     = get_permalink( $dashboard_page_id ) ;
				echo '<a href="' . $dashboard_url . '">' . __( 'Affiliate Dashboard' , FS_AFFILIATES_LOCALE ) . '</a>' ;
			} else {
				echo get_option('fs_affiliates_wc_account_management_message') . '<br>' ;
				$register_page_id = fs_affiliates_get_page_id( 'register' ) ;
				$reg_url          = get_permalink( $register_page_id ) ;
				echo '<a href="' . $reg_url . '">' . __( 'Register as an Affiliate' , FS_AFFILIATES_LOCALE ) . '</a> &nbsp' ;
			}
		}

		/**
		 * Check if Affiliate menu to show in my account
		 */
		public static function show_menu_in_my_account(){
			$wc_account_management = FS_Affiliates_Module_Instances::get_module_by_id( 'wc_account_management' ) ;
			if ( ! $wc_account_management->is_enabled() ) {
				return false;
			}
			
			$user = wp_get_current_user() ;

			if ( ! is_object( $user ) ) {
				return false;
			}

			$affiliate_id = fs_get_affiliate_id_for_user( $user->ID ) ;

			if ( 'yes' == get_option('fs_affiliates_wc_account_management_show_non_affiliates') && ! $affiliate_id ) {
				return false;
			}

			return true;
		}
	}

	FS_Myaccount_Handler::init();
}
