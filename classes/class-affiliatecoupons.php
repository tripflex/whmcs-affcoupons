<?php
/**
 * @@title
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

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

define( 'AC_ROOT', dirname( __FILE__ ) );

require_once( AC_ROOT . "/classes/class-affiliatecoupons-clientarea.php" );
require_once( AC_ROOT . "/classes/class-affiliatecoupons-adminarea.php" );
require_once( AC_ROOT . "/classes/class-affiliatecoupons-sidebar.php" );
require_once( AC_ROOT . "/classes/class-affiliatecoupons-whmcse.php" );

class AffiliateCoupons {
	const version_url = 'https://github.com/tripflex/whmcs-affcoupons/raw/master/release';
	protected static $instance = null;
	public static $name = "Affiliate Coupons";
	public static $description = "Allow affiliates to create custom promo codes from promotions specified by Admin which are tied to their affiliate ID.";
	public static $version = '2.1.2';
	public static $author = "<a href=\"http://smyl.es\" target=\"_blank\">Myles McNamara</a>";
	public static $language = "english";
    public static $index_page = "index.php";
	public $debug = true;

	public function __construct() {
		AC_WHMCSe::get_instance();
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function AdminArea(){
		return AffiliateCoupons_AdminArea::get_instance();
	}

	public static function ClientArea(){
		return AffiliateCoupons_ClientArea::get_instance();
	}

	public static function Sidebar(){
		return AffiliateCoupons_Sidebar::get_instance();
	}

	public static function OutputAdmin($vars){
		return self::AdminArea()->output($vars);
	}

	public static function OutputSidebar($vars){
		return self::Sidebar()->output($vars);
	}

	public static function OutputClient($vars){
		return self::ClientArea()->output($vars);
	}

	public static function activate(){
	    $query 	= array();
		$errors = array(); //taking care of affcoupon errors

		# Create Custom DB Tables for handling affcoupons
		$query['tblaffcoupons'] = "CREATE TABLE IF NOT EXISTS `tblaffcoupons` (
				`id` int(11) NOT NULL auto_increment,
				`coupon` int(11) NOT NULL,
				`aff_id` int(11) NOT NULL,
				PRIMARY KEY  (`id`));";
		$query['tblaffcouponslanding'] = "CREATE TABLE IF NOT EXISTS `tblaffcouponslanding` (
				`aff_id` int(11) NOT NULL,
				`landing` varchar(128) NOT NULL,
				PRIMARY KEY  (`aff_id`));";
		$query['tblaffcouponsconf'] = "CREATE TABLE IF NOT EXISTS `tblaffcouponsconf` (
				`id` INT(11) NOT NULL auto_increment,
				`type` VARCHAR( 16 ) NOT NULL,
				`recurring` BOOL NOT NULL,
				`value` INT(11) NOT NULL,
				`cycles` VARCHAR( 1024 ) NOT NULL,
				`appliesto` VARCHAR( 1024 ) NOT NULL,
				`expirationdate` VARCHAR( 12 ) NOT NULL,
				`maxuses` INT(11) NOT NULL,
				`applyonce` BOOL NOT NULL,
				`newsignups` BOOL NOT NULL,
				`existingclient` BOOL NOT NULL,
				`label` VARCHAR( 1024 ),
				PRIMARY KEY (`id`));";
		
		foreach ($query as $table => $q) {
			$result = full_query($q);

			if( !$result ) {
				$errors[$table] = false;
			}	
		}

	    return array(
	    	'status'=> (empty($errors) ? 'success' : 'error' ),
	    	'description'=>( empty($errors) ? 'Affiliate coupons activated successfully!  Dont forget to click on CONFIGURE and set permissions!' : "Errors creating tables: ".implode(array_keys($errors), ', ').".")
	    	);
	}

	public static function deactivate(){
	    // $query = "DROP TABLE `mod_affcoupons`";
//	    $result = full_query($query);

	    # Return Result
	    return array('status'=>'success','description'=>'Affiliate Coupons deactivated successfully.  Database values were NOT removed.');
	    // return array('status'=>'error','description'=>'If an error occurs you can return an error message for display here');
	    // return array('status'=>'info','description'=>'If you want to give an info message to a user you can return it here');
	}

	public function upgrade($vars){

    	$version = $vars['version'];

	    // # Run SQL Updates for V1.0 to V1.1
	    // if ($version < 1.1) {
	    //     $query = "ALTER `mod_addonexample` ADD `demo2` TEXT NOT NULL ";
	    //     $result = full_query($query);
	    // }

	    // # Run SQL Updates for V1.1 to V1.2
	    // if ($version < 1.2) {
	    //     $query = "ALTER `mod_addonexample` ADD `demo3` TEXT NOT NULL ";
	    //     $result = full_query($query);
	    // }
	}

	public function checkDebug(){
		return $this->debug;
	}

	public function setDebug($value = true){
		$this->$debug = $value;
	}

	public function debug($request, $action = 'debug', $response = '', $arraydata = "", $replacevars = array()){

		if ($this->checkDebug()) {
			logModuleCall('AffiliateCoupons', $action, $request, $response, $arraydata, $replacevars);
		}
	}

	public static function config(){
		return array(
			'name' => self::$name,
			'description' => self::$description,
			'version' => self::$version,
			'author' => self::$author,
			'language' => self::$language,
			'fields' => self::configFields()
			);
	}

	protected static function configFields(){
		// return array(
		// 		"option1" => array ("FriendlyName" => "Option1", "Type" => "text", "Size" => "25", "Description" => "Textbox", "Default" => "Example", )
		// 	);
		return null;
	}

}
