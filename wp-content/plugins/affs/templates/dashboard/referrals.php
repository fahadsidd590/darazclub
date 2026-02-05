<?php
/**
 * This template is used for display dashboard referrals.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/referrals.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @var array $date_filter
 * @var array $post_ids
 * @var int $per_page
 * @var float $unpaid_amount
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard referrals table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_referrals_table');
?>
<div class='fs_affiliates_form'>      
	<h2><?php _e('Referrals', FS_AFFILIATES_LOCALE); ?></h2>
	<?php if (!empty($unpaid_amount) && apply_filters('fs_affiliates_payout_request_enable', false)) { ?>
		<button style='margin-bottom: 15px !important;margin-left: 10px !important;' class='fs_request_unpaid_commission fs_affiliates_form_save' data-affiliateid='<?php echo $affiliate_id; ?>'><?php _e('Request Unpaid Commission', FS_AFFILIATES_LOCALE); ?></button>
	<?php } ?>
	<table class='fs_affiliates_referrals_frontend_table fs_affiliates_frontend_table' data-table_name='fs-referrals'>
		<tbody>
			<tr>
				<th class='fs_affiliates_sno fs_affiliates_referrals_sno'><?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Reference', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Description', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Amount', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?></th>
			</tr>
			<?php
			
			$sno = $offset + 1;

			use Automattic\WooCommerce\Utilities\OrderUtil;

			if (fs_affiliates_check_is_array($post_ids)) {
				foreach ($post_ids as $referral_id) {
					$reference_id = get_post_meta($referral_id, 'reference', true);
					$referral_name = '#' . $reference_id;

					if ( class_exists('OrderUtil') && 'shop_order' === OrderUtil::get_order_type($reference_id)) {
						$referral_name = apply_filters('fs_affiliates_order_link', $referral_name, $reference_id, $affiliate_id);
					}

					$description = get_post_meta($referral_id, 'description', true);
					$amount = get_post_meta($referral_id, 'amount', true);
					$status = get_post_status($referral_id);
					$timestamp = get_post_meta($referral_id, 'date', true);
					$reject_reason = get_post_meta($referral_id, 'rejected_reason', true);
					$paid_reason = get_post_meta($referral_id, 'paid_reason', true);
					$reason = '';

					if ('fs_rejected' == $status && !empty($reject_reason)) {
						$reason .= '</br>' . sprintf(esc_html__('Reason : %s', FS_AFFILIATES_LOCALE), $reject_reason);
					}
										
					if ('fs_paid' == $status && !empty($paid_reason)) {
						$reason .= '</br>' . sprintf(esc_html__('Reason : %s', FS_AFFILIATES_LOCALE), $paid_reason);
					}
					?>
					<tr>
						<td data-title ='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliates_referrals_sno'><?php echo $sno; ?></td>
						<td data-title ='<?php esc_html_e('Reference', FS_AFFILIATES_LOCALE); ?>' ><?php echo $referral_name; ?></td>
						<td data-title ='<?php esc_html_e('Description', FS_AFFILIATES_LOCALE); ?>' ><?php echo $description; ?></td>
						<td data-title ='<?php esc_html_e('Amount', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_price($amount); ?></td>
						<td data-title ='<?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_get_status_display($status) . $reason; ?></td>
						<td data-title ='<?php esc_html_e('Date', FS_AFFILIATES_LOCALE); ?>' ><?php echo empty($timestamp) ? '' : fs_affiliates_local_datetime($timestamp); ?></td>
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
					<?php fs_affiliates_get_template( 'dashboard/pagination.php', $pagination ); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tfoot>        
	</table>
</div>
<?php
/**
 * This hook is used to do extra action after affiliates dashboard referrals table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_referrals_table');
