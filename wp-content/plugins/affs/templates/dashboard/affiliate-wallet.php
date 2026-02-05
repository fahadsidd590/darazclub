<?php
/**
 * This template is used for display dashboard affiliate wallet.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/affiliate-wallet.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @var string|float $available_balance
 * @var int $affiliate_id
 * @var int $per_page
 * @var int $offset
 * @var int $pagination_length
 * @var array $transaction_logs
 * @var array $pagination
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard wallet log table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_wallet_table');
?>
<div class='fs_affiliates_form'>
	<h2><?php _e('Wallet', FS_AFFILIATES_LOCALE); ?></h2>
	<div class='fs_affiliate_available_balance_in_wallet'>
		<label>
			<?php _e('Wallet Balance : ', FS_AFFILIATES_LOCALE); ?>
			<?php echo fs_affiliates_price($available_balance); ?>
		</label>
	</div>
	<div class='fs_affiliate_transaction_log_for_wallet'>
		<h2><?php _e('Transaction Log', FS_AFFILIATES_LOCALE); ?></h2>
		<table class='fs_affiliate_transaction_log_table fs_affiliates_table fs_affiliates_frontend_table' data-table_name='fs-wallet-logs'>
			<th class='fs_affiliates_sno fs_affiliate_transaction_log_sno'><?php _e('S.no', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php _e('Event', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php _e('Earned Balance', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php _e('Used Balance', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php _e('Available Balance', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php _e('Date', FS_AFFILIATES_LOCALE); ?></th>
			<tbody>
				<?php
				$args = array(
					'post_type' => 'fs-wallet-logs',
					'offset' => $offset,
					'numberposts' => $per_page,
					'post_status' => 'publish',
					'author' => $affiliate_id,
					'fields' => 'ids',
				);

				$pagination_length = get_option('fs_affiliates_pagination_range');
				$start_page = $current_page;
				$end_page = ( $current_page + ( $pagination_length - 1 ) );
				$pagination = fs_dashboard_get_pagination_args($current_page, $page_count);

				$transaction_logs = get_posts($args);
				if (fs_affiliates_check_is_array($transaction_logs)) {
					$i = 1;
					foreach ($transaction_logs as $transactionid) {
						$WalletObj = new FS_Affiliates_Wallet($transactionid);
						?>
						<tr>
							<td class='fs_affiliates_sno fs_affiliate_transaction_log_sno' data-title='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>'><?php echo $i; ?></td>
							<td data-title='<?php esc_html_e('Event', FS_AFFILIATES_LOCALE); ?>'><?php echo $WalletObj->event; ?></td>
							<td data-title='<?php esc_html_e('Earned Balance', FS_AFFILIATES_LOCALE); ?>'><?php echo fs_affiliates_price($WalletObj->earned_balance); ?></td>
							<td data-title='<?php esc_html_e('Used Balance', FS_AFFILIATES_LOCALE); ?>'><?php echo fs_affiliates_price($WalletObj->used_balance); ?></td>
							<td data-title='<?php esc_html_e('Available Balance', FS_AFFILIATES_LOCALE); ?>'><?php echo fs_affiliates_price($WalletObj->available_balance); ?></td>
							<td data-title='<?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?>'><?php echo fs_affiliates_local_datetime($WalletObj->date); ?></td>
						</tr>
						<?php
						++$i;
					}
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
</div>
<?php
/**
 * This hook is used to do extra action after affiliates dashboard wallet log table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_wallet_table');
