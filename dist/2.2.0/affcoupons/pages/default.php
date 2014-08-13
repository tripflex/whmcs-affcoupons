<?php
/**
 * WHMCS Affiliate Coupons Admin Config Page
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1a
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date       :   2014-03-19 21:42:52
 * @Last       Modified by:   Myles McNamara
 * @Last       Modified time: 2014-03-23 01:43:07
 */

if ( ! defined( "WHMCS" ) ) {
	die( "This file cannot be accessed directly" );
}

if ( isset( $_REQUEST[ 'cmd' ] ) ) {
	switch ( $_REQUEST[ 'cmd' ] ) {
		case "del":
			$id = $_REQUEST[ 'id' ];
			delete_query( "tblaffcouponsconf", "id='$id'" );
			break;
		case "add":
			$label     = $_POST[ 'label' ];
			$type      = $_POST[ 'type' ];
			$recurring = $_POST[ 'recurring' ];
			$value     = $_POST[ 'value' ];
			$cyclestmp = $_POST[ 'cycles' ];
			foreach ( $cyclestmp as $v ) {
				if ( $acycles ) {
					$acycles = $acycles . ",$v";
				} else {
					$acycles = $v;
				}
			}
			$appliestotmp = $_POST[ 'appliesto' ];
			foreach ( $appliestotmp as $v ) {
				if ( $aappliesto ) {
					$aappliesto = $aappliesto . ",$v";
				} else {
					$aappliesto = $v;
				}
			}
			$expirationdate = $_POST[ 'expirationdate' ];
			$maxuses        = $_POST[ 'maxuses' ];
			$applyonce      = $_POST[ 'applyonce' ];
			$newsignups     = $_POST[ 'newsignups' ];
			$existingclient = $_POST[ 'existingclient' ];
			$r              = insert_query( "tblaffcouponsconf",
			                                array(
				                                "type"           => $type,
				                                "recurring"      => $recurring,
				                                "value"          => $value,
				                                "cycles"         => $acycles,
				                                "appliesto"      => $aappliesto,
				                                "expirationdate" => $expirationdate,
				                                "maxuses"        => $maxuses,
				                                "applyonce"      => $applyonce,
				                                "newsignups"     => $newsignups,
				                                "existingclient" => $existingclient,
				                                "label"          => $label
			                                ) );
			break;
	}
}
$products = array();
$result   = select_query( "tblproducts",
                          "tblproducts.id,tblproducts.name,tblproductgroups.name AS groupname",
                          "", "tblproductgroups`.`order` ASC,`tblproducts`.`order` ASC,`name", "ASC", "",
                          "tblproductgroups ON tblproducts.gid=tblproductgroups.id" );
while ( $data = mysql_fetch_array( $result ) ) {
	$pid                         = $data[ "id" ];
	$group                       = $data[ "groupname" ];
	$prodname                    = $data[ "name" ];
	$products[ $pid ][ 'group' ] = $group;
	$products[ $pid ][ 'name' ]  = $prodname;
}
print "<style type=\"text/css\">
		#AddForm label.error {
			background:url(\"images/icons/accessdenied.png\") no-repeat 0px 0px;
			padding-left: 16px;
			padding-bottom: 2px;
			font-weight: bold;
			color: #EA5200;
		}
		</style>
        <script src=\"https://ajax.aspnetcdn.com/ajax/jquery.validate/1.5.5/jquery.validate.min.js\" type=\"text/javascript\"></script>
		<script type=\"text/javascript\">
		$().ready(function() {
			$(\"#AddForm\").validate({
				rules: {
					label: { required: true },
					value: { required: true, number: true },
					\"appliesto[]\": { required: true },
					maxuses: { required: true, number: true }
				},
				errorPlacement: function(error, element) {
					error.appendTo( element.parent().next() );
				},
				success: function(label) {
					label.html(\" \").addClass(\"error\");
				},
				submitHandler: function() {
					$(form).submit();
				}
			});
			$(\".affdatepick\").datepicker({
				dateFormat: \"yy-mm-dd\",
				showOn: \"button\",
				buttonImage: \"images/showcalendar.gif\",
				buttonImageOnly: true,
				showButtonPanel: true
			});
		});
		</script>
		<h3>Add New Coupon Parameters</h3>
		<form id=\"AddForm\" method=\"POST\" action=\"$modulelink&cmd=add\">
		<table class=\"form\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\" width=\"100%\">
		<tr><td width=\"25%\" class=\"fieldlabel\">Label:</td><td class=\"fieldarea\"><input type=\"text\" name=\"label\" size=\"25\">(i.e. \"25% Recurring\")</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Type:</td><td class=\"fieldarea\">
		<select name=\"type\">
		<option value=\"Percentage\">Percentage</option>
		<option value=\"Fixed Amount\">Fixed Amount</option>
		<option value=\"Free Setup\">Free Setup</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Value:</td><td class=\"fieldarea\"><input type=\"text\" name=\"value\" size=\"25\"></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Recurring:</td><td class=\"fieldarea\">
		<select name=\"recurring\">
		<option value=\"0\">No</option>
		<option value=\"1\">Yes</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Valid Cycles</td><td class=\"fieldarea\">
		<select multiple name=\"cycles[]\" size=\"5\">
		<option value=\"\" selected>Any Payment Term</option>
		<option value=\"One Time\">One Time</option>
		<option value=\"Monthly\">Monthly</option>
		<option value=\"Quarterly\">Quarterly</option>
		<option value=\"Semi-Annually\">Semi-Annually</option>
		<option value=\"Annually\">Annually</option>
		<option value=\"Biennially\">Biennially</option>
		<option value=\"Triennially\">Triennially</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Applies To:</td><td class=\"fieldarea\">
		<select multiple name=\"appliesto[]\" size=\"5\">";
