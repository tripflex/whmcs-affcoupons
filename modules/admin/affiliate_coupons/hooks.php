<?php
/**
 * @title Affliate Coupons Hook
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @author     Frank Laszlo (frank@asmallorange.com)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GNU GPL v3+
 * @version    2.0
 * @link       https://gh.smyl.es/whmcs-affcoupons
 * 
 */
function verify_affiliate_coupon($vars) {
	if (isset($_SESSION['cart']['promo'])) {
		$promocode = $_SESSION['cart']['promo'];
		$data = select_query('tblpromotions', 'id', array("code"=>"$promocode"));
		if (mysql_num_rows($data)) {
			$row = mysql_fetch_array($data);
			$couponid = $row[0];
			$pdata = select_query('tblaffcoupons', 'aff_id', array("coupon"=>$couponid));
			if (mysql_num_rows($pdata)) {
				$prow = mysql_fetch_array($pdata);
				$affid = $prow[0];
				$_COOKIE['AffiliateID'] = $affid;
			}
		}
	}
}
add_hook("PreShoppingCartCheckout",1,"verify_affiliate_coupon","");
?>
