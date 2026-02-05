<?php
/*
 * Common functions
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

include_once 'fs-affiliates-post-common-functions.php';
include_once 'fs-affiliates-default-common-functions.php';
include_once 'fs-affiliates-font-awesome-codes.php';
include_once 'class-fs-affiliates-layout-functions.php';
include_once 'fs-affiliates-store-api-functions.php';

if (!function_exists('fs_affiliates_get_eligible_affiliates')) {

	function fs_affiliates_get_eligible_affiliates() {
		$eligible_affiliates = get_option('fs_affiliatesaffiliate_wallet_selected_affiliates');
		return fs_affiliates_check_is_array($eligible_affiliates) ? $eligible_affiliates : '';
	}

}

if (!function_exists('fs_affiliates_check_is_array')) {

	/**
	 * Function to check given a variable is array and not empty
	 * */
	function fs_affiliates_check_is_array( $array ) {
		if (is_array($array) && !empty($array)) {
			return true;
		} else {
			return false;
		}
	}

}
if (!function_exists('fp_affiliates_get_order_statuses')) {

	/**
	 * Function to order status
	 * */
	function fp_affiliates_get_order_statuses() {
		$order_statuses = array();
		if (function_exists('wc_get_order_statuses')) {
			$wc_order_statuses = wc_get_order_statuses();
			$orderstatuses = str_replace('wc-', '', array_keys($wc_order_statuses));
			$orderslugs = array_values($wc_order_statuses);
			$order_statuses = array_combine((array) $orderstatuses, (array) $orderslugs);
		}

		return $order_statuses;
	}

}
if (!function_exists('fs_affiliates_get_page_ids')) {

	/**
	 * Function to prepare page ids
	 * */
	function fs_affiliates_get_page_ids() {
		$format_page_ids = array();
		$pages = get_pages();

		if (!fs_affiliates_check_is_array($pages)) {
			return $format_page_ids;
		}

		foreach ($pages as $page) {

			if (!is_object($page)) {
				continue;
			}

			$format_page_ids[$page->ID] = $page->post_title;
		}

		return $format_page_ids;
	}

}


if (!function_exists('fs_affiliates_get_page_id')) {

	/**
	 * Function to get page id
	 * */
	function fs_affiliates_get_page_id( $page_name = 'register' ) {

		return get_option('fs_affiliates_' . $page_name . '_page_id');
	}

}


if (!function_exists('fs_affiliates_insert_user')) {

	/**
	 * Function to insert a user
	 * */
	function fs_affiliates_insert_user( $userdata ) {

		if (!fs_affiliates_check_is_array($userdata)) {
			return false;
		}

		$user_id = wp_insert_user($userdata);

		if (is_wp_error($user_id)) {
			$user_id = false;
		}

		update_user_meta($user_id, 'fs_affiliates_created', 'yes');

		return $user_id;
	}

}

if (!function_exists('fs_affiliates_get_user_roles')) {

	function fs_affiliates_get_user_roles() {
		global $wp_roles;
		$user_roles = array();

		if (!isset($wp_roles->roles) || !fs_affiliates_check_is_array($wp_roles->roles)) {
			return $user_roles;
		}

		foreach ($wp_roles->roles as $slug => $role) {
			$user_roles[$slug] = $role['name'];
		}

		return $user_roles;
	}

}

if (!function_exists('fs_affiliates_check_if_woocommerce_is_active')) {

	/**
	 * Function to check whether WooCommerce is active or not
	 * */
	function fs_affiliates_check_if_woocommerce_is_active() {

		if (is_multisite() && !is_plugin_active_for_network('woocommerce/woocommerce.php') && ( !is_plugin_active('woocommerce/woocommerce.php') )) {
			// This Condition is for Multi Site WooCommerce Installation
			return false;
		} elseif (!is_plugin_active('woocommerce/woocommerce.php')) {
			// This Condition is for Single Site WooCommerce Installation
			return false;
		}

		return true;
	}

}

if (!function_exists('fs_affiliates_check_if_reward_points_is_active')) {

	/**
	 * Function to check whether WooCommerce is active or not
	 * */
	function fs_affiliates_check_if_reward_points_is_active() {

		if (is_multisite() && !is_plugin_active_for_network('rewardsystem/rewardsystem.php') && ( !is_plugin_active('rewardsystem/rewardsystem.php') )) {
			// This Condition is for Multi Site WooCommerce Installation
			return false;
		} elseif (!is_plugin_active('rewardsystem/rewardsystem.php')) {
			// This Condition is for Single Site WooCommerce Installation
			return false;
		}

		return true;
	}

}

if (!function_exists('fs_affiliates_check_if_sumo_memberships_is_active')) {

	/**
	 * Function to check whether Sumo Memberships is active or not
	 * */
	function fs_affiliates_check_if_sumo_memberships_is_active() {

		if (is_multisite() && !is_plugin_active_for_network('sumomemberships/sumomemberships.php') && ( !is_plugin_active('sumomemberships/sumomemberships.php') )) {
			return false;
		} elseif (!is_plugin_active('sumomemberships/sumomemberships.php')) {
			return false;
		}

		return true;
	}

}

if (!function_exists('fs_affiliates_select2_html')) {

	/**
	 * Function to display Select2 html
	 * */
	function fs_affiliates_select2_html( $args, $echo = true ) {
		$args = wp_parse_args($args, array(
			'class' => '',
			'id' => '',
			'name' => '',
			'list_type' => '',
			'action' => '',
			'placeholder' => '',
			'css' => '',
			'multiple' => true,
			'allow_clear' => true,
			'selected' => true,
			'options' => array(),
		));

		$multiple = $args['multiple'] ? 'multiple="multiple"' : '';
		$name = esc_attr('' !== $args['name'] ? $args['name'] : $args['id']) . '[]';

		ob_start();
		?><select <?php echo $multiple; ?>
			name="<?php echo $name; ?>"
			id="<?php echo esc_attr($args['id']); ?>"
			data-action="<?php echo esc_attr($args['action']); ?>"
			class="fs_affiliates_select2_search <?php echo esc_attr($args['class']); ?>"
			data-placeholder="<?php echo esc_attr($args['placeholder']); ?>"
			<?php echo $args['allow_clear'] ? 'data-allow_clear="true"' : ''; ?>
			style="<?php echo esc_attr($args['css']); ?>">
				<?php
				if (is_array($args['options'])) {
					foreach ($args['options'] as $option_id) {
						$option_value = '';
						switch ($args['list_type']) {

							case 'affiliates':
								$affiliates = new FS_Affiliates_Data($option_id);
								if (is_object($affiliates) && $affiliates->get_status()) {
									$option_value = esc_html(esc_html($affiliates->first_name . ' ' . $affiliates->last_name) . '(#' . absint($affiliates->get_id()) . ' &ndash; ' . esc_html($affiliates->email) . ')');
								}
								break;
							case 'coupons':
								$option_value = esc_html(esc_html(get_the_title($option_id)));
								break;
							case 'customers':
								if ($user = get_user_by('id', $option_id)) {
									$option_value = esc_html(esc_html($user->display_name) . '(#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')');
								}
								break;
							case 'post':
								$option_value = esc_html(get_the_title($option_id));
								break;
							case 'products':
								$option_value = esc_html(get_the_title($option_id)) . ' (#' . absint($option_id) . ')';
								break;
						}

						if ($option_value) {
							?>
						<option value="<?php echo esc_attr($option_id); ?>" <?php echo $args['selected'] ? 'selected="selected"' : ''; ?>><?php echo $option_value; ?></option>
							<?php
						}
					}
				}
				?>
		</select>
		<?php
		$html = ob_get_clean();

		if ($echo) {
			echo $html;
		}

		return $html;
	}

}