foreach ( $products as $k => $v ) {
	$pid      = $k;
	$group    = $v[ 'group' ];
	$prodname = $v[ 'name' ];
	print "<option value=\"$pid\">$group - $prodname</option>";
}
print "</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Expiration:</td><td class=\"fieldarea\"><input type=\"text\" name=\"expirationdate\" class=\"affdatepick\"> (leave blank for none)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Max Uses:</td><td class=\"fieldarea\"><input type=\"text\" name=\"maxuses\" size=\"25\" value=\"0\"> (0 for unlimited)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Apply Once:</td><td class=\"fieldarea\">
		<select name=\"applyonce\">
		<option value=\"1\">Yes</option>
		<option value=\"0\">No</option>
		</select> (Apply only once per order)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">New Signups Only:</td><td class=\"fieldarea\">
		<select name=\"newsignups\">
		<option value=\"1\">Yes</option>
		<option value=\"0\">No</option>
		</select> (Apply to new signups only)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Existing Client Only:</td><td class=\"fieldarea\">
		<select name=\"existingclient\">
		<option value=\"0\">No</option>
		<option value=\"1\">Yes</option>
		</select> (Apply to existing clients only) </td><td></td></tr>
		<tr><td class=\"fieldlabel\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Add\" /><input type=\"reset\" value=\"Clear\"></td><td></td><td></td></tr>
		</table></form>";
print "<h3>Current Coupon Parameters</h3>
		<div class=\"tablebg\">
		<table class=\"datatable\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\">
		<tr><th>&nbsp;</th><th>Label</th><th>Type</th><th>Value</th><th>Recurring</th><th>Cycles</th><th>Applies To</th><th>Expiration</th><th>Max Uses</th>
		<th>Apply Once</th><th>New Signups</th><th>Existing Client</th></tr>";
$data = select_query( "tblaffcouponsconf", "*", array() );
while ( $r = mysql_fetch_array( $data ) ) {
	$id    = $r[ 'id' ];
	$label = $r[ 'label' ];
	$type  = $r[ 'type' ];
	$value = $r[ 'value' ];
	switch ( $r[ 'recurring' ] ) {
		case "1" :
			$recurring = "Yes";
			break;
		case "0" :
			$recurring = "No";
			break;
		default :
			$recurring = "No";
			break;
	}
	if ( ! $r[ 'cycles' ] ) {
		$cycles = array( "Any Payment Term" );
	} else {
		$cycles = explode( ",", $r[ 'cycles' ] );
	}
	$appliestotmp = explode( ",", $r[ 'appliesto' ] );
	$appliesto    = array();
	foreach ( $appliestotmp as $v ) {
		$prodname     = $products[ $v ][ 'name' ];
		$group        = $products[ $v ][ 'group' ];
		$appliesto[ ] = "$group - $prodname";
	}
	if ( $r[ 'expirationdate' ] ) {
		$expirationdate = $r[ 'expirationdate' ];
	} else {
		$expirationdate = "No Expire Date";
	}
	$maxuses = $r[ 'maxuses' ];
	switch ( $r[ 'applyonce' ] ) {
		case "1" :
			$applyonce = "Yes";
			break;
		case "0" :
			$applyonce = "No";
			break;
		default :
			$applyonce = "Yes";
			break;
	}
	switch ( $r[ 'newsignups' ] ) {
		case "1" :
			$newsignups = "Yes";
			break;
		case "0" :
			$newsignups = "No";
			break;
		default :
			$newsignups = "Yes";
			break;
	}
	switch ( $r[ 'existingclient' ] ) {
		case "1" :
			$existingclient = "Yes";
			break;
		case "0" :
			$existingclient = "No";
			break;
		default:
			$existingclient = "No";
			break;
	}
	print "<tr><td><a href=\"$modulelink&cmd=del&id=$id\"><img src=\"images/icons/delete.png\"></a></td>
			<td width=\"100\">$label</td><td width=\"100\">$type</td><td>$value</td><td>$recurring</td><td><select>";
	foreach ( $cycles as $v ) {
		print "<option>$v</option>";
	}
	print "</select></td><td><select>";
	foreach ( $appliesto as $v ) {
		print "<option>$v</option>";
	}
	print "</select></td><td width=\"100\">$expirationdate</td><td>$maxuses</td><td>$applyonce</td><td>$newsignups</td>
			<td>$existingclient</td></tr>";

}
print "</table><br /></div>";