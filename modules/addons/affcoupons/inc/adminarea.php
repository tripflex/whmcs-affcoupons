<?php
/**
 * WHMCS Affiliate Coupons Admin Class
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1a
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-23 16:15:43
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

define( 'AC_ROOT', dirname( __FILE__ ) );

class AffiliateCoupons_AdminArea extends AffiliateCoupons {

	protected static $instance = null;

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function __construct(){
		define("ADMINAREA", true);
	}

	public function output($vars){
//        required for config.php file
        $modulelink = $vars['modulelink'];
        if(self::check_for_update()){
            echo '<div class="infobox"><strong>New Version Available!</strong><br>There is a new version available, you should upgrade ASAP!<br><a href="https://github.com/tripflex/whmcs-affcoupons" target="_blank">Go here for latest release</a></div>';
        }
		if($_GET['page'] === 'test'):
			include_once( dirname( __FILE__ ) . "/pages/test.php" );
		else:
			require_once( dirname( __FILE__ ) . "/pages/config.php" );

		endif;
	}

    public function check_for_update(){
        $url = 'https://github.com/tripflex/whmcs-affcoupons/raw/master/release';
        $release = file_get_contents($url, "r");
        if (intval($release) > intval(parent::$version)){
            return true;
        } else {
            return false;
        }
    }

	public function footer($vars){
		return '';
	}
	public function head($vars){
		return '';
	}
	public function header($vars){
		return '';
	}

}

?>