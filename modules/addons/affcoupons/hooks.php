<?php
/**
 * WHMCS Affiliate Coupons Admin Class
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-23 02:21:45
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

require_once( dirname( __FILE__ ) . "/inc/core.php" );

class AffiliateCoupons_Hooks extends AffiliateCoupons {

	protected static $instance = null;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

function affcoupons_set_affiliate_cookie($vars) {
	if (isset($vars['promo'])) {
		$promocode = $vars['promo'];
		$data = select_query('tblpromotions', 'id', array("code"=>"$promocode"));
		if (mysql_num_rows($data)) {
			$row = mysql_fetch_array($data);
			$couponid = $row[0];
			$pdata = select_query('tblaffcoupons', 'aff_id', array("coupon"=>$couponid));
			if (mysql_num_rows($pdata)) {
				$prow = mysql_fetch_array($pdata);
				$affid = $prow[0];
				$checkcookie = WHMCS_Cookie::get("AffiliateID", true);
				if($affid){
					// update_query("tblaffiliates",array("visitors"=>"+1"),array("id"=>$affid));
    				WHMCS_Cookie::set('AffiliateID',$affid,'3m');
				}
			}
		}
	}
}
function affcoupons_admin_footer($vars){
	return AffiliateCoupons::AdminArea()->footer($vars);
}
function affcoupons_admin_header($vars){
	return AffiliateCoupons::AdminArea()->header($vars);
}
function affcoupons_admin_head($vars){
	return AffiliateCoupons::AdminArea()->head($vars);
}
function affcoupons_client_head($vars){
	return AffiliateCoupons::ClientArea()->head($vars);
}

function affcoupons_hook_check_update() {

	$output = AC_WHMCSe::output_update( AffiliateCoupons::version_url, AffiliateCoupons::$version, 'Affiliate Coupons' );

	return $output;
}

add_hook( "AdminHomepage", 1, "affcoupons_hook_check_update" );

add_hook("PreCalculateCartTotals",1,"affcoupons_set_affiliate_cookie");

// Runs when loading any admin area page and can be used to define additional HTML output to be output immediately following the <body> tag of the page.
add_hook("AdminAreaHeaderOutput",1,"affcoupons_admin_header");

// Exactly the same as AdminAreaHeaderOutput above but output immediately before the closing </body> tag of the page.
add_hook("AdminAreaFooterOutput",1,"affcoupons_admin_footer");

// Runs when loading any admin area page and can be used to define additional HTML output within the <head> section of the page.
add_hook("AdminAreaHeadOutput",1,"affcoupons_admin_head");

// Runs when loading any client area page and can be used to define additional HTML output within the <head> section of the page.
add_hook("ClientAreaHeadOutput",1,"affcoupons_client_head");

?>