if (!function_exists('fs_affiliates_array_merge_recursive_distinct')) {

	/**
	 * Prepare array merge recursive distinct
	 */
	function fs_affiliates_array_merge_recursive_distinct( array &$custom, array &$default ) {
		$merged = $custom;
		foreach ($default as $key => &$value) {

			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = fs_affiliates_array_merge_recursive_distinct($value, $merged[$key]);
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}

}
if (!function_exists('fs_affiliates_array_merge_based_on_first')) {

	/**
	 * array merge based on first array
	 */
	function fs_affiliates_array_merge_based_on_first( array &$custom, array &$default ) {
		$merged = array();

		foreach ($default as $key => &$value) {
			$merged[$key] = isset($custom[$key]) ? $custom[$key] : $value;
		}

		return $merged;
	}

}

if (!function_exists('fs_affiliates_get_form_fields')) {

	/**
	 * Prepare form fields
	 */
	function fs_affiliates_get_form_fields() {
		$default_fields = fs_affiliates_get_default_form_fields();
		$form_fields = (array) get_option('fs_affiliates_frontend_form_fields');

		return array_filter(fs_affiliates_array_merge_recursive_distinct($form_fields, $default_fields));
	}

}

if (!function_exists('fs_affiliates_get_form_fields_status')) {

	/**
	 * Prepare form fields
	 */
	function fs_affiliates_get_form_fields_status( $field_type ) {

		$selected_fields = fs_affiliates_get_form_fields();
		if ($selected_fields["$field_type"]['field_status'] == 'enabled') {
			return true;
		}

		return false;
	}

}




if (!function_exists('fs_affiliates_get_opt_in_form_fields')) {

	/**
	 * Prepare form fields
	 */
	function fs_affiliates_get_opt_in_form_fields() {
		$default_fields = fs_affiliates_get_default_opt_in_form_fields();

		$form_fields = (array) get_option('fs_affiliates_opt_in_form_fields');

		return array_filter(fs_affiliates_array_merge_recursive_distinct($form_fields, $default_fields));
	}

}

if (!function_exists('fs_affiliates_get_account_creation_type')) {

	/**
	 * Get affiliate account creation type
	 */
	function fs_affiliates_get_account_creation_type( $user_id = 'default' ) {

		if ($user_id == 'default') {
			$user_id = get_current_user_id();
		}

		$account_type = get_option('fs_affiliates_account_creation_type', 'existing_account');

		return ( !empty($user_id) ) ? $account_type : 'new_account';
	}

}

if (!function_exists('fs_get_affiliate_id_for_user')) {

	function fs_get_affiliate_id_for_user( $UserId ) {
		$CheckIfAffiliate = get_user_meta($UserId, 'fs_affiliates_enabled', true);

		if ($CheckIfAffiliate != 'yes') {
			return false;
		}

		$Args = array(
			'post_type' => 'fs-affiliates',
			'post_status' => 'fs_active',
			'author' => $UserId,
			'fields' => 'ids',
		);

		$AffiliateID = get_posts($Args);

		if (!fs_affiliates_check_is_array($AffiliateID)) {
			return false;
		}

		return $AffiliateID[0];
	}

}

if (!function_exists('fs_affiliates_get_cookie_validity_value')) {

	function fs_affiliates_get_cookie_validity_value( $cookieValidity = false ) {
		if (!$cookieValidity) {
			$cookieValidity = get_option('fs_affiliates_referral_cookie_validity');
		}

		$unit = isset($cookieValidity['unit']) ? $cookieValidity['unit'] : '';
		$number = (float) ( !empty($cookieValidity['number']) ? $cookieValidity['number'] : '1' );

		switch ($unit) {
			case 'months':
				$validity = 2.628e+6 * $number;
				break;
			case 'weeks':
				$validity = 604800 * $number;
				break;
			default:
				$validity = 86400 * $number;
				break;
		}

		return $validity;
	}

}

if (!function_exists('fp_affiliates_get_categories')) {

	function fp_affiliates_get_categories() {
		$categorylist = array();
		$categoryname = array();
		$categoryid = array();

		$categories = get_terms('product_cat');

		if (is_wp_error($categories) || !fs_affiliates_check_is_array($categories)) {
			return $categorylist;
		}

		foreach ($categories as $category) {
			$categoryname[] = $category->name;
			$categoryid[] = $category->term_id;
		}

		$categorylist = array_combine((array) $categoryid, (array) $categoryname);

		return $categorylist;
	}

}

if (!function_exists('fs_affiliates_get_id_from_cookie')) {

	function fs_affiliates_get_id_from_cookie( $cookie_name, $default = 0 ) {
		if (!isset($_COOKIE[$cookie_name])) {
			return $default;
		}

		$cookie_value = base64_decode(stripslashes($_COOKIE[$cookie_name]));

		return $cookie_value;
	}

}

if (!function_exists('fs_affiliates_get_paymethod_preference_status')) {

	function fs_affiliates_get_paymethod_preference_status( $paykey = '' ) {


		$options_status = array(
			'direct' => 'enable',
			'paypal' => 'enable',
		);

		$status = apply_filters('fs_affiliates_custom_payment_preference_status', $options_status);

		if ($paykey == '') {
			return $status;
		}

		return isset($status[$paykey]) ? $status[$paykey] : '';
	}

}

if (!function_exists('fs_affiliates_paymethod_preference')) {

	function fs_affiliates_paymethod_preference() {

		$payment_preference = get_option('fs_affiliates_payment_preference', array( 'direct' => 'enable', 'paypal' => 'enable', 'wallet' => 'enable' ));
		$status = fs_affiliates_get_paymethod_preference_status();

		return array_merge($status, $payment_preference);
	}

}

if (!function_exists('fs_affiliates_get_paymethod_preference')) {

	function fs_affiliates_get_paymethod_preference( $paykey = '' ) {

		$options = array(
			'direct' => __('Bank Transfer', FS_AFFILIATES_LOCALE),
			'paypal' => __('Paypal', FS_AFFILIATES_LOCALE),
		);

		$available_paymethods = apply_filters('fs_affiliates_custom_payment_preference_option', $options);

		if ($paykey == '') {
			return $available_paymethods;
		}

		return isset($available_paymethods[$paykey]) ? $available_paymethods[$paykey] : '';
	}

}

if (!function_exists('fs_affiliates_get_available_payment_method')) {

	function fs_affiliates_get_available_payment_method( $affiliate_id = '' ) {
		$payment_preference = fs_affiliates_paymethod_preference();
		$available_payments = array();

		if (!fs_affiliates_check_is_array($payment_preference)) {
			return array( 'none' => esc_html__('No Payment Method Available', FS_AFFILIATES_LOCALE) );
		}

		foreach ($payment_preference as $paykey => $status) {

			$payment_label = fs_affiliates_get_paymethod_preference($paykey);

			if (( $affiliate_id ) && ( $paykey == 'wallet' && !fs_affiliates_is_wallet_eligible($affiliate_id) )) {
				continue;
			}

			$available_payments[$paykey] = $payment_label;
		}

		return $available_payments;
	}

}


if (!function_exists('fs_affiliates_is_wallet_eligible')) {

	function fs_affiliates_is_wallet_eligible( $affiliate_id ) {

		$is_valid_affiliates = apply_filters('fs_affiliates_is_valid_affiliate', true, $affiliate_id);
		$is_wallet_enabled = FS_Affiliates_Module_Instances::get_module_by_id('affiliate_wallet')->is_enabled();

		if ($is_wallet_enabled && $is_valid_affiliates) {
			return true;
		}
		return false;
	}

}

if (!function_exists('fs_affiliates_is_affiliate_active')) {

	function fs_affiliates_is_affiliate_active( $affiliate_id ) {

		if ($affiliate_id == '') {
			return false;
		}

		$affiliate_status = get_post_status($affiliate_id);

		return ( $affiliate_status == 'fs_active' ) ? true : false;
	}

}


if (!function_exists('fs_affiliates_get_default_parent_affiliate')) {

	function fs_affiliates_get_default_parent_affiliate() {
		$parent_affiliate = get_option('fs_affiliates_default_affiliate');

		if (!isset($parent_affiliate[0])) {
			return 0;
		}

		return $parent_affiliate[0];
	}

}

if (!function_exists('fs_affiliates_local_datetime')) {

	function fs_affiliates_local_datetime( $strtotime, $date = true, $time = true ) {
		$format_string = '';

		if ($date) {
			$format_string .= get_option('date_format') . ' ';
		}

		if ($time) {
			$format_string .= get_option('time_format');
		}

		$strtodate = date('Y-m-d H:i:s', $strtotime);
		
		$timezone_added = get_date_from_gmt($strtodate, 'Y-m-d H:i:s');
		
		return date_i18n($format_string, strtotime($timezone_added));
	}

}


if (!function_exists('fs_affiliates_is_user_having_affiliate')) {

	function fs_affiliates_is_user_having_affiliate( $user_id = false ) {
		if (!$user_id) {
			$user_id = get_current_user_id();
		}

		if (empty($user_id)) {
			return 0;
		}

		$args = array(
			'post_type' => 'fs-affiliates',
			'numberposts' => -1,
			'post_status' => array( 'fs_active', 'fs_inactive', 'fs_pending_approval', 'fs_rejected', 'fs_suspended', 'fs_hold', 'fs_pending_payment' ),
			'author' => $user_id,
			'fields' => 'ids',
		);

		$affiliate = get_posts($args);

		if (!fs_affiliates_check_is_array($affiliate)) {
			return false;
		}

		return $affiliate[0];
	}

}

if (!function_exists('fs_affiliates_get_ip_address')) {

	function fs_affiliates_get_ip_address() {
		$ipaddress = '';

		if (isset($_SERVER['HTTP_X_REAL_IP'])) {
			$ipaddress = $_SERVER['HTTP_X_REAL_IP'];
		} else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}

		return $ipaddress;
	}

}

if (!function_exists('fs_insert_payout_data')) {

	function fs_insert_payout_data( $DataToInserts = array() ) {
		if (!fs_affiliates_check_is_array($DataToInserts)) {
			return true;
		}

		foreach ($DataToInserts as $AffiliateId => $IndividualData) {
			$AffiliateObj = new FS_Affiliates_Data($AffiliateId);
			$PayoutData['referrals'] = $IndividualData['referral_count'];
			$PayoutData['payment_mode'] = $IndividualData['payment_mode'];
			$PayoutData['paid_amount'] = $IndividualData['commission'];
			$PayoutData['generate_by'] = $IndividualData['generated_by'];
			$PayoutData['referral_id'] = $IndividualData['referral_ids'];
			$PayoutData['date'] = time();

			$payout_id = fs_affiliates_create_new_payouts($PayoutData, array( 'post_status' => 'fs_paid', 'post_author' => $AffiliateId, 'post_parent' => get_current_user_id() ));

			do_action('fs_affiliates_payment_success_for_affiliate', $AffiliateId, $payout_id);
		}

		return true;
	}

}

