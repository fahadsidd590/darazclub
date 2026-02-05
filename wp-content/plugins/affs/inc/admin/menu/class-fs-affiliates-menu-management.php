<?php

/*
 * Menu Management
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

if (!class_exists('FS_Affiliates_Menu_Management')) {

	include_once 'class-fs-affiliates-settings.php';

	/**
	 * FS_Affiliates_Menu_Management Class.
	 */
	class FS_Affiliates_Menu_Management {

		/**
		 * Affiliates slug
		 */
		private static $menu_slug = 'fs_affiliates';

		/**
		 * Plugin slug.
		 */
		protected static $plugin_slug = 'fs_affiliates';

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action('admin_menu', array( __CLASS__, 'add_menu_page' ));

			// Add order affiliate Meta box in shop order page.
			add_action('add_meta_boxes', array( __CLASS__, 'add_order_meta_boxes' ));
		}

		/**
		 * Add menu pages
		 */
		public static function add_menu_page() {
			$menu_option = get_option('fs_affiliates_menu_disp_type', '0') == '1' ? 'manage_woocommerce' : 'manage_options';
			$dash_icon_url = FS_AFFILIATES_PLUGIN_URL . '/assets/images/dash-icon.png';
			$settings_page = add_menu_page(__('SUMO Affiliates Pro', FS_AFFILIATES_LOCALE), __('SUMO Affiliates Pro', FS_AFFILIATES_LOCALE), $menu_option, self::$menu_slug, array( __CLASS__, 'settings_page' ), $dash_icon_url);

			add_action('load-' . $settings_page, array( __CLASS__, 'settings_page_init' ));
		}

		/**
		 * Settings page init
		 */
		public static function settings_page_init() {
			global $current_tab, $current_section, $current_sub_section;

			// Include settings pages.
			FS_Affiliates_Settings::get_settings_pages();

			$tabs = fs_affiliates_get_allowed_setting_tabs();

			// Get current tab/section.
			$current_tab = ( empty($_GET['tab']) || !array_key_exists($_GET['tab'], $tabs) ) ? key($tabs) : sanitize_title(wp_unslash($_GET['tab']));
			$current_section = empty($_REQUEST['section']) ? '' : sanitize_title(wp_unslash($_REQUEST['section']));
			$current_sub_section = empty($_REQUEST['subsection']) ? '' : sanitize_title(wp_unslash($_REQUEST['subsection']));

			do_action(self::$plugin_slug . '_settings_save_' . $current_tab, $current_section);
			do_action(self::$plugin_slug . '_settings_reset_' . $current_tab, $current_section);
		}

		/**
		 * Settings page output
		 */
		public static function settings_page() {
			FS_Affiliates_Settings::output();
		}

		/**
		 * Order meta box.
		 */
		public static function add_order_meta_boxes() {
			global $theorder;                   
			
			if (!is_object($theorder) || !in_array( OrderUtil::get_order_type($theorder->get_id()), fs_affiliates_get_shop_order_screen_ids() )) {
				return;
			}

			if ('' !== $theorder->get_meta('fs_affiliate_in_order')) {
				return;
			}

			if ('auto-draft' === $theorder->get_status()) {
				return;
			}

			add_meta_box('fs_assign_affiliate_order', __('Select Affiliate', 'woocommerce'), array( __CLASS__, 'render_affiliate_for_order' ), fs_affiliates_get_current_screen_id(), 'side', 'core');
		}

		/**
		 * Display order affiliate.
		 */
		public static function render_affiliate_for_order( $order ) {
			global $theorder;      
			
			if (!is_object($theorder) || 'shop_order' !== OrderUtil::get_order_type($theorder->get_id())) {
				return;
			}

			if ('' !== $theorder->get_meta('fs_affiliate_in_order')) {
				return;
			}

			if ('auto-draft' === $theorder->get_status()) {
				return;
			}

			include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/html-order-assign-affiliate.php' ;
		}
	}

	FS_Affiliates_Menu_Management::init();
}
