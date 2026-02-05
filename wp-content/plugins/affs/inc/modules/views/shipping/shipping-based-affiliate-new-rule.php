<?php
/**
 * Shipping Rules - Global 
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<tr>
	<td>
		<?php
		$selection_args = array(
			'class'       => 'fs_affiliates_selection',
			'name'        => $name . '[affiliate_id]',
			'list_type'   => 'affiliates',
			'action'      => 'fs_affiliates_search',
			'placeholder' => esc_html__( 'Search a Affiliate' , FS_AFFILIATES_LOCALE ),
			'multiple'    => false,
			'selected'    => true,
			'options'     => array(),
				) ;
		fs_affiliates_select2_html( $selection_args ) ;
		?>
	</td>
	<td>
		<select class="fs_affiliates_select2" name="<?php echo esc_attr($name . '[shipping_id]'); ?>">
			<?php foreach (fs_get_shipping_methods() as $shipping_id => $shipping_title ) : ?>
				<option value="<?php echo esc_attr($shipping_id); ?>"><?php echo wp_kses_post($shipping_title); ?></option>
			<?php endforeach; ?>
		</select>
	</td>
	<td>
		<p class="fs_remove_shipping_based_affiliate_rule"> <img src="<?php echo esc_url(FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png'); ?>"></img></p>
	</td>
</tr>
<?php