if (!function_exists('fs_affiliates_price')) {

	function fs_affiliates_price( $price, $args = array() ) {
		$format = '%1$s%2$s';

		switch (get_option('fs_affiliates_currency_position', 'left')) {
			case 'left':
				$format = '%1$s%2$s';
				break;
			case 'right':
				$format = '%2$s%1$s';
				break;
			case 'left_space':
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space':
				$format = '%2$s&nbsp;%1$s';
				break;
		}

		$args = apply_filters('fs_affiliates_price_args', wp_parse_args($args, array(
			'currency' => '',
			'decimal_separator' => stripslashes(get_option('fs_affiliates_currency_decimal_separator', '.')),
			'thousand_separator' => stripslashes(get_option('fs_affiliates_currency_thousand_separator', ',')),
			'decimals' => absint(get_option('fs_affiliates_price_num_decimals', '2')),
			'price_format' => $format,
		)));

		$unformatted_price = $price;
		$price = floatval($price);
		$negative = $price < 0;
		$price = floatval($negative ? $price * -1 : $price);
		$price = number_format($price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator']);

		if (apply_filters('fs_affiliates_price_trim_zeros', false) && $args['decimals'] > 0) {
			$price = wc_trim_zeros($price);
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf($args['price_format'], '<span class="fs_affiliates-Price-currencySymbol">' . get_fs_affiliates_currency_symbol($args['currency']) . '</span>', $price);
		$return = '<span class="fs_affiliates-Price-amount">' . $formatted_price . '</span>';

		return apply_filters('fs_affiliates_price', $return, $price, $args, $unformatted_price);
	}

}

if (!function_exists('fs_affiliates_get_decimal_separator')) {

	function fs_affiliates_get_decimal_separator() {
		return get_option('fs_affiliates_currency_decimal_separator', '.');
	}

}

if (!function_exists('fs_affiliates_get_thousand_separator')) {

	function fs_affiliates_get_thousand_separator() {
		return get_option('fs_affiliates_currency_thousand_separator', ',');
	}

}

if (!function_exists('fs_affiliates_format_decimal')) {

	function fs_affiliates_format_decimal( $number, $dp = false ) {
		$decimals = fs_affiliates_get_decimal_separator();

		if ($dp) {
			$number = str_replace($decimals, '.', $number);
			$number = preg_replace('/[^0-9\.,-]/', '', sanitize_text_field($number));
		} else {
			$number = str_replace('.', $decimals, $number);
		}

		return $number;
	}

}


if (!function_exists('fs_affiliates_display_content')) {

	function fs_affiliates_display_content( $display_content, $settings_slug ) {

		$filter_type = isset($_REQUEST["$settings_slug"]) ? $_REQUEST["$settings_slug"] : 'all';

		$label = fs_affiliates_get_report_based_on($filter_type);

		return '<div align="center">' . $display_content . '</div>' . '<div align="center">' . $label . '</div>';
	}

}

if (!function_exists('fs_affiliates_get_extracted_value')) {

	function fs_affiliates_get_extracted_value( $result, $count = '' ) {

		$column_value = fs_affiliates_get_array_column_values($result, 'result_value');

		if ($count) {
			return count($column_value);
		}

		$result_value = isset($column_value[0]) ? $column_value[0] : 0;

		return $result_value;
	}

}


if (!function_exists('fs_affiliates_get_array_column_values')) {

	function fs_affiliates_get_array_column_values( $myarray, $index_value ) {

		if (function_exists('array_map')) {
			$column_values = array_map(function ( $element ) use ( $index_value ) {
				return $element["$index_value"];
			}, $myarray);
		} else if (function_exists('array_column')) {
			$column_values = array_column($myarray, $index_value);
		}
		return $column_values;
	}

}

if (!function_exists('fs_affiliates_get_date_ranges')) {

	function fs_affiliates_get_date_ranges( $selected_type, $type ) {

		$date_ranges = array();

		$date = '';

		$time = current_time('timestamp');

		$get_date = date('d', $time);
		$get_month = date('n', $time);
		$get_year = date('Y', $time);

		switch ($selected_type) :
			case '0 DAY':
				$date_ranges['begin_day'] = $get_date;
				$date_ranges['end_day'] = $get_date;
				$date_ranges['begin_month'] = $get_month;
				$date_ranges['end_month'] = $get_month;
				$date_ranges['choosen_year'] = $get_year;
				break;

			case '1 DAY':
				if ($get_month == 1 && $get_date == 1) {
					$choosen_month = 12;
				} elseif ($get_month != 1 && $get_date == 1) {
					$choosen_month = $get_month - 1;
				} else {
					$choosen_month = $get_month;
				}

				$days_in_month = cal_days_in_month(CAL_GREGORIAN, $choosen_month, $get_year);

				$choosen_day = $get_date == 1 ? $days_in_month : $get_date - 1;

				$date_ranges['begin_day'] = $choosen_day;
				$date_ranges['end_day'] = $choosen_day;
				$date_ranges['begin_month'] = $choosen_month;
				$date_ranges['end_month'] = $choosen_month;
				$date_ranges['choosen_year'] = $get_month == 1 && $get_date == 1 ? $get_year - 1 : $get_year;
				break;

			case '0 MONTH':
				$date_ranges['begin_day'] = 1;
				$date_ranges['begin_month'] = $get_month;
				$date_ranges['end_month'] = $get_month;
				$date_ranges['end_day'] = cal_days_in_month(CAL_GREGORIAN, $date_ranges['begin_month'], $get_year);
				$date_ranges['choosen_year'] = $get_year;
				break;
			case '1 MONTH':
				if ($get_month == 1) {
					$date_ranges['begin_day'] = 1;
					$date_ranges['end_day'] = cal_days_in_month(CAL_GREGORIAN, 12, $get_year);
					$date_ranges['begin_month'] = 12;
					$date_ranges['end_month'] = 12;
					$date_ranges['choosen_year'] = $get_year - 1;
					$date_ranges['choosen_year_end'] = $get_year - 1;
				} else {
					$date_ranges['begin_day'] = 1;
					$date_ranges['end_day'] = cal_days_in_month(CAL_GREGORIAN, $get_month - 1, $get_year);
					$date_ranges['begin_month'] = $get_month - 1;
					$date_ranges['end_month'] = $get_month - 1;
					$date_ranges['choosen_year'] = $get_year;
				}
				break;

			case '0 YEAR':
				$date_ranges['begin_day'] = 1;
				$date_ranges['end_day'] = cal_days_in_month(CAL_GREGORIAN, 12, $get_year);
				$date_ranges['begin_month'] = 1;
				$date_ranges['end_month'] = 12;
				$date_ranges['choosen_year'] = $get_year;
				$date_ranges['year_end'] = $get_year;
				break;

			case '1 YEAR':
				$date_ranges['begin_day'] = 1;
				$date_ranges['end_day'] = cal_days_in_month(CAL_GREGORIAN, 12, $get_year - 1);
				$date_ranges['begin_month'] = 1;
				$date_ranges['end_month'] = 12;
				$date_ranges['choosen_year'] = $get_year - 1;
				$date_ranges['choosen_year_end'] = $get_year - 1;
				break;

			case '0 WEEK':
				$date_ranges['begin_day'] = date('d', $time - ( date('w', $time) - 1 ) * 60 * 60 * 24) - 1;
				$date_ranges['begin_day'] += get_option('start_of_week');
				$date_ranges['end_day'] = $date_ranges['begin_day'] + 6;
				$end_day = $date_ranges['end_day'] > date('d', strtotime('last day of previous month'));
				$date_ranges['end_day'] = $end_day ? $date_ranges['end_day'] - date('d', strtotime('last day of previous month')) : $date_ranges['end_day'];
				$date_ranges['begin_month'] = ( $get_month != 1 && $get_date == 1 ) || $end_day ? $get_month - 1 : $get_month;
				$date_ranges['end_month'] = $get_month;
				$date_ranges['choosen_year'] = $get_year;
				break;

			case '1 WEEK':
				$date_ranges['begin_day'] = date('d', $time - ( date('w') - 1 ) * 60 * 60 * 24) - 8;
				$date_ranges['begin_day'] += get_option('start_of_week');
				$date_ranges['end_day'] = $date_ranges['begin_day'] + 6;
				$date_ranges['begin_day'] = $date_ranges['begin_day'] < 1 ? (int) date('d', strtotime('last day of previous month')) + $date_ranges['begin_day'] : $date_ranges['begin_day'];
				$end_day = $date_ranges['end_day'] > date('d', strtotime('last day of previous month'));
				$date_ranges['end_day'] = $end_day ? $date_ranges['end_day'] - (int) date('d', strtotime('last day of previous month')) : $date_ranges['end_day'];
				$date_ranges['choosen_year'] = $get_year;

				if (date('j', $time) <= 7) {
					$date_ranges['begin_month'] = $get_month - 1;
					$date_ranges['end_month'] = ( $get_month != 1 && $get_date == 1 ) ? $get_month - 1 : $get_month;
					if ($date_ranges['begin_month'] <= 1 && $get_month == '1') {
						$date_ranges['choosen_year'] = $get_year - 1;
						$date_ranges['choosen_year_end'] = $get_year - 1;
					}
				} else {
					$date_ranges['begin_month'] = ( $get_month != 1 && $get_date == 1 ) ? $get_month - 1 : $get_month;
					$date_ranges['end_month'] = $get_month;
				}

				break;
		endswitch;

		if ($type == 'from' && isset($date_ranges['begin_day']) && $date_ranges['begin_month'] && $date_ranges['choosen_year']) {

			$date = $date_ranges['choosen_year'] . '-' . $date_ranges['begin_month'] . '-' . $date_ranges['begin_day'] . ' 00:00:00';
		}

		if ($type == 'to' && isset($date_ranges['end_day']) && $date_ranges['end_month'] && $date_ranges['choosen_year']) {

			$choosen_year = isset($date_ranges['choosen_year_end']) ? $date_ranges['choosen_year_end'] : $date_ranges['choosen_year'];

			$date = $choosen_year . '-' . $date_ranges['end_month'] . '-' . $date_ranges['end_day'] . ' 23:59:59';
		}

		return $date;
	}

}
if (!function_exists('fs_affiliates_get_dashboard_formatted_name')) {

	function fs_affiliates_get_dashboard_formatted_name( $value ) {

		return $value ? $value : '-';
	}

}

if (!function_exists('fs_get_referral_identifier')) {

	function fs_get_referral_identifier() {

		$ReferralIdentifier = get_option('fs_affiliates_referral_identifier') != '' ? get_option('fs_affiliates_referral_identifier') : 'ref';

		return $ReferralIdentifier;
	}

}

if (!function_exists('is_linked_coupon')) {

	function is_linked_coupon( $coupon_id ) {

		global $wpdb;
		$query = $wpdb->prepare("SELECT DISTINCT posts.ID FROM {$wpdb->posts} as posts
                        INNER JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
			WHERE posts.post_type='fs-coupon-linking' AND posts.post_status IN('fs_link')
                        AND postmeta.meta_key IN('coupon_data') AND (postmeta.meta_value = %s) LIMIT 1", $coupon_id);

		$search_results = $wpdb->get_results($query, ARRAY_A);
		if (!fs_affiliates_check_is_array($search_results)) {
			return 0;
		}

		foreach ($search_results as $result) {
			return $result['ID'];
		}
	}

}

if (!function_exists('get_affiliate_id_for_lifetime_commission')) {

	function get_affiliate_id_for_lifetime_commission( $BillingEmail ) {
		$args = array(
			'status' => 'any',
			'limit' => -1,
			'return' => 'ids',
			'billing_email' => $BillingEmail,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'fs_commission_awarded',
					'value' => 'yes',
					'compare' => '=',
				),
				array(
					'key' => 'fs_affiliate_in_order',
					'value' => '',
					'compare' => '!=',
				),
		),
		);
		$OrderIds = wc_get_orders($args);
		if (!fs_affiliates_check_is_array($OrderIds)) {
			return 0;
		}

		$OrderId = reset($OrderIds);
		$order = wc_get_order($OrderId);
		if (!is_object($order)) {
			return 0;
		}

		$AffiliateId = $order->get_meta('fs_affiliate_in_order');

		return $AffiliateId;
	}

}

if (!function_exists('fs_affiliates_get_child_affiliate')) {

	function fs_affiliates_get_child_affiliate( $affiliate_id ) {
		$args = array(
			'post_parent' => $affiliate_id,
			'post_type' => 'fs-affiliates',
			'numberposts' => -1,
			'post_status' => 'fs_active',
			'fields' => 'ids',
			'order' => 'ASC',
		);
		$children = get_children($args);
		return $children;
	}

}

if (!function_exists('fs_affiliates_get_affiliate_mlm_structures')) {

	function fs_affiliates_get_affiliate_mlm_structures( $affiliates, $parent ) {
		$mlm_structures = '';
		if (!fs_affiliates_check_is_array($affiliates)) {
			return $mlm_structures;
		}

		$parent_node_title = get_the_title($parent);
		foreach ($affiliates as $affiliate_id) {
			$child_affiliates = fs_affiliates_get_child_affiliate($affiliate_id);
			$each_node_title = get_the_title($affiliate_id);

			$mlm_structures .= "[{ v : '" . $each_node_title . "' , f : '" . fs_affiliates_prepare_thickbox_url($affiliate_id, $each_node_title) . "' } , '" . $parent_node_title . "' , ''],";

			if (fs_affiliates_check_is_array($child_affiliates)) {
				$mlm_structures .= fs_affiliates_get_affiliate_mlm_structures($child_affiliates, $affiliate_id);
			}
		}

		return $mlm_structures;
	}

}

if (!function_exists('fs_graph_for_mlm')) {

	function fs_graph_for_mlm( $affiliate_id ) {
		$mlm_module = new FS_Affiliates_Multi_Level_Marketing();

		if ($mlm_module->enabled != 'yes') {
			return;
		}

		add_thickbox();

		$ChildIdsforParent = fs_affiliates_get_child_affiliate($affiliate_id);
		if (!fs_affiliates_check_is_array($ChildIdsforParent)) {
			return;
		}
		$parent_node_title = get_the_title($affiliate_id);
		$mlm_structure = "[{ v : '" . $parent_node_title . "' , f : '' } , '' , 'Main'] ,";
		$mlm_structure .= fs_affiliates_get_affiliate_mlm_structures($ChildIdsforParent, $affiliate_id);
		if (!$mlm_structure) {
			return;
		}
		?>
		<h3><?php _e('MLM Tree', FS_AFFILIATES_LOCALE); ?></h3>
		<div id="chart_div"></div>
		<style type="text/css">
			#chart_div table {
				border-collapse: separate !important;
			}
			#chart_div table tr td {
				border-color:#000 !important;
			}
			body #chart_div table {
				border-collapse:collapse !important;
			}
			.entry-summary table th, .entry-summary table td {
				border:none !important ;
				padding: 10px;
			}

			body #chart_div table td{
				border-color:#000 !important;
			}
			body #chart_div table .google-visualization-orgchart-node{
				border:2px solid #000 !important;
			}
			body #chart_div table .google-visualization-orgchart-lineleft{
				border-left:1px solid #000 !important;
			}
			body #chart_div table .google-visualization-orgchart-lineright{
				border-right:1px solid #000 !important;
			}
			body #chart_div table .google-visualization-orgchart-linebottom{
				border-bottom:1px solid #000 !important;
			}
			#chart_div{
				overflow: scroll;
			}
		</style>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {packages: ["orgchart"]});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Name');
				data.addColumn('string', 'Manager');
				data.addColumn('string', 'ToolTip');

				// For each orgchart box, provide the name, manager, and tooltip to show.
				data.addRows([<?php echo rtrim($mlm_structure); ?>]);

				// Create the chart.
				var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
				// Draw the chart, setting the allowHtml option to true for the tooltips.
				chart.draw(data, {allowHtml: true});
			}
		</script>
		<?php
	}

}



