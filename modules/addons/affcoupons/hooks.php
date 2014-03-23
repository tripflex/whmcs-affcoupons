<?php
/**
 * WHMCS Affiliate Coupons
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

require_once( dirname( __FILE__ ) . "/inc/hooks.php" );

AffiliateCoupons_Hooks::generate();

?>