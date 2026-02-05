<?php
/**
 * This template displays the affiliate field selection in the checkout block.
 *
 * This template can be overridden by copying it to yourtheme/affs/block/checkout-affiliate-field.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 * 
 * @since 10.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
$radio_default     = 1 ;
$affiliate_options = array( '' => __( 'None' , FS_AFFILIATES_LOCALE ) ) ;
$current_affiliate = fs_affiliates_is_user_having_affiliate() ;

foreach ( $affiliates as $affiliate_id ) {
	if ( $current_affiliate == $affiliate_id && ! apply_filters( 'fs_affiliates_is_restricted_own_commission' , false ) ) {
		continue ;
	}

	$affiliate = get_post( $affiliate_id ) ;
	$user_id   = $affiliate->post_author ;
	$user      = get_user_by( 'id' , $user_id ) ;

	if ( get_option( 'fs_affiliates_checkout_affiliate_display_style' ) == '3' ) {
		$affiliate_options[ $affiliate_id ] = $user->nickname ;
	} else if ( get_option( 'fs_affiliates_checkout_affiliate_display_style' ) == '2' ) {
		$affiliate_options[ $affiliate_id ] = $user->display_name ;
	} else {
		$affiliate_options[ $affiliate_id ] = $affiliate->post_title ;
	}
}
if ( get_option( 'fs_affiliates_checkout_affiliate_affs_selection' ) == 3 ) {
	?>
	<span class='woocommerce-input-wrapper'>
		<label><?php echo get_option( 'fs_affiliates_checkout_affiliate_selection_title' ) ; ?></label>
		<br><input type='radio' class='input-radio ' value='1' name='affiliate_referrer_radio'
		<?php
		if ( $radio_default == 1 ) {
			?>
					   checked='checked' <?php } ?> id='affiliate_referrer_radio_1'>
		<label for='affiliate_referrer_radio_1' class='radio '> <?php echo get_option( 'fs_affiliates_checkout_affiliate_selection_value1' ) ; ?> </label>
		<br><input type='radio' class='input-radio ' value='2' name='affiliate_referrer_radio' id='affiliate_referrer_radio_2' 
		<?php
		if ( $radio_default == 2 ) {
			?>
					   checked='checked' <?php } ?> >
		<label for='affiliate_referrer_radio_2' class='radio '> <?php echo get_option( 'fs_affiliates_checkout_affiliate_selection_value2' ) ; ?> </label>
	</span>
<?php } ?>
<p class='form-row affiliate_referrer_fields' id='affiliate_referrer_fields'>
	<label>
		<?php
		echo get_option( 'fs_affiliates_checkout_affiliate_label' ) ;

		if ( get_option( 'fs_affiliates_checkout_affiliate_affs_selection' ) != 1 ) {
			?>
			<abbr class='required' title='required'>*</abbr>
		<?php } ?>

	</label> 

	<span class='woocommerce-input-wrapper'>
		<select name='affiliate_referrer' id='affiliate_referrer' class='input-text affiliate_referrer'>
			<?php
			foreach ( $affiliate_options as $affs_id => $each_options ) {
				?>
				<option value='<?php echo $affs_id ; ?>'><?php echo $each_options ; ?></option>
			<?php } ?>
		</select>
	</span>
</p>
<?php


