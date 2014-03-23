<?php
/**
 * WHMCS Affiliate Coupons Client Area
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-23 15:40:01
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

define( 'AC_ROOT', dirname( __FILE__ ) );

class AffiliateCoupons_ClientArea extends AffiliateCoupons {

	protected static $instance = null;
	protected static $aff_id;
	protected static $clientid;
	protected static $landing;
	protected static $coupon;
	protected static $avail_coupon;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){
		define("CLIENTAREA", true);
		self::$clientid = self::get_clientid();
		self::$aff_id = self::get_aff_id();
		self::$landing = self::get_landing();
		self::$coupon = self::get_existing_coupons();
		self::$avail_coupon = self::get_avail_coupon();
	}

	public function head($vars){

		return '<script src="' . WHMCSe::get_module_url('affcoupons') . '/inc/js/affiliates.js"></script>';
	}

	public function output($vars){
		return array(
	        'pagetitle' => 'Affiliate Promo Codes',
	        'breadcrumb' => array('index.php?m=affcoupons'=>'Affiliate Promo Code'),
	        'templatefile' => 'clientaffcoupons',
	        'requirelogin' => true, # or false
	        'vars' => array(
	            'aff_id' => self::$aff_id,
	            'clientid' => self::$clientid,
	            'landing' => self::$landing,
	            'coupon' => self::$coupon,
	            'avail_coupon' => self::$avail_coupon
        			),
			);
	}

	protected function get_avail_coupon(){
		$avail_coupon = array();
		$data = select_query("tblaffcouponsconf", "*", array());
		while ($val = mysql_fetch_array($data)) {
			$type = $val['type'];
			$recurring = $val['recurring'];
			$value = $val['value'];
			$cycles = $val['cycles'];
			$appliesto = $val['appliesto'];
			$expirationdate = $val['expirationdate'];
			$maxuses = $val['maxuses'];
			$applyonce = $val['applyonce'];
			$newsignups = $val['newsignups'];
			$existingclient = $val['existingclient'];
			$label = $val['label'];
			$string = "$type@$recurring@$value@$cycles@$appliesto@$expirationdate@$maxuses@$applyonce@$newsignups@$existingclient";
			$enc_string = base64_encode($string);
			$avail_coupon[$val['id']]['label'] = $label;
			$avail_coupon[$val['id']]['enc_string'] = $enc_string;
		}

		return $avail_coupon;
	}

	protected function get_aff_id(){
		$data = select_query('tblaffiliates', 'id', array('clientid'=>self::$clientid));
		$r = mysql_fetch_array($data);
		return $r[0];
	}

	protected function get_clientid(){
		global $CONFIG;

		if (isset($_SESSION['uid'])) {
			return intval($_SESSION['uid']);
		} else {
			return 0;
		}
	}

	protected function get_landing(){
		// Get Landing Page
		$data = select_query('tblaffcouponslanding', 'landing', array('aff_id'=>self::$aff_id));
		$r = mysql_fetch_array($data);
		if (!$r['landing']) {
			$landing = WHMCSe::get_url();
			$nolanding = 1;
		} else {
			$landing = $r['landing'];
			$nolanding = 0;
		}
		if (isset($_POST['landing'])) {
			$landing = $_POST['landing'];
		}
		return $landing;
	}

	protected function get_existing_coupons(){
		// Get Existing Coupons
		$coupon = array();
		$sql = "SELECT p.code, p.type, p.value, p.uses, p.id
				FROM tblpromotions p, tblaffcoupons a
				WHERE a.aff_id = 'self::$aff_id' AND a.coupon = p.id";
		$data = mysql_query($sql);
		while ($r = mysql_fetch_array($data)) {
			$coupon[$r[4]]['code'] = $r[0];
			$coupon[$r[4]]['type'] = $r[1];
			$coupon[$r[4]]['value'] = $r[2];
			$coupon[$r[4]]['uses'] = $r[3];
			$coupon[$r[4]]['id'] = $r[4];
		}

		return $coupon;
	}
}

?>