<?php

/*
 * Affiliates List Table
 * @since 9.8.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
if (!class_exists('WP_List_Table')) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ;
}

if (!class_exists('FS_Affiliates_List_Table')) {

	/**
	 * FS_Affiliates_List_Table Class.
	 * 
	 * @since 9.8.0
	 */
	class FS_Affiliates_List_Table extends WP_List_Table {

		/**
		 * Per page
		 * 
		 * @since 9.8.0
		 * @var int
		 */
		protected $perpage;

		/**
		 * Total Items
		 * 
		 * @since 9.8.0
		 * @var int
		 */
		protected $total_items;

		/**
		 * Offset
		 * 
		 * @since 9.8.0
		 * @var int
		 */
		protected $offset;

		/**
		 * Base URL
		 * 
		 * @since 9.8.0
		 * @var string
		 * */
		protected $base_url;

		/**
		 * Current URL
		 * 
		 * @since 9.8.0
		 * @var string
		 * */
		protected $current_url;

		/**
		 * Order BY
		 * 
		 * @since 9.8.0
		 * @var string
		 * */
		protected $orderby = 'ORDER BY ID DESC';

		/**
		 * Plugin slug.
		 * 
		 * @since 9.9.0
		 * @var string
		 */
		protected $plugin_slug = 'fs_affiliates';

		/**
		 * Table slug.
		 * 
		 * @since 9.9.0
		 * @var string
		 */
		protected $table_slug;
		
		/**
		 * Class initialization.
		 * 
		 * @since 9.8.0
		 */
		public function prepare_items() {
			add_filter($this->table_slug . '_query_where', array( $this, 'custom_search' ), 10, 1);

			$this->prepare_current_url();
			$this->set_perpage_count();
			$this->get_current_pagenum();
			$this->process_bulk_action();
			$this->get_current_page_items();
			$this->prepare_pagination_args();
			//display header columns
			$this->prepare_column_headers();
		}

		/**
		 * Set per page count
		 * 
		 * @since 9.8.0         
		 * */
		public function set_perpage_count() {
			$this->perpage = 20;
		}

		/**
		 * Prepare pagination args
		 * 
		 * @since 9.8.0        
		 * */
		public function prepare_pagination_args() {
			$args = array(
				'total_items' => $this->total_items,
				'per_page' => $this->perpage,
			);

			if (isset($_REQUEST['s'])) {
				$args['s'] = wc_clean(wp_unslash($_REQUEST['s']));
			}

			$this->set_pagination_args($args);
		}

		/**
		 * get current page number
		 * 
		 * @since 9.8.0         
		 * */
		public function get_current_pagenum() {
			$this->offset = 20 * ( $this->get_pagenum() - 1 );
		}

		/**
		 * Prepare column headers
		 * 
		 * @since 9.8.0        
		 * */
		public function prepare_column_headers() {
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable );
		}

		/**
		 * Prepare current url
		 * 
		 * @since 9.8.0         
		 * */
		public function prepare_current_url() {
			//Build row actions
			if (isset($_GET['status'])) {
				$args['status'] = $_GET['status'];
			}

			if (isset($_REQUEST['s'])) {
				$args['s'] = wc_clean(wp_unslash($_REQUEST['s']));
			}

			$pagenum = $this->get_pagenum();
			$args['paged'] = $pagenum;
			$url = add_query_arg($args, $this->base_url);

			$this->current_url = $url;
		}

		/**
		 * Prepare cb column data
		 * 
		 * @param object $item
		 * @since 9.8.0
		 * */
		public function column_cb( $item ) {           
			return sprintf(
					'<input type="checkbox" name="id[]" value="%s" />', $item->get_id()
			);
		}

		/**
		 * Bulk action functionality
		 * 
		 * @since 9.8.0
		 * */
		public function process_bulk_action() {

			$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
			$ids = !is_array($ids) ? explode(',', $ids) : $ids;

			if (!fs_affiliates_check_is_array($ids)) {
				return;
			}

			$action = $this->current_action();

			foreach ($ids as $id) {

				if (!current_user_can('edit_post', $id)) {
					wp_die('<p class="fs_affiliates_warning_notice">' . __('Sorry, you are not allowed to edit this item.', FS_AFFILIATES_LOCALE) . '</p>');
				}

				if ('delete' === $action) {
					wp_delete_post($id, true);
				} elseif ('trash' === $action) {
					wp_trash_post($id);
				} elseif ('restore' === $action) {
					wp_untrash_post($id);
				} elseif ('fs_active' === $action) {
					$post_object = new FS_Affiliates_Landing_Commission($id);
					$post_object->update_status('fs_active');
				} elseif ('fs_inactive' === $action) {
					$post_object = new FS_Affiliates_Landing_Commission($id);
					$post_object->update_status('fs_inactive');
				} elseif ('fs_rejected' === $action) {
					$ReferralObj = new FS_Affiliates_Referrals($id);
					$ReferralObj->update_status('fs_rejected');
				} elseif ('fs_unpaid' === $action) {
					$ReferralObj = new FS_Affiliates_Referrals($id);
					$ReferralObj->update_status('fs_unpaid');
				} elseif ('mark_as_paid' === $action) {
					$ReferralObj = new FS_Affiliates_Referrals($id);
					$payment_data = get_post_meta($ReferralObj->affiliate, 'fs_affiliates_user_payment_datas', true);
					
					if (fs_affiliates_check_is_array($payment_data)) {
						do_action($this->plugin_slug . '_admin_field_referral_pay', array( $id ), $payment_data['fs_affiliates_payment_method']);
						if ('direct' == $payment_data['fs_affiliates_payment_method']) {
							$ReferralObj->update_status('fs_paid');
						}
					}
				} elseif ('fs_submitted' === $action) {
					if (get_post_status($id) != 'fs_closed' && get_post_status($id) != 'fs_progress') {
						$post = array( 'ID' => $id, 'post_status' => 'fs_submitted' );
						wp_update_post($post);
						do_action('fs_affiliates_status_to_' . $action, $id);
					}
				} elseif ('fs_progress' === $action) {
					if (get_post_status($id) != 'fs_closed') {
						$post = array( 'ID' => $id, 'post_status' => 'fs_progress' );
						wp_update_post($post);
						do_action('fs_affiliates_status_to_' . $action, $id);
					}
				} elseif ('fs_closed' === $action) {
					$post = array( 'ID' => $id, 'post_status' => 'fs_closed' );
					wp_update_post($post);
					update_post_meta($id, 'fs_closed_date', time());
					do_action('fs_affiliates_status_to_' . $action, $id);
				} elseif ('mark_as_unpaid' === $action) {
					$ReferralObj = new FS_Affiliates_Referrals($id);
					$payment_data = get_post_meta($ReferralObj->affiliate, 'fs_affiliates_user_payment_datas', true);
					
					if (fs_affiliates_check_is_array($payment_data) &&  'fs_pending' === get_post_status( $id )) {
						do_action($this->plugin_slug . '_admin_field_referral_pay', array( $id ), $payment_data['fs_affiliates_payment_method']);
						if ('direct' == $payment_data['fs_affiliates_payment_method']) {
							$ReferralObj->update_status('fs_unpaid');
						}
					}
				}
			}
			do_action( $this->plugin_slug . '_admin_field_referral_pay' , $ids , $action ) ;

			wp_safe_redirect($this->current_url);
			exit();
		}

		/**
		 * Initialize the columns
		 * 
		 * @since 9.8.0
		 * */
		public function get_current_page_items() {
			global $wpdb;

			$status = isset($_GET['status']) ? ' IN("' . $_GET['status'] . '")' : ' NOT IN("trash")';
			$where = " where post_type='" . $this->post_type . "' and post_status" . $status;
			$where = apply_filters($this->table_slug . '_query_where', $where);
			$limit = apply_filters($this->table_slug . '_query_limit', $this->perpage);
			$offset = apply_filters($this->table_slug . '_query_offset', $this->offset);
			$orderby = apply_filters($this->table_slug . '_query_orderby', $this->orderby);

			$count_items = $wpdb->get_results('SELECT ID FROM ' . $wpdb->posts . " $where $orderby");
			$this->total_items = count($count_items);

			$prepare_query = $wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . " $where $orderby LIMIT %d,%d", $offset, $limit);
			$items = $wpdb->get_results($prepare_query, ARRAY_A);

			$this->prepare_item_object($items);
		}

		/**
		 * Prepare item Object
		 * 
		 * @param array $items
		 * @since 9.8.0
		 * */
		public function prepare_item_object( $items ) {
			$prepare_items = array();
			if (fs_affiliates_check_is_array($items)) {
				foreach ($items as $item) {
					$prepare_items[] = new FS_Affiliates_Data($item['ID']);
				}
			}

			$this->items = $prepare_items;
		}

		/**
		 * Search Functionality
		 * 
		 * @param string $where
		 * @return string
		 * */
		public function custom_search( $where ) {
			return $where;
		}

		/**
		 * Displays the pagination.
		 *
		 * @param string $which 
		 * @since 9.8.0   
		 *         
		 */
		public function pagination( $which ) {
			if (empty($this->_pagination_args)) {
				return;
			}

			$total_items = $this->_pagination_args['total_items'];
			$total_pages = $this->_pagination_args['total_pages'];
			$infinite_scroll = false;
			if (isset($this->_pagination_args['infinite_scroll'])) {
				$infinite_scroll = $this->_pagination_args['infinite_scroll'];
			}

			if ('top' === $which && $total_pages > 1) {
				$this->screen->render_screen_reader_content('heading_pagination');
			}

			$output = '<span class="displaying-num">' . sprintf(
							/* translators: %s: Number of items. */
							_n('%s item', '%s items', $total_items),
							number_format_i18n($total_items)
					) . '</span>';

			$current = $this->get_pagenum();
			$removable_query_args = wp_removable_query_args();

			$current_url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			$current_url = remove_query_arg($removable_query_args, $current_url);

			if (isset($_REQUEST['s'])) {
				$current_url = add_query_arg(array( 's' => wc_clean(wp_unslash($_REQUEST['s'])) ), $current_url);
			}

			$page_links = array();

			$total_pages_before = '<span class="paging-input">';
			$total_pages_after = '</span></span>';

			$disable_first = ( 1 == $current ) ? true : false;
			$disable_last = ( $total_pages == $current ) ? true : false;
			$disable_prev = ( 1 == $current ) ? true : false;
			$disable_next = ( $total_pages == $current ) ? true : false;

			if ($disable_first) {
				$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
			} else {
				$page_links[] = sprintf(
						"<a class='first-page button' href='%s'>" .
						"<span class='screen-reader-text'>%s</span>" .
						"<span aria-hidden='true'>%s</span>" .
						'</a>',
						esc_url(remove_query_arg('paged', $current_url)),
						/* translators: Hidden accessibility text. */
						__('First page'),
						'&laquo;'
				);
			}

			if ($disable_prev) {
				$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
			} else {
				$page_links[] = sprintf(
						"<a class='prev-page button' href='%s'>" .
						"<span class='screen-reader-text'>%s</span>" .
						"<span aria-hidden='true'>%s</span>" .
						'</a>',
						esc_url(add_query_arg('paged', max(1, $current - 1), $current_url)),
						/* translators: Hidden accessibility text. */
						__('Previous page'),
						'&lsaquo;'
				);
			}

			if ('bottom' === $which) {
				$html_current_page = $current;
				$total_pages_before = sprintf(
						'<span class="screen-reader-text">%s</span>' .
						'<span id="table-paging" class="paging-input">' .
						'<span class="tablenav-paging-text">',
						/* translators: Hidden accessibility text. */
						__('Current Page')
				);
			} else {
				$html_current_page = sprintf(
						'<label for="current-page-selector" class="screen-reader-text">%s</label>' .
						"<input class='current-page' id='current-page-selector' type='text'
					name='paged' value='%s' size='%d' aria-describedby='table-paging' />" .
						"<span class='tablenav-paging-text'>",
						/* translators: Hidden accessibility text. */
						__('Current Page'),
						$current,
						strlen($total_pages)
				);
			}

			$html_total_pages = sprintf("<span class='total-pages'>%s</span>", number_format_i18n($total_pages));
			$page_links[] = $total_pages_before . sprintf(
							/* translators: 1: Current page, 2: Total pages. */
							_x('%1$s of %2$s', 'paging'),
							$html_current_page,
							$html_total_pages
					) . $total_pages_after;

			if ($disable_next) {
				$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
			} else {
				$page_links[] = sprintf(
						"<a class='next-page button' href='%s'>" .
						"<span class='screen-reader-text'>%s</span>" .
						"<span aria-hidden='true'>%s</span>" .
						'</a>',
						esc_url(add_query_arg('paged', min($total_pages, $current + 1), $current_url)),
						/* translators: Hidden accessibility text. */
						__('Next page'),
						'&rsaquo;'
				);
			}

			if ($disable_last) {
				$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
			} else {
				$page_links[] = sprintf(
						"<a class='last-page button' href='%s'>" .
						"<span class='screen-reader-text'>%s</span>" .
						"<span aria-hidden='true'>%s</span>" .
						'</a>',
						esc_url(add_query_arg('paged', $total_pages, $current_url)),
						/* translators: Hidden accessibility text. */
						__('Last page'),
						'&raquo;'
				);
			}

			$pagination_links_class = 'pagination-links';
			if (!empty($infinite_scroll)) {
				$pagination_links_class .= ' hide-if-js';
			}
			$output .= "\n<span class='$pagination_links_class'>" . implode("\n", $page_links) . '</span>';

			if ($total_pages) {
				$page_class = $total_pages < 2 ? ' one-page' : '';
			} else {
				$page_class = ' no-pages';
			}
			$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

			echo $this->_pagination;
		}
	}

}
