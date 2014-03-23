<?php
/**
 * WHMCS Affiliate Coupons Admin Class
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-22 21:19:58
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

require_once( dirname( __FILE__ ) . "/core.php" );

class AffiliateCoupons_Hooks extends AffiliateCoupons {

	protected static $instance = null;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function __construct(){

	}

	public function generate(){
		add_hook("PreCalculateCartTotals",1,"set_affiliate_cookie");
		add_hook("AdminAreaHeaderOutput",1,"admin_footer");
	}

	public function set_affiliate_cookie($vars) {
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

	public function admin_footer(){
		return AffiliateCoupons_AdminArea::footer();
	}
}

?>