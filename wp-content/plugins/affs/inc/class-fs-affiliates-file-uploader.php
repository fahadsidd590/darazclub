<?php

/*
 * File Uploader
 */
if ( !defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( !class_exists( 'FS_Affiliates_File_Uploader' ) ) {

	/**
	 * Class
	 */
	class FS_Affiliates_File_Uploader {
		/*
		 * Upload Folder Name
		 */

		protected $folder_name ;

		/*
		 * Upload Directory
		 */
		protected $directory ;

		/*
		 * Key
		 */
		protected $key ;

		/*
		 * Baseurl
		 */
		protected $baseurl ;

		/*
		 * Constructor
		 */

		public function __construct( $key = '' ) {
			$this->key         = $key ;
			$this->folder_name = 'fs-files' ;

			$this->make_directory() ;
		}

		/**
		 * get directory to upload file
		 */
		public function get_directory() {
			if ( $this->directory ) {
				return $this->directory ;
			}

			$upload_dir      = wp_upload_dir() ;
			$this->directory = $upload_dir[ 'basedir' ] . '/' . $this->folder_name ;

			return $this->directory ;
		}

		/**
		 * get baseurl to upload file
		 */
		public function get_baseurl() {
			if ( $this->baseurl ) {
				return $this->baseurl ;
			}

			$upload_dir    = wp_upload_dir() ;
			$this->baseurl = $upload_dir[ 'baseurl' ] . '/' . $this->folder_name ;

			return $this->baseurl ;
		}

		/**
		 * Make directory to upload file
		 */
		public function make_directory() {
			if ( !file_exists( $this->get_directory() ) ) {
				wp_mkdir_p( $this->get_directory() ) ;
			}
		}

		/**
		 * Prepare file name
		 */
		public function prepare_file_name( $file_name ) {
			return $this->get_directory() . '/' . $file_name ;
		}

		/**
		 * Move a file to server
		 */
		public function upload_files() {

			if ( !isset( $_FILES[ $this->key ] ) ) {
				return array() ;
			}

			$temp_name = $_FILES[ $this->key ][ 'tmp_name' ] ;

			$overrides = array(
				'test_form' => false,
			);

			$uploaded_file_data = wp_handle_upload( $_FILES[ $this->key ], $overrides );

			if ( isset( $uploaded_file_data['error'] ) ) {
				throw new Exception($uploaded_file_data['error']) ;
			}

			$file_url = array() ;

			if ( ! isset( $uploaded_file_data['error'] ) ) {
				$file_url[ $_FILES[ $this->key ][ 'name' ] ] = $uploaded_file_data['file'] ;
			}

			return $file_url ;
		}
	}

}
