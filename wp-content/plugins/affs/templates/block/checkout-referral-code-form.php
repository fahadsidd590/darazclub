<?php
/**
 * This template displays the referral code form in the checkout block.
 *
 * This template can be overridden by copying it to yourtheme/affs/block/checkout-referral-code-form.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

/**
 * This hook is used to display the extra content before referral code form in the checkout block.
 * 
 * @since 10.1.0
 */
do_action( 'fs_affiliates_before_checkout_block_referral_code_form_wrapper' ) ;
?>
<div class='fs-affiliates-checkout-block-referral-code-form_wrapper fs-affiliates-block-referral-code-form_wrapper'>
	<div class='fs-affiliates-block-referral-code-form_fields wc-block-components-text-input'>
		<input type='text' id='fs_affiliates_referral_code_field' class='input-text fs-affiliates-block-referral-code wc-block-components-text-input' name='referral_code' value='' placeholder='<?php echo esc_html( get_option( 'fs_affiliates_referral_code_field_placeholder' ) ) ; ?>'>
		<input type='hidden' name='action' value='referral_code'/>
		<input type='hidden' name='fs_nonce' value='<?php echo wp_create_nonce( 'referral_code' ) ; ?>'/>
		<div class='wc-block-components-validation-error'></div>
		<button type='button' disabled='disabled' class='components-button wc-block-components-button wp-element-button fs-affiliates-block-referral-code_button'><?php echo esc_html( get_option( 'fs_affiliates_referral_code_submit_button_caption' ) ) ; ?></button>
	</div>
</div>
<?php
/**
 * This hook is used to display the extra content after referral code form in the checkout block.
 * 
 * @since 10.1.0
 */
do_action( 'fs_affiliates_after_checkout_block_referral_code_form_wrapper' ) ;
