<?php

/*
 * Affiliates Wallet
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Wallet' ) ) {

	/**
	 * FS_Affiliates_Wallet Class.
	 */
	class FS_Affiliates_Wallet extends FS_Affiliates_Post {

		/**
		 * Post type
		 */
		protected $post_type = 'fs-wallet-logs';

		/**
		 * Post Status
		 */
		protected $post_status = 'publish';

		/**
		 * Affiliate ID.
		 *
		 * @since 10.0.0
		 * @var int|string
		 */
		public $affiliate_id;

		/**
		 * Event.
		 *
		 * @since 10.0.0
		 * @var string
		 */
		public $event;

		/**
		 * Earned balance.
		 *
		 * @since 10.0.0
		 * @var float
		 */
		public $earned_balance;

		/**
		 * Used balance.
		 *
		 * @since 10.0.0
		 * @var float
		 */
		public $used_balance;

		/**
		 * Available balance.
		 *
		 * @since 10.0.0
		 * @var float
		 */
		public $available_balance;

		/**
		 * Date.
		 *
		 * @since 10.0.0
		 * @var string
		 */
		public $date;

		/**
		 * Meta data keys
		 */
		protected $meta_data_keys = array(
			'affiliate_id'      => '',
			'event'             => '',
			'earned_balance'    => '',
			'used_balance'      => '',
			'available_balance' => '',
			'date'              => '',
		);
	}

}
