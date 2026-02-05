<?php
/**
 * Payout Request
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Payout_Request')) {

	/**
	 * Class FS_Affiliates_Payout_Request
	 */
	class FS_Affiliates_Payout_Request extends FS_Affiliates_Modules {

		/**
		 * Menu Label.
		 *
		 * @var string
		 */
		protected $menu_label;

		/**
		 * Payout Threshold.
		 *
		 * @var string
		 */
		protected $payout_threshold;

		/**
		 * Success Message.
		 *
		 * @var string
		 */
		protected $success_msg;

		/**
		 * Error Message Threshold.
		 *
		 * @var string
		 */
		protected $errmsg_for_threshold;

		/**
		 * Error Message for Multiple Request.
		 *
		 * @var string
		 */
		protected $errmsg_for_multiple_request;

		/*
		 * Data
		 */
		protected $data = array(
			'enabled' => 'no',
			'menu_label' => '',
			'payout_threshold' => '',
			'success_msg' => '',
			'errmsg_for_threshold' => '',
			'errmsg_for_multiple_request' => '',
				);

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id = 'payout_request';
			$this->title = __('Payout Request', FS_AFFILIATES_LOCALE);

			parent::__construct();
		}

		/*
		 * Get settings link
		 */

		public function settings_link() {
			return add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id ), admin_url('admin.php'));
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			return array(
				array(
					'type' => 'title',
					'title' => __('Payout Request', FS_AFFILIATES_LOCALE),
					'id' => 'fp_payout_request',
				),
				array(
					'title' => __('Affiliate Menu Label', FS_AFFILIATES_LOCALE),
					'desc' => __('This label will be used for displaying the Payout Request menu  in the affiliate dashboard.', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_menu_label',
					'type' => 'text',
					'default' => 'Payout Request',
				),
				array(
					'title' => __('Payout Request Threshold', FS_AFFILIATES_LOCALE),
					'desc' => __('Affiliate can give payout request when they have unpaid commission equal to or more than the value specified.', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_payout_threshold',
					'type' => 'text',
					'default' => '',
				),
				array(
					'type' => 'sectionend',
					'id' => 'fp_payout_request',
				),
				array(
					'type' => 'title',
					'title' => __('Message Settings', FS_AFFILIATES_LOCALE),
					'id' => 'fp_msg_customization',
				),
				array(
					'title' => __('Success Message when Affiliate Submit Payout Request', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_success_msg',
					'type' => 'textarea',
					'default' => 'Request Submitted Successfully.',
				),
				array(
					'title' => __('Error Message when Affiliate Submit Multiple Payout Request', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_errmsg_for_multiple_request',
					'type' => 'textarea',
					'default' => 'Already you have submitted payout request.You cannot submit multiple payout requests.',
				),
				array(
					'title' => __('Error Message when Affiliate Submit Payout Request less than Threshold Value', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_errmsg_for_threshold',
					'type' => 'textarea',
					'default' => 'You don\'t have sufficient unpaid commission to submit the payout request.',
				),
				array(
					'type' => 'sectionend',
					'id' => 'fp_msg_customization',
				),
					);
		}

		/**
		 * Output the affiliates
		 */
		public function extra_fields() {
			global $current_sub_section;

			switch ($current_sub_section) {
				case 'fs_edit_request':
					$this->display_edit_pages();
					break;
				default:
					$this->display_table();
					break;
			}
		}

		/**
		 * Output the settings buttons.
		 */
		public function output_buttons() {
			global $current_sub_section;

			if (!$current_sub_section) {
				FS_Affiliates_Settings::output_buttons();
			}
		}

		/**
		 * Save settings.
		 */
		public function before_save() {

			if (!empty($_POST['payout_request'])) {
				$this->update_payout_request();
			}
		}

		/*
		 * Extra Fields
		 */

		public function display_table() {
			if (!class_exists('FS_Affiliates_Payout_Request_Logs')) {
				require_once FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-payout-request-logs-table.php' ;
			}


			$post_table = new FS_Affiliates_Payout_Request_Logs();
			$post_table->prepare_items();
			$post_table->display();
		}

		/**
		 * Output the edit affiliate page
		 */
		public function display_edit_pages() {
			if (!isset($_GET['id'])) {
				return;
			}

			$payoutrequest = get_post($_GET['id']);
			$affiliate_id = $payoutrequest->post_author;
			$affiliates_object = new FS_Affiliates_Data($affiliate_id);

			include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/payout-request-edit.php' ;
		}

		public function update_payout_request() {
			global $current_sub_section;
			if ($current_sub_section == '') {
				return;
			}

			check_admin_referer($this->plugin_slug . '_edit_payout_request', '_' . $this->plugin_slug . '_nonce');

			try {
				$meta_data = $_POST['payout_request'];
				$post = array(
					'ID' => $_REQUEST['id'],
					'post_status' => $meta_data['status'],
					'post_content' => $meta_data['notes'],
						);
				wp_update_post($post);

				do_action('fs_affiliates_status_to_' . $meta_data['status'], $_REQUEST['id']);

				FS_Affiliates_Settings::add_message(__('Payout Request has been updated successfully.', FS_AFFILIATES_LOCALE));
			} catch (Exception $ex) {
				FS_Affiliates_Settings::add_error($ex->getMessage());
			}
		}

		/*
		 * Action
		 */

		public function actions() {
		}

		/**
		 * Frontend Actions
		 */
		public function frontend_action() {
			add_filter('fs_affiliates_frontend_dashboard_menu', array( $this, 'payout_request_menu' ), 16, 2);
			add_filter('fs_affiliates_payout_request_enable', '__return_true', 10, 1);
			add_action('fs_affiliates_dashboard_content_payout_request', array( $this, 'payout_request_content' ), 10, 2);
		}

		/*
		 * Custom Payment Preference Option
		 */

		public function payout_request_menu( $menus, $user_id ) {

			$profile = $menus['profile'];
			unset($menus['profile']);

			$menus['payout_request'] = array( 'label' => $this->menu_label, 'code' => 'fa-arrow-circle-left' );

			$menus['profile'] = $profile;

			return $menus;
		}

		/*
		 * Content for Menu in Frontend
		 */

		public function payout_request_content( $user_id, $affiliate_id ) {
			$args = array( 'post_type' => 'fs-payout-request', 'post_status' => array( 'fs_submitted', 'fs_progress', 'fs_closed' ), 'numberposts' => -1, 'author' => $affiliate_id, 'fields' => 'ids' );
			$payout_request_logs = get_posts($args);
			$affiliates_object = new FS_Affiliates_Data($affiliate_id);

			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$per_page = 5;
			$offset = ( $current_page - 1 ) * $per_page;           
			$count = count($payout_request_logs);
			$page_count = ceil($count / $per_page);
			
			$pagination_length = get_option('fs_affiliates_pagination_range');
			$start_page = $current_page;
			$end_page = ( $current_page + ( $pagination_length - 1 ) );
			
			$table_args = array(
				'post_ids' => array_slice($payout_request_logs, $offset, $per_page),
				'affiliate_id'=> $affiliate_id,
				'offset' => $offset,
				'per_page' => $per_page,                
				'count' => $count,
				'page_count' => $page_count,
				'pagination' => fs_dashboard_get_pagination_args($current_page, $page_count),
			);
			
			 /**
			 * This hook is used to do extra action to before dashboard filters
			 * 
			 * @since 10.0.0
			 * @param int $affiliate_id
			 * @param string $post_type
			 */
			do_action('fs_affiliates_before_dashboard_filters', $affiliate_id, 'fs-payouts-request');
			fs_affiliates_get_template('dashboard/payouts-request.php', $table_args);
		}
	}

}
