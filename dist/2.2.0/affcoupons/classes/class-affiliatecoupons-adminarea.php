<?php
/**
 * WHMCS Affiliate Coupons Admin Area
 *
 * @package      WHMCS 5.2.1+
 * @author       Myles McNamara (https://smyl.es)
 * @copyright    Copyright (c) Myles McNamara 2014
 * @license      GNU GPL v3+
 * @version      2.2.0
 * @updated      Mon Aug 11 2014 11:13:12
 *
 * @link         https://github.com/tripflex/whmcs-affcoupons
 */

if ( ! defined( "WHMCS" ) ) die( "This file cannot be accessed directly" );

class AffiliateCoupons_AdminArea extends AffiliateCoupons {

	protected static $instance = NULL;

	function __construct() {

		define( "ADMINAREA", TRUE );

	}

	public function footer( $vars ) {

		return '';
	}

	public function head( $vars ) {

		return '';
	}

	public function header( $vars ) {

		return '';
	}

	public function output( $vars ) {

//        required for config.php file
		$modulelink = $vars[ 'modulelink' ];
		$page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$module     = filter_input( INPUT_GET, 'module', FILTER_SANITIZE_STRING );

		$update = AC_WHMCSe::output_update( AffiliateCoupons::VERSION_URL, AffiliateCoupons::VERSION, 'Affiliate Coupons' );

		if ( $update ) echo $update;

		if ( $page && $module == 'affcoupons' ) {

			include_once( AC_ROOT . "/pages/" . $page . ".php" );

		} else {

			require_once( AC_ROOT . "/pages/default.php" );

		}

	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

?>
