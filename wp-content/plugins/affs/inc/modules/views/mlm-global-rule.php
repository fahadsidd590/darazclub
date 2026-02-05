<?php
/**
 * MLM Rule - Global
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<tr>
	<td>
		<input type="hidden" id="fs_affiliates_mlm_rule_id" value="<?php echo esc_attr( $key ) ; ?>"/>
		<span><?php echo wp_kses_post( sprintf( __( 'Level %s', FS_AFFILIATES_LOCALE ), $key ) ) ; ?></span>
	</td>
	<td>
		<select name="<?php echo esc_attr( $name ) ; ?>[commission_type]" class='fs_affiliates_commission_type'>
			<option value="percentage_commission"><?php esc_html_e( 'Percentage Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
			<option value="fixed_commission" ><?php esc_html_e( 'Fixed Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
		</select>   
	</td>
	<td>
		<input type="text" class ='fs_affiliates_input_price' name="<?php echo esc_attr( $name ) ; ?>[commission_value]"/>
	</td>
	<td>
		<p class="fs_affiliates_remove_mlm_rule fs_affiliates_remove_mlm_global_rule"> <img src="<?php echo esc_url( FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png' ) ; ?>"></img></p>
	</td>
</tr>
<?php
