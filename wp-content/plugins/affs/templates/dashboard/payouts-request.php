<?php
/**
 * This template is used for display dashboard payouts request.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/payouts-request.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @var int|string $per_page
 * @var array $post_ids
 * @var object $payoutrequestobj
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard payouts request table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_payouts_request_table');
?>
<div class='fs_affiliates_form'>
	<h2><?php _e('Payouts Request(s)', FS_AFFILIATES_LOCALE); ?></h2>
	<table class='fs_affiliate_payout_request_log_table fs_affiliates_table fs_affiliates_frontend_table'>
		<th class='fs_affiliates_sno fs_affiliate_payout_request_log_sno'><?php _e('S.No', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Total Unpaid Commission', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Status', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Requested Date', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Notes', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Closed Date', FS_AFFILIATES_LOCALE); ?></th>
		<tbody>
			<?php
			if (fs_affiliates_check_is_array($post_ids)) {
				$i = 1;
				foreach ($post_ids as $postid) {
					$payoutrequestobj = get_post($postid);
					$ClosedDate = get_post_meta($postid, 'fs_closed_date', true);
					?>
					<tr>
						<td data-title='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliate_payout_request_log_sno'><?php echo $i; ?></td>
						<td data-title='<?php esc_html_e('Total Unpaid Commission', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_price(get_post_meta($postid, 'fs_affiliates_unpaid_commission', true)); ?></td>
						<td data-title='<?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?>'>
							<?php
							if (get_post_status($postid) == 'fs_submitted') {
								_e('Submitted', FS_AFFILIATES_LOCALE);
							} elseif (get_post_status($postid) == 'fs_progress') {
								_e('In-Progress', FS_AFFILIATES_LOCALE);
							} else {
								_e('Closed', FS_AFFILIATES_LOCALE);
							}
							?>
						</td>
						<td data-title='<?php esc_html_e('Requested Date', FS_AFFILIATES_LOCALE); ?>' ><?php echo $payoutrequestobj->post_date; ?></td>
						<td data-title='<?php esc_html_e('Notes', FS_AFFILIATES_LOCALE); ?>' ><?php echo empty($payoutrequestobj->post_content) ? '-' : $payoutrequestobj->post_content; ?></td>
						<td data-title='<?php esc_html_e('Closed Date', FS_AFFILIATES_LOCALE); ?>' ><?php echo empty($ClosedDate) ? '-' : date('Y-m-d h:i:s', $ClosedDate); ?></td>
					</tr>
					<?php
					$i++;
				}
			}
			?>
		</tbody>
		<tfoot>
			<?php if ($page_count > 1) : ?>
				<tr style='clear:both;'>
					<td colspan='6' class='footable-visible'>                    
						<?php fs_affiliates_get_template('dashboard/pagination.php', $table_args['pagination']); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tfoot>  
	</table>
</div>
<?php
/**
 * This hook is used to do extra action after affiliates dashboard payouts request table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_payouts_request_table');
