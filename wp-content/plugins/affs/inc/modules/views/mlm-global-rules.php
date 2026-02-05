<?php
/**
 * MLM Rules - Global 
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<input type="button" class="fs_affiliates_add_mlm_global_rule fs_affiliates_add_mlm_rule" value="<?php esc_attr_e('Add Level', FS_AFFILIATES_LOCALE); ?>"/>
<table class="widefat fs_affiliates_mlm_rules_table fs-affiliates-mlm-global-rules-table">
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
				$name = $this->plugin_slug . '_' . $this->id . '_rules[' . $key . ']';
				?>
				<tr>
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
						<p class="fs_affiliates_remove_mlm_global_rule fs_affiliates_remove_mlm_rule"> <img src="<?php echo esc_url(FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png'); ?>"></img></p>
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
<?php
