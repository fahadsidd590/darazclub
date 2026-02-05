<?php

/**
 * Affiliate Request Submitted
 */
if ( !defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Request_Submitted' ) ) {

	/**
	 * Class FS_Affiliates_Request_Submitted
	 */
	class FS_Affiliates_Request_Submitted extends FS_Affiliates_Notifications {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'affiliate_request_submitted' ;
			$this->title = __( 'Affiliate - Affiliate Request Submitted' , FS_AFFILIATES_LOCALE ) ;

			// Triggers for this email.
			add_action('fs_affiliates_status_changed_new_to_fs_pending_approval', array( $this, 'trigger' ), 10, 1);
			// Display the shortcode information.
			add_action($this->plugin_slug . '_admin_field_fs_affiliates_shortcodes_details_' . $this->id, array( $this, 'shortcodes_information' ));
			parent::__construct();
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return esc_html__( '{site_name} - Affiliate Request Submitted Successfully' , FS_AFFILIATES_LOCALE ) ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return esc_html__( 'Hi,

                        Your Affiliate Application {affiliate_ref_number} has been Submitted on {site_name}. You will be notified shortly.

                       Thanks.' , FS_AFFILIATES_LOCALE ) ;
		}

		/*
		 * Default SMS Message
		 */

		public function get_sms_default_message() {

			return esc_html__( 'Your Affiliate Application {affiliate_ref_number} has been Submitted on {site_name}. You will be notified shortly.' , FS_AFFILIATES_LOCALE ) ;
		}

		/*
		 * Get settings link
		 */

		public function settings_link() {
			return add_query_arg( array( 'page' => 'fs_affiliates', 'tab' => 'notifications', 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
		}

		/**
		 * Trigger the sending of this email.
		 */
		public function trigger( $affiliate_id, $affiliate = false ) {
			if ( $affiliate_id && !is_a( $affiliate , 'FS_Affiliates_Data' ) ) {
				$affiliate = new FS_Affiliates_Data( $affiliate_id ) ;
			}

			if (is_a($affiliate, 'FS_Affiliates_Data')) {
				$this->recipient = $affiliate->email;
				$this->sms_recipient = $affiliate->phone_number;
				$this->placeholders['{fs_user_name}'] = $affiliate->user_name;
				$this->placeholders['{fs_first_name}'] = $affiliate->first_name;
				$this->placeholders['{fs_last_name}'] = $affiliate->last_name;
				$this->placeholders['{affiliate_ref_number}'] = $affiliate->get_id();
			}

			if ( $this->is_email_enabled() && $this->get_recipient() ) {
				$this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
			}

			if ( $this->is_sms_enabled() && $this->get_sms_recipient() ) {

				$this->send_sms( $this->get_sms_recipient() , $this->get_sms_message() ) ;
			}
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			$settings = array() ;

			$settings[] = array(
				'type' => 'title',
				'title' => __('Email Settings', FS_AFFILIATES_LOCALE),
				'id' => 'request_submitted_options',
			);
			$settings[] = array(
				'title' => __('Send Affiliate Request Submitted Email to Affiliate', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_email_enabled',
				'type' => 'checkbox',
				'default' => '',
			);
			$settings[] = array(
				'title' => __('Email Subject', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_subject',
				'type' => 'text',
				'default' => $this->get_default_subject(),
			);
			$settings[] = array(
				'title' => __('Email Message', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_message',
				'type' => 'wpeditor',
				'default' => $this->get_default_message(),
			);
			$settings[] = array(
				'title' => __('Email Attachments', FS_AFFILIATES_LOCALE),
				'id' => $this->plugin_slug . '_' . $this->id . '_email_attachments',
				'type' => 'file_upload',
				'default' => array(),
			);
			$settings[] = array(
				'type' => 'fs_affiliates_shortcodes_details_' . $this->id,
			);
			$settings[] = array(
				'type' => 'sectionend',
				'id' => 'request_submitted_options',
			);

			if ( $this->sms_module_enabled() ) {

				$settings[] = array(
					'type'  => 'title',
					'title' => __( 'SMS Settings' , FS_AFFILIATES_LOCALE ),
					'id'    => 'request_submitted_sms_options',
						) ;
				$settings[] = array(
					'title'   => __( 'Send Affiliate Request Submitted SMS to Affiliate' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_sms_enabled',
					'type'    => 'checkbox',
					'default' => '',
						) ;
				$settings[] = array(
					'title'   => __( 'SMS Message' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_sms_message',
					'type'    => 'wpeditor',
					'default' => $this->get_sms_default_message(),
						) ;
				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'request_submitted_sms_options',
						) ;
			}

			return $settings ;
		}

		/**
		 * Display the short code information.
		 *
		 * @since 10.0.0
		 *
		 * @return void
		 * */
		public function shortcodes_information() {
			$shortcodes_info = array(
				'{affiliate_ref_number}' => array(
					'usage' => __('Displays the Reference Number', FS_AFFILIATES_LOCALE),
				),
				'{site_name}' => array(
					'usage' => __('Displays the Site Name', FS_AFFILIATES_LOCALE),
				),
				'{fs_user_name}' => array(
					'usage' => __('Displays the Username', FS_AFFILIATES_LOCALE),
				),
				'{fs_first_name}' => array(
					'usage' => __("Displays the User's First Name", FS_AFFILIATES_LOCALE),
				),
				'{fs_last_name}' => array(
					'usage' => __("Displays the User's Last Name", FS_AFFILIATES_LOCALE),
				),
			);

			include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/html-email-shortcodes-info.php'  ;
		}
	}

}
