<?php
/**
 * WHMCS Affiliate Coupons Client Area
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1.2
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date       2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-06-07 18:43:28
 */

if ( ! defined( "WHMCS" ) ) die( "This file cannot be accessed directly" );

define( 'AC_ROOT', dirname( __FILE__ ) );

class AffiliateCoupons_ClientArea extends AffiliateCoupons {

	protected static $instance = NULL;
	protected static $aff_id;
	protected static $clientid;
	protected static $landing;
	protected static $coupon;
	protected static $avail_coupon;
	protected static $notice;
	protected static $notice_type;

	public static function get_instance () {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct () {

		define( "CLIENTAREA", true );
		self::$clientid     = self::get_clientid();
		self::$aff_id       = self::get_aff_id();
		self::$landing      = self::get_landing();
		self::$coupon       = self::get_existing_coupons();
		self::$avail_coupon = self::get_avail_coupon();
	}

	public function head ( $vars ) {

		$redirect = self::check_redirect();
		if ( $redirect ) {
			$return_html = "<script>window.location.replace('" . self::$landing . "');</script>";
		} else {
			$return_html = '<script src="' . AC_WHMCSe::get_module_url( 'affcoupons' ) . '/inc/js/affiliates.js"></script>';
			$return_html .= '<input type="hidden" id="index_page" value="' . parent::$index_page . '">';
			$return_html .= '<input type="hidden" id="script_name" value="' . $vars['SCRIPT_NAME'] . '">';
		}

		return $return_html;
	}

	protected function check_redirect () {

		//        Coming soon, should no longer require replacing aff.php file
		return false;
	}

	public function output ( $vars ) {

		return array(
			'pagetitle'    => 'Affiliate Promo Codes',
			'breadcrumb'   => array( parent::$index_page . '?m=affcoupons' => 'Affiliate Promo Code' ),
			'templatefile' => 'clientaffcoupons',
			'requirelogin' => true, # or false
			'forcessl'     => true,
			'vars'         => array(
				'aff_id'       => self::$aff_id,
				'clientid'     => self::$clientid,
				'landing'      => self::$landing,
				'coupon'       => self::$coupon,
				'avail_coupon' => self::$avail_coupon,
				'notice'       => self::$notice,
				'notice_type'  => self::$notice_type,
				'index_page'   => parent::$index_page
			),
		);
	}

	/**
	 * Get admin created promos that clients can use
	 * @return array Returns an array with the base64 encoded string values and label
	 */
	protected function get_avail_coupon () {

		$avail_coupon = array();
		$data         = select_query( "tblaffcouponsconf", "*", array() );
		while ( $val = mysql_fetch_array( $data ) ) {
			$type                                     = $val['type'];
			$recurring                                = $val['recurring'];
			$value                                    = $val['value'];
			$cycles                                   = $val['cycles'];
			$appliesto                                = $val['appliesto'];
			$expirationdate                           = $val['expirationdate'];
			$maxuses                                  = $val['maxuses'];
			$applyonce                                = $val['applyonce'];
			$newsignups                               = $val['newsignups'];
			$existingclient                           = $val['existingclient'];
			$label                                    = $val['label'];
			$string                                   = "$type@$recurring@$value@$cycles@$appliesto@$expirationdate@$maxuses@$applyonce@$newsignups@$existingclient";
			$enc_string                               = base64_encode( $string );
			$avail_coupon[ $val['id'] ]['label']      = $label;
			$avail_coupon[ $val['id'] ]['enc_string'] = $enc_string;
		}

		return $avail_coupon;
	}

	/**
	 * Get affiliate ID based off logged in client id from db
	 * @return integer affiliate id
	 */
	protected function get_aff_id () {

		$data = select_query( 'tblaffiliates', 'id', array( 'clientid' => self::$clientid ) );
		$r    = mysql_fetch_array( $data );

		return $r[0];
	}

	/**
	 * Get logged in client id from session
	 * @return integer current logged in user session uid
	 */
	protected function get_clientid () {

		global $CONFIG;

		if ( isset( $_SESSION['uid'] ) ) {
			return intval( $_SESSION['uid'] );
		} else {
			return 0;
		}
	}

	/**
	 * Set landing URL in db, or create new entry if does not exist
	 *
	 * @param string $landing sanitized and validated URL
	 */
	protected function set_landing ( $landing ) {

		//  TODO: add another check to validate and sanitize
		self::$landing = $landing;
		//		// Attempt to insert new landing entry in db, if existing entry update existing
		//		$query = mysql_query('INSERT INTO tblaffcouponslanding (aff_id, landing)
		//								VALUES ('. self::$aff_id .', ' . $landing .')
		//								ON DUPLICATE KEY
		//									UPDATE landing=\'$landing\'
		//				');
		$data = select_query( 'tblaffcouponslanding', 'landing', array( 'aff_id' => self::$aff_id ) );
		$r    = mysql_fetch_array( $data );
		if ( ! $r['landing'] ) {
			insert_query( "tblaffcouponslanding", array( "aff_id" => self::$aff_id, "landing" => $landing ) );
		} else {
			update_query( "tblaffcouponslanding", array( "landing" => $landing ), array( "aff_id" => self::$aff_id ) );
		}
	}

	protected function get_landing_from_db () {

		$data = select_query( 'tblaffcouponslanding', 'landing', array( 'aff_id' => self::$aff_id ) );
		$r    = mysql_fetch_array( $data );
		if ( ! $r['landing'] ) {
			$landing = AC_WHMCSe::get_url();
		} else {
			$landing = $r['landing'];
		}

		return $landing;
	}

	protected function get_landing () {

		// Check if landing URL was sent in POST first, meaning the update button was pressed
		if ( isset( $_POST['landing'] ) ) {
			$landing_sanitized = filter_input( INPUT_POST, 'landing', FILTER_SANITIZE_URL );
			$landing_validated = filter_var( $landing_sanitized, FILTER_VALIDATE_URL );
			if ( $landing_validated ) {
				$landing = $landing_validated;
				self::set_landing( $landing );
				self::set_notice( "Landing page was updated" );
			} else {
				//                Invalid URL used, lets get the entry saved in the db
				$landing = self::get_landing_from_db();
				self::set_notice( "There was an error updating the landing page", "danger" );
			}
		} else {
			// landing wasn't in POST, let's attempt to get it from the db
			$landing = self::get_landing_from_db();
		}

		return $landing;
	}

	protected function get_existing_coupons () {

		if ( isset( $_POST['cmd'] ) && $_POST['cmd'] === 'add' ) self::add_new_coupon();

		if ( isset( $_GET['cmd'] ) && $_GET['cmd'] === 'del' ) self::remove_coupon();

		$aff_id = self::$aff_id;
		// Get Existing Coupons
		$coupon = array();
		$sql    = "SELECT p.code, p.type, p.value, p.uses, p.id
				FROM tblpromotions p, tblaffcoupons a
				WHERE a.aff_id = '$aff_id' AND a.coupon = p.id";
		$data   = mysql_query( $sql );
		while ( $r = mysql_fetch_array( $data ) ) {
			$coupon[ $r[4] ]['code']  = $r[0];
			$coupon[ $r[4] ]['type']  = $r[1];
			$coupon[ $r[4] ]['value'] = $r[2];
			$coupon[ $r[4] ]['uses']  = $r[3];
			$coupon[ $r[4] ]['id']    = $r[4];
		}

		return $coupon;
	}

	protected function add_new_coupon () {

		//        TODO: sanitize and validate selected coupon
		$enc_type = $_POST['type'];
		$code     = filter_input( INPUT_POST, 'code', FILTER_SANITIZE_STRING );
		if ( $code ) {
			$dec_type = base64_decode( $enc_type );
			list( $atype, $arecurring, $avalue, $acycles, $aappliesto, $aexpirationdate, $amaxuses, $aapplyonce, $anewsignups, $aexistingclient ) = explode( "@", $dec_type );
			$data = select_query( 'tblpromotions', 'id', array( 'code' => $code ) );
			if ( ! mysql_num_rows( $data ) ) {
				insert_query( "tblpromotions", array(
						"code"           => $code,
						"type"           => $atype,
						"recurring"      => $arecurring,
						"value"          => $avalue,
						"cycles"         => $acycles,
						"appliesto"      => $aappliesto,
						"expirationdate" => $aexpirationdate,
						"maxuses"        => $amaxuses,
						"applyonce"      => $aapplyonce,
						"newsignups"     => $anewsignups,
						"existingclient" => $aexistingclient
					) );
				$data   = select_query( 'tblpromotions', 'id', array( "code" => $code ) );
				$r      = mysql_fetch_array( $data );
				$newcid = $r[0];
				insert_query( "tblaffcoupons", array( "coupon" => $newcid, "aff_id" => self::$aff_id ) );
				self::set_notice( "Coupon $newcid added successfully." );
			} else {
				self::set_notice( "Coupon already exists." );
			}
		} else {
			self::set_notice( 'Invalid coupon code', 'danger' );
		}
	}

	protected function remove_coupon () {

		$coupon_id = filter_input( INPUT_GET, 'cid', FILTER_SANITIZE_NUMBER_INT );
		if ( $coupon_id ) {
			$data = select_query( 'tblaffcoupons', 'aff_id', array( 'coupon' => $coupon_id, 'aff_id' => self::$aff_id ) );
			if ( mysql_num_rows( $data ) ) {
				delete_query( "tblaffcoupons", "coupon='$coupon_id'" );
				delete_query( "tblpromotions", "id='$coupon_id'" );
				self::set_notice( "Coupon $coupon_id has been deleted." );
			} else {
				self::set_notice( "You do not own Coupon $coupon_id", 'danger' );
			}
		} else {
			self::set_notice( "Error removing coupon", 'danger' );
		}
	}

	protected function set_notice ( $message, $type = NULL ) {

		self::$notice = $message;
		if ( $type ) {
			self::$notice_type = $type;
		}
	}
}

?>