if (!function_exists('fs_affiliates_get_landing_pages')) {

	function fs_affiliates_get_landing_pages( $AffiliateID ) {

		$landing_pages = get_post_meta($AffiliateID, 'landing_pages', true);

		return $landing_pages;
	}

}

if (!function_exists('get_mail_chimp_header')) {

	function get_mail_chimp_header( $api_key ) {
		$headers = array(
			'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
		);

		return $headers;
	}

}

if (!function_exists('is_already_in_list_active_campaign')) {

	function is_already_in_list_active_campaign( $url, $email ) {
		$params = array(
			'timeout' => 45,
			'sslverify' => false,
			'httpversion' => '1.1',
		);
		$prepared_url = $url . '&email=' . $email;

		$response = wp_remote_get($prepared_url, $params);

		if (is_wp_error($response)) {

			$this->add_error($response->get_error_code(), $response->get_error_message());
		}

		$decoded_response = json_decode($response['body']);

		if (!empty($decoded_response->result_code)) {
			return true;
		}

		return false;
	}

}

if (!function_exists('is_already_in_list_mailchimp')) {

	function is_already_in_list_mailchimp( $url, $api_key, $email ) {

		$converted_mail = md5(strtolower($email));
		$url = $url . $converted_mail;

		$headers = get_mail_chimp_header($api_key);

		$args = array(
			'timeout' => 45,
			'sslverify' => false,
			'httpversion' => '1.1',
			'headers' => $headers,
		);

		$request = wp_remote_get($url, $args);

		if (is_wp_error($request)) {
			throw new Exception(__($request->get_error_code() . ' -' . $request->get_error_message(), FS_AFFILIATES_LOCALE));
		}

		if (200 === wp_remote_retrieve_response_code($request)) {
			return true;
		}

		return false;
	}

}


if (!function_exists('process_double_optin_mail')) {

	function process_double_optin_mail( $mail, $mode ) {

		$opt_in_email_subject = get_option('fs_affiliates_affs_email_opt_in_affs_subject');
		$opt_in_email_message = get_option('fs_affiliates_affs_email_opt_in_affs_message');
		$sitename = get_bloginfo();
		$hash = base64_encode($mail);

		$verification_link = add_query_arg(array( 'opt_in_action' => $mode, 'fs_opt_in_nonce' => $hash ), site_url());
		$verification_link = '<a href="' . $verification_link . '">' . $verification_link . '</a>';

		$shortcode_array = array( '{site_link}', '{verification_link}', '{affiliate_email}' );
		$replace_array = array( $sitename, $verification_link, $mail );

		$subject = str_replace($shortcode_array, $replace_array, $opt_in_email_subject);
		$message = str_replace($shortcode_array, $replace_array, $opt_in_email_message);

		$notifications = new FS_Affiliates_Notifications();
		$send = $notifications->send_email($mail, $subject, $message);

		if ($send) {
			return true;
		}

		return false;
	}

}

if (!function_exists('process_adding_list_in_mail')) {

	function process_adding_list_in_mail( $mail, $meta_data, $mode ) {
		$selected_service = get_option('fs_affiliates_affs_email_opt_in_email_service');
		if ($selected_service == 'mailchimp') {
			process_adding_list_in_mailchimp($mail, $meta_data, $mode);
		} else if ($selected_service == 'active_campaign') {
			process_adding_list_in_activecampaign($mail, $meta_data, $mode);
		}

		if (( $selected_service == 'mailchimp' || $selected_service == 'activecampaign' ) && get_option('fs_affiliates_affs_email_opt_in_award_commision_form_filling') == 'yes' && $mode != 'register') {
			$time_now = time();

			$format_reference = get_option('fs_affiliates_affs_email_opt_in_list_id');
			$affiliate_id = fs_affiliates_get_id_from_cookie('fsaffiliateid');

			if ($affiliate_id != '') {
				$meta_data = array(
					'reference' => $format_reference,
					'description' => get_option('fs_affiliates_referral_desc_email_subs_label', 'Email Subscription'),
					'campaign' => ' - ',
					'amount' => get_option('fs_affiliates_affs_email_opt_in_affs_commision'),
					'type' => 'Opt-in',
					'date' => $time_now,
					'visit_id' => fs_affiliates_get_id_from_cookie('fsvisitid'),
					'campaign' => fs_affiliates_get_id_from_cookie('fscampaign', ''),
				);

				fs_affiliates_create_new_referral($meta_data, array( 'post_author' => $affiliate_id ));
			}
		}
	}

}


if (!function_exists('process_adding_list_in_activecampaign')) {

	function process_adding_list_in_activecampaign( $mail, $meta_data ) {

		$url = get_option('fs_affiliates_affs_email_opt_in_url');
		$list_id = get_option('fs_affiliates_affs_email_opt_in_list_id');
		$api_key = get_option('fs_affiliates_affs_email_opt_in_api_key');

		$url = trailingslashit($url) . 'admin/api.php?api_action=subscriber_add&api_output=json&api_key=' . $api_key;

		if (is_already_in_list_active_campaign($url, $mail)) {
			throw new Exception(__('Mail Already Exist', FS_AFFILIATES_LOCALE));
		}

		$body = array(
			'email' => $mail,
			'first_name' => $meta_data['first_name'],
			'last_name' => $meta_data['last_name'],
			'ip4' => fs_affiliates_get_ip_address(),
			'p[' . $list_id . ']' => $list_id,
			'status[' . $list_id . ']' => 1,
		);

		$args = array(
			'timeout' => 45,
			'sslverify' => false,
			'httpversion' => '1.1',
			'headers' => array(),
			'body' => $body,
		);

		$response = wp_remote_post($url, $args);
	}

}

if (!function_exists('process_adding_list_in_mailchimp')) {

	function process_adding_list_in_mailchimp( $mail, $meta_data ) {
		$list_id = get_option('fs_affiliates_affs_email_opt_in_list_id');
		$api_key = get_option('fs_affiliates_affs_email_opt_in_api_key');
		$data_center = 'us4';

		if (!empty($api_key)) {
			$data_center = substr($api_key, strpos($api_key, '-') + 1);
		}

		$headers = array(
			'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
		);

		$url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/';

		if (is_already_in_list_mailchimp($url, $api_key, $mail)) {
			throw new Exception(__('Mail Already Exist', FS_AFFILIATES_LOCALE));
		}

		$body = array(
			'email_address' => $mail,
			'status' => 'subscribed',
			'merge_fields' => array(
				'FNAME' => isset($meta_data['first_name']) ? $meta_data['first_name'] : '',
				'LNAME' => isset($meta_data['last_name']) ? $meta_data['last_name'] : '',
			),
		);

		$args = array(
			'timeout' => 45,
			'sslverify' => false,
			'httpversion' => '1.1',
			'headers' => $headers,
			'body' => json_encode($body),
		);

		$request = wp_remote_post($url, $args);

		if (is_wp_error($request)) {
			throw new Exception(__($request->get_error_code() . ' -' . $request->get_error_message(), FS_AFFILIATES_LOCALE));
		}

		if (200 !== wp_remote_retrieve_response_code($request)) {
			throw new Exception(__(wp_remote_retrieve_response_code($request) . ' -' . wp_remote_retrieve_response_message($request), FS_AFFILIATES_LOCALE));
		}
	}

}

