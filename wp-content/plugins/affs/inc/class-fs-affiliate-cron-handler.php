<?php

/**
 * Handles the Crons.
 * 
 * @since 10.6.0
 * */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('FS_Affiliate_Cron_Handler')) {

	/**
	 * Class.
	 * */
	class FS_Affiliate_Cron_Handler {

		/**
		 *  Class initialization.
		 * */
		public static function init() {

			// Maybe set the WP schedule event.
			add_action('init', array( __CLASS__, 'maybe_set_wp_schedule_event' ), 10);
			// Prepare the master log IDs for deletion WP Cron.
			add_action('fs_affiliate_log_deletion', array( __CLASS__, 'prepare_log_deletion' ));
			// Handle the master log deletion schedule action.
			add_action('fs_affiliate_clean_up_user_logs', array( __CLASS__, 'handle_log_deletion' ), 10, 1);
		}

		/**
		 * Maybe set the WP schedule event.
		 * */
		public static function maybe_set_wp_schedule_event() {
			$schedule_events = self::get_wp_schedule_events();
			// Return if the schedule events are not exists.
			if (!fs_affiliates_check_is_array($schedule_events)) {
				return;
			}

			// Set schedule event , if the event is not scheduled.
			foreach ($schedule_events as $key => $cron_events) {
				$interval = isset($cron_events['interval']) ? $cron_events['interval'] : '';

				if ($cron_events['set'] && !wp_next_scheduled($key)) {
					wp_schedule_event(time(), $interval , $key);
				} elseif (!$cron_events['set'] && wp_next_scheduled($key)) {
					wp_clear_scheduled_hook($key);
				}
			}
		}

		/**
		 * Get WP schedule event.
		 * 
		 * @return array.
		 * */
		public static function get_wp_schedule_events() {
			$log_deletion = ( 'yes' == get_option('fs_affiliate_log_deletion') ) ? true : false;
			$schedule_events = array(
				'fs_affiliate_log_deletion' => array(
					'interval' => 'twicedaily',
					'set' => $log_deletion,
				),
			);

			/**
			 * The hook is used to alter the WP schedule events.
			 * 
			 * @since 10.6.0
			 */
			return apply_filters('fs_affiliate_wp_schedule_events', $schedule_events);
		}

		/**
		 * Prepare the master log deletion.
		 * */
		public static function prepare_log_deletion() {
			// Update the WP cron current date. 
			update_option('fs_affiliate_log_deletion_last_updated_date', FS_Date_Time::get_mysql_date_time_format('now', true));

			$log_ids = self::get_log_ids();
			$log_ids = array_filter(array_chunk($log_ids, 100));

			foreach ($log_ids as $count => $chunked_log_ids) {
				as_schedule_single_action(time() + $count, 'fs_affiliate_clean_up_user_logs', array( 'log_ids' => $chunked_log_ids ));
			}
		}

		/**
		 * Handles the master log deletion.
		 * */
		public static function handle_log_deletion( $log_ids ) {
			if (!fs_affiliates_check_is_array($log_ids)) {
				return;
			}

			foreach ($log_ids as $log_id) {
				wp_delete_post($log_id);
			}
		}

		/**
		 * Get the master log IDs based on deletion settings.
		 * 
		 * @return array
		 */
		public static function get_log_ids() {
			$visit_logs = array();
			$referral_logs = array();
			$time_duration = self::get_log_deletion_time_duration();
			$date_object = FS_Date_Time::get_date_time_object('now', true);
			$date_object->modify('-' . $time_duration['number'] . ' ' . $time_duration['unit']);

			if ('yes' == get_option('fs_affiliates_delete_visit_log')) {
				$visit_args = array(
					'post_type' => 'fs-visits',
					'post_status' => array( 'fs_converted', 'fs_notconverted', 'trash' ),
					'posts_per_page' => '-1',
					'fields' => 'ids',
					'date_query' => array(
						array(
							'column' => 'post_date_gmt',
							'before' => $date_object->format('Y-m-d H:i:s'),
						),
					),
				);

				$visit_logs = get_posts($visit_args);
			}

			if ('yes' == get_option('fs_affiliates_delete_referral_log')) {
				$referral_args = array(
					'post_type' => 'fs-referrals',
					'post_status' => array( 'fs_pending', 'fs_unpaid', 'fs_paid', 'fs_rejected' ),
					'posts_per_page' => '-1',
					'fields' => 'ids',
					'date_query' => array(
						array(
							'column' => 'post_date_gmt',
							'before' => $date_object->format('Y-m-d H:i:s'),
						),
					),
				);

				$referral_logs = get_posts($referral_args);
			}

			return array_merge( $visit_logs, $referral_logs);
		}

		/**
		 * Get the master log deletion time duration.
		 * 
		 * @return array
		 */
		public static function get_log_deletion_time_duration() {
			$duration = get_option('fs_affiliate_log_deletion_duration');

			return wp_parse_args($duration, array( 'number' => 1, 'unit' => 'years' ));
		}
	}

	FS_Affiliate_Cron_Handler::init();
}
