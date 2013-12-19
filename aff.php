<?php
/*
	Affiliate Coupons 1.2 - WHMCS Module
	Written by: Frank Laszlo <frank@asmallorange.com>
*/

define("CLIENTAREA",true);

include("dbconnect.php");
include("includes/functions.php");

if (isset($aff)) {
	update_query("tblaffiliates",array("visitors"=>"+1"),array("id"=>$aff));
	setcookie("WHMCSAffiliateID", $aff, time()+90*24*60*60);
	$r = select_query("tblaffcouponslanding", "landing", array("aff_id"=>$aff));
	$data = mysql_fetch_array($r);
	$landing = $data['landing'];
	header("HTTP/1.1 301 Moved Permanently");
	if ($landing) {
		header("Location: $landing");
		exit;
	} else {
		header("Location: ".$CONFIG["Domain"]);
		exit;
	}
}

if ($pid) {
    header("Location: cart.php?a=add&pid=$pid");
    exit;
}

header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$CONFIG["Domain"]);

?>
