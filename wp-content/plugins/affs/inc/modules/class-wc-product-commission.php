<?php

/**
 * WooCommerce Product Commission.
 *
 * @since 9.2
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_WC_product_commission')) {

	/**
	 * Class
	 */
	class FS_Affiliates_WC_product_commission extends FS_Affiliates_Modules {
		/*
		 * Data
		 */

		protected $data = array(
			'enabled' => 'no',
		);

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id = 'wc_product_commission';
			$this->title = __('WooCommerce Product Commission', FS_AFFILIATES_LOCALE);

			parent::__construct();
		}

		/*
		 * Plugin enabled
		 * 
		 * @return bool.
		 */

		public function is_plugin_enabled() {
			$woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id('woocommerce');

			if ($woocommerce->is_enabled()) {
				return true;
			}

			return false;
		}

		/*
		 * Get settings link
		 * 
		 * @return mixed.
		 */

		public function settings_link() {
			return add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id ), admin_url('admin.php'));
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			$settings_array = array();
			$settings_array[] = array(
				'type' => 'title',
				'title' => __('WooCommerce Product Commission', FS_AFFILIATES_LOCALE),
				'id' => 'wc_product_commission_options',
			);
			$settings_array[] = array(
				'title' => __('Affiliate Commission for Products', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_enabled',
				'type' => 'checkbox',
				'default' => 'no',
				'desc' => __('By enabling this checkbox, you can allow affiliates to see the commission rates for products from their Dashboard.', FS_AFFILIATES_LOCALE),
			);
			$settings_array[] = array(
				'title' => esc_html__('Dashboard Menu Label', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_menu_label',
				'type' => 'text',
				'default' => 'Product Commission Rate(s)',
			);
			$settings_array[] = array(
				'title' => __('Number of entries to display in each Pagination', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_number_of_pagination_count',
				'type' => 'number',
				'default' => '5',
			);
			$settings_array[] = array(
				'type' => 'sectionend',
				'id' => 'wc_product_commission_options',
			);

			return $settings_array;
		}

		/**
		 * Frontend Actions
		 */
		public function frontend_action() {
			add_filter('fs_affiliates_frontend_dashboard_menu', array( $this, 'alter_menus' ), 14, 3);
			add_action('fs_affiliates_dashboard_content_wc_product_commission', array( $this, 'render_product_commission_menu_content' ), 10, 2);
		}

		/*
		 * Add product commissiom menu in the dashboard.
		 * 
		 * @return array.
		 */

		public function alter_menus( $menus, $user_id, $affiliate_id ) {
			if ('yes' != get_option('fs_affiliates_wc_product_commission_enabled')) {
				return $menus;
			}
			$menus['wc_product_commission'] = array( 'label' => get_option('fs_affiliates_wc_product_commission_menu_label', 'Product Commission Rate(s)'), 'code' => 'fa-box' );

			return $menus;
		}

		/*
		 * Display product commission menu content.
		 * 
		 * @return mixed.
		 */

		public function render_product_commission_menu_content( $user_id, $affiliate_id ) {
			if ('yes' != get_option('fs_affiliates_wc_product_commission_enabled')) {
				return;
			}

			$search = '';
			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$per_page = get_option('fs_affiliates_wc_product_commission_number_of_pagination_count', '5');
			$offset = ( $current_page - 1 ) * $per_page;
			$product_ids = fs_get_product_ids($search);
			$count = count($product_ids);
			$page_count = ceil($count / $per_page);

			$pagination_length = get_option('fs_affiliates_pagination_range');
			$start_page = $current_page;
			$end_page = ( $current_page + ( $pagination_length - 1 ) );

			$table_args = array(
				'post_ids' => array_slice($product_ids, $offset, $per_page),
				'affiliate_id' => $affiliate_id,
				'offset' => $offset,
				'per_page' => $per_page,
				'search' => $search,
				'count' => $count,
				'page_count' => $page_count,
				'pagination' => fs_dashboard_get_pagination_args( $current_page, $page_count ),
			);

			fs_affiliates_get_template('dashboard/wc-product-commission-wrapper.php', $table_args);
		}
	}

}
