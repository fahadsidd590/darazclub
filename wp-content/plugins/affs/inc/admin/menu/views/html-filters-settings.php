<?php
/**
 * Dashboard Filters
 *
 * @since 10.0.0
 * @param bool $show_data_filter
 * @param array $date_filter
 * @param string $selected_filter
 * @param string $from_date
 * @param string $to_date
 * */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<div class='fs-date-filter'>
	<?php
	if ($show_data_filter) :
		?>
		<div class='fs-date-filters'>
			<select name='fs_filter' class='fs-date-filter-type'>
				<?php foreach ($date_filter as $option_key => $option_value) : ?>
					<option value="<?php echo esc_attr($option_key); ?>" <?php selected($selected_filter, $option_key); ?>><?php echo esc_html($option_value); ?></option>
				<?php endforeach; ?>
			</select>
			<div class='fs-custom-date-range'>
				<?php
				fs_get_datepicker_html(array(
					'name' => 'fs_from_date',
					'wp_zone' => false,
					'placeholder' => FS_Date_Time::get_wp_datetime_format(),
					'value' => $from_date,
				));
				fs_get_datepicker_html(array(
					'name' => 'fs_to_date',
					'wp_zone' => false,
					'placeholder' => FS_Date_Time::get_wp_datetime_format(),
					'value' => $to_date,
				));
				?>
			</div>
			<input type='submit' class='fs-date-filter-button button-primary' name='fs_filter_button' value='<?php esc_html_e('Filter', FS_AFFILIATES_LOCALE); ?>'/>
		</div>
		<?php
	endif;
	?>
</div>
<?php
