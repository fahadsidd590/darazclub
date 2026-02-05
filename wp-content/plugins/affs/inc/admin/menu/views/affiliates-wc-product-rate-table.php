<?php
/* Edit Affiliates Page */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
$wc_categories = fp_affiliates_get_categories() ;
foreach ( $wcratesdata as $key => $individualdata ) {
	$individualdata     = fs_format_affiliate_level_product_rule_data( $individualdata ) ;
	?>
	<tr class="fs-affiliate-level-product-rule">
	<input type="hidden" id="fs_product_rate_rule_id" value="<?php echo $key ; ?>"/>
	<td>
		<select name='affiliate[wc_product_rates][<?php echo $key ; ?>][type]' class='fs-affiliate-product-rule-type'>
			<?php
			$commission_options = array(
				'1' => __( 'Products', FS_AFFILIATES_LOCALE ),
				'2' => __( 'Category', FS_AFFILIATES_LOCALE ),
					) ;
			foreach ( $commission_options as $type => $name ) {
				?>
				<option value="<?php echo $type ; ?>" <?php selected( $individualdata[ 'type' ], $type ) ; ?>><?php echo $name ; ?></option>
			<?php } ?>
		</select>
	</td>
	<td>
		<span class="fs-affiliate-product-rule-products">
			<?php
			$product_selection_args = array(
				'id'          => 'product_ids',
				'name'        => 'affiliate[wc_product_rates][' . $key . '][products]',
				'list_type'   => 'products',
				'class'       => 'wc-product-search',
				'action'      => 'fs_affiliates_products_search',
				'placeholder' => __( 'Search a Product', FS_AFFILIATES_LOCALE ),
				'multiple'    => true,
				'selected'    => true,
				'options'     => isset( $individualdata[ 'products' ] ) ? ( array ) $individualdata[ 'products' ] : array(),
					) ;
			fs_affiliates_select2_html( $product_selection_args ) ;
			?>
		</span>
		<span class="fs-affiliate-product-rule-categories">
			<select name='affiliate[wc_product_rates][<?php echo $key ; ?>][categories][]' multiple="multiple" class='fs_affiliates_select2'>
				<?php
				foreach ( $wc_categories as $category_id => $category_name ) {
					?>
					<option value="<?php echo $category_id ; ?>"<?php echo in_array( $category_id, $individualdata[ 'categories' ] ) ? ' selected="selected"' : '' ; ?>><?php echo $category_name ; ?></option>
				<?php } ?>
			</select>
		</span>
	</td>
	<td>
		<select name='affiliate[wc_product_rates][<?php echo $key ; ?>][commission_type]' class='fs_affiliates_commission_type'>
			<?php
			$commission_options = array(
				'percentage' => __( 'Percentage Commission', FS_AFFILIATES_LOCALE ),
				'fixed'      => __( 'Fixed Commission', FS_AFFILIATES_LOCALE ),
					) ;
			foreach ( $commission_options as $type => $name ) {
				?>
				<option value="<?php echo $type ; ?>" <?php selected( $individualdata[ 'commission_type' ], $type ) ; ?>><?php echo $name ; ?></option>
			<?php } ?>
		</select>
	</td>
	<td>
		<input type="text" name='affiliate[wc_product_rates][<?php echo $key ; ?>][commission_value]' class='fs_affiliates_commission_value' value='<?php echo $individualdata[ 'commission_value' ] ; ?>'/>
	</td>
	<td class="column-columnname num" scope="col">
		<input class='button-primary fs_affiliates_remove_dashboard_tab_rule fs_remove_product_rates' type="button" value="<?php _e( 'Remove', FS_AFFILIATES_LOCALE ) ; ?>"
			   </td>
	</tr>
	<?php
}
