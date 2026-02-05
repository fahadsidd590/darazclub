<?php
/**
 * Add Product Level MLM Rules 
 *
 * @since 9.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<div id="fs_mlm_tabs" class="panel woocommerce_options_panel">      

	<div class="options_group show_if_simple show_if_variable show_if_mto_course">
		<h2><?php esc_html_e('MLM Commission Settings', FS_AFFILIATES_LOCALE); ?> </h2>
		<?php
		$value = get_post_meta($post->ID, 'fs_affiliate_product_level_mlm', true);
		woocommerce_wp_checkbox(
				array(
					'id' => 'fs_affiliate_product_level_mlm',
					'value' => $value,
					'label' => __('MLM Commission', FS_AFFILIATES_LOCALE),
					'class' => 'fs_affiliate_product_level_mlm',
					'description' => __('By enabling this checkbox, you can set the MLM Commission to calculate from Global Level/Category Level/Product Level.', FS_AFFILIATES_LOCALE),
				)
		);
		?>

		<p class="form-field">              
			<label> <?php esc_html_e('MLM Commission should calculate from', FS_AFFILIATES_LOCALE); ?></label>    
			<select id="fs_affiliates_product_mlm_mode" name="fs_affiliates_product_mlm_mode" class="postform">
				<option value="1"<?php selected('1', $mode); ?>><?php esc_html_e('Global Level', FS_AFFILIATES_LOCALE); ?></option>                
				<option value="2"<?php selected('2', $mode); ?>><?php esc_html_e('Category Level', FS_AFFILIATES_LOCALE); ?></option>
				<option value="3"<?php selected('3', $mode); ?>><?php esc_html_e('Product Level', FS_AFFILIATES_LOCALE); ?></option>
			</select>    
		</p>
	</div>

	<div class="form-field">        
		<div class="fs-affiliates-mlm-product-rules-wrapper">
			<p>
				<label><h2> <?php esc_html_e('Affiliate Depth Level', FS_AFFILIATES_LOCALE); ?></h2></label>
				<input type="button" class="fs_affiliates_add_mlm_product_rule button-primary" value="<?php esc_attr_e('Add Level', FS_AFFILIATES_LOCALE); ?>"/>
			</p>
			<p>
			<table class="widefat fs_affiliates_mlm_rules_product_table fs-affiliates-mlm-product-rules-table widefat">
				<thead>
					<tr>
						<th><?php esc_html_e('Depth Level', FS_AFFILIATES_LOCALE); ?></th>
						<th><?php esc_html_e('Commission Type', FS_AFFILIATES_LOCALE); ?></th>
						<th><?php esc_html_e('Commission', FS_AFFILIATES_LOCALE); ?></th>
						<th><?php esc_html_e('Remove Level', FS_AFFILIATES_LOCALE); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (fs_affiliates_check_is_array($rules)) {
						foreach ($rules as $key => $rule) {
							$name = 'fs_affiliates_mlm_rules[' . $key . ']';
							?>
							<tr class="fs-affiliate-product-commision">
								<td>
									<input type="hidden" id="fs_affiliates_mlm_rule_id" value="<?php echo esc_attr($key); ?>"/>
									<span><?php echo wp_kses_post(sprintf(__('Level %s', FS_AFFILIATES_LOCALE), $key)); ?></span>
								</td>
								<td>
									<select name="<?php echo esc_attr($name); ?>[commission_type]" class='fs_affiliates_commission_type'>
										<option value="percentage_commission" <?php isset($rule['commission_type']) ? selected($rule['commission_type'], 'percentage_commission') : ''; ?>><?php esc_html_e('Percentage Commission', FS_AFFILIATES_LOCALE); ?></option>
										<option value="fixed_commission" <?php isset($rule['commission_type']) ? selected($rule['commission_type'], 'fixed_commission') : ''; ?>><?php esc_html_e('Fixed Commission', FS_AFFILIATES_LOCALE); ?></option>
									</select>   
								</td>
								<td>
									<input type="text" name="<?php echo esc_attr($name); ?>[commission_value]" class ='fs_affiliates_input_price' value="<?php echo esc_attr(fs_affiliates_format_decimal($rule['commission_value'])); ?>" />
								</td>

								<td>
									<p class="fs_affiliates_remove_mlm_product_rule fs_affiliates_remove_mlm_rule"> <img src="<?php echo esc_url(FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png'); ?>"></img></p>
								</td>
							</tr>
							<?php
						}
					} else {
						?>
						 <tr class="fs-affiliates-mlm-no-data"><td colspan="4"><?php esc_html_e('No rules found', FS_AFFILIATES_LOCALE); ?></td></tr> 
						<?php
					}
					?>
				</tbody>
			</table>    
			</p>
		</div>
	</div>
</div>
<?php
