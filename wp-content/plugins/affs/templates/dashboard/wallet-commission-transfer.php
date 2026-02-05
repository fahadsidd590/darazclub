<?php
/**
 * This template is used for display dashboard wallet commission transfer.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/wallet-commission-transfer.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @var float $available_balance
 * @var array $post_ids
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before dashboard wallet commission transfer.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_wallet_commmission_transfer');
?>
<div class='fs_affiliates_form'>
	<h2><?php _e('Wallet', FS_AFFILIATES_LOCALE); ?></h2>
	<div class='fs-affiliate-wallet-balance'>
		<label>
			<?php _e('Wallet Balance : ', FS_AFFILIATES_LOCALE); ?>
			<?php echo fs_affiliates_price($available_balance); ?>
		</label>
	</div>
	<div class='fs-affiliate-wallet-commission-transfer'>
	<h2><?php echo wp_kses_post(get_option('fs_affiliates_affiliate_wallet_commission_transfer_label')); ?></h2>
	<table class='fs-affiliate-commission-transfer-to-wallet-table fs_affiliates_table fs_affiliates_frontend_table' data-table_name='fs-commission-transfer'>
		<thead>
		<th><input type='checkbox' class='fs-select-all-referral-commission-ids' name='fs_select_all_referral_commission_ids'></th>
		<th><?php _e('Description', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Amount', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Date', FS_AFFILIATES_LOCALE); ?></th>
		</thead>
		<tbody>
			<?php
			if (fs_affiliates_check_is_array($post_ids)) {
				foreach ($post_ids as $referral_id) {
					$referral_object = new FS_Affiliates_Referrals($referral_id);
					if (!is_object($referral_object)) {
						continue;
					}
					?>
					<tr>
						<td data-title='<?php esc_html_e('Select', FS_AFFILIATES_LOCALE); ?>'><input type='checkbox' class='fs-affiliate-referral-commission fs_affiliate_referral_commission_<?php echo esc_attr($referral_id); ?>' name='fs_affiliate_referral_commission_ids' value='<?php echo esc_attr($referral_id); ?>' data-amount='<?php echo esc_attr($referral_object->amount); ?>'></td>
						<td data-title='<?php esc_html_e('Description', FS_AFFILIATES_LOCALE); ?>'><?php echo esc_html($referral_object->description); ?></td>
						<td data-title='<?php esc_html_e('Amount', FS_AFFILIATES_LOCALE); ?>'><?php echo wp_kses_post(fs_affiliates_price($referral_object->amount)); ?></td>
						<td data-title='<?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?>'><?php echo wp_kses_post(fs_affiliates_local_datetime($referral_object->date)); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan='4'><?php esc_html_e('No Records found!', FS_AFFILIATES_LOCALE); ?></td>
				<tr>
					<?php
			}
			?>
		</tbody>
		<tfoot>
			<?php if ($page_count > 1) { ?>
				<tr style='clear:both;'>
					<td colspan='6' class='footable-visible'>
						<div class='pagination pagination-centered'>
							<?php fs_affiliates_get_template('dashboard/pagination.php', $pagination); ?>
						</div>
					</td>
				</tr>
			<?php } ?>
		</tfoot>
	</table>
	<button class='fs-commission-transfer-to-wallet-btn' disabled><?php esc_html_e('Add to Wallet', FS_AFFILIATES_LOCALE); ?>[<span class='fs-commission-transfer-to-wallet-amount'><?php echo wp_kses_post(fs_affiliates_price(0)); ?></span>]</button>
</div>
<?php
/**
 * This hook is used to do extra action after dashboard wallet commission transfer.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_wallet_commmission_transfer');
