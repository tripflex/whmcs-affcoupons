<?php
/**
 * @@title Client Area
 *
 * @package      @@package
 * @author       Myles McNamara (https://smyl.es)
 * @copyright    Copyright (c) Myles McNamara 2014
 * @license      GNU GPL v3+
 * @version      @@version
 * @updated      @@timestamp
 *
 * @link         @@link
 */

if ( ! defined( "WHMCS" ) ) die( "This file cannot be accessed directly" );

class AffiliateCoupons_ClientArea extends AffiliateCoupons {

	protected static $instance = NULL;

	private $aff_id;
	private $avail_coupon;
	private $clientid;
	private $coupon;
	private $landing;
	private $notice;
	private $notice_type;

	public function __construct() {

		define( "CLIENTAREA", TRUE );

		$this->clientid     = $this->get_clientid();
		$this->aff_id       = $this->get_aff_id();
		$this->landing      = $this->get_landing();
		$this->coupon       = $this->get_existing_coupons();
		$this->avail_coupon = $this->get_avail_coupon();

		parent::__construct();
		$this->check_cmd();
	}

	function check_cmd(){

		$cmd = filter_input( INPUT_POST, 'cmd', FILTER_SANITIZE_STRING );
		if ( ! $cmd ) $cmd = filter_input( INPUT_GET, 'cmd', FILTER_SANITIZE_STRING );

		if ( ! empty( $code ) ) {

			switch ( $cmd ) {

				case 'add':
					$this->add_new_coupon();
					break;

				case 'del':
					$this->remove_coupon();
					break;

			}

		}

	}

	/**
	 * Get logged in client id from session
	 *
	 * @return integer current logged in user session uid
	 */
	protected function get_clientid() {

		global $CONFIG;

		if ( isset( $_SESSION[ 'uid' ] ) ) return intval( $_SESSION[ 'uid' ] );

		return 0;
	}

	/**
	 * Get affiliate ID based off logged in client id from db
	 *
	 * @return integer affiliate id
	 */
	protected function get_aff_id() {

		$data = select_query( 'tblaffiliates', 'id', array( 'clientid' => $this->clientid ) );
		$r    = mysql_fetch_array( $data );

		if( ! empty( $r[ 0 ] ) ) return $r[ 0 ];

		return false;
	}

	protected function get_landing() {

		// Check if landing URL was sent in POST first, meaning the update button was pressed
		if ( isset( $_POST[ 'landing' ] ) ) {

			$landing_sanitized = filter_input( INPUT_POST, 'landing', FILTER_SANITIZE_URL );
			$landing_validated = filter_var( $landing_sanitized, FILTER_VALIDATE_URL );

			if ( $landing_validated ) {

				$landing = $landing_validated;
				$this->set_landing( $landing );
				$this->set_notice( "Landing page was updated" );

			} else {

				// Invalid URL used, lets get the entry saved in the db
				$landing = $this->get_landing_from_db();
				$this->set_notice( "There was an error updating the landing page", "danger" );

			}

		} else {

			// landing wasn't in POST, let's attempt to get it from the db
			$landing = $this->get_landing_from_db();

		}

		return $landing;
	}

	/**
	 * Set landing URL in db, or create new entry if does not exist
	 *
	 * @param string $landing sanitized and validated URL
	 */
	protected function set_landing( $landing ) {

		//  TODO: add another check to validate and sanitize
		$this->landing = $landing;
// Attempt to insert new landing entry in db, if existing entry update existing
//		$query = full_query("
//			INSERT INTO tblaffcouponslanding (aff_id, landing)
//			VALUES ('". $this->aff_id ."', '" . $landing . "')
//			ON DUPLICATE KEY
//			UPDATE landing='" . $landing . "'"
//		);
		$data = select_query( 'tblaffcouponslanding', 'landing', array( 'aff_id' => $this->aff_id ) );
		$r    = mysql_fetch_array( $data );

		if ( ! $r[ 'landing' ] ) {

			insert_query( "tblaffcouponslanding", array( "aff_id" => $this->aff_id, "landing" => $landing ) );

		} else {

			update_query( "tblaffcouponslanding", array( "landing" => $landing ), array( "aff_id" => $this->aff_id ) );

		}
	}

	protected function set_notice( $message, $type = NULL ) {

		$this->notice = $message;
		if ( $type ) $this->notice_type = $type;

	}

	protected function get_landing_from_db() {

		$data = select_query( 'tblaffcouponslanding', 'landing', array( 'aff_id' => $this->aff_id ) );
		$r    = mysql_fetch_array( $data );

		if ( empty( $r[ 'landing' ] ) ) return AC_WHMCSe::get_url();

		return $r[ 'landing' ];

	}

