<?php
/**
 * This template is used to display the landing page commission data.
 *
 * This template can be overridden by copying it to yourtheme/affs/landing-commission-details/landing-commission-details.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.6.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before landing page commission detail table.
 * 
 * @since 10.6.0
 */
do_action('fs_affiliates_before_landing_commission_detail_table');
?>
<div class="fs_landing_commission_detail">
	<h2><?php _e('Landing Commission Details', FS_AFFILIATES_LOCALE); ?></h2>
	<table class="fs_landing_commission_detail_table fs_affiliates_frontend_table" data-table_name='fs-creatives' >
		<tbody>
			<tr>
				<th><?php esc_html_e('Commission ID', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Usage/Limit', FS_AFFILIATES_LOCALE); ?></th>
			</tr>
			<tr>
				<td>
					<?php
					echo esc_html($commission_id);
					?>
				</td>
				<td>
					<?php
					if ('2' == $landing_commission_object->usage_type) {
						$used_count = !empty(get_post_meta($landing_commission_object->get_id(), 'fs_affs_lc_used_count', true)) ? get_post_meta($landing_commission_object->get_id(), 'fs_affs_lc_used_count', true) : 0;
						echo wp_kses_post($used_count . ' / ' . $landing_commission_object->validity_count);
					} else {
						echo wp_kses_post('-');
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
/**
 * This hook is used to do extra action after landing page commission detail table.
 * 
 * @since 10.6.0
 */
do_action('fs_affiliates_after_landing_commission_detail_table');
