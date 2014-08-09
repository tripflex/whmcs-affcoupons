<?php
/**
 * WHMCSe Framework
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    1.0.1
 * @link       https://smyl.es
 */

if ( ! defined( "WHMCS" ) ) die( "This file cannot be accessed directly" );

if ( ! class_exists( 'WHMCSe' ) ) {
	class WHMCSe {

		protected static $instance = NULL;

		/**
		 * __construct
		 */
		function __construct () {
			// define("ADMINAREA", true);
		}

		/**
		 * Singleton Instance
		 * @return object
		 */
		public static function get_instance () {

			// If the single instance hasn't been set, set it now.
			if ( NULL == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Log data to WHMCS Module Log
		 * @since 1.0.0
		 *
		 * @param  string $description Description of what is being logged
		 * @param  mixed  $request     Data to be logged to request section
		 * @param  mixed  $response    Data to be logged to response section
		 */
		public static function log ( $description, $request, $response ) {

			logModuleCall( 'whmcse', $description, $request, $response );
		}

		/**
		 * Log debug data to WHMCS Module Log
		 * @since 1.0.0
		 *
		 * @param  mixed $debugdata  data to be logged in the request section
		 * @param  mixed $debugdata2 data to be logged in the response section
		 */
		public static function debug ( $debugdata, $debugdata2 ) {

			logModuleCall( 'whmcse', 'debug', $debugdata, $debugdata2 );
		}

		/**
		 * Get the full URL for WHMCS Installation
		 * @since 1.0.0
		 *
		 * @param  boolean $SSL Return SSL URL if exists by default
		 *
		 * @return string       Full URL to WHMCS Installation
		 */
		public static function get_url ( $SSL = true ) {

			global $CONFIG;
			$SystemURL    = $CONFIG['SystemURL'];
			$SystemSSLURL = $CONFIG['SystemSSLURL'];

			if ( ! $SSL || ! $SystemSSLURL && $SystemURL ) return $SystemURL;

			if ( $SSL && $SystemSSLURL ) return $SystemSSLURL;

		}

		/**
		 * Check for custom admin path
		 * @since 1.0.0
		 * @return String Returns custom admin path, or admin by default
		 */
		private function get_custom_admin_path () {

			$customadminpath = $GLOBALS['customadminpath'];
			if ( ! $customadminpath ) $customadminpath = "admin";

			return $customadminpath;
		}

		/**
		 * Get Admin URL for WHMCS Installation
		 * @since 1.0.0
		 *
		 * @param boolean $SSL Specify to return SSL URL or not, SSL by default
		 *
		 * @return String Full URL to WHMCS Installation, SSL is used by default
		 */
		public static function get_admin_url ( $SSL = true ) {

			return self::get_url( $SSL ) . "/" . self::get_custom_admin_path();
		}

		/**
		 * Get addon module URL
		 * @since 1.0.0
		 *
		 * @param  string $module Module name, should be exactly as named in addon module folder
		 *
		 * @return string         Full URL path to addon module
		 */
		public static function get_module_url ( $module ) {

			return self::get_url() . '/modules/addons/' . $module;
		}

		/**
		 * Make CURL call and check versions
		 * @since 1.0.1
		 *
		 * @param  string  $url     HTTPS Full URL to raw file with version number
		 * @param  integer $version Full current version number
		 * @param  string  $module  Current module checking update for
		 *
		 * @return boolean         True if newer version is available, false if not
		 */
		public static function check_need_update ( $url, $version, $module = 'whmcse' ) {

			//			$release = file_get_contents( $url, "r" );

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt( $ch, CURLOPT_USERAGENT, 'tripflex/whmcs-pushover/' . $version );
			$release = curl_exec( $ch );
			curl_close( $ch );

			logModuleCall( $module, 'update check', $version, $release );

			if ( version_compare( $release, $version ) > 0 ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Output update notice HTML
		 * @since 1.0.1
		 *
		 * @param  string  $url     HTTPS Full URL to raw file with version number
		 * @param  integer $version Full current version number
		 * @param  string  $module  Current module checking update for
		 *
		 * @return string          HTML to show on admin dashboard if current version is older than current release
		 */
		public static function output_update ( $url, $version, $module = 'whmcse' ) {

			$notice      = '';
			$need_update = self::check_need_update( $url, $version, $module );
			$update_url  = str_replace( '/release', '', $url );

			if ( $need_update ) {
				$notice = '<div class="infobox"><strong><span class="title">' . $module . ' update available!</span></strong><br>You can download the update from <a href="' . $update_url . '">GitHub</a></div>';
			}

			return $notice;
		}
	}
}
AC_WHMCSe::get_instance();