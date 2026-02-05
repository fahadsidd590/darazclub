<?php
/**
 * This template is used for display dashboard leaderboard amount spend.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/leaderboard/amount-spend.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @var int $overall_count
 * @var int $current_count
 * @var int $affiliate_id
 * @var int $per_page
 * @var int $offset
 * @var int $current_page
 * @var int $pagination_length
 * @var array $post_ids
 * @var object $affiliate_data
 * @var array $pagination
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard wallet amount spend.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_wallet_amount_spend');
?>
<div class ='fs_affiliates_form'>    
	<h2> <?php _e('Leaderboard', FS_AFFILIATES_LOCALE); ?></h2>
	<p><label><b><?php _e('Current Leaderboard Position', FS_AFFILIATES_LOCALE); ?></b><?php echo ++$position; ?> </label></p>
	<table class='fs_affiliates_leaderboard_frontend_table fs_affiliates_leaderboard_frontend_table_three fs_affiliates_frontend_table' data-display_type='<?php echo esc_attr($display_type); ?>' data-table_name='fs-amount-spend'>
		<tbody>
			<tr>
				<th class='fs_affiliates_sno fs_affiliates_leaderboard_three_sno'><?php _e('S.No', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php _e('Affiliate Name', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php _e('Amount Spent by Referrals', FS_AFFILIATES_LOCALE); ?></th>
			</tr>
			<?php
			foreach ($post_ids as $affiliate_id => $value) {
				$affiliate_data = new FS_Affiliates_Data($affiliate_id);
				?>
				<tr>
					<td data-title='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliates_leaderboard_three_sno'><?php echo $offset + 1; ?></td>
					<td data-title='<?php esc_html_e('Affiliate Name', FS_AFFILIATES_LOCALE); ?>' ><?php echo $affiliate_data->user_name; ?></td>
					<td data-title='<?php esc_html_e('Amount Spent by Referrals', FS_AFFILIATES_LOCALE); ?>' ><?php echo fs_affiliates_price($value); ?></td>
				</tr>
				<?php
				$offset++;
			}
			?>
			</tbody>
		<tfoot>
			<?php if ($page_count > 1) : ?>
				<tr style='clear:both;'>
					<td colspan='3' class='footable-visible'>
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
 * This hook is used to do extra action after affiliates dashboard wallet amount spend.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_wallet_amount_spend');
