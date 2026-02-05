<?php
/**
 * This template is used for display dashboard creatives.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/creatives.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 1.0.0
 * @var int $offset
 * @var int $per_page
 * @var array $post
 * @var array $pagination
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard creatives table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_creatives_table');
?>
<div class="fs_affiliates_form">
	<h2><?php _e('Creatives', FS_AFFILIATES_LOCALE); ?></h2>
	<table class="fs_affiliates_creatives_frontend_table fs_affiliates_frontend_table" data-table_name='fs-creatives' >
		<tbody>
			<tr>
				<th class="fs_affiliates_sno fs_affiliates_creatives_frontend_sno"><?php _e('S.No', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Image', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('URL', FS_AFFILIATES_LOCALE); ?></th>
				<th><?php esc_html_e('Copy Link', FS_AFFILIATES_LOCALE); ?></th>
			</tr>
			<?php
			$args = array(
				'post_type' => 'fs-creatives',
				'offset' => $offset,
				'numberposts' => $per_page,
				'post_status' => 'fs_active',
				'fields' => 'ids',
			);
			$post = get_posts($args);
			$affiliate_name_id = get_option('fs_affiliates_referral_id_format') == 'name' ? get_the_title($affiliate_id) : $affiliate_id;
			$sno = $offset + 1;
			
			$start_page = $current_page;
			$end_page = ( $current_page + ( get_option('fs_affiliates_pagination_range') - 1 ) );
			$pagination = fs_dashboard_get_pagination_args($current_page, $page_count);

			foreach ($post as $creative_id) {
				if (!FS_Affiliates_Dashboard::is_valid_creative($creative_id, $affiliate_id)) {
					continue;
				}

				$image = get_post_meta($creative_id, 'image', true);
				$alt = get_post_meta($creative_id, 'alternative_text', true);
				$name = get_the_title($creative_id);
				$myurl = get_post_meta($creative_id, 'url', true);

				$query_arg = esc_url_raw(add_query_arg(array( 'ref' => $affiliate_name_id ), $myurl));
				$url = '<a href="' . $query_arg . '" title="' . $alt . '"><img src="' . $image . '" alt="' . $alt . '"/></a>';
				?>
				<tr>
					<td data-title="<?php esc_html_e('S.No', FS_AFFILIATES_LOCALE); ?>" class="fs_affiliates_sno fs_affiliates_creatives_frontend_sno"><?php echo $sno; ?></td>
					<td data-title="<?php esc_html_e('Image', FS_AFFILIATES_LOCALE); ?>" ><image src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" title="<?php echo $name; ?>"></td>
					<td data-title="<?php esc_html_e('URL', FS_AFFILIATES_LOCALE); ?>" ><?php echo htmlentities($url); ?></td>
					<td data-title="<?php esc_html_e('Copy Link', FS_AFFILIATES_LOCALE); ?>" class="fs_copy_creatives_link"><?php echo fs_display_copy_affiliate_link_image(htmlentities($url)); ?></td>
				</tr>
				<?php
				++$sno;
			}
			?>
		</tbody>
		<tfoot>
			<?php if ($page_count > 1) : ?>
				<tr style="clear:both;">
					<td colspan="6" class="footable-visible">
						<div class="pagination pagination-centered">
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
 * This hook is used to do extra action after affiliates dashboard creatives table.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_after_dashboard_creatives_table');
