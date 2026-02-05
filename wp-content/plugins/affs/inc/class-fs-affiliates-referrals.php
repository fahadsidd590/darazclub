<?php

/*
 * Affiliates Referrals Data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Referrals' ) ) {

	/**
	 * FS_Affiliates_Referrals Class.
	 */
	class FS_Affiliates_Referrals extends FS_Affiliates_Post {

		/**
		 * Post type
		 */
		protected $post_type = 'fs-referrals' ;

		/**
		 * Post Status
		 */
		protected $post_status = 'fs_unpaid' ;
				
				/**
		 * Affiliate 
				 *
				 * @var string
		 */
		public $affiliate;
				
				/**
		 * Reference 
				 *
				 * @var string
		 */
		public $reference;
				
				/**
		 * Description 
				 *
				 * @var string
		 */
		public $description;
				
				/**
		 * Campaign 
				 *
				 * @var string
		 */
		public $campaign;
				
				/**
		 * Visit ID 
				 *
				 * @var string
		 */
		public $visit_id;
				
				/**
		 * Amount 
				 *
				 * @var string
		 */
		public $amount;
				
				/**
		 * Type 
				 *
				 * @var string
		 */
		public $type;
				
				/**
		 * Date 
				 *
				 * @var string
		 */
		public $date;
				
				 /**
		 * Rejected Reason 
				  *
				 * @var string
		 */
		public $rejected_reason;
				
				/**
		 * Paid Reason 
				  *
				 * @var string
				 * @since 10.2.0
		 */
		public $paid_reason;
				
				/**
		 * IP Address 
				 *
				 * @var string
		 */
		public $ip_address;
				
				/**
		 * Landing Commission ID
				 *
				 * @var string
		 */
		public $landing_commission_id;

		/**
		 * Meta data keys
		 */
		protected $meta_data_keys = array(
			'reference'             => '',
			'description'           => '',
			'campaign'              => '',
			'visit_id'              => '',
			'amount'                => '',
			'type'                  => '',
			'date'                  => '',
			'rejected_reason'       => '',
						'paid_reason'         => '',
			'ip_address'            => '',
			'landing_commission_id' => '',
				) ;

		/**
		 * Prepare extra post data
		 */
		public function load_extra_postdata() {

			$this->affiliate = $this->post->post_author ;
		}
	}

}
