<?php

/*
 * Affiliates Payouts Data
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('FS_Affiliates_Payouts')) {

	/**
	 * FS_Affiliates_Payouts Class.
	 */
	class FS_Affiliates_Payouts extends FS_Affiliates_Post {

		/**
		 * Post type
		 */
		protected $post_type = 'fs-payouts';

		/**
		 * Post Status
		 */
		protected $post_status = 'fs_paid';

		/**
		 * Referrals.
		 *
		 * @var string
		 */
		public $referrals = '';

		/**
		 * Payment Mode.
		 *
		 * @var string
		 */
		public $payment_mode = '';

		/**
		 * Generate By.
		 *
		 * @var string
		 */
		public $generate_by = '';

		/**
		 * Paid Amount.
		 *
		 * @var string
		 */
		public $paid_amount = '';

		/**
		 * Referral ID.
		 *
		 * @var string
		 */
		public $referral_id = '';

		/**
		 * Date.
		 *
		 * @var string
		 */
		public $date = '';

		/**
		 * Pay Statement File Name.
		 *
		 * @var string
		 */
		public $pay_statement_file_name = '';

		/**
		 * ID.
		 *
		 * @since 9.9.0
		 * @var string
		 */
		public $ID = '';

		/**
		 * Meta data keys
		 */
		protected $meta_data_keys = array(
			'referrals' => '',
			'payment_mode' => '',
			'generate_by' => '',
			'paid_amount' => '',
			'referral_id' => '',
			'date' => '',
			'pay_statement_file_name' => '',
				);

		/**
		 * Prepare extra post data
		 */
		public function load_extra_postdata() {

			$this->affiliate = $this->post->post_author;
		}
	}

}
