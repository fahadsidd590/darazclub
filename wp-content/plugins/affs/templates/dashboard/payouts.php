<?php
/**
 * This template is used for display dashboard payouts.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/payouts.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @param array $date_filter
 * @param int|string $per_page
 * @param array $post_ids
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard payouts table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_payouts_table');
?>
<div class='fs_affiliates_form'>    
	<h2><?php esc_html_e('Payouts', FS_AFFILIATES_LOCALE); ?></h2>
	<table class='fs_affiliates_Payout_frontend_table fs_affiliates_frontend_table' data-table_name='fs-payouts'>
		<tbody>
			<tr>
				<th class='fs_affiliates_sno fs_affiliates_Payout_sno'><?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Payout ID', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Payment Mode', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Paid Amount', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?></th>
				<?php if (apply_filters('fs_affiliates_is_payout_statements_available', false)) { ?>
					<th><?php esc_html_e('Payout Statements', FS_AFFILIATES_LOCALE); ?></th>
				<?php } ?>
			</tr>
			<?php
			$sno = $offset + 1;
			if (fs_affiliates_check_is_array($post_ids)) {
				foreach ($post_ids as $payout_id) {
					$payout_obj = new FS_Affiliates_Payouts($payout_id);
					$preparre_dwnld_url = '<a href="' . esc_url_raw(add_query_arg(array( 'section' => 'payout_statements', 'payout_statement_id' => $payout_id ), get_permalink())) . '">' . __('Download', FS_AFFILIATES_LOCALE) . '</a>';
					?>
					<tr>
						<td data-title='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliates_Payout_sno'><?php echo $sno; ?></td>
						<td data-title='<?php esc_html_e('Payout ID', FS_AFFILIATES_LOCALE); ?>' ><?php echo $payout_id; ?></td>
						<td data-title='<?php esc_html_e('Payment Mode', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_display_payment_method($payout_obj->payment_mode); ?></td>
						<td data-title='<?php esc_html_e('Paid Amount', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_price($payout_obj->paid_amount); ?></td>
						<td data-title='<?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_get_status_display($payout_obj->get_status()); ?></td>
						<td data-title='<?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_local_datetime($payout_obj->date); ?></td>
						<?php
						if (apply_filters('fs_affiliates_is_payout_statements_available', false)) {
							$footable_colspan = 7;
							?>
							<td data-title='<?php esc_html_e('Payout Statements', FS_AFFILIATES_LOCALE); ?>' style='text-align:center'>
								<?php
								echo apply_filters('fs_affiliates_is_pay_slip_exists', false, $payout_id) ? $preparre_dwnld_url : '-'
								?>
							</td>
						<?php } ?>

					</tr>
					<?php
					$sno++;
				}
			} else {
				?>
				<tr>
					<td colspan='6'><?php esc_html_e('No Records found', FS_AFFILIATES_LOCALE); ?></td>
				<tr>
					<?php
			}
			?>
		</tbody>
		<tfoot>
			<?php if ($page_count > 1) : ?>
				<tr style='clear:both;'>
					<td colspan='6' class='footable-visible'>
						<div class='pagination pagination-centered'>
							<?php fs_affiliates_get_template('dashboard/pagination.php', $pagination); ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>
</div>
<?php
/**
 * This hook is used to do extra action after affiliates dashboard payouts table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_payouts_table');
