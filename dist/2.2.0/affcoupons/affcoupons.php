<?php
/**
 * WHMCS Affiliate Coupons
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
if ( ! defined( 'AC_ROOT' ) ) define( 'AC_ROOT', dirname( __FILE__ ) );

require_once( dirname( __FILE__ ) . "/classes/class-affiliatecoupons.php" );

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
	return AffiliateCoupons::activate();
}

/**
 * Addon Deactivated
 * @return array Status array for notice (success, error, info)
 */
function affcoupons_deactivate() {
	return AffiliateCoupons::deactivate();
}

/**
 * Addon Upgrade
 * @param  array $vars WHMCS vars
 */
function affcoupons_upgrade($vars) {
	// nothing returned or output on upgrade
	AffiliateCoupons::upgrade($vars);
}

/**
 * WHMCS Affiliate Coupons Admin Area Output
 * @param  array $vars WHMCS vars
 */
function affcoupons_output($vars){
	// Only admin area uses echo to output, all others must return data or html
	AffiliateCoupons::OutputAdmin($vars);
}

/**
 * Output Admin Area Sidebar
 * @param  array $vars WHMCS vars
 * @return string       HTML to output
 */
function affcoupons_sidebar($vars) {
	return AffiliateCoupons::OutputSidebar($vars);
}

/**
 * Output Client Area
 * @param  array $vars WHMCS vars
 * @return array       pagetitle, breadcrumb(array), templatefile, requirelogin (boolean), vars (array)
 */
function affcoupons_clientarea($vars) {
	return AffiliateCoupons::OutputClient($vars);
}
