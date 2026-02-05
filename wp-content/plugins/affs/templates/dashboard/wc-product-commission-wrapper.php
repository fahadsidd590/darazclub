<?php
/**
 * This template is used for displaying the WooCommerce product commission wrapper.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/wc-product-commission-wrapper.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 9.2.0
 * @var string $search
 * @var array $post_ids
 * @var int $affiliate_id
 * @var array $pagination
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<div class='fs_affiliates_form'>
	<h2><?php _e('Product Commission Rate(s)', FS_AFFILIATES_LOCALE); ?></h2>   
	<div class='fs-product-commission-wrapper'>
		<p class='fs-product-commission-filters fs-frontend-filter'>
			<input type='text' class='fs-product-commission-search fs-frontend-search' value='<?php echo esc_attr($search); ?>' />
			<button type='button' class='fs-product-commission-search-btn fs-frontend-search-btn'><?php _e('Search Products', FS_AFFILIATES_LOCALE); ?></button>
		</p>

		<table class='fs-affiliates-product-commisssion-table fs_affiliates_frontend_table' data-table_name='fs-product-commission'>   
			<thead>
				<tr> 
					<th><?php _e('SKU', FS_AFFILIATES_LOCALE); ?></th>
					<th><?php _e('Product Name', FS_AFFILIATES_LOCALE); ?></th>
					<th><?php _e('Product Price', FS_AFFILIATES_LOCALE); ?></th>
					<th><?php _e('Commission Value', FS_AFFILIATES_LOCALE); ?></th>
					<th><?php _e('Commission Amount', FS_AFFILIATES_LOCALE); ?></th>  
				</tr>
			</thead>
			<tbody>
				<?php
				fs_affiliates_get_template('dashboard/wc-product-commissions.php', array( 'post_ids' => $post_ids, 'affiliate_id' => $affiliate_id ));
				?>
			</tbody>    
			<tfoot>       
				<?php if ($page_count > 1) : ?>
					<tr>
						<td colspan="<?php echo esc_attr($count); ?>" class='footable-visible actions'>               
							<?php
							fs_affiliates_get_template('dashboard/pagination.php', $pagination);
							?>
											
						</td>
					</tr>
				<?php endif; ?>
			</tfoot>  
		</table>
	</div>
</div>
		<?php

