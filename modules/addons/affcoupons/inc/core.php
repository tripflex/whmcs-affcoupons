<?php
/**
 * WHMCS Affiliate Coupons
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-22 21:21:53
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

define( 'AC_ROOT', dirname( __FILE__ ) );

class AffiliateCoupons {
	protected static $instance = null;
	public static $name = "Affiliate Coupons";
	public static $description = "Allow affiliates to create custom promo codes from promotions spefied by Admin which are tied to their affiliate ID.";
	public static $version = '2.1.0';
	public static $author = "<a href=\"http://smyl.es\" target=\"_blank\">Myles McNamara</a>";
	public static $language = "english";
	public $debug = true;

	public function __construct() {
		require_once( dirname( __FILE__ ) . "/clientarea.php" );
		require_once( dirname( __FILE__ ) . "/adminarea.php" );
		require_once( dirname( __FILE__ ) . "/sidebar.php" );
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function admin(){
		return AffiliateCoupons_AdminArea::get_instance()->output($vars);
	}

	public static function sidebar(){
		return AffiliateCoupons_Sidebar::get_instance()->output($vars);
	}

	public static function client(){
		return AffiliateCoupons_ClientArea::get_instance()->output($vars);
	}

	public function activate(){
	    # Create Custom DB Table
	    $query = "CREATE TABLE IF NOT EXISTS `mod_affcoupons` (`id` INT( 1 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`affcoupons` TEXT NOT NULL )";
	    $result = full_query($query);
	    // Assumes success, need to add check to output correct response
	    return array(
	    	'status'=>'success',
	    	'description'=>'Affiliate coupons activated successfully!' . '<script>jQuery(document).ready(function(){ showConfig("affcoupons"); }); </script>'
	    	);
    	// return array('status'=>'error','description'=>'You can use the error status return to indicate there was a problem activating the module');
    	// return array('status'=>'info','description'=>'You can use the info status return to display a message to the user');
	}

	public function deactivate(){
	    // $query = "DROP TABLE `mod_affcoupons`";
	    $result = full_query($query);

	    # Return Result
	    return array('status'=>'success','description'=>'Affiliate Coupons deactivated successfully.');
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
		return array(
				"option1" => array ("FriendlyName" => "Option1", "Type" => "text", "Size" => "25", "Description" => "Textbox", "Default" => "Example", )
			);
	}

}

?>