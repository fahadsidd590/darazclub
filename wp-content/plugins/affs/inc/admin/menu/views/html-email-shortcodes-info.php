<?php
/**
 * Shortcodes information.
 *
 * @since 10.0.0
 */
defined('ABSPATH') || exit;

/**
 * This hook is used to display the content before short code contents.
 *
 * @since 10.0.0
 */
do_action('fs_affiliates_before_shortcode_contents');
?>
<table class="form-table fs_shortcodes_info widefat striped">
	<thead>
		<tr>
			<th><?php esc_html_e('Shortcode', FS_AFFILIATES_LOCALE); ?></th>			
			<th><?php esc_html_e('Description', FS_AFFILIATES_LOCALE); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (fs_affiliates_check_is_array($shortcodes_info)) :
			foreach ($shortcodes_info as $shortcode => $details) :
				?>
				<tr>
					<td><?php echo esc_html($shortcode); ?></td>					
					<td><?php echo esc_html($details['usage']); ?></td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>
</table>

<?php
/**
 * This hook is used to display the content after short code contents.
 *
 * @since 10.0.0
 */
do_action('fs_affiliates_after_shortcodes_content');

