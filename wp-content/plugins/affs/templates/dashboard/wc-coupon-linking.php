<?php
/**
 * This template is used for display dashboard WC coupon linking .
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/wc-coupon-linking.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.0.0
 * @var array $post_ids
 * @var object $LinkedCouponObj
 * @var string $CouponName
 * @var string $Status
 * @var int $page_count
 * @var int $offset
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action after affiliates dashboard WC coupon linking.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_wc_coupon_linking');
?>
<div class='fs_affiliates_form'>
	<h2><?php _e('Linked Coupon(s)', FS_AFFILIATES_LOCALE); ?></h2>
	<table class='fs_affiliates_coupon_linking_table fs_affiliates_frontend_table'>
		<th class='fs_affiliates_sno fs_affiliates_coupon_linking_sno'><?php _e('S.No', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Coupon Name', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Coupon Attributes', FS_AFFILIATES_LOCALE); ?></th>
		<th><?php _e('Status', FS_AFFILIATES_LOCALE); ?></th>
		<tbody>
			<?php
			if (fs_affiliates_check_is_array($post_ids)) {                
				$i = $offset + 1;
				foreach ($post_ids as $key => $LinkedId) {
					$LinkedCouponObj = new FS_Linked_Affiliates_Data($LinkedId);
					$CouponName = !empty($LinkedCouponObj->coupon_data) ? get_the_title($LinkedCouponObj->coupon_data) : '';
										$coupon_linking_object = new FS_Affiliates_WC_Coupon_Linking();
					$Status = $coupon_linking_object->get_coupon_status($LinkedCouponObj->coupon_data);
					?>
					<tr>
						<td data-title='<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliates_coupon_linking_sno'><?php echo $i; ?></td>
						<td data-title='<?php esc_html_e('Coupon Name', FS_AFFILIATES_LOCALE); ?>' ><?php echo $CouponName; ?></td>
						<td data-title='<?php esc_html_e('Coupon Attributes', FS_AFFILIATES_LOCALE); ?>' ><?php echo $coupon_linking_object->get_coupon_datas($LinkedCouponObj->coupon_data); ?></td>
						<?php if (in_array('Invalid', $Status)) { ?>
							<td data-title='<?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?>' ><?php _e('Invalid', FS_AFFILIATES_LOCALE); ?></td>
						<?php } else { ?>
							<td data-title='<?php esc_html_e('Status', FS_AFFILIATES_LOCALE); ?>' ><?php _e('Valid', FS_AFFILIATES_LOCALE); ?></td>
						<?php } ?>
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
 * This hook is used to do extra action after affiliates dashboard WC coupon linking.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_wc_coupon_linking');