if (!function_exists('fs_affiliates_access_mail_api')) {

	function fs_affiliates_access_mail_api( $mail, $meta_data, $mode = 'subcription' ) {
		if (get_option('fs_affiliates_affs_email_opt_in_enable_double_opt_in') == 'yes') {
			process_double_optin_mail($mail, $mode);
			return 'mail_sent';
		} else {
			process_adding_list_in_mail($mail, $meta_data, $mode);
			return 'mail_added';
		}
	}

}

if (!function_exists('fs_affiliates_code_generator')) {

	function fs_affiliates_code_generator() {

		$length = get_option( 'fs_affiliates_referral_code_creation_length', '10' );
		$referral_code_type = get_option('fs_affiliates_referral_code_type');

		$prefix = get_option( 'fs_affiliates_referral_code_creation_prefix' );
		$suffix = get_option( 'fs_affiliates_referral_code_creation_sufix' );

		$referral_code = '';
		// Prepare the characters.
		switch ($referral_code_type) {
			case '2':
				$characters = array_merge(range('a', 'z'), range('0', '9'));
				break;
				
			case '3':
				$characters = range(0, 9);
				break;

			default:
				$characters = '';
				break;
		}

		if ( empty( $characters ) ) {
			$referral_code = wp_generate_password( $length, true, false );
		} else {
			// Pick the random characters.
			for ($i = 0; $i < $length; $i++) {
				$random_key = array_rand($characters);
				$referral_code .= $characters[$random_key];
			}
		}

		if ( ! empty( $prefix ) ) {
			$referral_code = $prefix . $referral_code;
		}

		if ( ! empty( $suffix ) ) {
			$referral_code .= $suffix;
		}

		/**
		 * This hook is used to alter the generated random code.
		 *
		 * @since 10.8.0
		 * 
		 * @param string $referral_code
		 * @return string
		 */
		return apply_filters('fs_generate_random_code', $referral_code);
	}

}


if (!function_exists('fs_affiliates_insert_url_masking_domain')) {

	function fs_affiliates_insert_url_masking_domain( $AffiliateId, $domain_name, $user_id, $status = 'fs_pending_approval' ) {

		$meta_data = array(
			'affs_id' => $AffiliateId,
			'url_masking_domain' => $domain_name,
			'date' => time(),
			'status' => $status,
		);

		$post_args = array(
			'post_status' => $meta_data['status'],
			'post_author' => $user_id,
			'post_title' => $domain_name,
		);
		$url_masking_object = new FS_URL_Masking_Data();
		$url_masking_object->create($meta_data, $post_args);
	}

}


if (!function_exists('fs_affiliates_get_restricted_domain_names')) {

	function fs_affiliates_get_restricted_domain_names() {

		$doamin_names = get_option('fs_affiliates_url_masking_restricted_domains');

		$exploded_value = explode(',', $doamin_names);

		return $exploded_value;
	}

}


if (!function_exists('fs_affiliates_get_reg_domains')) {

	function fs_affiliates_get_reg_domains( $AffiliateId, $type = 'data' ) {
		$args = array(
			'post_type' => 'fs-url-masking',
			'numberposts' => -1,
			'post_status' => array( 'fs_active', 'fs_pending_approval', 'fs_suspended', 'fs_rejected' ),
			'fields' => 'ids',
			'meta_key' => 'affs_id',
			'meta_value' => $AffiliateId,
		);

		$get_data = get_posts($args);

		if ($type == 'count') {
			$get_data = count($get_data);
		}

		return $get_data;
	}

}

if (!function_exists('fs_affiliates_get_allowed_setting_tabs')) {

	function fs_affiliates_get_allowed_setting_tabs() {
		$user_roles = fs_affiliates_get_user_roles();

		$tabs = apply_filters('fs_affiliates_settings_tabs_array', array());

		if (!fs_affiliates_check_is_array($user_roles)) {
			return $tabs;
		}

		$current_user = wp_get_current_user();

		if (!$current_user->exists()) {
			return $tabs;
		}

		foreach ($user_roles as $role_key => $role_name) {
			if ($role_key == 'administrator' || $role_key == 'customer') {
				continue;
			}

			if (!in_array($role_key, (array) $current_user->roles)) {
				continue;
			}

			$restricted_tabs = get_option('fs_affiliates_restrict_setting_menu_for_' . $role_key, array());

			$tabs = array_diff_key($tabs, array_flip($restricted_tabs));
		}

		return $tabs;
	}

}

function fs_affs_get_domain_name( $url ) {
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		$exploded_value = explode('.', $regs['domain']);
		$domain = isset($exploded_value[0]) ? $exploded_value[0] : '';
		return $domain;
	}
	return '';
}

if (!function_exists('process_url_masking_user_notify_mail')) {

	function process_url_masking_user_notify_mail( $mail, $flag ) {

		$is_enable = get_option('fs_affiliates_url_masking_domain_' . $flag);

		if ($is_enable != 'yes') {
			return;
		}
		$email_subject = get_option('fs_affiliates_url_masking_domain_' . $flag . '_mail_subject');
		$email_message = get_option('fs_affiliates_url_masking_domain_' . $flag . '_mail_message');

		$sitename = get_bloginfo();

		$optin_link = site_url();
		$optin_link = '<a href="' . $optin_link . '">' . $optin_link . '</a>';

		$shortcode_array = array( '{site_name}', '{optin_link}', '{affiliate_email}' );
		$replace_array = array( $sitename, $optin_link, $mail );

		$subject = str_replace($shortcode_array, $replace_array, $email_subject);
		$message = str_replace($shortcode_array, $replace_array, $email_message);

		$notifications = new FS_Affiliates_Notifications();
		$send = $notifications->send_email($mail, $subject, $message);

		if ($send) {
			return true;
		}

		return false;
	}

}


if (!function_exists('process_url_masking_admin_notify_mail')) {

	function process_url_masking_admin_notify_mail( $mail ) {

		$is_enable = get_option('fs_affiliates_url_masking_admin_email_notify');

		if ($is_enable != 'yes') {
			return;
		}
		$email_subject = get_option('fs_affiliates_url_masking_admin_mail_subject');
		$email_message = get_option('fs_affiliates_url_masking_admin_mail_message');

		$sitename = get_bloginfo();

		$optin_link = site_url();
		$optin_link = '<a href="' . $optin_link . '">' . $optin_link . '</a>';

		$shortcode_array = array( '{site_name}', '{optin_link}', '{affiliate_email}' );
		$replace_array = array( $sitename, $optin_link, $mail );

		$subject = str_replace($shortcode_array, $replace_array, $email_subject);
		$message = str_replace($shortcode_array, $replace_array, $email_message);

		$notifications = new FS_Affiliates_Notifications();
		$send = $notifications->send_email($mail, $subject, $message);

		if ($send) {
			return true;
		}

		return false;
	}

}
if (!function_exists('fs_affiliates_setcookie')) {

	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 */
	function fs_affiliates_setcookie( $name, $value, $expire = 0 ) {
		/**
		 * This hook is used to alter Cookie http only 
		 *
		 * @since 9.5.0
		 */
		$http_only = apply_filters('fs_affiliates_cookie_http_only', true, $name, $value);
		if (!headers_sent()) {
			setcookie($name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), $http_only);
		} elseif (defined('WP_DEBUG') && WP_DEBUG) {
			headers_sent($file, $line);
			trigger_error("{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE); // @codingStandardsIgnoreLine
		}
	}

}

if (!function_exists('fs_affiliates_get_payout_fields')) {

	/**
	 * getpayout labels
	 */
	function fs_affiliates_get_payout_fields() {
		$payout_object = FS_Affiliates_Module_Instances::get_module_by_id('payout_statements');
		//Mandatory Fields
		$payout_labels = array(
			'name_label_heading' => $payout_object->name_label_heading,
			'addr1_label' => $payout_object->addr1_label,
			'addr2_label' => $payout_object->addr2_label,
			'city_label' => $payout_object->city_label,
			'state_label' => $payout_object->state_label,
			'zip_code_label' => $payout_object->zip_code_label,
			'tax_cred_label' => $payout_object->tax_cred_label,
		);

		return $payout_labels;
	}

}

if (!function_exists('get_payout_pdf_statement_name')) {

	function get_payout_pdf_statement_name( $sequence_number, $length, $statement_prefix, $statement_suffix ) {
		$random_code = '';
		$alphabets = range('a', 'z');
		$numbers = range('0', '9');
		$alpha_numeric = array_merge($alphabets, $numbers);
		while ($length--) {
			$random_numbers = array_rand($alpha_numeric);
			$random_code .= $alpha_numeric[$random_numbers];
		}
		return $statement_prefix . '-' . $random_code . '-' . $sequence_number . '-' . $statement_suffix;
	}

}

if (!function_exists('fs_affiliates_is_file_exists')) {

	function fs_affiliates_is_file_exists( $payout_id ) {
		$get_payout_datas = new FS_Affiliates_Payouts($payout_id);
		$filename = $get_payout_datas->pay_statement_file_name;

		if (file_exists($filename)) {
			return true;
		}

		return false;
	}

}

if (!function_exists('fs_affiliates_get_default_classes')) {

	function fs_affiliates_get_default_classes() {
		$default_classes = array(
		'.fs_affiliates_frontend_table', '.fs_affiliates_sno', '
.fs_affiliates_creatives_frontend_sno', 'fs_affiliates_referrals_sno', '
.fs_affiliates_visits_sno', '.fs_affiliates_Payout_sno', '.fs_affiliates_landing_page_sno', '
.fs_affiliate_transaction_log_sno', '.fs_affiliates_leaderboard_sno', '.fs_affiliates_leaderboard_two_sno', '.fs_affiliates_leaderboard_three_sno', '.fs_affiliates_leaderboard_order_placed_sno', '
.fs_affiliates_domain_sno', '.fs_affiliates_coupon_linking_sno',
		);
		return $default_classes;
	}

}

