<?php
/**
 * @title Affiliate Cookie Tracking + Redirection Handler + Affliate Coupons
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @author     WHMCS WHMCS Limited (development@whmcs.com)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GNU GPL v3+
 * @version    2.0
 * @link       https://gh.smyl.es/whmcs-affcoupons
 * 
 */

define("CLIENTAREA",true);

require("init.php");

// if affiliate id is present, update visitor count & set cookie
if ($aff = $whmcs->get_req_var('aff')) {
	update_query("tblaffiliates",array("visitors"=>"+1"),array("id"=>$aff));
    WHMCS_Cookie::set('AffiliateID',$aff,'3m');
}

// if product id passed in, redirect to order form
if ($pid = $whmcs->get_req_var('pid')) redir("a=add&pid=".(int)$pid,"cart.php");

// if product group id passed in, redirect to product group
if ($gid = $whmcs->get_req_var('gid')) redir("gid=".(int)$gid,"cart.php");

// if register = true, redirect to registration form
if ($whmcs->get_req_var('register')) redir("","register.php");

// if gocart = true, redirect to cart with request params
if ($whmcs->get_req_var('gocart')) {
    $reqvars = '';
    foreach ($_GET AS $k=>$v) $reqvars .= $k.'='.urlencode($v).'&';
    redir($reqvars,"cart.php");
}

// get landing to redirect to from affiliate number
$r = select_query("tblaffcouponslanding", "landing", array("aff_id"=>$aff));
$data = mysql_fetch_array($r);
$landing = $data['landing'];
header("HTTP/1.1 301 Moved Permanently");
if ($landing) {
	header("Location: $landing");
	exit;
} else {
	header("Location: ".$whmcs->get_config('Domain'),true,301);
	exit;
}