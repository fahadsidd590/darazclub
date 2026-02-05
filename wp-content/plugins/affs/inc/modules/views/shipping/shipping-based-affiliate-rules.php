<?php
/**
 * MLM Rules - Global 
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<table class="widefat fs_affiliates_shipping_rule_table fs-affiliates-shipping-rules-table">
	<thead>
		<tr>
			<th><?php esc_html_e('Affiliate Name', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php esc_html_e('Shipping Method', FS_AFFILIATES_LOCALE); ?></th>
			<th><?php esc_html_e('Remove Level', FS_AFFILIATES_LOCALE); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( fs_affiliates_check_is_array( $rule_ids ) ) {
			foreach ($rule_ids as $rule_id) {
				$rule = fs_affiliate_get_shipping_rule($rule_id);
				$name = 'fs_shipping_based_affiliate_rules[' . $rule_id . ']';
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
							'options'     => $rule->get_affiliate_id(),
								) ;
						fs_affiliates_select2_html( $selection_args ) ;
						?>
					</td>
					<td>
						<select class="fs_affiliates_select2" name="<?php echo esc_attr($name . '[shipping_id]'); ?>">
							<?php foreach (fs_get_shipping_methods() as $shipping_id => $shipping_title ) : ?>
								<option value="<?php echo esc_attr($shipping_id); ?>" <?php echo wp_kses_post(selected($shipping_id, $rule->get_shipping_id())); ?>><?php echo wp_kses_post($shipping_title); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<p class="fs_remove_shipping_based_affiliate_rule"> <img src="<?php echo esc_url(FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png'); ?>"></img></p>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="fs-affiliates-shipping-no-data"><td colspan="4"><?php esc_html_e('No Data found', FS_AFFILIATES_LOCALE); ?></td></tr> 
			<?php
		}
		?>
	</tbody>
</table>
<input type="button" class="fs_add_shipping_based_affiliate_rule" value="<?php esc_attr_e('Add Rule', FS_AFFILIATES_LOCALE); ?>"/>
<?php