if (!function_exists('fs_affiliates_prepare_thickbox_url')) {

	function fs_affiliates_prepare_thickbox_url( $id, $content ) {

		if (apply_filters('fs_affiliates_is_affs_data_diplay', false)) {
			$url = esc_url(add_query_arg(array( 'get_affs_content' => 'yes', 'affs_id' => $id, 'TB_iframe' => 'true', 'width' => '600', 'height' => '480' ), home_url()));
			$content = '<div><a href="' . $url . '" title="' . __('Affiliate Details') . '" class="thickbox">' . $content . '</a></div>';
		}

		return $content;
	}

}

if (!function_exists('fs_affiliates_change_user_as_affiliate')) {

	/**
	 *  Change User as affiliate if needed
	 */
	function fs_affiliates_change_user_as_affiliate( $user_id ) {

		$affiliate_id = fs_affiliates_is_user_having_affiliate($user_id);

		if ($affiliate_id) {
			return $affiliate_id;
		}

		$user = get_user_by('id', $user_id);

		$meta_data['first_name'] = $user->first_name;
		$meta_data['last_name'] = $user->last_name;
		$meta_data['email'] = $user->user_email;
		$meta_data['user_role'] = $user->role;
		$meta_data['website'] = $user->user_url;
		$meta_data['date'] = time();

		$post_args = array(
			'post_author' => $user_id,
			'post_title' => $user->user_login,
			'post_parent' => fs_affiliates_get_default_parent_affiliate(),
		);

		$affiliate_id = fs_affiliates_create_new_affiliate($meta_data, $post_args);

		do_action( 'fs_affiliates_admin_to_affiliate_notification' , $affiliate_id , $meta_data ) ;

		return $affiliate_id;
	}

}

if (!function_exists('fs_affiliates_get_user_data')) {

	function fs_affiliates_get_user_data( $element ) {
		$user = get_user_by('id', get_current_user_id());
		$element_value = ( is_user_logged_in() && isset($user->$element) ) ? $user->$element : '';
		return $element_value;
	}

}

if (!function_exists('fs_affiliates_get_default_gateway')) {

	function fs_affiliates_get_default_gateway( $payment_preference ) {

		if (!fs_affiliates_check_is_array($payment_preference)) {
			return '';
		}

		foreach ($payment_preference as $pay_key => $status) {

			$payment_label = fs_affiliates_get_paymethod_preference($pay_key);
			if ($payment_label == '') {
				continue;
			}
			if ($status == 'enable') {
				return $pay_key;
			}
		}

		return '';
	}

}

if (!function_exists('fs_affiliates_settings_default_gateway')) {

	/**
	 * Affiliates settings default gateway
	 * 
	 * @since 1.0.0
	 * @param int $affiliate_id
	 * @param string $default_pay_method    
	 * @return bool
	 */
	function fs_affiliates_settings_default_gateway( $affiliate_id, $default_pay_method ) {

		if ($default_pay_method == 'reward_points' || $default_pay_method == 'wallet') {
			$payment_datas = array(
				'fs_affiliates_current_id' => $affiliate_id,
				'fs_affiliates_payment_method' => $default_pay_method,
				'fs_affiliates_paypal_email' => '',
				'fs_affiliates_bank_details' => '',
			);

			update_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', $payment_datas);
		}
	}

}


if (!function_exists('fs_affiliates_is_payment_method_enable')) {

	/**
	 * Affiliates payment method enable
	 * 
	 * @since 1.0.0
	 * @param array $payment_preference
	 * @param string $pay_method    
	 * @return bool
	 */
	function fs_affiliates_is_payment_method_enable( $payment_preference, $pay_method ) {

		if (isset($payment_preference[$pay_method]) && $payment_preference[$pay_method] == 'disable') {
			return true;
		}

		return false;
	}

}


add_action('fs_affiliates_before_dashboard_content', 'display_before_dashboard_content', 10, 3);

/**
 * Display before dashboard content
 * 
 * @since 1.0.0
 * @param string $current_tab
 * @param int $user_id
 * @param int $affiliate_id
 * @return string
 */
function display_before_dashboard_content( $current_tab, $user_id, $affiliate_id ) {
	$payment_preference = get_option('fs_affiliates_payment_preference', array( 'direct' => 'enable', 'paypal' => 'enable', 'wallet' => 'enable' ));
	$payment_datas = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);
	$pay_method = isset($payment_datas['fs_affiliates_payment_method']) ? $payment_datas['fs_affiliates_payment_method'] : '';
	$paypal_email = isset($payment_datas['fs_affiliates_paypal_email']) ? $payment_datas['fs_affiliates_paypal_email'] : '';
	$pay_bank = isset($payment_datas['fs_affiliates_bank_details']) ? $payment_datas['fs_affiliates_bank_details'] : '';
	$query_nonce = wp_create_nonce('affiliates-' . $user_id);
	$get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$payout_statement_href = fs_affiliates_dashboard_menu_link($get_permalink, 'payment_management', $query_nonce);
	$payment_management_link = ' <a style="color:white;text-decoration:underline" href=' . $payout_statement_href . '> ' . __('Click here', FS_AFFILIATES_LOCALE) . '</a>';

	if ($current_tab != 'payment_management' && ( ( $pay_method == 'direct' && $pay_bank == '' ) || ( $pay_method == 'paypal' && $paypal_email == '' ) || $pay_method == '' || ( fs_affiliates_is_payment_method_enable($payment_preference, $pay_method) && '1' == get_option('fs_affiliates_payment_method_selection_type', '1') ) )) {

		if (apply_filters('fs_affiliates_show_warning_notice', true, 'profile', 'payment_management')) {
			?>
			<div>
				<span class="fs_affiliates_msg_fails_post fs_affiliates_payment_methods_error_msg"><i class="fa fa-exclamation-triangle"></i><?php printf(__('Payment Method selection is required to get your commission. To select the Payment Method %s', FS_AFFILIATES_LOCALE), $payment_management_link); ?></span>
			</div>
			<?php
		}
	}

	$payment_change_notice = get_post_meta($affiliate_id, 'fs_affiliates_payment_notice_disp_type', true);
	$msg = esc_html__('FYI, the payment method has changed by the site admin. %s to see the modified payment method', FS_AFFILIATES_LOCALE);

	if ('new' == $payment_change_notice) {
		$msg = esc_html__('You can get your commission through the payment method selected by the site admin. %s to see the payment method', FS_AFFILIATES_LOCALE);
	}

	if (( $current_tab != 'payment_management' ) && ( in_array($pay_method, array( 'wallet', 'reward_points' )) || ( $pay_method == 'direct' && $pay_bank != '' ) || ( $pay_method == 'paypal' && $paypal_email != '' ) ) && ( in_array($payment_change_notice, array( 'new', 'exist' )) && apply_filters('fs_affiliates_show_warning_notice', true, 'profile', 'payment_management') )) {
		?>
		<div>
			<span class="fs_affiliates_msg_fails_post fs_affiliates_payment_methods_error_msg"><i class="fa fa-exclamation-triangle"></i><?php printf($msg, $payment_management_link); ?></span>
		</div>
		<?php
	}
}

if (!function_exists('fs_affiliates_user_spent_order_total')) {

	/**
	 * Get Affiliate/User spent total
	 * 
	 * @since 1.0.0
	 * @param int $user_id
	 * @return string
	 */
	function fs_affiliates_user_spent_order_total( $user_id ) {

		$selected_date = get_option('fs_affiliates_sumo_memberships_reset_commision_rate');

		$reset_commision = get_option('fs_affiliates_sumo_memberships_reset_commision');

		$date = $selected_date < 10 ? '0' . $selected_date : $selected_date;

		$date_selection = strtotime(date('Y') . '-' . date('m') . '-' . $date . '00' . ':' . '00');

		$orders = get_posts(array(
			'numberposts' => - 1,
			'meta_key' => '_customer_user',
			'meta_value' => $user_id,
			'post_type' => array( 'shop_order' ),
			'post_status' => array( 'wc-completed', 'wc-processing' ),
			'fields' => 'ids',
		));

		$result = 0;

		foreach ($orders as $each_order) {
			$order = wc_get_order($each_order);

			if ($reset_commision == 'yes') {
				$formated_date = $order->get_date_created() ? gmdate('Y-m-d H:i:s', $order->get_date_created()->getOffsetTimestamp()) : '';
				$order_date = strtotime($formated_date);

				if ($date_selection < $order_date) {
					$result += $order->get_total();
				}
			} else if ($reset_commision != 'yes') {
				$result += $order->get_total();
			}
		}

		return $result;
	}

}

if (!function_exists('fs_affiliates_refferals_spent_order_total')) {

	/**
	 * Get referrals id by affiliate id
	 * 
	 * @since 1.0.0
	 * @param int $affiliate_id
	 * @return float
	 */
	function fs_affiliates_refferals_spent_order_total( $affiliate_id ) {
		$get_reffs_args = array(
			'post_type' => 'fs-referrals',
			'numberposts' => -1,
			'fields' => 'ids',
			'author' => $affiliate_id,
			'post_status' => array( 'fs_paid', 'fs_unpaid' ),
		);
		$referal_ids = get_posts($get_reffs_args);

		if (!fs_affiliates_check_is_array($referal_ids)) {
			return 0;
		}

		$referral_order_total = get_referrals_ids_spent_order_total($referal_ids);

		return $referral_order_total;
	}

}


if (!function_exists('get_referrals_ids_spent_order_total')) {

	/**
	 * Get referals id by affiliate id
	 * 
	 * @since 1.0.0
	 * @param array $referal_ids
	 * @return float 
	 */
	function get_referrals_ids_spent_order_total( $referal_ids ) {
		$order_total = 0;
		foreach ($referal_ids as $each_id) {

			//            if ( fs_affiliates_is_affiliate_active ( $each_id ) ) {
			$referral_data = new FS_Affiliates_Referrals($each_id);
			$order_id = $referral_data->reference;
			$order_data = wc_get_order($order_id);
			$user_id = isset($order_data->user_id) ? $order_data->user_id : '';
			$order_total += fs_affiliates_user_spent_order_total($user_id);
			//            }
		}
		return $order_total;
	}

}

