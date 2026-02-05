<?php

/**
 * Form Fields Post Table
 * 
 * @since 1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_Email_Opt_In_Post_Table' ) ) {

	/**
	 * FS_Affiliates_Email_Opt_In_Post_Table Class.
		 * 
		 * @since 1.0.0
	 * */
	class FS_Affiliates_Email_Opt_In_Post_Table extends FS_Affiliates_List_Table {
		/**
		 * Prepare the table Data to display table based on pagination.
				 * 
				 * @since 1.0.0
		 * */
		public function prepare_items() {
			$this->base_url = add_query_arg( array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => 'affs_email_opt_in' ) , admin_url( 'admin.php' ) ) ;
			
						parent::prepare_items();  
						
			$this->process_bulk_action() ;  
			$this->get_current_page_items() ;
		}
		
		/**
		 * Initialize the columns
				 * 
				 * @since 1.0.0
		 * */
		public function get_columns() {
			$columns = array(
				'cb'             => '<input type="checkbox" />', //Render a checkbox instead of text
				'field_name'     => __( 'Field Name' , FS_AFFILIATES_LOCALE ),
				'field_status'   => __( 'Field Status' , FS_AFFILIATES_LOCALE ),
				'field_required' => __( 'Field Type' , FS_AFFILIATES_LOCALE ),
				'sort'           => __( 'Sort' , FS_AFFILIATES_LOCALE ),
					) ;

			return $columns ;
		}

		/**
		 * Initialize the sortable columns
				 * 
				 * @since 1.0.0
		 * */
		public function get_sortable_columns() {
			return array() ;
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
			$action = array() ;

			$action[ 'enabled' ]  = __( 'Enable' , FS_AFFILIATES_LOCALE ) ;
			$action[ 'disabled' ] = __( 'Disable' , FS_AFFILIATES_LOCALE ) ;

			return $action ;
		}

		/**
		 * add row actions
				 * 
				 * @param string $item
				 * @since 1.0.0
		 * */
		public function column_field_name( $item ) {
			$actions             = array() ;
			$action_status       = ( $item[ 'field_status' ] == 'enabled' ) ? 'disabled' : 'enabled' ;
			$action_status_label = ( $item[ 'field_status' ] == 'enabled' ) ? __( 'Disable' , FS_AFFILIATES_LOCALE ) : __( 'Enable' , FS_AFFILIATES_LOCALE ) ;
			$actions[ 'edit' ]   = sprintf( '<a href="' . $this->base_url . '&subsection=%s&key=%s">' . __( 'Edit' , FS_AFFILIATES_LOCALE ) . '</a>' , 'edit' , $item[ 'field_key' ] ) ;

			$actions [ 'status' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . $action_status_label . '</a>' , $action_status , $item[ 'field_key' ] ) ;

			//Return the title contents
			return sprintf( '%1$s %2$s' ,
					/* $1%s */ $item[ 'field_name' ] ,
					/* $3%s */ $this->row_actions( $actions )
					) ;
		}

		/**
		 * bulk action functionality
				 * 
				 * @since 1.0.0
		 * */
		public function process_bulk_action() {

			$ids = isset( $_REQUEST[ 'id' ] ) ? $_REQUEST[ 'id' ] : array() ;
			$ids = !is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

			if ( !fs_affiliates_check_is_array( $ids ) ) {
				return ;
			}

			$action = $this->current_action() ;
			$fields = fs_affiliates_get_opt_in_form_fields() ;

			foreach ( $ids as $id ) {

				if ( !isset( $fields[ $id ] ) ) {
					continue ;
				}

				$field = $fields[ $id ] ;
				if ( 'disabled' === $action ) {
					$field[ 'field_status' ] = 'disabled' ;
				} elseif ( 'enabled' === $action ) {
					$field[ 'field_status' ] = 'enabled' ;
				}

				$fields[ $id ] = $field ;
			}

			update_option( 'fs_affiliates_opt_in_form_fields' , $fields ) ;

			wp_safe_redirect( $this->current_url ) ;
			exit() ;
		}

		/**
		 * Prepare cb column data
				 * 
				 * @param string $item
				 * @since 1.0.0
		 * */
		public function column_cb( $item ) {

			return sprintf(
					'<input type="checkbox" class="fs_affiliates_sortable" name="id[]" value="%s"  />' , $item[ 'field_key' ]
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

			switch ( $column_name ) {
				case 'field_status':
					return ucfirst( $item[ 'field_status' ] ) ;
					break ;
				case 'field_required':
					return ucfirst( $item[ 'field_required' ] ) ;
					break ;
				case 'sort':
					return '<div class="fs_affiliates_fields_sort_handle"><i class="fa fa-bars" ></i></div>' ;
					break ;
			}
		}

		/**
		 * Initialize the columns
				 * 
				 * @since 1.0.0
		 * */
		public function get_current_page_items() {
			$fields            = fs_affiliates_get_opt_in_form_fields() ;
			$this->total_items = count( $fields ) ;
			$this->items       = $fields ;
		}
	}

}
