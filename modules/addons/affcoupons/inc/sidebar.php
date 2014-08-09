<?php
/**
 * @@title Sidebar
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

class AffiliateCoupons_Sidebar extends AffiliateCoupons {

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
	    $sidebar = '<span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" /> Example</span>
	<ul class="menu">
	        <li><a href="' . $modulelink . '">Demo Sidebar Content</a></li>
	        <li>Version: ' . parent::$version . '</li>
	    </ul>';
	    parent::debug($sidebar, 'sidebar output');
	    return $sidebar;
	}

}

?>