if (!function_exists('fs_display_copy_affiliate_link_image')) {

	/**
	 * Display Copy Affiliate Link Image
	 * 
	 * @since 1.0.0
	 * @param string $link
	 * @param bool $static
	 * @return html
	 */
	function fs_display_copy_affiliate_link_image( $link, $static = false ) {
		$copy_img_class = ( $static ) ? 'fs_static_copy_clipboard_image' : 'fs_copy_clipboard_image';
		$copy_msg_class = ( $static ) ? 'fs_static_copy_message' : 'fs_copy_message';

		ob_start();
		?>
		<img data-url="<?php echo $link; ?>" 
			 title="<?php esc_html_e('Click to copy the link', FS_AFFILIATES_LOCALE); ?>" 
			 alt="<?php esc_html_e('Click to copy the link', FS_AFFILIATES_LOCALE); ?>" 
			 src="<?php echo FS_AFFILIATES_PLUGIN_URL; ?>/assets/images/frontend/copy_link.png" 
			 id="fs_copy_clipboard_image" class="<?php echo esc_attr($copy_img_class); ?>"/>

		<div style="display:none;"class="<?php echo esc_attr($copy_msg_class); ?>">
			<p><?php echo '<b>' . esc_html('Link Copied', FS_AFFILIATES_LOCALE) . '</b>'; ?></p>
		</div>

		<?php
		return ob_get_clean();
	}

}

if (!function_exists('fs_affiliates_get_product_name_from_order')) {

	/**
	 * Get referals id by affiliate id
	 * 
	 * @since 1.0.0
	 * @param int $order_id 
	 */
	function fs_affiliates_get_product_name_from_order( $order_id ) {
		$product_name = array();
		$order = wc_get_order($order_id);
		$items = $order->get_items();

		foreach ($items as $item) {
			$product_name[] = $item->get_name();
		}

		if (count($product_name) > 2) {
			return '(' . $product_name[0] . ',' . $product_name[1] . '...)';
		} elseif (count($product_name) == 2) {
			return '(' . $product_name[0] . ',' . $product_name[1] . ')';
		}

		return '(' . $product_name[0] . ')';
	}

}

if (!function_exists('fs_affiliates_verify_captcha')) {

	/**
	 * Verify Google Captcha
	 * 
	 * @since 1.0.0
	 * @param bool $response
	 * @return string
	 */
	function fs_affiliates_verify_captcha( $response = false ) {
		$secre_key = trim(get_option('fs_affiliates_recaptcha_secret_key', ''));
		$remoteip = $_SERVER['REMOTE_ADDR'];

		if (false === $response) {
			$response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
		}

		if (!$response || !$remoteip) {
			return false;
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';

		// make a POST request to the Google reCAPTCHA Server
		$request = wp_remote_post(
				$url, array(
			'timeout' => 10,
			'body' => array(
				'secret' => $secre_key,
				'response' => $response,
				'remoteip' => $remoteip,
				),
				)
		);

		// get the request response body
		$request_body = wp_remote_retrieve_body($request);

		if (!$request_body) {
			return false;
		}

		$result = json_decode($request_body, true);

		return $result;
	}

}

if (!function_exists('fs_affiliates_display_payment_method')) {

	/**
	 * Display Payment Method.
	 * 
	 * @since 1.0.0
	 * @param string $method
	 * @return string
	 */
	function fs_affiliates_display_payment_method( $method ) {

		if (empty($method)) {
			return;
		}

		switch ($method) {
			case 'BACS':
				return esc_html__('BACS', FS_AFFILIATES_LOCALE);
				break;
			case 'PayPal':
				return esc_html__('PayPal', FS_AFFILIATES_LOCALE);
				break;
			case 'Wallet':
				return esc_html__('Wallet', FS_AFFILIATES_LOCALE);
				break;
			default:
				return esc_html__('SUMO Reward Points', FS_AFFILIATES_LOCALE);
				break;
		}
	}

}


if (!function_exists('fs_get_all_affiliate_ids')) {

	/**
	 * Return all affiliate id's
	 * 
	 * @since 1.0.0
	 */
	function fs_get_all_affiliate_ids() {

		return get_posts(array(
			'post_type' => 'fs-affiliates',
			'post_status' => 'fs_active',
			'fields' => 'ids',
			'numberposts' => -1,
			'post_status' => array( 'fs_active', 'fs_inactive', 'fs_pending_approval', 'fs_rejected', 'fs_suspended', 'fs_hold', 'fs_pending_payment' ),
		));
	}

}

if (!function_exists('fs_update_affiliate_payment_data')) {

	/**
	 * Return all affiliate id's
	 * 
	 * @since 1.0.0
	 * @param int $affiliate_id
	 * @param string $payment_method
	 * @param int $disp_notice_type
	 * @return void
	 */
	function fs_update_affiliate_payment_data( $affiliate_id, $payment_method, $disp_notice_type ) {
		if (empty($affiliate_id) || empty($payment_method) || empty($disp_notice_type)) {
			return;
		}

		$paypal_email = '';
		$pay_bank = '';
		$already_payment_method = '';
		$saved_payment_datas = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);

		if (fs_affiliates_check_is_array($saved_payment_datas)) {
			$paypal_email = ( isset($saved_payment_datas['fs_affiliates_paypal_email']) ) ? $saved_payment_datas['fs_affiliates_paypal_email'] : '';
			$pay_bank = ( isset($saved_payment_datas['fs_affiliates_bank_details']) ) ? $saved_payment_datas['fs_affiliates_bank_details'] : '';
			$already_payment_method = ( isset($saved_payment_datas['fs_affiliates_payment_method']) ) ? $saved_payment_datas['fs_affiliates_payment_method'] : '';
		}

		$payment_datas = array(
			'fs_affiliates_current_id' => $affiliate_id,
			'fs_affiliates_payment_method' => $payment_method,
			'fs_affiliates_paypal_email' => $paypal_email,
			'fs_affiliates_bank_details' => $pay_bank,
		);

		update_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', $payment_datas);

		$already_saved_disp_notice_type = get_post_meta($affiliate_id, 'fs_affiliates_payment_notice_disp_type', true);

		if ($already_payment_method != $payment_method) {
			update_post_meta($affiliate_id, 'fs_affiliates_payment_notice_disp_type', $disp_notice_type);
		}
	}

}

if (!function_exists('fs_affiliates_get_template')) {

	/**
	 * Get the template from theme or plugin.
	 * 
	 * @since 1.0.0
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return void
	 */
	function fs_affiliates_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		$cache_key = sanitize_key(implode('-', array( 'template', $template_name, $template_path, $default_path )));
		$template = (string) wp_cache_get($cache_key, 'fs_affiliates');

		if (!$template) {
			$template = fs_affiliates_locate_template($template_name, $template_path = '', $default_path = '');
		}

		if (!file_exists($template)) {
			return;
		}

		if (fs_affiliates_check_is_array($args)) {
			extract($args);
		}

		include $template ;
	}

}

if (!function_exists('fs_affiliates_locate_template')) {

	/**
	 * Locate the template.
	 * 
	 * @since 1.0.0
	 * @param string $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	function fs_affiliates_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		if (!$template_path) {
			$template_path = apply_filters('fs_affiliates_template_path', 'affs/');
		}

		if (!$default_path) {
			$default_path = FS_AFFILIATES_PLUGIN_PATH . '/templates/';
		}

		$template = locate_template(array(
			trailingslashit($template_path) . $template_name,
			$template_name . '.php',
		));

		if (!$template) {
			$template = $default_path . $template_name;
		}

		/**
		 * This hook is used to alter the locate template
		 * 
		 * @since 1.0.0
		 * @param string $template
		 * @param string $template_name
		 * @return string
		 */
		return apply_filters('fs_affiliates_locate_template', $template, $template_name);
	}

}

if (!function_exists('fs_affiliates_get_template_html')) {

	/**
	 * Get the template as HTML.
	 * 
	 * @since 1.0.0
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return html
	 */
	function fs_affiliates_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		ob_start();
		fs_affiliates_get_template($template_name, $args, $template_path, $default_path);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

if (!function_exists('fs_affiliates_dashboard_menu_link')) {

	/**
	 * Get the dashboard menu link.
	 * 
	 * @since 1.0.0 
	 * @param string $get_permalink
	 * @param string $action
	 * @param string $query_nonce
	 * @return mixed
	 */
	function fs_affiliates_dashboard_menu_link( $get_permalink, $action, $query_nonce ) {

		if ($action == '') {
			return $get_permalink;
		}

		$permalink = esc_url(remove_query_arg(array( 'order_id', 'fs_nonce', 'fs_status' ), $get_permalink));

		return esc_url_raw(add_query_arg(array( 'fs_section' => $action, 'fs_nonce' => $query_nonce ), $permalink));
	}

}

if (!function_exists('fs_affiliates_is_payment_gateway_selected')) {

	/**
	 * Is affiliates payment gateway selected ?.
	 * 
	 * @param int $affiliate_id
	 * @return bool
	 */
	function fs_affiliates_is_payment_gateway_selected( $affiliate_id ) {

		if (empty($affiliate_id)) {
			return false;
		}

		$payment_data = get_post_meta($affiliate_id, 'fs_affiliates_user_payment_datas', true);
		if (!fs_affiliates_check_is_array($payment_data)) {
			return false;
		}
		$method = isset($payment_data['fs_affiliates_payment_method']) ? $payment_data['fs_affiliates_payment_method'] : false;
		$payment_method = false;

		switch ($method) {
			case 'direct':
				$pay_bank = isset($payment_data['fs_affiliates_bank_details']) ? $payment_data['fs_affiliates_bank_details'] : '';
				$payment_method = !empty($pay_bank) ? true : false;
				break;
			case 'paypal':
				$paypal_email = isset($payment_data['fs_affiliates_paypal_email']) ? $payment_data['fs_affiliates_paypal_email'] : '';
				$payment_method = !empty($paypal_email) ? true : false;
				break;
			case 'wallet':
			case 'reward_points':
				$payment_method = true;
				break;
		}

		/**
		 * This hook is used to alter the payment gateway selected.
		 * 
		 * @since 1.0.0
		 * @param bool $payment_method
		 * @param int $affiliate_id
		 */
		return apply_filters('fs_affiliates_is_payment_gateway_selected', $payment_method, $affiliate_id);
	}

}

if (!function_exists('fs_format_affiliate_level_product_rule_data')) {

	/**
	 * Format the affiliate level product rule data.
	 *
	 * @since 1.0.0
	 * 
	 * @return array
	 */
	function fs_format_affiliate_level_product_rule_data( $data ) {
		$default_data = array(
			'type' => '1',
			'products' => array(),
			'categories' => array(),
			'commission_type' => 'percentage',
			'commission_value' => '',
		);

		return wp_parse_args($data, $default_data);
	}

}

if (!function_exists('fs_get_pagination_number')) {

	/**
	 * Get the pagination number.
	 *
	 * @since 9.2.0
	 *
	 * @return int
	 */
	function fs_get_pagination_number( $start, $page_count, $current_page ) {
		$page_no = false;
		if ($current_page <= $page_count && $start <= $page_count) {
			$page_no = $start;
		} else if ($current_page > $page_count) {
			$overall_count = $current_page - $page_count + $start;
			if ($overall_count <= $current_page) {
				$page_no = $overall_count;
			}
		}
		/**
		 * This hook is used to alter the pagination number.
		 * 
		 * @since 9.2.0
		 */
		return apply_filters('fs_pagination_number', $page_no, $start, $page_count, $current_page);
	}

}

if (!function_exists('fs_get_pagination_classes')) {

	/**
	 * Get the pagination classes.
	 *
	 * @since 9.2.0
	 *
	 * @return array
	 */
	function fs_get_pagination_classes( $page_no, $current_page ) {
		$classes = array( 'fs-pagination', 'fs-pagination-' . $page_no );
		if ($current_page == $page_no) {
			$classes[] = 'current';
		}
		/**
		 * This hook is used to alter the pagination classes.
		 * 
		 * @since 9.2.0
		 */
		return apply_filters('fs_pagination_classes', $classes, $page_no, $current_page);
	}

}

if (!function_exists('fs_get_product_ids')) {

	/**
	 * Get the product ids.
	 *
	 * @since 9.2.0
	 *
	 * @return array
	 */
	function fs_get_product_ids( $search ) {
		$args = array(
			'fields' => 'ids',
			'numberposts' => -1,
			'post_type' => array( 'product', 'product_variation' ),
			'orderby' => 'title',
			'order' => 'asc',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => '_stock_status',
					'value' => 'instock',
				),
				array(
					'key' => '_price',
					'value' => 0,
					'compare' => '!=',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => array( 'variable' ),
					'operator' => 'NOT IN',
				),
			),
		);

		if (!empty($search)) {
			$args['s'] = $search;
		}

		/**
		 * This hook is used to product post arguments.
		 * 
		 * @since 9.2.0
		 */
		$post_ids = get_posts(apply_filters('fs_affs_product_post_args', $args));

		/**
		 * This hook is used to alter the product ids.
		 * 
		 * @since 9.2.0
		 */
		return apply_filters('fs_product_ids', $post_ids);
	}

}

