<?php
/**
 * WHMCS Affiliate Coupons Hooks
 *
 * @package      WHMCS 5.2.1+
 * @author       Myles McNamara (https://smyl.es)
 * @copyright    Copyright (c) Myles McNamara 2014
 * @license      GNU GPL v3+
 * @version      2.2.0
 * @updated      Mon Aug 11 2014 12:33:56
 *
 * @link         https://github.com/tripflex/whmcs-affcoupons
 */

if ( ! defined( "WHMCS" ) ) {
	die( "This file cannot be accessed directly" );
}

if ( ! class_exists( 'AffiliateCoupons' ) ) {
	require_once( dirname( __FILE__ ) . "/classes/class-affiliatecoupons.php" );
}

function affcoupons_set_affiliate_cookie( $vars ) {

	AffiliateCoupons::ClientArea()->set_cookie( $vars );
}

function affcoupons_admin_footer( $vars ) {

	return AffiliateCoupons::AdminArea()->footer( $vars );
}

function affcoupons_client_footer( $vars ) {

	return AffiliateCoupons::ClientArea()->footer( $vars );
}

function affcoupons_admin_header( $vars ) {

	return AffiliateCoupons::AdminArea()->header( $vars );
}

function affcoupons_admin_head( $vars ) {

	return AffiliateCoupons::AdminArea()->head( $vars );
}

function affcoupons_client_head( $vars ) {

	return AffiliateCoupons::ClientArea()->head( $vars );
}

function affcoupons_hook_check_update() {

	$output = AC_WHMCSe::output_update( AffiliateCoupons::VERSION_URL, AffiliateCoupons::VERSION, 'Affiliate Coupons' );

	return $output;
}

add_hook( "AdminHomepage", 1, "affcoupons_hook_check_update" );

add_hook( "PreCalculateCartTotals", 1, "affcoupons_set_affiliate_cookie" );

// Runs when loading any admin area page and can be used to define additional HTML output to be output immediately following the <body> tag of the page.
add_hook( "AdminAreaHeaderOutput", 1, "affcoupons_admin_header" );

// Exactly the same as AdminAreaHeaderOutput above but output immediately before the closing </body> tag of the page.
add_hook( "AdminAreaFooterOutput", 1, "affcoupons_admin_footer" );

// Runs when loading any admin area page and can be used to define additional HTML output within the <head> section of the page.
add_hook( "AdminAreaHeadOutput", 1, "affcoupons_admin_head" );

// Runs when loading any client area page and can be used to define additional HTML output within the <head> section of the page.
add_hook( "ClientAreaHeadOutput", 1, "affcoupons_client_head" );

// Output immediately before the closing </body> tag of the page.
add_hook( "ClientAreaFooterOutput", 1, "affcoupons_client_footer" );