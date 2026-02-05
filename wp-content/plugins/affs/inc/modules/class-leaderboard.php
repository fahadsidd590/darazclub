<?php
/**
 * Leaderboard
 * 
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Leaderboard')) {

	/**
	 * Class FS_Affiliates_Leaderboard
	 * 
	 * @since 1.0.0
	 */
	class FS_Affiliates_Leaderboard extends FS_Affiliates_Modules {

		/**
		 * Limit.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $limit;

		/**
		 * Predefined type.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $predefined_type;

		/**
		 * Display Method.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $display_method;

		/**
		 * Menu Label.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $menu_label;

		/*
		 * Data
		 * 
		 * @since 1.0.0
		 */
		protected $data = array(
			'enabled' => 'no',
			'limit' => '50',
			'predefined_type' => '1',
			'display_method' => '1',
			'menu_label' => '',
		);

		/**
		 * Class Constructor
		 * 
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->id = 'leaderboard';
			$this->title = __('Leaderboard', FS_AFFILIATES_LOCALE);

			parent::__construct();
		}

		/*
		 * Get settings link
		 * 
		 * @since 1.0.0
		 */

		public function settings_link() {
			return add_query_arg(array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id ), admin_url('admin.php'));
		}

		/*
		 * Get settings options array
		 * 
		 * @since 1.0.0
		 */

		public function settings_options_array() {
			return array(
				array(
					'type' => 'title',
					'title' => __('Admin Leaderboard Settings', FS_AFFILIATES_LOCALE),
					'id' => 'admin_leaderboard_options',
				),
				array(
					'title' => __('Menu Label', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_menu_label',
					'type' => 'text',
					'default' => 'Leaderboard',
				),
				array(
					'title' => __('Limit', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_limit',
					'desc' => __('The Number of Affiliates to be Displayed in a Page', FS_AFFILIATES_LOCALE),
					'type' => 'number',
					'default' => '50',
				),
				array(
					'title' => __('Leaderboard Display Method', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_display_method',
					'desc' => __('Predefined – Leaderboard will be visible to the affiliates based on admin option. User Defined – Affiliates can view the Leaderboard based on multiple filters.', FS_AFFILIATES_LOCALE),
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'1' => __('Predefined', FS_AFFILIATES_LOCALE),
						'2' => __('User Defined', FS_AFFILIATES_LOCALE),
					),
				),
				array(
					'title' => __('Predefined Leaderboard Type', FS_AFFILIATES_LOCALE),
					'id' => $this->plugin_slug . '_' . $this->id . '_predefined_type',
					'desc' => __('Leaderboard will be generated based on the value set in this option', FS_AFFILIATES_LOCALE),
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'1' => __('Commission Earned', FS_AFFILIATES_LOCALE),
						'2' => __('No of Referrals', FS_AFFILIATES_LOCALE),
						'3' => __('No of Orders Placed by Referrals', FS_AFFILIATES_LOCALE),
						'4' => __('Amount Spent by Referrals', FS_AFFILIATES_LOCALE),
					),
				),
				array(
					'type' => 'sectionend',
					'id' => 'admin_leaderboard_options',
				),
			);
		}

		/**
		 * Frontend Actions
		 * 
		 * @since 1.0.0
		 */
		public function frontend_action() {
			add_filter('fs_affiliates_frontend_dashboard_menu', array( $this, 'leaderboard_menu' ), 12, 3);

			add_action('fs_affiliates_dashboard_content_leaderboard', array( $this, 'display_dashboard_content' ), 10, 3);
		}

		/**
		 * Custom Dashboard Menu
		 * 
		 * @since 1.0.0
		 * @param array $menus
		 * @param int $user_id
		 * @param int $affiliate_id
		 * @return array
		 */
		public function leaderboard_menu( $menus, $user_id, $affiliate_id ) {

			$menus['leaderboard'] = array( 'label' => $this->menu_label, 'code' => 'fa-trophy' );

			return $menus;
		}

		/**
		 * Display Dashboard Content
		 * 
		 * @since 1.0.0
		 * @param int $user_id User ID 
		 * @param int $affiliate_id Affiliate ID
		 */
		public function display_dashboard_content( $user_id, $affiliate_id ) {
			$display_type = isset($_GET['type']) ? $_GET['type'] : $this->predefined_type;

			if ($this->display_method == '2') {
				if (isset($_POST['fs_affiliates_leaderboard_type'])) {
					$display_type = $_POST['fs_affiliates_leaderboard_type'];
					unset($_REQUEST['page_no']);
				}
				$this->display_filter($display_type);
			}

			$get_permalink = FS_AFFILIATES_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$get_permalink = remove_query_arg(array( 'type' ), $get_permalink);
			$get_permalink = add_query_arg(array( 'type' => $display_type ), $get_permalink);

			//prepare the affiliates
			$affiliates = self::prepare_data($display_type);
			//sort the affiliates
			arsort($affiliates);

			switch ($display_type) {
				case '2':
					$this->display_no_of_referrals($affiliates, $display_type, $affiliate_id);
					break;
				case '3':
					$this->display_no_of_orders_placed_by_referrals($affiliates, $display_type, $affiliate_id);
					break;
				case '4':
					$this->display_amount_spent_by_referrals($affiliates, $display_type, $affiliate_id);
					break;
				default:
					$this->display_commision_earned($affiliates, $display_type, $affiliate_id);
					break;
			}
			echo '</div>';
		}

		/**
		 * Prepare data for leader board
		 * 
		 * @since 1.0.0
		 * @global $wpdb
		 * @param int|string $display_type
		 * @return array
		 */
		public static function prepare_data( $display_type ) {
			global $wpdb;

			$overall_affiliates = $wpdb->get_results(
					$wpdb->prepare("SELECT p.ID as id FROM $wpdb->posts as p "
							. "WHERE p.post_type='fs-affiliates' AND p.post_status=%s ORDER BY p.post_date", 'fs_active'), ARRAY_A);

			$overall_affiliates = fs_affiliates_get_array_column_values($overall_affiliates, 'id');
			$overall_affiliates = array_fill_keys($overall_affiliates, 0);

			switch ($display_type) {
				case '2':
					$referral_affiliates = $wpdb->get_results(
							$wpdb->prepare("SELECT p.post_author as id ,count(p.post_author) as count FROM $wpdb->posts as p "
									. 'WHERE p.post_type=%s GROUP BY p.post_author', 'fs-referrals'), ARRAY_A);

					$referral_affiliates_ids = fs_affiliates_get_array_column_values($referral_affiliates, 'id');
					$referral_affiliates_values = fs_affiliates_get_array_column_values($referral_affiliates, 'count');
					$affiliates = array_combine($referral_affiliates_ids, $referral_affiliates_values);

					break;
				case '3':
					$referral_affiliates = $wpdb->get_results(
							$wpdb->prepare("SELECT meta2.meta_value as id, count(meta2.meta_value) as count FROM $wpdb->posts as p "
									. "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
									. "INNER JOIN $wpdb->postmeta as meta2 ON p.ID=meta2.post_id "
									. "WHERE p.post_type=%s AND meta.meta_key='fs_commission_awarded' AND meta.meta_value='yes' "
									. "AND meta2.meta_key='fs_affiliate_in_order' GROUP BY meta2.meta_value "
									. 'ORDER BY count(meta2.meta_value) ASC', 'shop_order'), ARRAY_A);

					$referral_affiliates_ids = fs_affiliates_get_array_column_values($referral_affiliates, 'id');
					$referral_affiliates_values = fs_affiliates_get_array_column_values($referral_affiliates, 'count');
					$affiliates = array_combine($referral_affiliates_ids, $referral_affiliates_values);
					break;
				case '4':
					$referral_affiliates = $wpdb->get_results(
							$wpdb->prepare("SELECT meta3.meta_value as id, sum(meta2.meta_value) as count FROM $wpdb->posts as p "
									. "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
									. "INNER JOIN $wpdb->postmeta as meta2 ON p.ID=meta2.post_id "
									. "INNER JOIN $wpdb->postmeta as meta3 ON p.ID=meta3.post_id "
									. "WHERE p.post_type=%s AND meta.meta_key='fs_commission_awarded' AND meta.meta_value='yes' "
									. "AND meta2.meta_key='_order_total' AND meta3.meta_key='fs_affiliate_in_order' "
									. "AND meta3.meta_value!='' GROUP BY meta3.meta_value "
									. 'ORDER BY sum(meta3.meta_value) ASC', 'shop_order'), ARRAY_A);

					$referral_affiliates_ids = fs_affiliates_get_array_column_values($referral_affiliates, 'id');
					$referral_affiliates_values = fs_affiliates_get_array_column_values($referral_affiliates, 'count');
					$affiliates = array_combine($referral_affiliates_ids, $referral_affiliates_values);
					break;
				default:
					$query = $wpdb->prepare("SELECT p.ID as id FROM $wpdb->posts as p "
							. "INNER JOIN $wpdb->postmeta as meta ON p.ID=meta.post_id "
							. "WHERE p.post_type='fs-affiliates' AND p.post_status=%s "
							. "AND meta.meta_key='paid_earnings' ORDER BY meta.meta_value+0 ASC", 'fs_active');

					$affiliate_commission_ids = $wpdb->get_results($query, ARRAY_A);

					$affiliate_commission_ids = fs_affiliates_get_array_column_values($affiliate_commission_ids, 'id');
					$affiliates = array_flip($affiliate_commission_ids);
					break;
			}

			return fs_affiliates_array_merge_based_on_first($affiliates, $overall_affiliates);
		}

		/**
		 * Display user defined filter
		 * 
		 * @since 1.0.0
		 * @param int|string $display_type
		 * @return void
		 */
		public function display_filter( $display_type ) {
			if ($this->display_method != '2') {
				return;
			}
			?>
			<form method="post">
				<p><label><?php echo _e('Display Leaderboard Based On', FS_AFFILIATES_LOCALE); ?></label>
					<select name="fs_affiliates_leaderboard_type" >
						<option value="1" <?php selected($display_type, '1'); ?>><?php _e('Commission Earned', FS_AFFILIATES_LOCALE); ?></option>
						<option value="2" <?php selected($display_type, '2'); ?>><?php _e('No of Referrals', FS_AFFILIATES_LOCALE); ?></option>
						<option value="3" <?php selected($display_type, '3'); ?>><?php _e('No of Orders Placed by Referrals', FS_AFFILIATES_LOCALE); ?></option>
						<option value="4" <?php selected($display_type, '4'); ?>><?php _e('Amount Spent by Referrals', FS_AFFILIATES_LOCALE); ?></option>
					</select>
					<input type="submit" class="fs_affiliates_form_save" value="<?php _e('Filter', FS_AFFILIATES_LOCALE); ?>"/>
				</p>
			</form>
			<?php
		}

		/**
		 * Display Commission earned leader board
		 * 
		 * @since 1.0.0
		 * @param array $affiliates
		 * @param int|string $display_type
		 * @return void
		 */
		public function display_commision_earned( &$affiliates, $display_type, $affiliate_id ) {
			$perpage = 5;
			$count = count($affiliates);
			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$limit = ( $count > $this->limit ) ? $this->limit : $count;
			$page_count = ceil($limit / $perpage);
			$offset = ( $current_page - 1 ) * $perpage;
			$position = array_search($affiliate_id, array_keys($affiliates));

			$table_args = array(
				'post_ids' => $affiliates,
				'offset' => $offset,
				'per_page' => $perpage,
				'count' => $count,
				'page_count' => $page_count,
				'current_page' => $current_page,
				'limit' => $limit,
				'affiliates' => $affiliates,
				'display_type' => $display_type,
				'position' => $position,
				'pagination' => fs_dashboard_get_pagination_args($current_page, $page_count),
			);

			fs_affiliates_get_template('dashboard/leaderboard/commission-earned.php', $table_args);
		}

		/**
		 * Display No of Referrals for Affiliate
		 * 
		 * @since 1.0.0
		 * @param array $affiliates
		 * @param int|string $display_type
		 * @return void
		 */
		public function display_no_of_referrals( &$affiliates, $display_type, $affiliate_id ) {
			$perpage = 5;
			$count = count($affiliates);
			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$limit = ( $count > $this->limit ) ? $this->limit : $count;
			$page_count = ceil($limit / $perpage);
			$offset = ( $current_page - 1 ) * $perpage;
			$position = array_search($affiliate_id, array_keys($affiliates));

			$table_args = array(
				'post_ids' => $affiliates,
				'offset' => $offset,
				'per_page' => $perpage,
				'count' => $count,
				'page_count' => $page_count,
				'current_page' => $current_page,
				'limit' => $limit,
				'affiliates' => $affiliates,
				'display_type' => $display_type,
				'position' => $position,
				'pagination' => fs_dashboard_get_pagination_args($current_page, $page_count),
			);

			fs_affiliates_get_template('dashboard/leaderboard/no-of-referrals.php', $table_args);
		}

		/**
		 * Display amount spent by Referrals for Affiliate
		 * 
		 * @since 1.0.0
		 * @param array $affiliates
		 * @param int|string $display_type
		 * @return void
		 */
		public function display_amount_spent_by_referrals( &$affiliates, $display_type, $affiliate_id ) {
			$perpage = 5;
			$count = count($affiliates);
			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$limit = ( $count > $this->limit ) ? $this->limit : $count;
			$page_count = ceil($limit / $perpage);
			$offset = ( $current_page - 1 ) * $perpage;
			$position = array_search($affiliate_id, array_keys($affiliates));

			$table_args = array(
				'post_ids' => $affiliates,
				'offset' => $offset,
				'per_page' => $perpage,
				'count' => $count,
				'page_count' => $page_count,
				'current_page' => $current_page,
				'limit' => $limit,
				'affiliates' => $affiliates,
				'display_type' => $display_type,
				'position' => $position,
				'pagination' => fs_dashboard_get_pagination_args($current_page, $page_count),
			);

			fs_affiliates_get_template('dashboard/leaderboard/amount-spend.php', $table_args);
		}

		/**
		 * Display no of orders placed by Referrals for Affiliate
		 * 
		 * @since 1.0.0
		 * @param array $affiliates
		 * @param int|string $display_type
		 * @return void
		 */
		public function display_no_of_orders_placed_by_referrals( &$affiliates, $display_type, $affiliate_id ) {
			$perpage = 5;
			$count = count($affiliates);
			$current_page = isset($_REQUEST['page_no']) && $_REQUEST['page_no'] ? (int) $_REQUEST['page_no'] : 1;
			$limit = ( $count > $this->limit ) ? $this->limit : $count;
			$page_count = ceil($limit / $perpage);
			$offset = ( $current_page - 1 ) * $perpage;
			$position = array_search($affiliate_id, array_keys($affiliates));

			$table_args = array(
				'post_ids' => $affiliates,
				'offset' => $offset,
				'per_page' => $perpage,
				'count' => $count,
				'page_count' => $page_count,
				'current_page' => $current_page,
				'limit' => $limit,
				'display_type' => $display_type,
				'affiliates' => $affiliates,
				'position' => $position,
				'pagination' => fs_dashboard_get_pagination_args($current_page, $page_count),
			);

			fs_affiliates_get_template('dashboard/leaderboard/no-of-orders-placed.php', $table_args);
		}
	}

}
