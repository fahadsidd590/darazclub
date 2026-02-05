<?php

/**
 * Affiliates Wallet Post Table
 * 
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Payout_Request_Logs' ) ) {

	/**
	 * FS_Affiliates_Payout_Request_Logs Class.
	 * */
	class FS_Affiliates_Payout_Request_Logs extends FS_Affiliates_List_Table {

		/**
		 * Order BY
				 * 
				 * @since 1.0.0
				 * @var string
		 * */
		protected $orderby = 'ORDER BY ID DESC' ;

		/**
		 * Post type
				 * 
				 * @since 1.0.0
				 * @var string
		 * */
		protected $post_type = 'fs-payout-request' ;

		/**
		 * Prepare the table Data to display table based on pagination.
				 * 
				 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg( array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'payout_request' ) , admin_url( 'admin.php' ) ) ;
			
						parent::prepare_items();
														
			$this->get_current_page_items() ;                            
		}

		/**
		 * Initialize the columns
				 * 
				 * @since 1.0.0
		 * */
		public function get_columns() {
			$columns = array(
				'cb'                => '<input type="checkbox" />',
				'ID'                => __( 'ID' , FS_AFFILIATES_LOCALE ),
				'affiliate_name'    => __( 'Affiliate' , FS_AFFILIATES_LOCALE ),
				'unpaid_commission' => __( 'Total Unpaid Commission' , FS_AFFILIATES_LOCALE ),
				'status'            => __( 'Status' , FS_AFFILIATES_LOCALE ),
				'requested_date'    => __( 'Requested Date' , FS_AFFILIATES_LOCALE ),
				'notes'             => __( 'Notes' , FS_AFFILIATES_LOCALE ),
				'closed_date'       => __( 'Closed Date' , FS_AFFILIATES_LOCALE ),
					) ;

			return $columns ;
		}

		/**
		 * Initialize the hidden columns
				 * 
				 * @since 1.0.0
		 * */
		public function get_hidden_columns() {
			return array() ;
		}

		/**
		 * Initialize the bulk actions
				 * 
				 * @since 1.0.0
		 * */
		protected function get_bulk_actions() {
			$action                   = array() ;
			$action[ 'fs_submitted' ] = __( 'Submitted' , FS_AFFILIATES_LOCALE ) ;
			$action[ 'fs_progress' ]  = __( 'In-Progress' , FS_AFFILIATES_LOCALE ) ;
			$action[ 'fs_closed' ]    = __( 'Closed' , FS_AFFILIATES_LOCALE ) ;
			$action[ 'delete' ]       = __( 'Delete' , FS_AFFILIATES_LOCALE ) ;

			return $action ;
		}

		/**
		 * add row actions
				 * 
				 * @param string $item
				 * @since 1.0.0
		 * */
		public function column_ID( $item ) {
			$actions = array() ;

			if ( get_post_status( $item->get_id() ) != 'fs_closed' ) {
				$actions[ 'edit' ]      = sprintf( '<a href="' . $this->base_url . '&subsection=%s&id=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'fs_edit_request' , $item->get_id() ) ;
				$actions[ 'fs_closed' ] = sprintf( '<a href="' . $this->base_url . '&action=%s&id=%s">' . __( 'Mark as Closed' , FS_AFFILIATES_LOCALE ) . '</a>' , 'fs_closed' , $item->get_id() ) ;
			}
			$actions[ 'delete' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , FS_AFFILIATES_LOCALE ) . '</a>' , 'delete' , $item->get_id() ) ;

			//Return the title contents
			return sprintf( '%1$s %2$s' ,
					/* $1%s */ '#' . $item->get_id() ,
					/* $3%s */ $this->row_actions( $actions )
					) ;
		}

		/**
		 * Prepare each column data
				 * 
				 * @param string $item
				 * @param string $column_name
				 * @since 1.0.0
		 * */
		protected function column_default( $item, $column_name ) {
			$payoutrequest     = get_post( $item->get_id() ) ;
			$affiliate_id      = $payoutrequest->post_author ;
			$affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
			switch ( $column_name ) {
				case 'affiliate_name':
					return $affiliates_object->user_name ;
					break ;
				case 'unpaid_commission':
					return fs_affiliates_price( get_post_meta( $item->get_id() , 'fs_affiliates_unpaid_commission' , true ) ) ;
					break ;
				case 'status':
					if ( get_post_status( $item->get_id() ) == 'fs_submitted' ) {
						return __( 'Submitted' , FS_AFFILIATES_LOCALE ) ;
					} elseif ( get_post_status( $item->get_id() ) == 'fs_progress' ) {
						return __( 'In-Progess' , FS_AFFILIATES_LOCALE ) ;
					} else {
						return __( 'Closed' , FS_AFFILIATES_LOCALE ) ;
					}
					return get_post_status( $item->get_id() ) ;
					break ;
				case 'requested_date':
					return $payoutrequest->post_date ;
					break ;
				case 'notes':
					return empty( $payoutrequest->post_content ) ? '-' : $payoutrequest->post_content ;
					break ;
				case 'closed_date':
					$ClosedDate = get_post_meta( $item->get_id() , 'fs_closed_date' , true ) ;
					return empty( $ClosedDate ) ? '-' : date( 'Y-m-d h:i:s' , $ClosedDate ) ;
					break ;
			}
		}
	}

}
