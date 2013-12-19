<?php
/*
	Affiliate Coupons 1.2 - WHMCS Module
	Written by: Frank Laszlo <frank@asmallorange.com>
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
				$_COOKIE['WHMCSAffiliateID'] = $affid;
			}
		}
	}
}
add_hook("PreShoppingCartCheckout",1,"verify_affiliate_coupon","");
?>
