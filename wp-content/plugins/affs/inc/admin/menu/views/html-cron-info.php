<?php
/**
 * Cron information.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<table class="form-table fs-affiliate-cron-info widefat striped">
	<thead>
		<tr>
			<th><?php esc_html_e('Cron Name', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php esc_html_e('Last Updated', FS_AFFILIATES_LOCALE); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (fs_affiliates_check_is_array($server_cron_info)) {
			foreach ($server_cron_info as $key => $values) {
				?>
				<tr>
					<td><?php echo esc_html($values['cron']); ?></td>
					<td><?php echo esc_html($values['last_updated_date']); ?></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
<?php


