<?php
/**
 * This template displays the referral code field in the checkout block.
 *
 * This template can be overridden by copying it to yourtheme/affs/block/checkout-referral-code-field.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<p class='fs-affiliates-form-row'>
	<label for='fs_affiliates_referral_code_field'><?php echo esc_html( get_option( 'fs_affiliates_referral_code_apply_field_caption' ) ) ; ?>
		<?php if ( '2' == get_option( 'fs_affiliates_referral_code_checkout_page_visible_type' ) ) { ?>
			<abbr class='required' title='required'>*</abbr>
		<?php } ?>
	</label>
	<input id='fs_affiliates_block_referral_code_fields' class='fs_affiliates_block_referral_code_field' type='password' name='fs_affiliates_apply_referral_code' placeholder='<?php echo esc_html( get_option( 'fs_affiliates_referral_code_field_placeholder' ) ) ; ?>' value=''/>
</p>
<?php
