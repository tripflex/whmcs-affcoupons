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

require_once( dirname( __FILE__ ) . "/inc/core.php" );

/**
 * Configuration
 * @return array Array of configuration values
 */
function affcoupons_config() {
	return AffiliateCoupons::config();
}

/**
 * Addon Activation
 * @return array Status array for notice (success, error, info)
 */
function affcoupons_activate() {
	return AffiliateCoupons::get_instance()->activate();
}

/**
 * Addon Deactivated
 * @return array Status array for notice (success, error, info)
 */
function affcoupons_deactivate() {
	return AffiliateCoupons::get_instance()->deactivate();
}

/**
 * Addon Upgrade
 * @param  array $vars WHMCS vars
 */
function affcoupons_upgrade($vars) {
	AffiliateCoupons::get_instance()->upgrade($vars);
}

/**
 * WHMCS Affiliate Coupons Admin Area Output
 * @param  array $vars WHMCS vars
 */
function affcoupons_output($vars){
	AffiliateCoupons::get_instance()->admin();
}

/**
 * Output Admin Area Sidebar
 * @param  array $vars WHMCS vars
 * @return string       HTML to output
 */
function affcoupons_sidebar($vars) {
	AffiliateCoupons::get_instance()->sidebar();
}

/**
 * Output Client Area
 * @param  array $vars WHMCS vars
 * @return array       pagetitle, breadcrumb(array), templatefile, requirelogin (boolean), vars (array)
 */
function affcoupons_clientarea($vars) {
	AffiliateCoupons::get_instance()->client();
}

?>