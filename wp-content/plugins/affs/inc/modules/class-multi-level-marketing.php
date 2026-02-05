<?php
/**
 * Multi Level Marketing
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Multi_Level_Marketing')) {

	/**
	 * Class FS_Affiliates_Multi_Level_Marketing
	 */
	class FS_Affiliates_Multi_Level_Marketing extends FS_Affiliates_Modules {
		
		/**
	 * Referrals Count.
	 *
	 * @var string
	 */
		protected $referrals_count;
		
		/**
	 * Rules.
	 *
	 * @var string
	 */
		protected $rules;
		
		/**
	 * Display Data Enable.
	 *
	 * @var string
	 */
		protected $display_data_enable;
		
		/**
	 * Data Display.
	 *
	 * @var array
	 */
		protected $data_display;
		
		/**
	 * Commission Type.
	 *
	 * @var string
	 */
		protected $commission_type;
		
		/**
	 * Commission Value.
	 *
	 * @var string
	 */
		protected $commission_value;
		
		/*
		 * Data
		 */
		protected $data = array(
			'enabled' => 'no',
			'referrals_count' => '',
			'rules' => array(),
			'display_data_enable' => 'no',
			'data_display' => array(),
			'commission_type' => 'percentage_commission',
			'commission_value' => '',
		);

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id = 'multi_level_marketing';
			$this->title = __('Multi Level Marketing', FS_AFFILIATES_LOCALE);

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
					'title' => __('Multi Level Marketing', FS_AFFILIATES_LOCALE),
					'desc' => sprintf(__('%s - Use this shortcode to display the MLM Tree for Affiliates', FS_AFFILIATES_LOCALE), '[fs_affiliates_mlm_tree]'),
					'id' => 'multi_level_marketing_options',
				),
				array(
					'title' => __('Number of Direct Referrals', FS_AFFILIATES_LOCALE),
					'desc' => __('This option controls the number of referrals for which an affiliate can earn a commission. Once the limit is reached, the affiliate can only earn commissions through their child affiliates.', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_referrals_count',
					'type' => 'number',
					'default' => '',
				),
				array(
					'type' => 'sectionend',
					'id' => 'multi_level_marketing_options',
				),
				array(
					'type' => 'title',
					'title' => __('Affiliate Level Depth', FS_AFFILIATES_LOCALE),
					'id' => 'multi_level_marketing_affiliate_level',
				),
				array(
					'type' => 'mlm_rules',
					'default' => $this->default_rules(),
				),
				array(
					'type' => 'sectionend',
					'id' => 'multi_level_marketing_affiliate_level',
				),
				array(
					'type' => 'title',
					'title' => __('MLM Graph Settings', FS_AFFILIATES_LOCALE),
					'id' => 'multi_level_marketing_graph_settings',
				),
				array(
					'title' => 'Display Affiliate Details in the MLM Graph',
					'id' => $this->plugin_slug . '_' . $this->id . '_display_data_enable',
					'type' => 'checkbox',
					'default' => 'no',
				),
				array(
					'title' => __('Details to Display', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_data_display',
					'type' => 'multiselect',
					'class' => 'fs_affiliates_select2 fs_affiliates_node_link_display',
					'default' => array( 'first_name', 'last_name', 'total_commission_earned' ),
					'options' => array(
						'first_name' => __('First Name', FS_AFFILIATES_LOCALE),
						'last_name' => __('Last Name', FS_AFFILIATES_LOCALE),
						'user_name' => __('Username', FS_AFFILIATES_LOCALE),
						'email' => __('Email', FS_AFFILIATES_LOCALE),
						'country' => __('Country', FS_AFFILIATES_LOCALE),
						'website' => __('Website', FS_AFFILIATES_LOCALE),
						'phone_number' => __('Phone Number', FS_AFFILIATES_LOCALE),
						'commission_value' => __('Total Commission Earned', FS_AFFILIATES_LOCALE),
					),
				), array(
					'title' => __('Total Commission Earned Label', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_commission_value',
					'class' => 'fs_affiliates_node_link_display',
					'type' => 'text',
					'default' => '',
				),
				array(
					'type' => 'sectionend',
					'id' => 'multi_level_marketing_graph_settings',
				),
			);
		}

		/*
		 * Admin action
		 */

		public function admin_action() {

			//Product Levele settings.
			add_action('woocommerce_product_data_panels', array( $this, 'product_data_panel' ));
			add_action('fs_affiliates_product_settings_saved', array( $this, 'save_product_level_settings' ), 10, 2);

			// Module level settings.
			add_action('wp_ajax_fs_affiliates_get_mlm_global_rule_html', array( __CLASS__, 'get_global_mlm_rule_html' ));
			add_action($this->plugin_slug . '_admin_field_mlm_rules', array( $this, 'render_global_rules' ));

			// Category level settings.
			add_action('product_cat_add_form_fields', array( $this, 'render_category_add_form_rules' ));
			add_action('product_cat_edit_form_fields', array( $this, 'render_category_edit_form_rules' ), 10, 2);
			add_action('created_term', array( $this, 'save_category_level_settings' ), 10, 3);
			add_action('edit_term', array( $this, 'save_category_level_settings' ), 10, 3);
			add_action('wp_ajax_fs_affiliates_get_mlm_category_rule_html', array( __CLASS__, 'get_category_mlm_rule_html' ));
			add_action('wp_ajax_fs_affiliates_get_mlm_product_rule_html', array( __CLASS__, 'get_product_mlm_rule_html' ));
		}

		/*
		 * Default Rules
		 */

		public function default_rules() {
			return array( '1' => array( 'commission_type' => 'percentage_commission', 'commission_value' => '10' ) );
		}

		public function check_direct_referral_threshold( $bool, $affiliate_id ) {

			if (empty($this->referrals_count)) {
				return $bool;
			}

			$count = fs_affiliates_get_referrals_count($affiliate_id);

			if ($this->referrals_count < $count) {
				return false;
			}

			return $bool;
		}

		/*
		 * Get Rules
		 */

		public function get_rules() {
			if (fs_affiliates_check_is_array($this->rules)) {
				return $this->rules;
			}

			return $this->default_rules();
		}

		/**
		 * Get a MLM rule HTML for global level.
		 */
		public static function get_global_mlm_rule_html() {
			check_ajax_referer('fs-mlm-nonce', 'fs_security');

			try {
				if (!isset($_POST) || !isset($_POST['count'])) {
					throw new exception(__('Invalid Request', FS_AFFILIATES_LOCALE));
				}

				$key = wc_clean(wp_unslash($_POST['count']));
				$name = 'fs_affiliates_multi_level_marketing_rules[' . $key . ']';

				ob_start();
				include_once 'views/mlm-global-rule.php';
				$content = ob_get_clean();

				wp_send_json_success(array( 'content' => $content ));
			} catch (Exception $e) {
				wp_send_json_error(array( 'error' => $e->getMessage() ));
			}
		}

		/**
		 * Render the global MLM rules.
		 */
		public function render_global_rules() {
			$rules = $this->get_rules();

			include_once 'views/mlm-global-rules.php';
		}

		/**
		 * Get a MLM rule HTML for category level.
		 */
		public static function get_category_mlm_rule_html() {
			check_ajax_referer('fs-mlm-nonce', 'fs_security');

			try {
				if (!isset($_POST) || !isset($_POST['count'])) {
					throw new exception(__('Invalid Request', FS_AFFILIATES_LOCALE));
				}

				$key = wc_clean(wp_unslash($_POST['count']));
				$name = 'fs_affiliates_mlm_rules[' . $key . ']';

				ob_start();
				include_once 'views/mlm-category-rule.php';
				$content = ob_get_clean();

				wp_send_json_success(array( 'content' => $content ));
			} catch (Exception $e) {
				wp_send_json_error(array( 'error' => $e->getMessage() ));
			}
		}

		/**
		 * Get a MLM rule HTML for product level.
		 *
		 * @since 9.0 
		 */
		public static function get_product_mlm_rule_html() {
			check_ajax_referer('fs-mlm-nonce', 'fs_security');

			try {
				if (!isset($_POST) || !isset($_POST['count'])) {
					throw new exception(__('Invalid Request', FS_AFFILIATES_LOCALE));
				}

				$key = wc_clean(wp_unslash($_POST['count']));
				$name = 'fs_affiliates_mlm_rules[' . $key . ']';

				ob_start();
				include_once 'views/edit-product-mlm-rules.php';
				$content = ob_get_clean();

				wp_send_json_success(array( 'content' => $content ));
			} catch (Exception $e) {
				wp_send_json_error(array( 'error' => $e->getMessage() ));
			}
		}

		/**
		 * Render the category add form MLM rules.
		 */
		public function render_category_add_form_rules() {
			$mode = 1;
			$rules = array();

			include_once 'views/add-category-mlm-rules.php';
		}

		/**
		 * Render the category edit form MLM rules.
		 */
		public function render_category_edit_form_rules( $term, $taxonomy ) {
			if (!is_object($term)) {
				return;
			}

			$mode = get_term_meta($term->term_id, 'fs_affiliates_mlm_mode', true);
			$rules = get_term_meta($term->term_id, 'fs_affiliates_mlm_rules', true);

			include_once 'views/edit-category-mlm-rules.php';
		}

		/**
		 * MLM Product data Panel.
		 * */
		public function product_data_panel() {
			global $post;
			$mode = get_post_meta($post->ID, 'fs_affiliates_product_mlm_mode', true);
			$rules = get_post_meta($post->ID, 'fs_affiliates_mlm_rules', true);

			include_once 'views/add-product-mlm-rules.php';
		}

		/**
		 * Save the MLM setting in the product.
		 */
		public function save_product_level_settings( $PostId, $Post ) {
			$enable_mlm = isset($_POST['fs_affiliate_product_level_mlm']) ? $_POST['fs_affiliate_product_level_mlm'] : 'no';
			update_post_meta($PostId, 'fs_affiliate_product_level_mlm', $enable_mlm);

			$mode = isset($_POST['fs_affiliates_product_mlm_mode']) ? wc_clean(wp_unslash($_POST['fs_affiliates_product_mlm_mode'])) : '1';
			$rules = isset($_POST['fs_affiliates_mlm_rules']) ? wc_clean(wp_unslash($_POST['fs_affiliates_mlm_rules'])) : array();

			update_post_meta($PostId, 'fs_affiliates_product_mlm_mode', $mode);
			update_post_meta($PostId, 'fs_affiliates_mlm_rules', $this->format_rules($rules));
		}

		/**
		 * Save the MLM setting in the category.
		 */
		public function save_category_level_settings( $term_id, $id, $taxonomy ) {
			$mode = isset($_POST['fs_affiliates_mlm_mode']) ? wc_clean(wp_unslash($_POST['fs_affiliates_mlm_mode'])) : array();
			$rules = isset($_POST['fs_affiliates_mlm_rules']) ? wc_clean(wp_unslash($_POST['fs_affiliates_mlm_rules'])) : array();

			update_term_meta($term_id, 'fs_affiliates_mlm_mode', $mode);
			update_term_meta($term_id, 'fs_affiliates_mlm_rules', $this->format_rules($rules));
		}

		/*
		 * is affs data enable
		 */

		public function is_affs_data_diplay( $bool ) {

			if ($this->display_data_enable == 'yes') {
				return true;
			}

			return $bool;
		}

		/*
		 * Save
		 */

		public function save() {
			if (!isset($_POST[$this->plugin_slug . '_' . $this->id . '_rules'])) {
				return;
			}

			$rules = $_POST[$this->plugin_slug . '_' . $this->id . '_rules'];

			if (!fs_affiliates_check_is_array($rules)) {
				return;
			}

			$saving_rules = $this->format_rules($rules);

			$this->rules = $saving_rules;
			$this->update_option('rules', $saving_rules);
		}

		/**
		 * Format the rules to save.
		 * 
		 * @since 9.2.0
		 * @param array $rules
		 * @return array
		 */
		public function format_rules( $rules ) {
			$saving_rules = array();
			$key = 1;
			foreach ($rules as $rule) {
				$rule['commission_value'] = isset($rule['commission_value']) ? fs_affiliates_format_decimal($rule['commission_value'], true) : '';
				$saving_rules[$key] = $rule;
				$key++;
			}

			return $saving_rules;
		}

		public function get_affs_content() {
			if (isset($_GET['get_affs_content'])) {

				$fields = fs_affiliates_get_form_fields();

				$affs_id = $_GET['affs_id'];
				$affilate_data = new FS_Affiliates_Data($affs_id);

				if (!fs_affiliates_check_is_array($this->data_display)) {
					return;
				}
				$datas_to_display = $this->data_display;
				$formated_value = ' - ';
				?>
				<style>
					.fs_affiliates_mlm_table{
						border:1px solid #000;
						border-collapse:collapse;
					}
					.fs_affiliates_mlm_table tr th{
						background:#093f77;
						color:#fff;
						padding:20px 10px 20px 40px;
						text-align:left;
						border-bottom:1px solid #fff;
					}
					.fs_affiliates_mlm_table tr td{
						padding:20px 10px 20px 40px;
						text-align:left;
						border-bottom:1px solid #000;
					}
				</style>
				<table width="600px" class="fs_affiliates_mlm_table" >
					<?php
					foreach ($datas_to_display as $each_data) {

						$formated_value = '';
						//Head Labels
						if (isset($fields[$each_data]['field_key']) && $fields[$each_data]['field_key'] == $each_data) {
							$field_label = $fields[$each_data]['field_name'];
						} else {
							if ($each_data == 'phone_number') {
								$field_label = $fields['phonenumber']['field_name'];
							}
							if ($each_data == 'commission_value') {
								$field_label = $this->$each_data;
							}
						}

						//TD Values
						if ($each_data == 'commission_value') {
							$formated_value = $affilate_data->get_paid_commission();
						} elseif (isset($affilate_data->$each_data) && !empty($affilate_data->$each_data)) {
								$formated_value = $affilate_data->$each_data;
						}
						?>
						<tr align="center">
							<th><?php echo $field_label; ?></th>
							<td><?php echo $formated_value; ?></td>
						</tr>
					<?php } ?>
				</table>
				<?php
				exit();
			}
		}

		/**
		 * Actions
		 */
		public function actions() {
			add_filter('fs_affiliates_check_direct_referral_threshold', array( $this, 'check_direct_referral_threshold' ), 10, 2);
			add_action('fs_affiliates_create_referrals', array( $this, 'insert_commission_based_on_mlm' ), 10, 3);
			add_action('init', array( $this, 'get_affs_content' ));
			add_filter('fs_affiliates_is_affs_data_diplay', array( $this, 'is_affs_data_diplay' ));
			add_action('fs_affiliates_order_commission', array( $this, 'update_category_level_mlm_rules' ), 10, 4);
		}

		/**
		 * Update the category level MLM rules in the order meta.
		 */
		public function update_category_level_mlm_rules( $commission, $order_id, $order, $affiliate_id ) {
			if (!is_object($order)) {
				return $commission;
			}

			if (!fs_affiliates_check_is_array($order->get_items())) {
				return $commission;
			}

			$mlm_rules = array();
			foreach ($order->get_items() as $key => $item) {
				$rules = self::get_product_category_level_mlm_rules($item['product_id']);
				if (!fs_affiliates_check_is_array($rules)) {
					continue;
				}

				$regular_price = FS_Affiliates_WC_Commission::get_regular_price($item, $order);
				$regular_price = apply_filters('fs_affiliate_regular_price_for_purchase', $regular_price, $order_id, $item);
				$restricted_product = apply_filters('fs_affiliates_is_restricted_product', true, $item['product_id'], $item['variation_id']);
				$allowed_commission = FS_Affiliates_WC_Commission::is_restricted_own_commission($affiliate_id, $order->get_user_id());
				if (!$restricted_product || !$allowed_commission) {
					continue;
				}

				$mlm_rules[] = array( 'amount' => $regular_price, 'rules' => $rules );
			}

			$order->update_meta_data('fs_category_level_mlm_rules', $mlm_rules);
			$order->save();

			return $commission;
		}

		/**
		 * 
		 * @param type $product_id
		 * @return array/bool
		 */
		public function get_product_category_level_mlm_rules( $product_id ) {
			$mlm_rules = array();

			if (empty($product_id)) {
				return $mlm_rules;
			}

			$product_level_mlm = get_post_meta($product_id, 'fs_affiliate_product_level_mlm', true);
			$mlm_mode = get_post_meta($product_id, 'fs_affiliates_product_mlm_mode', true);

			if ('no' == $product_level_mlm || ( 'yes' == $product_level_mlm && '2' == $mlm_mode )) {
				if (!fs_affiliates_check_is_array($mlm_rules)) {
					$terms = wp_get_post_terms($product_id, 'product_cat');
					if (!fs_affiliates_check_is_array($terms)) {
						return;
					}

					foreach ($terms as $term) {
						if (!is_object($term)) {
							continue;
						}

						$mode = get_term_meta($term->term_id, 'fs_affiliates_mlm_mode', true);
						if ('2' != $mode) {
							continue;
						}

						$mlm_rules = get_term_meta($term->term_id, 'fs_affiliates_mlm_rules', true);
						break;
					}
				}
			} else if ('yes' == $product_level_mlm && '3' == $mlm_mode) {
				$mlm_rules = get_post_meta($product_id, 'fs_affiliates_mlm_rules', true);
				$mlm_rules = ( $mlm_rules ) ? $mlm_rules : array();
			}

			return $mlm_rules;
		}

		public function insert_commission_based_on_mlm( $affiliate_id, $referral_args, $post_args ) {

			if (isset($referral_args['is_mlm']) && $referral_args['is_mlm'] == 'no') {
				return;
			}

			$i = 1;
			$referral_ids = array();
			$prepare_rules = array();
			$category_amount = 0;

			$amount = isset($referral_args['original_price']) ? $referral_args['original_price'] : $referral_args['amount'];
			$referral_description = $referral_args['description'];
			$description = get_option('fs_affiliates_referral_desc_mlm_label', 'MLM Level {affiliate_level} Commission for {referral_actions}');
			$default_desc_shortcodes = array( '{affiliate_level}', '{referral_actions}' );

			if (isset($referral_args['mlm_rules']) && fs_affiliates_check_is_array($referral_args['mlm_rules'])) {

				foreach ($referral_args['mlm_rules'] as $commission => $rules) {
					$prepare_rules = $this->get_level($affiliate_id, $rules['amount'], $prepare_rules, $rules['rules']);
					$category_amount += $rules['amount'];
				}
			}

			$amount = ( $category_amount < $amount ) ? $amount - $category_amount : 0;
			$prepare_rules = $this->get_level($affiliate_id, $amount, $prepare_rules);

			foreach ($prepare_rules as $parent_affiliate_id => $amount) {
				$referral_args['amount'] = $amount;
				$referral_args['description'] = str_replace($default_desc_shortcodes, array( $i, $referral_description ), $description);
				$ReferralObj = new FS_Affiliates_Referrals();
				$post_args['post_author'] = $parent_affiliate_id;
				$referral_ids[$parent_affiliate_id] = $ReferralObj->create($referral_args, $post_args);
				do_action('fs_affiliates_new_mlm_referral', $referral_ids[$parent_affiliate_id], $parent_affiliate_id);
				$i++;
			}

			return $referral_ids;
		}

		public function get_level( $affiliate_id, $amount, $levels = false, $rules = false ) {
			$levels = ( $levels ) ? $levels : array();
			if (empty($amount)) {
				return $levels;
			}
			$rules = ( $rules ) ? $rules : $this->rules;
			if (!fs_affiliates_check_is_array($rules)) {
				return $levels;
			}

			foreach ($rules as $key => $rule) {
				$affiliates_object = new FS_Affiliates_Data($affiliate_id);
				$affiliate_id = $affiliates_object->parent;

				if (!$affiliate_id) {
					break;
				}

				if (!$rule['commission_value']) {
					break;
				}

				$commission = ( isset($rule['commission_type']) && 'fixed_commission' === $rule['commission_type'] ) ? $rule['commission_value'] : ( ( (float) $amount * (float) $rule['commission_value'] ) / 100 );

				$levels[$affiliate_id] = isset($levels[$affiliate_id]) ? $levels[$affiliate_id] + $commission : $commission;
			}

			return $levels;
		}
	}

}
