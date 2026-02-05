<?php
/**
 * WooCommerce Affiliate Account Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_WC_Account_Management' ) ) {


	/**
	 * Class FS_Affiliates_WC_Account_Management
	 */
	class FS_Affiliates_WC_Account_Management extends FS_Affiliates_Modules {
		/*
		 * Custom Endpoint
		 */

		protected $custom_endpoint = 'fs-affiliates-section' ;

		/**
		 * Allow Users.
		 *
		 * @var string
		 */
		protected $allow_users ;

		/**
		 * Show Non Affiliates.
		 *
		 * @var string
		 */
		protected $show_non_affiliates ;

		/**
		 * Creating Account SignUp
		 *
		 * @var string
		 */
		protected $creating_account_signup ;

		/**
		 * Checkout Page SignUp.
		 *
		 * @var string
		 */
		protected $checkout_page_signup ;

		/**
		 * Menu Label.
		 *
		 * @var string
		 */
		protected $menu_label ;

		/**
		 * Menu Position
		 *
		 * @var string
		 */
		protected $menu_position ;

		/**
		 * Message.
		 *
		 * @var string
		 */
		protected $message ;

		/*
		 * Data
		 */
		protected $data = array(
			'enabled'                 => 'no',
			'allow_users'             => 'no',
			'show_non_affiliates'     => 'no',
			'creating_account_signup' => 'no',
			'checkout_page_signup'    => 'no',
			'menu_label'              => '',
			'menu_position'           => '',
			'message'                 => '',
				) ;

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'wc_account_management' ;
			$this->title = __( 'WooCommerce Affiliate Account Management' , FS_AFFILIATES_LOCALE ) ;

			parent::__construct() ;
		}

		/*
		 * Plugin enabled
		 */

		public function is_plugin_enabled() {
			$woocommerce = FS_Affiliates_Integration_Instances::get_integration_by_id( 'woocommerce' ) ;

			if ( $woocommerce->is_enabled() ) {
				return true ;
			}

			return false ;
		}

		/*
		 * Get settings link
		 */

		public function settings_link() {
			return add_query_arg( array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			return array(
				array(
					'type'  => 'title',
					'title' => __( 'WooCommerce Account Management' , FS_AFFILIATES_LOCALE ),
					'id'    => 'wc_account_management_options',
				),
				array(
					'title'   => __( 'Allow Logged In Users to Signup for Affiliates From My Account Page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_allow_users',
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'When enabled, logged in users can become affiliates from their My Account Page' , FS_AFFILIATES_LOCALE ),
				),
				array(
					'title'   => esc_html__( 'Hide the Menu for Non-Affiliates from My Account Page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_show_non_affiliates',
					'type'    => 'checkbox',
					'default' => 'no',
				),
				array(
					'title'   => __( 'Allow Guest to Signup for Affiliate while Creating Account' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_creating_account_signup',
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'When enabled, guests can become affiliate while creating an account through WooCommerce Registration Form.' , FS_AFFILIATES_LOCALE ),
				),
				array(
					'title'   => __( 'Allow Guest to Signup for Affiliate on Checkout Page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_checkout_page_signup',
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'When enabled, guests can become affiliate while placing the order on the checkout page.' , FS_AFFILIATES_LOCALE ),
				),
				array(
					'title'   => __( 'My Account Page Affiliate Label' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This Label will be displayed on the WooCommerce My Account Page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_menu_label',
					'type'    => 'text',
					'default' => 'Affiliates',
				),
				array(
					'title'   => __( 'Menu Position' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This option controls the position of the Affiliates link on the My Account Page.' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_menu_position',
					'type'    => 'number',
					'default' => '6',
				),
				array(
					'title'   => __( 'Message for Non-Affiliates' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This message will be displayed for Non-Affiliates when they click the Affiliates link on the My Account Page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_message',
					'type'    => 'textarea',
					'default' => 'Want to become  an Affiliate. Submit a request by clicking the link below',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'fs_affiliates_wc_account_management_options',
				),
					) ;
		}

		/**
		 * Frontend Actions
		 */
		public function frontend_action() {

			if ( $this->creating_account_signup == 'yes' ) {
				add_action( 'woocommerce_register_form' , array( $this, 'affs_registration_form' ) ) ;
				add_filter( 'woocommerce_process_registration_errors' , array( __CLASS__, 'process_registration_errors' ) , 10 , 4 ) ;
				add_action( 'woocommerce_created_customer' , array( __CLASS__, 'after_registration_success' ) , 10 , 3 ) ;
			}

			if ( $this->checkout_page_signup == 'yes' ) {
				add_action( 'woocommerce_after_checkout_registration_form' , array( $this, 'affs_registration_form_checkout' ) ) ;
				add_action( 'woocommerce_after_checkout_validation' , array( __CLASS__, 'process_registration_errors_checkout' ) , 10 , 2 ) ;
				add_action( 'woocommerce_checkout_order_processed' , array( __CLASS__, 'after_registration_success_checkout' ) , 10 , 3 ) ;
				add_filter( 'fs_affiliates_extend_cart_data' , array( $this, 'extend_cart_data' ) , 10 , 1 ) ;
				add_action( 'fs_affiliates_block_update_order_meta' , array( $this, 'process_register_affiliate_checkout' ) , 10 , 2 ) ;
			}
		}

		/**
		 * Extend cart data.
		 * 
		 * @since 10.1.0
		 * @return array
		 */
		public function extend_cart_data( $notices ) {
			if ( ! fs_affiliates_check_is_array( $notices ) ) {
				$notices = array() ;
			}

			return array_merge( $notices , array(
				'register_affiliate_form_title'         => __( 'Register as an Affiliate?' , FS_AFFILIATES_LOCALE ),
				'checkout_register_affiliate_form_html' => fs_affiliates_get_block_register_affiliate_html(),
					) ) ;
		}

		/**
		 * Add a message.
		 */
		public static function after_registration_success( $user_id, $new_customer_data, $password_generated ) {

			if ( ! isset( $_POST[ 'fs_affiliates_form_show_account' ] ) || fs_affiliates_is_user_having_affiliate( $user_id ) ) {
				return ;
			}
			//Collect Datas
			$meta_data = $_POST[ 'affiliate' ] ;

			$meta_data[ 'user_id' ]    = $user_id ;
			$meta_data[ 'email' ]      = isset( $new_customer_data[ 'user_email' ] ) ? $new_customer_data[ 'user_email' ] : '' ;
			$meta_data[ 'status' ]     = apply_filters( 'fs_affiliate_status_while_submit_application' , $meta_data[ 'status' ] , $meta_data ) ;
			$meta_data [ 'user_name' ] = isset( $new_customer_data[ 'user_login' ] ) ? $new_customer_data[ 'user_login' ] : '' ;
			self::process_registration_common( $meta_data , 'my_account' ) ;
		}

		/**
		 * Process register affiliate checkout.
		 * 
		 * @param object $order
		 * @param array $params
		 * @since 10.1.0
		 */
		public static function process_register_affiliate_checkout( $order, $params ) {
			
			if ( ! is_object( $order ) ) {
				return ;
			}
			$user_id = $order->get_user_id() ;
			//            if ( ! isset( $_POST[ 'fs_affiliates_form_show_checkout' ] ) || fs_affiliates_is_user_having_affiliate( $user_id ) ) {
			//                return ;
			//            }

			$user_data                   = get_userdata( $user_id ) ;
			//Collect Datas
			$meta_data                   = $_POST[ 'website' ] ;
			$meta_data                   = $_POST[ 'promotion' ] ;
			$meta_data                   = $_POST[ 'uploaded_key' ] ;
			$meta_data                   = $_POST[ 'file_upload' ] ;
			$meta_data[ 'email' ]        = isset( $_POST[ 'email' ] ) ? $_POST[ 'email' ] : '' ;
			$meta_data[ 'first_name' ]   = isset( $_POST[ 'first_name' ] ) ? $_POST[ 'first_name' ] : '' ;
			$meta_data[ 'last_name' ]    = isset( $_POST[ 'last_name' ] ) ? $_POST[ 'last_name' ] : '' ;
			$meta_data[ 'phone_number' ] = isset( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : '' ;
			$meta_data [ 'user_name' ]   = isset( $user_data->user_login ) ? $user_data->user_login : '' ;
			$meta_data[ 'user_id' ]      = $user_id ;

			self::process_registration_common( $meta_data , 'checkout' ) ;
		}

		/**
		 * Add a message.
		 */
		public static function after_registration_success_checkout( $order_id, $new_customer_data, $order ) {
			$user_id = $order->get_user_id() ;
			if ( ! isset( $_POST[ 'fs_affiliates_form_show_checkout' ] ) || fs_affiliates_is_user_having_affiliate( $user_id ) ) {
				return ;
			}

			$user_data                   = get_userdata( $user_id ) ;
			//Collect Datas
			$meta_data                   = $_POST[ 'affiliate' ] ;
			$meta_data[ 'email' ]        = isset( $new_customer_data[ 'billing_email' ] ) ? $new_customer_data[ 'billing_email' ] : '' ;
			$meta_data[ 'first_name' ]   = isset( $new_customer_data[ 'billing_first_name' ] ) ? $new_customer_data[ 'billing_first_name' ] : '' ;
			$meta_data[ 'last_name' ]    = isset( $new_customer_data[ 'billing_last_name' ] ) ? $new_customer_data[ 'billing_last_name' ] : '' ;
			$meta_data[ 'phone_number' ] = isset( $new_customer_data[ 'billing_phone' ] ) ? $new_customer_data[ 'billing_phone' ] : '' ;
			$meta_data [ 'user_name' ]   = isset( $user_data->user_login ) ? $user_data->user_login : '' ;
			$meta_data[ 'user_id' ]      = $user_id ;

			self::process_registration_common( $meta_data , 'checkout' ) ;
		}

		public static function process_registration_common( $meta_data, $form_type ) {
			$required_approval     = get_option( 'fs_affiliates_admin_approval_required' ) ;
			$meta_data[ 'status' ] = ( $required_approval == 'yes' ) ? 'fs_pending_approval' : 'fs_active' ;
			$meta_data[ 'status' ] = apply_filters( 'fs_affiliate_status_while_submit_application' , $meta_data[ 'status' ] , $meta_data ) ;
			$meta_data[ 'date' ]   = time() ;
			$parent_affiliate_id   = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;
			$parent_affiliate_id   = ( $parent_affiliate_id ) ? $parent_affiliate_id : fs_affiliates_get_default_parent_affiliate() ;
			$post_args             = array(
				'post_status' => $meta_data[ 'status' ],
				'post_author' => $meta_data[ 'user_id' ],
				'post_parent' => $parent_affiliate_id,
				'post_title'  => $meta_data [ 'user_name' ],
					) ;

			$meta_data[ 'uploaded_files' ]     = isset( $meta_data[ 'uploaded_key' ] ) ? get_transient( $meta_data[ 'uploaded_key' ] ) : '' ;
			$meta_data[ 'signup_visit_id' ]    = fs_affiliates_get_id_from_cookie( 'fsvisitid' ) ;
			$meta_data[ 'signup_campaign_id' ] = fs_affiliates_get_id_from_cookie( 'fscampaign' , '' ) ;

			$affiliate_id = fs_affiliates_create_new_affiliate( $meta_data , $post_args ) ;
			do_action( 'fs_affiliates_frontend_register_form_submitted' , $affiliate_id ) ;

			if ( isset( $_POST[ 'iagree_mail_subscribe' ] ) && ! empty( $email ) ) {
				fs_affiliates_access_mail_api( $email , $meta_data , $form_type ) ;
			}
		}

		/**
		 * Validate Register Form - checkout
		 */
		public static function process_registration_errors_checkout( $data, $errors ) {
			if ( ! isset( $_POST[ 'fs_affiliates_form_show_checkout' ] ) ) {
				return ;
			}
			self::fs_affs_process_registration_errors( $errors , 'checkout' ) ;
		}

		/**
		 * Validate Register Form - My Account
		 */
		public static function process_registration_errors( $validation_error, $username, $password, $email ) {
			if ( ! isset( $_POST[ 'fs_affiliates_form_show_account' ] ) ) {
				return $validation_error ;
			}
			$validation_error = self::fs_affs_process_registration_errors( $validation_error , 'my_account' ) ;
			return $validation_error ;
		}

		/**
		 * Validate Register Form - common
		 */
		public static function fs_affs_process_registration_errors( $errors, $error_type ) {

			$nonce_value = isset( $_POST[ 'fs-affiliates-my-acc-register-nonce' ] ) ? $_POST[ 'fs-affiliates-my-acc-register-nonce' ] : null ;
			if ( empty( $_POST[ 'fs-affiliates-my-acc-action' ] ) || ( ! wp_verify_nonce( $nonce_value , 'fs-affiliates-my-acc-register' ) ) ) {
				return $errors ;
			}

			try {

				$meta_data = $_POST[ 'affiliate' ] ; //get data from post

				FS_Affiliates_Form_Handler::validate_required_field( $meta_data , 'checkout' ) ;

				if ( ! isset( $_POST[ 'iagree' ] ) ) {
					throw new Exception( __( 'Please accept the Terms of Service' , FS_AFFILIATES_LOCALE ) ) ;
				}

				FS_Affiliates_Form_Handler::validate_google_captcha() ;

				$error = apply_filters( 'fs_affiliates_registration_errors' , '' , $meta_data ) ;

				if ( $error ) {
					throw new Exception( $error ) ;
				}
			} catch ( Exception $ex ) {
				if ( $error_type == 'my_account' ) {
					$errors = new WP_Error( 'affs-reg-error' , __( $ex->getMessage() , FS_AFFILIATES_LOCALE ) ) ;
				} else {
					$errors->add( 'affs-reg-error' , __( $ex->getMessage() , FS_AFFILIATES_LOCALE ) ) ;
				}
			}
			return $errors ;
		}

		public static function affs_registration_form() {
			self::registration_form_my_account( 'my_account' ) ;
		}

		public static function affs_registration_form_checkout() {
			self::registration_form_my_account( 'checkout' ) ;
		}

		/**
		 * Output Register form.
		 */
		public static function registration_form_my_account( $form_type ) {
			try {

				FS_Affiliates_Shortcodes::custom_css() ;
				// Display Error or Messages
				FS_Affiliates_Form_Handler::show_messages() ;

				$fields                 = fs_affiliates_get_form_fields() ;
				do_action( 'fs_affiliates_before_register_form' ) ;
				?>
				<!--<form class="fs_affiliates_forms" id="fs_affiliates_register_form" action="" method="post">-->
				<?php
				$gcaptcha_site_key      = get_option( 'fs_affiliates_recaptcha_site_key' ) ;
				$google_captcha_enabled = get_option( 'fs_affiliates_recaptcha_registration_page' ) == 'yes' ;

				/* Include Register page */
				include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/frontend/views/register-my-account.php' ;
				?>
				<!--</form>-->
				<?php
				do_action( 'fs_affiliates_after_register_form' ) ;
			} catch ( Exception $ex ) {
				echo $ex->getMessage() ;
			}
		}
	}

}
