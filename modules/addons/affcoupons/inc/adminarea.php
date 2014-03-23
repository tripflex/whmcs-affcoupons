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
 * @Last Modified time: 2014-03-22 21:20:11
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
		    $modulelink = $vars['modulelink'];
		    $version = $vars['version'];
		    $option1 = $vars['option1'];
		    $option2 = $vars['option2'];
		    $option3 = $vars['option3'];
		    $option4 = $vars['option4'];
		    $option5 = $vars['option5'];
		    $LANG = $vars['_lang'];

		    echo '<p>asdf'.$LANG['intro'].'</p>
			<p>'.$LANG['description'].'</p>
			<p>'.$LANG['documentation'].'</p>';
	}

	public function footer(){
		return '<b>Heya!</b>';
	}

}

?>