if (!function_exists('fs_affiliates_number_format')) {

	/**
	 * Get the Affiliates Number Format
	 *
	 * @since 9.4.0
	 * 
	 * @param int/float $commission
	 *
	 * @return int/float
	 */
	function fs_affiliates_number_format( $commission ) {

		return !empty($commission) ? number_format($commission, 6, '.', '') : 0;
	}

}

if (!function_exists('fs_affiliates_get_order_subtotal_tax')) {

	/**
	 * Get the Affiliates Order Subtotal Tax
	 *
	 * @since 9.6.0
	 * 
	 * @param object $order
	 *
	 * @return int/float
	 */
	function fs_affiliates_get_order_subtotal_tax( $order ) {
		$tax_total = 0;
		foreach ($order->get_items() as $item) {
			$tax_total += $item->get_subtotal_tax();
		}
		return $tax_total;
	}

}

if (!function_exists('fs_affiliates_get_current_screen_id')) {

	/**
	 * Get the affiliates get current screen id.
	 *
	 * @since 9.6.0
	 *
	 * @return string
	 */
	function fs_affiliates_get_current_screen_id() {
		$newscreenids = get_current_screen();

		return str_replace('edit-', '', $newscreenids->id);
	}

}

if (!function_exists('fs_affiliates_get_shop_order_screen_ids')) {

	/**
	 * Get the affiliates shop order screen ids.
	 * 
	 * @since 9.6.0
	 * @return array
	 */
	function fs_affiliates_get_shop_order_screen_ids() {
		/**
		 * This hook is used to alter the shop order screen ids
		 * 
		 * @since 9.6.0
		 */
		return apply_filters('fs_affiliates_shop_order_screen_ids', array( 'shop_order' ));
	}

}

if (!function_exists('fs_affiliates_page_screen_ids')) {

	/**
	 * Get the page screen IDs.
	 * 
	 * @since 9.6.0
	 * @return array
	 */
	function fs_affiliates_page_screen_ids() {
		$wc_screen_id = sanitize_title(__('WooCommerce', 'woocommerce'));
		/**
		 * This hook is used to alter the page screen IDs.
		 * 
		 * @since 9.6.0
		 */
		return apply_filters(
				'fs_affiliates_page_screen_ids', array(
			'toplevel_page_fs_affiliates',
			'product_cat',
			'product',
			'shop_order',
			$wc_screen_id . '_page_wc-orders',
			'woocommerce_page_wc-orders',
				)
		);
	}

}

if (!function_exists('fs_dashboard_get_pagination_args')) {

	/**
	 * Prepare the dashboard pagination arguments.
	 *
	 * @since 10.0.0
	 * @param int $current_page
	 * @param int $page_count
	 * @return array
	 */
	function fs_dashboard_get_pagination_args( $current_page, $page_count ) {
		$pagination_length = (int) get_option('fs_affiliates_pagination_range');
		$start_page = $current_page;
		$end_page = ( $current_page + ( $pagination_length - 1 ) );
		/**
		 * This hook is used to alter the dashboard pagination arguments.
		 *
		 * @since 10.0.0
		 */
		return apply_filters(
				'fs_dashboard_pagination_arguments',
				array(
					'page_count' => $page_count,
					'current_page' => $current_page,
					'start_page' => $start_page,
					'end_page' => ( $end_page < $page_count ) ? $end_page : $page_count,
					'pagination_length' => $pagination_length,
					'prev_page_count' => ( ( $current_page - 1 ) == 0 ) ? ( $current_page ) : ( $current_page - 1 ),
					'next_page_count' => ( ( $current_page + 1 ) <= ( $page_count ) ) ? ( $current_page + 1 ) : ( $current_page ),
					'prev_page_row' => ( ( $current_page - $pagination_length ) > 0 ) ? ( $current_page - $pagination_length ) : 1,
					'next_page_row' => ( ( $current_page + $pagination_length ) < ( $page_count ) ) ? ( $current_page + $pagination_length ) : ( $page_count ),
					'prev_dot' => ( $current_page <= $page_count && 1 != $current_page ),
					'next_dot' => ( $start_page + $pagination_length ) <= $page_count,
					'prev_arrows' => ( 1 != $current_page ),
					'next_arrows' => ( $page_count != $current_page ),
				),
		);
	}

}

if (!function_exists('fs_affiliates_get_pagination_template_path')) {

	/**
	 * Get the pagination template path
	 * 
	 * @since 10.0.0
	 * @param string $table_name
	 * @return array
	 */
	function fs_affiliates_get_pagination_template_path( $table_name ) {

		$template_args = array(
			'fs-referrals' => 'dashboard/referrals.php',
			'fs-visits' => 'dashboard/visits.php',
			'fs-payouts' => 'dashboard/payouts.php',
			'fs-product-commission' => 'dashboard/wc-product-commission-wrapper.php',
			'fs-wallet-logs' => 'dashboard/affiliate-wallet.php',
			'wc-coupon-linking' => 'dashboard/wc-coupon-linking.php',
			'fs-commission-transfer' => 'dashboard/wallet-commission-transfer.php',
			'fs-commission-earned' => 'dashboard/leaderboard/commission-earned.php.php',
			'fs-no-of-referrals' => 'dashboard/leaderboard/no-of-referrals.php',
			'fs-amount-spend' => 'dashboard/leaderboard/amount-spend.php',
			'fs-no-of-orders-placed' => 'dashboard/leaderboard/no-of-orders-placed.php',
		);

		$template_path = isset($template_args[$table_name]) ? $template_args[$table_name] : '';

		/**
		 * This hook is used to alter the pagination template path.
		 * 
		 * @since 10.0.0
		 * @param string $template_path
		 * @param string $table_name
		 * @return string
		 */
		return apply_filters('fs_affiliates_pagination_template_path', $template_path , $table_name );
	}

}

if ( ! function_exists( 'fs_affiliates_get_edit_order_link' ) ) {

	/**
	 * Get the user edit order page URL.
	 *
	 * @since 10.4.0
	 * @param int $order_id
	 * @return URL
	 */
	function fs_affiliates_get_edit_order_link( $order_id ) {
		if ( ! function_exists('wc_get_order') ) {
			return $order_id;
		}

		$order = wc_get_order($order_id);
		if ( !is_object($order)) {
			return;
		}

		return '<a href="' . esc_url(add_query_arg(array( 'post' => $order_id, 'action' => 'edit' ), admin_url( 'post.php' ))) . '" >' . '#' . $order_id . '</a>';
	}

}

if ( ! function_exists( 'fs_get_shipping_methods' ) ) {

	/**
	 * Get the shipping methods.
	 *
	 * @since 10.5.0
	 * @return array
	 */
	function fs_get_shipping_methods() {
		$available_shipping_methods = array();
		$shipping_zones = WC_Shipping_Zones::get_zones();

		foreach ( $shipping_zones as $shipping_zone ) {
			foreach ($shipping_zone['shipping_methods'] as $shipping_method) {
				$shipping_method_title = $shipping_method->method_title . ' - ' . $shipping_zone['zone_name'];
				$shipping_method_key = $shipping_method->id . '_' . $shipping_method->instance_id;
				$available_shipping_methods[$shipping_method_key] = $shipping_method_title;
			}
		}

		return $available_shipping_methods;
	}
}

if ( ! function_exists( 'fs_link_wc_coupon_for_affiliate' ) ) {

	/**
	 * Link the WooCommerce Coupon with an Affiliate.
	 *
	 * @since 10.8.0
	 * @return array
	 */
	function fs_link_wc_coupon_for_affiliate( $linking_data ) {
		$meta_data = array();
		$post_args = array(
			'post_status' => $linking_data['status'],
			'post_author' => $linking_data['affiliate_id'],
		);

		$meta_data['coupon_data'] = $linking_data['coupon_id'];
		$meta_data['commission_level'] = $linking_data['commission_level'];
		$meta_data['commission_type'] = $linking_data['commission_type'];
		$meta_data['commission_value'] = $linking_data['commission_value'];

		fs_affiliates_link_new_affiliate($meta_data, $post_args);
	}
}