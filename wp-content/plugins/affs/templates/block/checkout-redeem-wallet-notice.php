<?php
/**
 * This template displays the redeem wallet notice in the checkout block.
 *
 * This template can be overridden by copying it to yourtheme/affs/block/checkout-redeem-wallet-notice.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

/**
 * This hook is used to display the extra content before redeem wallet notice in the checkout block.
 * 
 * @since 10.1.0
 */
do_action( 'fs_affiliates_before_checkout_block_redeem_wallet_notice_wrapper' ) ;
?>
<div class='fs-affiliates-checkout-block-redeem-wallet-notice_wrapper fs-affiliates-block-redeem-wallet-notice_wrapper'>
	<div class='fs_button_to_redeem'>
		<?php printf( __( 'Available Wallet Balance is %s. You can make use of it to get a discount. Redeem Wallet Balance.' , FS_AFFILIATES_LOCALE ) , fs_affiliates_price( $AvailableWalletBalance ) ) ; ?>
		<input id='fs_wallet_available_balance' class='input-text' type='hidden'  value='<?php echo $AvailableWalletBalance ; ?>' name='fs_apply_wallet_balance'>
		<div class='wc-block-components-validation-error'></div>
		<input id='fs-apply-wallet-balance' class='fs_apply_wallet_balance_button' type='submit' value="<?php _e( 'Redeem It' , FS_AFFILIATES_LOCALE ) ; ?>" name='fs_apply_wallet_balance_button'>
	</div>
</div>
<?php
/**
 * This hook is used to display the extra content after redeem wallet notice in the checkout block.
 * 
 * @since 10.1.0
 */
do_action( 'fs_affiliates_after_checkout_block_redeem_wallet_notice_wrapper' ) ;
