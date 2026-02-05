<?php
/**
 * This template displays the wallet coupon wrapper in the cart/checkout block.
 *
 * This template can be overridden by copying it to yourtheme/affs/block/wallet-coupon-wrapper.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class='fs-affiliates-block-components-totals-fees wc-block-components-totals-item wc-block-components-totals-discount wc-block-components-totals-discount__'<?php echo esc_attr( $CouponName ) ; ?>>
	<span class='wc-block-components-totals-item__label'><?php echo esc_attr( get_option( 'fs_affiliates_affiliate_wallet_checkout_coupon_label' ) ) ; ?></span>
	<span class='wc-block-formatted-money-amount wc-block-components-formatted-money-amount wc-block-components-totals-item__value'>-<?php echo wp_kses_post( wc_price( $discount_amount ) ) ; ?>
		<a href='javascript:void(0)' class='fs-affiliates-block-remove-wallet-amount_link'><?php esc_html_e( 'Remove' , FS_AFFILIATES_LOCALE ) ; ?></a>
	</span>
</div>
<?php
