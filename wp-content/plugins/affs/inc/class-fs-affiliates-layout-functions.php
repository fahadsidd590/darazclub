<?php
/**
 * Layout functions.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!function_exists('fs_get_datepicker_html')) {

	/**
	 * Return or display Datepicker/DateTimepicker HTML.
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @param bool $echo
	 * @return string
	 * */
	function fs_get_datepicker_html( $args, $echo = true ) {
		$args = wp_parse_args(
				$args,
				array(
					'class' => '',
					'id' => '',
					'name' => '',
					'placeholder' => '',
					'custom_attributes' => array(),
					'value' => '',
					'wp_zone' => true,
					'with_time' => false,
					'error' => '',
				)
		);

		$name = ( '' !== $args['name'] ) ? $args['name'] : $args['id'];

		$allowed_html = array(
			'input' => array(
				'id' => array(),
				'type' => array(),
				'placeholder' => array(),
				'class' => array(),
				'value' => array(),
				'name' => array(),
				'min' => array(),
				'max' => array(),
				'data-error' => array(),
				'style' => array(),
			),
		);

		$class_name = ( $args['with_time'] ) ? 'fs_datetimepicker ' : 'fs_datepicker ';
		$format = ( $args['with_time'] ) ? 'Y-m-d H:i' : 'date';

		// Custom attribute handling.
		$custom_attributes = fs_format_custom_attributes($args);
		$value = !empty($args['value']) ? FS_Date_Time::get_wp_format_datetime($args['value'], $format, $args['wp_zone']) : '';
		ob_start();
		?>
		<input type = "text" 
			   id="<?php echo esc_attr($args['id']); ?>"
			   value = "<?php echo esc_attr($value); ?>"
			   class="<?php echo esc_attr($class_name . $args['class']); ?>" 
			   placeholder="<?php echo esc_attr($args['placeholder']); ?>" 
			   data-error="<?php echo esc_attr($args['error']); ?>" 
			   <?php echo wp_kses(implode(' ', $custom_attributes), $allowed_html); ?>
			   />

		<input type = "hidden" 
			   class="fs_alter_datepicker_value" 
			   name="<?php echo esc_attr($name); ?>"
			   value = "<?php echo esc_attr($args['value']); ?>"
			   /> 
		<?php
		$html = ob_get_clean();

		if ($echo) {
			echo wp_kses($html, $allowed_html);
		}

		return $html;
	}

}

if (!function_exists('fs_format_custom_attributes')) {

	/**
	 * Format Custom Attributes.
	 *
	 * @since 1.0.0
	 * @param array $value
	 * @return array
	 */
	function fs_format_custom_attributes( $value ) {
		$custom_attributes = array();

		if (!empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
			foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
				$custom_attributes[] = esc_attr($attribute) . '=' . esc_attr($attribute_value) . '';
			}
		}

		return $custom_attributes;
	}

}

if (!function_exists('fs_get_dashboard_filter_html')) {

	/**
	 * Return or display Filter HTML.
	 *
	 * @since 10.0.0
	 * @param array $date_filter
	 * @param string $post_type
	 * @param int|string $post_per_page
	 * @param bool $show_data_filter
	 * @return string
	 * */
	function fs_get_dashboard_filter_html( $date_filter, $post_type, $post_per_page, $show_data_filter ) {
		$selected_filter = isset($_REQUEST['fs_filter']) ? wc_clean(wp_unslash($_REQUEST['fs_filter'])) : 'all';
		$from_date = isset($_REQUEST['fs_from_date']) ? wp_unslash($_REQUEST['fs_from_date']) : '';
		$to_date = isset($_REQUEST['fs_to_date']) ? wp_unslash($_REQUEST['fs_to_date']) : '';
		
		ob_start();
		include_once FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/html-filters-settings.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}

}