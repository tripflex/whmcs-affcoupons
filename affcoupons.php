<?php
/*
	Affiliate Coupons 1.2 - WHMCS Module
	Written by: Frank Laszlo <frank@asmallorange.com>
*/

global $CONFIG;

if (isset($_SESSION['uid'])) {
	$clientid=$_SESSION['uid'];
} else {
	$clientid="0";
}

// Get Affiliate ID 
$data = select_query('tblaffiliates', 'id', array('clientid'=>$clientid));
$r = mysql_fetch_array($data);
$aff_id = $r[0];

// Get Landing Page
$data = select_query('tblaffcouponslanding', 'landing', array('aff_id'=>$aff_id));
$r = mysql_fetch_array($data);
if (!$r['landing']) {
	$landing = $CONFIG['Domain'];
	$nolanding = 1;
} else {
	$landing = $r['landing'];
	$nolanding = 0;
}
if (isset($_POST['landing'])) {
	$landing = $_POST['landing'];
}

print "<!--";
print "aff_id = $aff_id landing=$landing";
print "-->";
if ((isset($_REQUEST['cmd'])) || (isset($_POST['cmd']))) {
	if (isset($_REQUEST['cmd'])) { 
		$cmd = $_REQUEST['cmd'];
	} elseif (isset($_POST['cmd'])) {
		$cmd = $_POST['cmd'];
	}
	if ($cmd == "del") {
		$coupon_id = $_REQUEST['cid'];
		$data = select_query('tblaffcoupons', 'aff_id', array('coupon'=>$coupon_id, 'aff_id'=>$aff_id));
		if (mysql_num_rows($data)) {
			delete_query("tblaffcoupons", "coupon='$coupon_id'");
			delete_query("tblpromotions", "id='$coupon_id'");
			$msg = "Coupon $coupon_id has been deleted.";
		} else {
			$msg = "You do not own Coupon $coupon_id";
		}
	} elseif ($cmd == "add") {
		$enc_type = $_POST['type'];
		$code = $_POST['code'];
		$dec_type = base64_decode($enc_type);
		list($atype, $arecurring, $avalue, $acycles, $aappliesto, $aexpirationdate, $amaxuses, $aapplyonce, $anewsignups, $aexistingclient) = explode("@", $dec_type);
		$data = select_query('tblpromotions', 'id', array('code'=>$code));
		if (!mysql_num_rows($data)) {
			insert_query("tblpromotions", 
							array("code"=>$code, "type"=>$atype, "recurring"=>$arecurring, 
									"value"=>$avalue, "cycles"=>$acycles, "appliesto"=>$aappliesto,
									"expirationdate"=>$aexpirationdate, "maxuses"=>$amaxuses, "applyonce"=>$aapplyonce,
									"newsignups"=>$anewsignups, "existingclient"=>$aexistingclient));
			$data = select_query('tblpromotions', 'id', array("code"=>$code));
			$r = mysql_fetch_array($data);
			$newcid = $r[0];
			insert_query("tblaffcoupons", array("coupon"=>$newcid, "aff_id"=>$aff_id));
			$msg = "Coupon $newcid added successfully.";
		} else {
			$msg = "Coupon already exists.";
		}
	} elseif ($cmd == "modlanding") {
		$newlanding = $_POST['landing'];
		if ($nolanding) {
			insert_query("tblaffcouponslanding", array("aff_id"=>$aff_id, "landing"=>$newlanding));
		} else {
			update_query("tblaffcouponslanding", array("landing"=>$newlanding), array("aff_id"=>$aff_id));
		}
		if ($data) {
			$msg2 = "Landing page has been modified.";
		} else {
			$msg = "Error modifying landing page.";
		}
	}
}

print "<div class=\"page-header\"><div class=\"styled_title\"><h2>Landing Page</h2></div></div>
		<div class=\"styled_title\"><h3>$msg2</h3></div>
		<form action=\"affiliates.php\" method=\"POST\" name=\"landingpage\">
		<input type=\"hidden\" name=\"cmd\" value=\"modlanding\">
		<div class=\"well textcenter\">
			<input type=\"text\" name=\"landing\" id=\"landing\" value=\"$landing\" class=\"bigfield\">
			<div class=\"internalpaadding\">
				<p>This option will control where your referrals will be redirected after visiting your referral link.</p>
				<input type=\"submit\" name=\"Submit\" value=\"Update\" class=\"btn btn-primary btn-large\"></form>
			</div>
		</div>
		";
// Get Existing Coupons
$coupon = array();
$sql = "SELECT p.code, p.type, p.value, p.uses, p.id
		FROM tblpromotions p, tblaffcoupons a
		WHERE a.aff_id = '$aff_id' AND a.coupon = p.id";
$data = mysql_query($sql);
while ($r = mysql_fetch_array($data)) {
	$coupon[$r[4]]['code'] = $r[0];
	$coupon[$r[4]]['type'] = $r[1];
	$coupon[$r[4]]['value'] = $r[2];
	$coupon[$r[4]]['uses'] = $r[3];
	$coupon[$r[4]]['id'] = $r[4];
}
print "<div class=\"page-header\"><div class=\"styled_title\"><h2>Your Coupons</h2></div></div><div class=\"alert alert-success\"";

// Don't display alert div if there is no message, probably need to switch this to use jquery later
if (!$msg) print " style=\"display: none;\"";

print ">$msg</div><table class=\"table table-striped table-framed\">";

print "<thead><tr><th>&nbsp;</th><th>Coupon Code</th><th>Coupon Type</th><th>Coupon Value</th><th>Uses</th></tr></thead>";

if (!$coupon) print "<tbody><tr><td></td><td>No Coupons Found</td></tr></tbody>";

foreach ($coupon as $key => $val) {
	$code = $val['code'];
	$type = $val['type'];
	$value = $val['value'];
	$uses = $val['uses'];
	$id = $val['id'];
	print "<tbody><tr><td><a href=\"affiliates.php?cmd=del&cid=$id\"><img src=\"templates/default/images/delete.png\"></a></td><td>$code</td><td>$type</td><td>$value</td><td>$uses</td></tr></tbody>";
}
print "</table>";
print "<div class=\"page-header\"><div class=\"styled_title\"><h2>Add Coupons</h2></div></div>";
print "<form action=\"affiliates.php\" method=\"POST\" name=\"addcoupons\" class=\"form-horizontal\">
		<input type=\"hidden\" name=\"cmd\" value=\"add\">
		<div class=\"well\">
			<div class=\"control-group\">
				<label for=\"code\" class=\"control-label\">Coupon Code:</label>
				<div class=\"controls\">
					<input type=\"text\" name=\"code\" id=\"code\">
				</div>
			</div>
			<div class=\"control-group\">
				<label for=\"code\" class=\"control-label\">Coupon Type:</label>
				<div class=\"controls\">			
					<select name=\"type\">";

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
	print "<option value=\"$enc_string\">$label</option>";
}
print "</select>
		</div>
		</div>
	<div class=\"control-group\">
		<div class=\"controls\">
			<input type=\"submit\" name=\"Submit\" value=\"Add\" class=\"btn btn-primary btn-large\">
		</div>
	</div>
</div></form>";
