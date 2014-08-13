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

if ( ! defined( "WHMCS" ) ) die( "This file cannot be accessed directly" );

class AffiliateCoupons_Sidebar extends AffiliateCoupons {

	protected static $instance = NULL;

	function __construct() {

		define( "ADMINAREA", TRUE );

	}

	public function output( $vars ) {

		$modulelink = $vars[ 'modulelink' ];
		$version    = $vars[ 'version' ];

		$sidebar = '<span class="header">
						<img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" />
						 Affiliate Coupons
					</span>
					<ul class="menu">
				        <li><a target="_blank" href="' . $modulelink . '">GitHub</a></li>
				        <li><a target="_blank" href="' . $modulelink . '/issues">Report Issues</a></li>
				        <li><a target="_blank" href="https://smyl.es">Author Blog</a></li>
				        <li>Version: ' . $version . '</li>
				    </ul>';

		return $sidebar;
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( NULL == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}
