<?php

/*
 * Affiliates Data
 */
if ( ! defined ( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'FS_Affiliates_Data' ) ) {

	/**
	 * FS_Affiliates_Data Class.
	 */
	class FS_Affiliates_Data extends FS_Affiliates_Post {

		/**
		 * Post Type
		 */
		protected $post_type = 'fs-affiliates' ;

		/**
		 * Post Status
		 */
		protected $post_status = 'fs_active' ;
				
				/**
		 * Parent
				 *
				 * @var string
		 */
		public $parent;
				
				/**
		 * User ID
				 *
				 * @var string
		 */
		public $user_id;
				
				/**
		 * User Name
				 *
				 * @var string
		 */
		public $user_name;
				
				/**
		 * First Name
				 *
				 * @var string
		 */
		public $first_name;
				
				/**
		 * Last Name
				 *
				 * @var string
		 */
		public $last_name;
				
				/**
		 * Campaign
				 *
				 * @var string
		 */
		public $campaign;
				
				/**
		 * Email
				 *
				 * @var string
		 */
		public $email;
				
				/**
		 * Website
				 *
				 * @var string
		 */
		public $website;
				
				/**
		 * Promotion
				 *
				 * @var string
		 */
		public $promotion;
				
				/**
		 * Phone Number
				 *
				 * @var string
		 */
		public $phone_number;
				
				/**
		 * Payment Email
				 *
				 * @var string
		 */
		public $payment_email;
				
				/**
		 * Country
				 *
				 * @var string
		 */
		public $country;
				
				/**
		 * Hash
				 *
				 * @var string
		 */
		public $hash;
				
				/**
		 * Link Validity
				 *
				 * @var string
		 */
		public $link_validity;
				
				/**
		 * Paid Earnings
				 *
				 * @var string
		 */
		public $paid_earnings;
				
				/**
		 * Unpaid Earnings
				 *
				 * @var string
		 */
		public $unpaid_earnings;
				
				/**
		 * Commission Type
				 *
				 * @var string
		 */
		public $commission_type;

		/**
		 * Referral Code Type
		 *
		 * @var string
		 */
		public $referral_code_type;
				
				/**
		 * Referral Code
				 *
				 * @var string
		 */
		public $referral_code;
				
				/**
		 * Commission Value
				 *
				 * @var string
		 */
		public $commission_value;
				
				/**
		 * WC Product Rates
				 *
				 * @var string
		 */
		public $wc_product_rates;
				
				/**
		 * Rule Priority
				 *
				 * @var string
		 */
		public $rule_priority;
				
				/**
		 * Date
				 *
				 * @var string
		 */
		public $date;
				
				/**
		 * Commission Provided
				 *
				 * @var string
		 */
		public $commission_provided;
				
				/**
		 * Upload Files
				 *
				 * @var string
		 */
		public $uploaded_files;
				
				/**
		 * Modify Slug
				 *
				 * @var string
		 */
		public $modify_slug;
				
				/**
		 * PushOver Key
				 *
				 * @var string
		 */
		public $pushover_key;
				
				/**
		 * Device Name
				 *
				 * @var string
		 */
		public $device_name;
				
				/**
		 * Visit PushOver
				 *
				 * @var string
		 */
		public $visit_pushover;
				
				/**
		 * Referral PushOver
				 *
				 * @var string
		 */
		public $referral_pushover;
				
				/**
		 * Payout PushOver
				 *
				 * @var string
		 */
		public $payout_pushover;
				
				/**
		 * Landing Pages
				 *
				 * @var string
		 */
		public $landing_pages;
				
				/**
		 * Name Label Heading
				 *
				 * @var string
		 */
		public $name_label_heading;
				
				/**
		 * Address Label
				 *
				 * @var string
		 */
		public $addr1_label;
				
				/**
		 * Address Label2
				 *
				 * @var string
		 */
		public $addr2_label;
				
				/**
		 * City Label
				 *
				 * @var string
		 */
		public $city_label;
				
				/**
		 * State Label
				 *
				 * @var string
		 */
		public $state_label;
				
				/**
		 * Zip Code Label
				 *
				 * @var string
		 */
		public $zip_code_label;
				
				/**
		 * Tax Cred Label
				 *
				 * @var string
		 */
		public $tax_cred_label;
				
				/**
		 * Payout from status success full
				 *
				 * @var string
		 */
		public $payout_form_status_successfull;
				
				/**
		 * IS Bonus Awarded
				 *
				 * @var string
		 */
		public $is_bonus_awarded;
				
				/**
		 * SignUp Visit ID
				 *
				 * @var string
		 */
		public $signup_visit_id;
				
				/**
		 * SignUp Campaign ID Label
				 *
				 * @var string
		 */
		public $signup_campaign_id;
				
				/**
		 * Slug
				 *
				 * @var string
		 */
		public $slug;

		/**
		 * Meta data keys
		 */
		protected $meta_data_keys = array(
			'first_name'                     => '',
			'last_name'                      => '',
			'campaign'                       => '',
			'email'                          => '',
			'website'                        => '',
			'promotion'                      => '',
			'phone_number'                   => '',
			'payment_email'                  => '',
			'country'                        => '',
			'hash'                           => '',
			'link_validity'                  => '',
			'paid_earnings'                  => '',
			'unpaid_earnings'                => '',
			'commission_type'                => '',
			'referral_code_type'                  => '',
			'referral_code'                  => '',
			'commission_value'               => '',
			'wc_product_rates'               => '',
			'rule_priority'                  => '',
			'date'                           => '',
			'commission_provided'            => '',
			'uploaded_files'                 => array(),
			'modify_slug'                    => '',
			'pushover_key'                   => '',
			'device_name'                    => '',
			'visit_pushover'                 => 'no',
			'referral_pushover'              => 'no',
			'payout_pushover'                => 'no',
			'landing_pages'                  => '',
			'name_label_heading'             => '',
			'addr1_label'                    => '',
			'addr2_label'                    => '',
			'city_label'                     => '',
			'state_label'                    => '',
			'zip_code_label'                 => '',
			'tax_cred_label'                 => '',
			'payout_form_status_successfull' => '',
			'is_bonus_awarded'               => '',
			'signup_visit_id'                => '',
			'signup_campaign_id'             => '',
				) ;

		/**
		 * Prepare extra post data
		 */
		public function load_extra_postdata() {

			$this->parent    = $this->post->post_parent ;
			$this->user_id   = $this->post->post_author ;
			$this->user_name = $this->post->post_title ;
			$this->slug      = $this->post->post_name ;                        
		}

		/**
		 * parent exist
		 */
		public function parent_exists() {
			if ( ! $this->parent ) {
				return false ;
			}

			return get_post_status ( $this->parent ) ;
		}

		/**
		 * visits count
		 */
		public function get_visits_count() {      
			return count(fs_affiliates_get_visits_count ( $this->id )) ;
		}

		/**
		 * referrals count
		 */
		public function get_referrals_count() {
			return fs_affiliates_get_referrals_count ( $this->id ) ;
		}

		/**
		 * Unpaid Earnings amount
		 */
		public function get_unpaid_commission() {
			return fs_affiliates_price ( fs_affiliates_get_referrals_commission ( $this->id ) ) ;
		}

		/**
		 *  paid Earnings amount
		 */
		public function get_paid_commission() {
			return fs_affiliates_price ( $this->paid_earnings ) ;
		}

		/**
		 *  Overall Earnings amount
		 */
		public function get_overall_commission() {
			$overall = ( float ) fs_affiliates_get_referrals_commission ( $this->id ) + ( float ) $this->paid_earnings ;

			return fs_affiliates_price ( $overall ) ;
		}
	}

}
