<?php
/**
 * This template is used for displaying the WooCommerce product commissions.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/wc-product-commissions.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 9.2.0
 * @var array $post_ids
 * @var object $product
 * @var string $type
 * @var int $value
 * @var string $commission_type
 * @var string $commissio_value
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
foreach ($post_ids as $id) {
	$product = wc_get_product($id);
	if (!is_object($product)) {
		continue;
	}   
	$product_id   = 'variation' != $product->get_type() ? $id : 0 ; 
	$variation_id = 'variation' == $product->get_type() ? $id : 0 ;   
	$commission = FS_Affiliates_WC_Commission::check_if_product_level($product_id, $variation_id, 1, $affiliate_id, $product->get_price(), true);
	$type = isset( $commission['commission_type'] ) ? $commission['commission_type'] : '';
	$value = isset( $commission['commission_value'] ) ? $commission['commission_value'] : 0;
	$commission_type = ( 'fixed' == $type || '1' == $type ) ? wc_price($value) : $value . '%';
	$commissio_value = isset($commission['commission'])? $commission['commission'] : '-' ;
	  
	if ( empty($commission) ) {
		continue;
	}   
	?>
	<tr>
		<td data-title='<?php esc_html_e('SKU', FS_AFFILIATES_LOCALE); ?>' class='fs_affiliates_sno fs_affiliates_coupon_linking_sno'><?php echo $product->get_sku(); ?></td>
		<td data-title='<?php esc_html_e('Product', FS_AFFILIATES_LOCALE); ?>' ><?php echo $product->get_name(); ?></td>
		<td data-title='<?php esc_html_e('Product Sale Price', FS_AFFILIATES_LOCALE); ?>' ><?php echo wc_price($product->get_price()); ?></td>
		<td data-title='<?php esc_html_e('Value', FS_AFFILIATES_LOCALE); ?>' ><?php echo $commission_type; ?></td>
		<td data-title='<?php esc_html_e('Commission', FS_AFFILIATES_LOCALE); ?>' ><?php echo wc_price($commissio_value); ?></td>
	</tr>
	<?php
}


