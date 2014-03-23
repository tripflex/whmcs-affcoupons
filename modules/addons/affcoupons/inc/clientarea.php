<?php
/**
 * WHMCS Affiliate Coupons Client Area
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-22 21:20:22
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

define( 'AC_ROOT', dirname( __FILE__ ) );

class AffiliateCoupons_ClientArea extends AffiliateCoupons {

	protected static $instance = null;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){
		define("CLIENTAREA", true);
	}

	public function output($vars){

	}

}

?>