<?php

/*
 * Assign Affiliate for order html. 
 */

if ( !defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="fs-assign-order-affiliate-wrapper">
	<p >
	<?php    
	$user_selection_args = array(
		'id'          => 'fs_affiliate_id',
		'name'        => 'fs_affiliate_id',
		'list_type'   => 'affiliates',
		'action'      => 'fs_affiliates_search',
		'placeholder' => __( 'Search a Affiliates' , FS_AFFILIATES_LOCALE ),
		'multiple'    => false,
		'selected'    => true,
		'class'      => 'postbox',       
		) ;
	fs_affiliates_select2_html( $user_selection_args ) ;
	?>
	</p>    
	<p>
		<input type="hidden" class="fs-affiliate-order-id" value="<?php echo esc_attr( $theorder->get_id() ); ?>">
		<input type="button" value="<?php esc_html_e( 'Assign' , FS_AFFILIATES_LOCALE ); ?>" class="button fs-assign-affiliate-order-btn">
	</p>
</div>
