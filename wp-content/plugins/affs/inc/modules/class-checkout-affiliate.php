<?php
/**
 * Checkout Affiliate
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Checkout' ) ) {

	/**
	 * Class FS_Affiliates_Checkout
	 */
	class FS_Affiliates_Checkout extends FS_Affiliates_Modules {

		/**
		 * Force Users.
		 *
		 * @var string
		 */
		protected $force_users ;

		/**
		 * Affiliate Selection.
		 *
		 * @var string
		 */
		protected $affs_selection ;

		/**
		 * Selection Title.
		 *
		 * @var string
		 */
		protected $selection_title ;

		/**
		 * Selection Value1.
		 *
		 * @var string
		 */
		protected $selection_value1 ;

		/**
		 * Selection Value2.
		 *
		 * @var string
		 */
		protected $selection_value2 ;

		/**
		 * Label.
		 *
		 * @var string
		 */
		protected $label ;

		/**
		 * Display Style.
		 *
		 * @var string
		 */
		protected $display_style ;

		/**
		 * Allow Affiliate Method.
		 *
		 * @var string
		 */
		protected $allowed_affiliates_method ;

		/**
		 * Selected Affiliate.
		 *
		 * @var array
		 */
		protected $selected_affiliates ;

		/*
		 * Data
		 */
		protected $data = array(
			'enabled'                   => 'no',
			'force_users'               => '',
			'affs_selection'            => '',
			'selection_title'           => '',
			'selection_value1'          => '',
			'selection_value2'          => '',
			'label'                     => '',
			'display_style'             => '1',
			'allowed_affiliates_method' => '1',
			'selected_affiliates'       => array(),
				) ;

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'checkout_affiliate' ;
			$this->title = __( 'Checkout Affiliate' , FS_AFFILIATES_LOCALE ) ;

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

			$selected = ( $this->force_users == 'yes' ) ? 2 : 1 ;

			return array(
				array(
					'type'  => 'title',
					'title' => __( 'Checkout Affiliate Settings' , FS_AFFILIATES_LOCALE ),
					'id'    => 'checkout_affiliate_options',
				),
				array(
					'title'   => __( 'Affiliates to be Displayed in Listbox' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This option controls the list of affiliates which should be displayed in the listbox' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_allowed_affiliates_method',
					'type'    => 'select',
					'class'   => 'fs_affiliates_allowed_affiliates_method',
					'default' => '1',
					'options' => array(
						'1' => __( 'All Affiliates' , FS_AFFILIATES_LOCALE ),
						'2' => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ),
					),
				),
				array(
					'title'     => __( 'Selected Affiliates' , FS_AFFILIATES_LOCALE ),
					'id'        => $this->plugin_slug . '_' . $this->id . '_selected_affiliates',
					'type'      => 'ajaxmultiselect',
					'class'     => 'fs_affiliates_selected_affiliate',
					'list_type' => 'affiliates',
					'action'    => 'fs_affiliates_search',
					'default'   => array(),
				),
				array(
					'title'   => __( 'Affiliate Name Display Style' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This option controls the Affiliate Name display style on the checkout page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_display_style',
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => __( 'User Name' , FS_AFFILIATES_LOCALE ),
						'2' => __( 'Display Name' , FS_AFFILIATES_LOCALE ),
						'3' => __( 'Display Nickname' , FS_AFFILIATES_LOCALE ),
					),
				),
				array(
					'title'   => __( 'Affiliate Selection' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This option controls how the affiliate selection option can be used to display at checkout' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_affs_selection',
					'type'    => 'select',
					'default' => $selected,
					'options' => array(
						'1' => __( 'Optional' , FS_AFFILIATES_LOCALE ),
						'2' => __( 'Mandatory' , FS_AFFILIATES_LOCALE ),
						'3' => __( 'Based on User Selection' , FS_AFFILIATES_LOCALE ),
					),
				),
				array(
					'title'   => __( 'Affiliate Selection Title' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This label will be used for displaying the affiliate selection field on the checkout page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_selection_title',
					'class'   => 'fs_affiliates_user_selection_fields',
					'type'    => 'text',
					'default' => 'Affiliate Selection',
				),
				array(
					'title'   => __( 'Affiliate Selection Value1' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This label will be used for displaying the affiliate selection on the checkout page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_selection_value1',
					'class'   => 'fs_affiliates_user_selection_fields',
					'type'    => 'text',
					'default' => 'I want to select an Affiliate',
				),
				array(
					'title'   => __( 'Affiliate Selection Value2' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This label will be used for displaying the affiliate selection on the checkout page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_selection_value2',
					'class'   => 'fs_affiliates_user_selection_fields',
					'type'    => 'text',
					'default' => "I don't want to select the Affiliate",
				),
				array(
					'title'   => __( 'Affiliate Selection Label' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'This label will be used for displaying the affiliate selection field on the checkout page' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_label',
					'type'    => 'text',
					'default' => 'Select Affiliate',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'checkout_affiliate_options',
				),
				array(
					'type'  => 'title',
					'title' => __( 'Checkout Affiliate Based on Shipping Method' , FS_AFFILIATES_LOCALE ),
					'id'    => 'checkout_affiliate_shipping_options',
				),
				array(
					'title'   => __( 'Affiliate Selection' , FS_AFFILIATES_LOCALE ),
					'desc'    => __( 'If enabled, an affiliate selection list will be hidden at checkout' , FS_AFFILIATES_LOCALE ),
					'id'      => $this->plugin_slug . '_' . $this->id . '_disable_user_selection',
					'type'    => 'checkbox',
					'default' => 'no',
				),
				array(
					'type' => 'shipping_based_affiliate',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'checkout_affiliate_shipping_options',
				),
			);
		}

		/**
		 * Frontend Actions
		 */
		public function admin_action() {
			add_action('wp_ajax_fs_affiliates_get_shipping_based_rule_html', array( $this, 'get_shipping_rule_html' ));
			add_action($this->plugin_slug . '_admin_field_shipping_based_affiliate', array( $this, 'render_shipping_based_rules' ));
		}

		/**
		 * Frontend Actions
		 */
		public function frontend_action() {

			add_filter( 'fs_affiliates_extend_cart_data' , array( $this, 'extend_cart_data' ) , 10 , 1 ) ;

			add_action( 'woocommerce_checkout_after_customer_details' , array( $this, 'woocommerce_checkout_after_customer_details' ) ) ;

			add_action( 'woocommerce_checkout_process' , array( $this, 'woocommerce_checkout_process_validation' ) ) ;

			add_filter( 'fs_affiliates_valid_block_checkout_fields' , array( $this, 'process_valid_block_checkout_fields' ) , 10 , 1 ) ;

			add_action( 'woocommerce_checkout_update_order_meta' , array( $this, 'woocommerce_checkout_update_order_meta' ) , 10 , 1 ) ;

			add_action( 'woocommerce_store_api_checkout_order_processed' , array( $this, 'woocommerce_checkout_update_order_meta' ) , 10 , 1 ) ;

			add_action( 'wp_ajax_fs_select_checkout_affiliate' , array( $this, 'select_checkout_affiliate' ) , 10 , 1 ) ;

			add_action( 'wp_ajax_nopriv_fs_select_checkout_affiliate' , array( $this, 'select_checkout_affiliate' ) , 10 , 1 ) ;
		}

		/**
		 * Render the global MLM rules.
		 */
		public function render_shipping_based_rules() {
			$rule_ids = fs_affiliate_get_shipping_rule_ids();

			include_once 'views/shipping/shipping-based-affiliate-rules.php';
		}

		/**
		 * Get a Shipping Rule.
		 */
		public function get_shipping_rule_html() {
			check_ajax_referer('fs-shipping-nonce', 'fs_security');

			try {
				if ( ! isset($_POST) || ! isset($_POST['count'])) {
					throw new exception(__('Invalid Request', FS_AFFILIATES_LOCALE));
				}

				$key = wc_clean(wp_unslash($_POST['count']));
				$name = 'fs_shipping_based_affiliate_rules[new][' . $key . ']';

				ob_start();
				include_once 'views/shipping/shipping-based-affiliate-new-rule.php';
				$content = ob_get_clean();

				wp_send_json_success(array( 'content' => $content ));
			} catch (Exception $e) {
				wp_send_json_error(array( 'error' => $e->getMessage() ));
			}
		}

		/**
		 * Get a Shipping Rule.
		 */
		public function select_checkout_affiliate() {
			check_ajax_referer('fs-checkout-affiliate-nonce', 'fs_security');

			try {
				if ( ! isset($_POST) || ! isset($_POST['shipping_method'])) {
					throw new exception(__('No Shipping Method', FS_AFFILIATES_LOCALE));
				}

				$selected_shipping_method = wc_clean(wp_unslash($_POST['shipping_method']));
				$exploded_array = explode(':', $selected_shipping_method);
				$shipping_method_key = implode('_', $exploded_array);
					
				$args = array(
					'meta_key' => 'shipping_id',
					'meta_value' => $shipping_method_key,
				);

				$shipping_rule_ids = fs_affiliate_get_shipping_rule_ids($args);
				$shipping_rule_id = fs_affiliates_check_is_array($shipping_rule_ids) ? $shipping_rule_ids[0] : '';
				if (fs_affiliates_check_is_array($shipping_rule_ids)) {
					$shipping_rule = fs_affiliate_get_shipping_rule($shipping_rule_ids[0]);
					$affiliate_id = $shipping_rule->get_affiliate_id();
					$current_affiliate_id = fs_affiliates_is_user_having_affiliate(get_current_user_id());
					$affiliate_id = ( $current_affiliate_id == $affiliate_id[0] ) ? '' : $affiliate_id[0];
				} else {
					$affiliate_id = '';
				}

				wp_send_json_success(array( 'affiliate' => $affiliate_id ));
			} catch (Exception $e) {
				wp_send_json_error(array( 'error' => $e->getMessage() ));
			}
		}

		/**
		 * Save settings.
		 */
		public function save() {
				$shipping_based_rules = isset($_REQUEST['fs_shipping_based_affiliate_rules']) ? wc_clean($_REQUEST['fs_shipping_based_affiliate_rules']) : array();
				$shipping_rule_ids = fs_affiliate_get_shipping_rule_ids();
			if (isset($shipping_based_rules['new'])) {
				foreach ($shipping_based_rules['new'] as $shipping_based_rule) {
					fs_affiliates_create_new_shipping_rule($shipping_based_rule);
				}
			}

			foreach ( $shipping_rule_ids as $shipping_rule_id ) {
				if ( isset( $shipping_based_rules[ $shipping_rule_id ] ) ) {
					fs_affiliate_update_shipping_rule( $shipping_rule_id, $shipping_based_rules[ $shipping_rule_id ] );
				} else {
					fs_affiliate_delete_post( $shipping_rule_id );
				}
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
				'checkout_affiliate_form_field_html' => fs_affiliates_get_block_affiliate_field_html(),
					) ) ;
		}

		public function woocommerce_checkout_after_customer_details() {
			$cookie_affiliate_id = fs_affiliates_get_id_from_cookie( 'fsaffiliateid' ) ;

			if ( ! empty( $cookie_affiliate_id ) || ! apply_filters( 'fs_affiliates_display_checkout_affiliate' , true ) ) {
				return ;
			}

			$radio_default = 1 ;

			wp_localize_script(
					'fs-affiliates-checkout' , 'fs_affiliates_checkout_params' , array(
				'affs_selection' => $this->affs_selection,
				'radio_default'  => $radio_default,
				'checkout_affiliate'  => wp_create_nonce( 'fs-checkout-affiliate-nonce' ),
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'is_checkout'   => is_checkout() && empty( is_wc_endpoint_url('order-received') ),
					)
			) ;

			wp_enqueue_script( 'fs-affiliates-checkout' , FS_AFFILIATES_PLUGIN_URL . '/assets/js/frontend/checkout.js' , array( 'jquery' ) , FS_AFFILIATES_VERSION ) ;

			$affiliates = fs_affiliates_get_active_affiliates() ;

			if ( $this->allowed_affiliates_method == '2' ) {
				$affiliates = array_filter( array_unique( array_intersect( $this->selected_affiliates , $affiliates ) ) ) ;
			}

			if ( ! fs_affiliates_check_is_array( $affiliates ) ) {
				return ;
			}

			$affiliate_options = array( '' => __( 'None' , FS_AFFILIATES_LOCALE ) ) ;
			$current_affiliate = fs_affiliates_is_user_having_affiliate() ;

			foreach ( $affiliates as $affiliate_id ) {
				if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
					continue ;
				}

				$affiliate = get_post( $affiliate_id ) ;
				$user_id   = $affiliate->post_author ;
				$user      = get_user_by( 'id' , $user_id ) ;

				if ( $this->display_style == '3' ) {
					$affiliate_options[ $affiliate_id ] = $user->nickname ;
				} else if ( $this->display_style == '2' ) {
					$affiliate_options[ $affiliate_id ] = $user->display_name ;
				} else {
					$affiliate_options[ $affiliate_id ] = $affiliate->post_title ;
				}
			}

			if ( $this->affs_selection == 3 ) {
				?>
				<span class="woocommerce-input-wrapper">
					<label><?php echo $this->selection_title ; ?></label>
					<br><input type="radio" class="input-radio " value="1" name="affiliate_referrer_radio" 
					<?php
					if ( $radio_default == 1 ) {
						?>
								   checked="checked" <?php } ?> id="affiliate_referrer_radio_1">
					<label for="affiliate_referrer_radio_1" class="radio "> <?php echo $this->selection_value1 ; ?> </label>
					<br><input type="radio" class="input-radio " value="2" name="affiliate_referrer_radio" id="affiliate_referrer_radio_2" 
					<?php
					if ( $radio_default == 2 ) {
						?>
								   checked="checked" <?php } ?> >
					<label for="affiliate_referrer_radio_2" class="radio "> <?php echo $this->selection_value2 ; ?> </label>
				</span>
			<?php } ?>

			<?php if ('yes' != get_option('fs_affiliates_checkout_affiliate_disable_user_selection')) : ?>
				<p class="form-row affiliate_referrer_fields" id="affiliate_referrer_fields">
					<label>
						<?php
						echo $this->label ;

						if ( $this->affs_selection != 1 ) {
							?>
							<abbr class="required" title="required">*</abbr>
						<?php } ?>
					</label> 

					<span class="woocommerce-input-wrapper">
						<select name="affiliate_referrer" id="affiliate_referrer" class="input-text affiliate_referrer">
							<?php
							foreach ( $affiliate_options as $affs_id => $each_options ) {
								?>
								<option value="<?php echo $affs_id ; ?>"><?php echo $each_options ; ?></option>
							<?php } ?>
						</select>
					</span>
				</p>
			<?php else : ?>
				<p>
					<input type="hidden" name="affiliate_referrer" id="affiliate_referrer" value=""/>
				</p>
			<?php endif; ?>
			<?php
		}

		public function woocommerce_checkout_process_validation() {
			$notice_content = sprintf( esc_html__( '%1$s Select Affiliate %2$s is a required field' , FS_AFFILIATES_LOCALE ) , '<b>' , '</b>' ) ;

			if ( isset( $_POST[ 'affiliate_referrer' ] ) && ( '' == $_POST[ 'affiliate_referrer' ] && ( $this->affs_selection == 2 ) ) ) {
				wc_add_notice( wp_kses_post( $notice_content ) , 'error' ) ;
			}

			if ( isset( $_POST[ 'affiliate_referrer_radio' ] ) && isset( $_POST[ 'affiliate_referrer' ] ) ) {

				if ( ( 1 == $_POST[ 'affiliate_referrer_radio' ] ) && ( '' == $_POST[ 'affiliate_referrer' ] ) ) {
					wc_add_notice( wp_kses_post( $notice_content ) , 'error' ) ;
				}
			}
		}

		public function process_valid_block_checkout_fields( $errors ) {
			if ( isset( $_POST[ 'affiliate_referrer' ] ) && ( '' == $_POST[ 'affiliate_referrer' ] && ( $this->affs_selection == 2 ) ) ) {
				$errors->add( 'affs-reg-error' , __( 'Select Affiliate is a required field' , FS_AFFILIATES_LOCALE ) ) ;
			}

			if ( isset( $_POST[ 'affiliate_referrer_radio' ] ) && isset( $_POST[ 'affiliate_referrer' ] ) ) {

				if ( ( 1 == $_POST[ 'affiliate_referrer_radio' ] ) && ( '' == $_POST[ 'affiliate_referrer' ] ) ) {
					$errors->add( 'affs-reg-error' , __( 'Select Affiliate is a required field' , FS_AFFILIATES_LOCALE ) ) ;
				}
			}
		}

		public function woocommerce_checkout_update_order_meta( $order_id ) {
			$order_id = is_object( $order_id ) ? $order_id->get_id() : $order_id ;
			$order    = wc_get_order( $order_id ) ;
			if ( ! is_object( $order ) ) {
				return ;
			}

			if ( isset( $_POST[ 'affiliate_referrer' ] ) && ! empty( $_POST[ 'affiliate_referrer' ] ) ) {
				$order->update_meta_data( 'fs_affiliate_in_order' , $_POST[ 'affiliate_referrer' ] ) ;

				$commission = FS_Affiliates_WC_Commission::award_commission_for_product_purchase( $order_id , $_POST[ 'affiliate_referrer' ] ) ;
				$order->update_meta_data( 'fs_commission_to_be_awarded_in_order' , $commission ) ;
				$order->save() ;
			}
		}
	}

}
	