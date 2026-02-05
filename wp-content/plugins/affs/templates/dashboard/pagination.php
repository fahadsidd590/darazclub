<?php
/**
 * This template is used for display dashboard pagination.
 *
 * This template can be overridden by copying it to yourtheme/affs/dashboard/pagination.php
 *
 * To maintain compatibility, Sumo Affiliates Pro will update the template files and you have to copy the updated files to your theme
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * This hook is used to do extra action before affiliates dashboard pagination.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_pagination');
?>
<nav class="pagination pagination-centered woocommerce-pagination">
	<ul>
		<?php if ($prev_arrows) : ?>
			<li><a href="#" class="fs-pagination fs-first-pagination" data-page="1"><<</a>
			<li><a href="#" class="fs-pagination fs-prev-pagination" data-page="<?php echo esc_attr($prev_page_count); ?>"><</a></li>  
		<?php endif; ?>
					   
		<?php if ($prev_dot) : ?>
			<li><a href="#" class='fs-pagination fs-prev-pagination-dot' data-page='<?php echo esc_attr($prev_page_row); ?>'>...</a></li> 
			<?php
		endif;
		
		for ($start_page ; $start_page <= $end_page; $start_page++) :
			$page_no = fs_get_pagination_number($start_page, $page_count, $current_page);
			?>
										
			<li>
				<a href="#" class="<?php echo esc_attr(implode(' ', fs_get_pagination_classes($start_page, $current_page))); ?>"
				   data-page="<?php echo esc_attr($page_no); ?>">
					   <?php echo esc_html($page_no); ?>
				</a>
			</li>                       
			<?php
		endfor;
		if ($next_dot) :
			?>
			<li><a href="#" class="fs-pagination fs-next-pagination-dot" data-page='<?php echo esc_attr($next_page_row); ?>'>...</a></li> 
			<?php
		endif;
		if ($next_arrows) :
			?>
			<li><a href="#"  class="fs-pagination fs-next-pagination" data-page="<?php echo esc_attr($next_page_count); ?>">></a></li>
			<li><a href="#" class="fs-pagination fs-last-pagination" data-page="<?php echo esc_attr($page_count); ?>">>></a></li>
		<?php endif; ?>    
	</ul>
</nav>
<?php
/**
 * This hook is used to do extra action after affiliates dashboard pagination.
 * 
 * @since 10.0.0
 */
do_action('fs_affiliates_before_dashboard_pagination');