	protected function get_existing_coupons() {

		// Get Existing Coupons
		$coupon = array();
		$sql    = "SELECT p.code, p.type, p.value, p.uses, p.id
				FROM tblpromotions p, tblaffcoupons a
				WHERE a.aff_id = '" . $this->aff_id . "' AND a.coupon = p.id";

		$data   = mysql_query( $sql );

		while ( $r = mysql_fetch_array( $data ) ) {
			$coupon[ $r[ 4 ] ][ 'code' ]  = $r[ 0 ];
			$coupon[ $r[ 4 ] ][ 'type' ]  = $r[ 1 ];
			$coupon[ $r[ 4 ] ][ 'value' ] = $r[ 2 ];
			$coupon[ $r[ 4 ] ][ 'uses' ]  = $r[ 3 ];
			$coupon[ $r[ 4 ] ][ 'id' ]    = $r[ 4 ];
		}

		return $coupon;
	}

	protected function add_new_coupon() {

		//        TODO: sanitize and validate selected coupon
		$enc_type = $_POST[ 'type' ];
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
				$newcid = $r[ 0 ];
				insert_query( "tblaffcoupons", array( "coupon" => $newcid, "aff_id" => $this->aff_id ) );
				$this->set_notice( "Coupon $newcid added successfully." );
			} else {
				$this->set_notice( "Coupon already exists." );
			}
		} else {
			$this->set_notice( 'Invalid coupon code', 'danger' );
		}
	}

	protected function remove_coupon( $coupon_id = null ) {

		if( ! $coupon_id ) $coupon_id = filter_input( INPUT_GET, 'cid', FILTER_SANITIZE_NUMBER_INT );
		if( ! $coupon_id ) return false;

		$data = select_query( 'tblaffcoupons', 'aff_id', array( 'coupon' => $coupon_id, 'aff_id' => $this->aff_id ) );

		if ( mysql_num_rows( $data ) ) {

			delete_query( "tblaffcoupons", "coupon='{$coupon_id}'" );
			delete_query( "tblpromotions", "id='{$coupon_id}'" );
			$this->set_notice( "Coupon {$coupon_id} has been deleted." );

		} else {

			$this->set_notice( "You do not own Coupon {$coupon_id}", 'danger' );

		}
	}

	/**
	 * Get admin created promos that clients can use
	 *
	 * @return array Returns an array with the base64 encoded string values and label
	 */
	protected function get_avail_coupon() {

		$avail_coupon = array();
		$data         = select_query( "tblaffcouponsconf", "*", array() );

		while ( $val = mysql_fetch_array( $data ) ) {
			$avail_coupon[ $val[ 'id' ] ][ 'label' ]      = $val[ 'label' ];
			$avail_coupon[ $val[ 'id' ] ][ 'id' ]         = $val[ 'id' ];
		}

		return $avail_coupon;
	}

	public function head( $vars ) {

		$redirect = $this->check_redirect();
		if ( $redirect ) return "<script>window.location.replace('" . $redirect . "');</script>";

		$return_html = '<script src="' . $this->url . '/assets/js/clientarea.min.js"></script>';

		return $return_html;
	}

	protected function check_redirect() {

		$affid = filter_input( INPUT_GET, 'affid', FILTER_SANITIZE_NUMBER_INT );
		$r     = select_query( "tblaffcouponslanding", "landing", array( "aff_id" => $affid ) );
		$data  = mysql_fetch_array( $r );
		if ( ! empty( $data[ 'landing' ] ) ) return $data[ 'landing' ];

		return FALSE;
	}

	public function output( $vars ) {

		return array(
			'pagetitle'    => 'Affiliate Promo Codes',
			'breadcrumb'   => array( $this->get_index_page() . '?m=affcoupons' => 'Affiliate Promo Code' ),
			'templatefile' => 'clientaffcoupons',
			'requirelogin' => TRUE, # or false
			'forcessl'     => TRUE,
			'vars'         => array(
				'aff_id'       => $this->aff_id,
				'clientid'     => $this->clientid,
				'landing'      => $this->landing,
				'coupon'       => $this->coupon,
				'avail_coupon' => $this->avail_coupon,
				'notice'       => $this->notice,
				'notice_type'  => $this->notice_type,
				'index_page'   => $this->get_index_page()
			),
		);
	}

	function set_cookie( $vars ) {

		if ( empty( $vars[ 'promo' ] ) ) return false;

		$promocode = $vars[ 'promo' ];
		$data      = select_query( 'tblpromotions', 'id', array( "code" => $promocode ) );

		if ( mysql_num_rows( $data ) ) {

			$row      = mysql_fetch_array( $data );
			$couponid = $row[ 0 ];
			$pdata    = select_query( 'tblaffcoupons', 'aff_id', array( "coupon" => $couponid ) );

			if ( mysql_num_rows( $pdata ) ) {

				$prow        = mysql_fetch_array( $pdata );
				$affid       = $prow[ 0 ];
				$checkcookie = WHMCS_Cookie::get( "AffiliateID", TRUE );

				if ( $affid ) WHMCS_Cookie::set( 'AffiliateID', $affid, '3m' );

			}
		}

	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

?>
