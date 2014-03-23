<?php
/**
 *
 * @ WHMCS FULL DECODED & NULLED
 *
 * @ Version  : 5.2.15
 * @ Author   : MTIMER
 * @ Release on : 2013-12-24
 * @ Website  : http://www.mtimer.cn
 *
 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new WHMCS_Admin("View Promotions");
$aInt->title = $aInt->lang("promos", "title");
$aInt->sidebar = "config";
$aInt->icon = "autosettings";
$aInt->helplink = "Promotions";

if ($action == "genpromo") {
    $numbers = "0123456789";
    $uppercase = "ABCDEFGHIJKLMNOPQRSTUVYWXYZ";
    $str = "";
    $seeds_count = strlen($numbers) - 1;
    $i = 0;

    while ($i < 4) {
        $str .= $numbers[rand(0, $seeds_count)];
        ++$i;
    }

    $seeds_count = strlen($uppercase) - 1;
    $i = 0;

    while ($i < 8) {
        $str .= $uppercase[rand(0, $seeds_count)];
        ++$i;
    }

    $password = "";
    $i = 0;

    while ($i < 10) {
        $randomnum = rand(0, strlen($str) - 1);
        $password .= $str[$randomnum];
        $str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
        ++$i;
    }

    echo $password;
    exit();
}


if ($action == "save") {
    check_token("WHMCS.admin.default");
    checkPermission("Create/Edit Promotions");
    $code = trim($code);
    $startdate = ($startdate == "" ? "00000000" : toMySQLDate($startdate));
    $expirationdate = ($expirationdate == "" ? "00000000" : toMySQLDate($expirationdate));
    $cycles = (is_array($cycles) ? implode(",", $cycles) : "");
    $appliesto = (is_array($appliesto) ? implode(",", $appliesto) : "");
    $requires = (is_array($requires) ? implode(",", $requires) : "");
    $upgradeconfig = serialize(array("value" => format_as_currency($upgradevalue), "type" => $upgradetype, "discounttype" => $upgradediscounttype, "configoptions" => $configoptionupgrades));

    if ($id) {
        update_query("tblpromotions", array("code" => $code, "type" => $type, "recurring" => $recurring, "value" => $pvalue, "cycles" => $cycles, "appliesto" => $appliesto, "requires" => $requires, "requiresexisting" => $requiresexisting, "startdate" => $startdate, "expirationdate" => $expirationdate, "maxuses" => $maxuses, "lifetimepromo" => $lifetimepromo, "applyonce" => $applyonce, "newsignups" => $newsignups, "existingclient" => $existingclient, "onceperclient" => $onceperclient, "recurfor" => $recurfor, "upgrades" => $upgrades, "upgradeconfig" => $upgradeconfig, "notes" => $notes), array("id" => $id));
        redir("updated=true");
    }
    else {
        $result = select_query("tblpromotions", "COUNT(*)", array("code" => $code));
        $data = mysql_fetch_array($result);
        $duplicates = $data[0];
        $newid = insert_query("tblpromotions", array("code" => $code, "type" => $type, "recurring" => $recurring, "value" => $pvalue, "cycles" => $cycles, "appliesto" => $appliesto, "requires" => $requires, "requiresexisting" => $requiresexisting, "startdate" => $startdate, "expirationdate" => $expirationdate, "maxuses" => $maxuses, "lifetimepromo" => $lifetimepromo, "applyonce" => $applyonce, "newsignups" => $newsignups, "existingclient" => $existingclient, "onceperclient" => $onceperclient, "recurfor" => $recurfor, "upgrades" => $upgrades, "upgradeconfig" => $upgradeconfig, "notes" => $notes));

        if ($duplicates) {
            redir("action=manage&id=" . $newid);
        }
        else {
            redir("created=true");
        }
    }

    exit();
}


if ($action == "delete") {
    check_token("WHMCS.admin.default");
    checkPermission("Delete Promotions");
    delete_query("tblpromotions", array("id" => $id));
    redir("deleted=true");
    exit();
}


if ($expire) {
    check_token("WHMCS.admin.default");
    checkPermission("Create/Edit Promotions");
    update_query("tblpromotions", array("expirationdate" => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))), array("id" => $expire));
    redir("expired=true");
    exit();
}

ob_start();

if (!$action) {
    $aInt->deleteJSConfirm("doDelete", "promos", "deletesure", "?action=delete&id=");

    if ($deleted) {
        infoBox($aInt->lang("global", "success"), $aInt->lang("promos", "deletesuccess"));
    }


    if ($updated) {
        infoBox($aInt->lang("global", "success"), $aInt->lang("global", "changesuccess"));
    }


    if ($created) {
        infoBox($aInt->lang("global", "success"), $aInt->lang("promos", "addsuccess"));
    }


    if ($expired) {
        infoBox($aInt->lang("global", "success"), $aInt->lang("promos", "expiresuccess"));
    }

    echo $infobox;
    echo "
<div style=\"float:right;\"><a href=\"";
    echo $PHP_SELF;
    echo "?action=manage\"><img src=\"images/icons/add.png\" border=\"0\" align=\"absmiddle\" /> ";
    echo $aInt->lang("promos", "createpromo");
    echo "</a></div>

<p>";
    echo "<s";
    echo "trong>";
    echo $aInt->lang("global", "view");
    echo ":</strong> <a href=\"configpromotions.php\"";

    if (!$view) {
        echo " style=\"font-weight:bold\"";
    }

    echo ">";
    echo $aInt->lang("promos", "activepromos");
    echo "</a> | <a href=\"configpromotions.php?view=expired\"";

    if ($view == "expired") {
        echo " style=\"font-weight:bold\"";
    }

    echo ">";
    echo $aInt->lang("promos", "expiredpromos");
    echo "</a> | <a href=\"configpromotions.php?view=all\"";

    if ($view == "all") {
        echo " style=\"font-weight:bold\"";
    }

    echo ">";
    echo $aInt->lang("promos", "allpromos");
    echo "</a></p>

";
    $aInt->sortableTableInit("code", "ASC");

    if ($view == "all") {
        $where = "";
    }
    else {
        if ($view == "expired") {
            $where = "(maxuses>0 AND uses>=maxuses) OR (expirationdate!='0000-00-00' AND expirationdate<'" . date("Ymd") . "')";
        }
        else {
            $where = "(maxuses<=0 OR uses<maxuses) AND (expirationdate='0000-00-00' OR expirationdate>='" . date("Ymd") . "')";
        }
    }

    $result = select_query("tblpromotions", "COUNT(*)", $where);
    $data = mysql_fetch_array($result);
    $numrows = $data[0];
    $result = select_query("tblpromotions", "", $where, "code", "ASC", $page * $limit . ("," . $limit));

    while ($data = mysql_fetch_array($result)) {
        $pid = $data['id'];
        $code = $data['code'];
        $type = $data['type'];
        $recurring = $data['recurring'];
        $value = $data['value'];
        $uses = $data['uses'];
        $maxuses = $data['maxuses'];
        $startdate = $data['startdate'];
        $expirationdate = $data['expirationdate'];
        $notes = $data['notes'];

        if (0 < $maxuses && $maxuses <= $uses) {
            $uses = "<b>" . $uses;
        }


        if (0 < $maxuses) {
            $uses .= "/" . $maxuses;
        }

        $recurring = ($recurring ? "<img src=\"images/icons/tick.png\" width=\"16\" height=\"16\" alt=\"Yes\" />" : "");
        $startdate = ($startdate == "0000-00-00" ? "-" : fromMySQLDate($startdate));
        $expirationdate = ($expirationdate == "0000-00-00" ? "-" : fromMySQLDate($expirationdate));

        if ($notes) {
            $code = "<a title=\"" . $aInt->lang("fields", "notes") . (": " . $notes . "\">" . $code . "</a>");
        }


        if ($type == "Percentage") {
            $type = $aInt->lang("promos", "percentage");
        }
        else {
            if ($type == "Fixed Amount") {
                $type = $aInt->lang("promos", "fixedamount");
            }
            else {
                if ($type == "Free Setup") {
                    $type = $aInt->lang("promos", "freesetup");
                }
            }
        }

        $tabledata[] = array($code, $type, $value, $recurring, $uses, $startdate, $expirationdate, "<a href=\"" . $PHP_SELF . "?action=manage&duplicate=" . $pid . "\"><img src=\"images/icons/add.png\" border=\"0\" align=\"absmiddle\" /> " . $aInt->lang("promos", "duplicatepromo") . "</a>", "<a href=\"" . $PHP_SELF . "?expire=" . $pid . generate_token("link") . "\"><img src=\"images/icons/expire.png\" border=\"0\" align=\"absmiddle\" /> " . $aInt->lang("promos", "expirenow") . "</a>", "<a href=\"" . $PHP_SELF . "?action=manage&id=" . $pid . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "edit") . "\"></a>", "<a href=\"#\" onClick=\"doDelete('" . $pid . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\"></a>");
    }

    echo $aInt->sortableTable(array($aInt->lang("fields", "promocode"), $aInt->lang("fields", "type"), $aInt->lang("promos", "value"), $aInt->lang("promos", "recurring"), $aInt->lang("promos", "uses"), $aInt->lang("fields", "startdate"), $aInt->lang("fields", "expirydate"), "&nbsp;", "&nbsp;", "", ""), $tabledata);
}
else {
    if ($action == "duplicate") {
        checkPermission("Create/Edit Promotions");
        echo "
<p><b>";
        echo $aInt->lang("promos", "duplicatepromo");
        echo "</b></p>

<form method=\"get\" action=\"";
        echo $PHP_SELF;
        echo "\">
<input type=\"hidden\" name=\"action\" value=\"manage\" />
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
        echo $aInt->lang("promos", "existingpromo");
        echo "</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"duplicate\">";
        $query = "SELECT * FROM tblpromotions ORDER BY code ASC";
        $result = full_query($query);

        while ($data = mysql_fetch_array($result)) {
            $promoid = $data['id'];
            $promoname = $data['code'];
            echo "<option value=\"" . $promoid . "\">" . $promoname;
        }

        echo "</select></td></tr>
</table>
<p align=\"center\"><input type=\"submit\" value=\"";
        echo $aInt->lang("global", "continue");
        echo " >>\" class=\"button\"></p>
</form>

";
    }
    else {
        if ($action == "manage") {
            if ($id) {
                $result = select_query("tblpromotions", "", array("id" => $id));
                $data = mysql_fetch_array($result);
                $code = $data['code'];
                $type = $data['type'];
                $recurring = $data['recurring'];
                $value = $data['value'];
                $cycles = $data['cycles'];
                $appliesto = $data['appliesto'];
                $requires = $data['requires'];
                $requiresexisting = $data['requiresexisting'];
                $startdate = $data['startdate'];
                $expirationdate = $data['expirationdate'];
                $maxuses = $data['maxuses'];
                $uses = $data['uses'];
                $lifetimepromo = $data['lifetimepromo'];
                $applyonce = $data['applyonce'];
                $newsignups = $data['newsignups'];
                $existingclient = $data['existingclient'];
                $onceperclient = $data['onceperclient'];
                $recurfor = $data['recurfor'];
                $upgrades = $data['upgrades'];
                $upgradeconfig = $data['upgradeconfig'];
                $notes = $data['notes'];
                $startdate = ($startdate == "0000-00-00" ? "" : fromMySQLDate($startdate));
                $expirationdate = ($expirationdate == "0000-00-00" ? "" : fromMySQLDate($expirationdate));
                $cycles = explode(",", $cycles);
                $appliesto = explode(",", $appliesto);
                $requires = explode(",", $requires);
                $upgradeconfig = unserialize($upgradeconfig);
                $managetitle = $aInt->lang("promos", "editpromo");
                $result = select_query("tblpromotions", "COUNT(*)", array("code" => $code));
                $data = mysql_fetch_array($result);
                $duplicates = $data[0];
            }
            else {
                if ($duplicate) {
                    checkPermission("Create/Edit Promotions");
                    $result = select_query("tblpromotions", "", array("id" => $duplicate));
                    $data = mysql_fetch_array($result);
                    $code = "";
                    $type = $data['type'];
                    $recurring = $data['recurring'];
                    $value = $data['value'];
                    $cycles = $data['cycles'];
                    $appliesto = $data['appliesto'];
                    $requires = $data['requires'];
                    $requiresexisting = $data['requiresexisting'];
                    $startdate = $data['startdate'];
                    $expirationdate = $data['expirationdate'];
                    $maxuses = $data['maxuses'];
                    $uses = 0;
                    $lifetimepromo = $data['lifetimepromo'];
                    $applyonce = $data['applyonce'];
                    $newsignups = $data['newsignups'];
                    $existingclient = $data['existingclient'];
                    $onceperclient = $data['onceperclient'];
                    $recurfor = $data['recurfor'];
                    $upgrades = $data['upgrades'];
                    $upgradeconfig = $data['upgradeconfig'];
                    $notes = $data['notes'];
                    $startdate = ($startdate == "0000-00-00" ? "" : fromMySQLDate($startdate));
                    $expirationdate = ($expirationdate == "0000-00-00" ? "" : fromMySQLDate($expirationdate));
                    $cycles = explode(",", $cycles);
                    $appliesto = explode(",", $appliesto);
                    $requires = explode(",", $requires);
                    $upgradeconfig = unserialize($upgradeconfig);
                    $managetitle = $aInt->lang("promos", "duplicatepromo");
                }
                else {
                    checkPermission("Create/Edit Promotions");
                    $managetitle = $aInt->lang("promos", "createpromo");
                    $appliesto = array();
                    $requires = array();
                    $cycles = array();
                    $value = "";
                    $recurfor = "0";
                    $duplicates = 0;
                }
            }

            echo "<p><b>" . $managetitle . "</b></p>";

            if (1 < $duplicates) {
                infoBox($aInt->lang("promos", "duplicate"), $aInt->lang("promos", "duplicateinfo"));
                echo $infobox;
            }

            echo "
<form method=\"post\" action=\"";
            echo $PHP_SELF;
            echo "?action=save&id=";
            echo $id;
            echo "\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldlabel\" width=\"15%\">";
            echo $aInt->lang("fields", "promocode");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"code\" value=\"";
            echo $code;
            echo "\" id=\"promocode\" /> <input type=\"button\" value=\"";
            echo $aInt->lang("promos", "autogencode");
            echo "\" onclick=\"autoGenPromo()\" /></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("fields", "type");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"type\">
<option value=\"Percentage\"";

            if ($type == "Percentage") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "percentage");
            echo "</option>
<option value=\"Fixed Amount\"";

            if ($type == "Fixed Amount") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "fixedamount");
            echo "</option>
<option value=\"Price Override\"";

            if ($type == "Price Override") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "priceoverride");
            echo "</option>
<option value=\"Free Setup\"";

            if ($type == "Free Setup") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "freesetup");
            echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "recurring");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"recurring\" id=\"recurring\" value=\"1\"";

            if ($recurring) {
                echo " checked";
            }

            echo "> <label for=\"recurring\">";
            echo $aInt->lang("promos", "recurenable");
            echo "</label> <input type=\"text\" name=\"recurfor\" size=\"3\" value=\"";
            echo $recurfor;
            echo "\" /> ";
            echo $aInt->lang("promos", "recurenable2");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "value");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"pvalue\" size=\"10\" value=\"";
            echo $value;
            echo "\"></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "appliesto");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"appliesto[]\" size=\"8\" style=\"width:90%\" multiple>
";
            $result = select_query("tblproducts", "tblproducts.id,tblproducts.gid,tblproducts.name,tblproductgroups.name AS groupname", "", "tblproductgroups`.`order` ASC,`tblproducts`.`order` ASC,`name", "ASC", "", "tblproductgroups ON tblproducts.gid=tblproductgroups.id");
            $jscode = "function autoGenPromo() {
    $.post(\"configpromotions.php\", \"action=genpromo\", function(data) {
        $(\"#promocode\").val(data);
    });
}";

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $groupname = $data['groupname'];
                $name = $data['name'];
                echo "<option value=\"" . $id . "\"";

                if (in_array($id, $appliesto)) {
                    echo " selected";
                }

                echo ">" . $groupname . " - " . $name . "</option>";
            }

            $result = select_query("tbladdons", "", "", "name", "ASC");

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $name = $data['name'];
                $description = $data['description'];
                echo "<option value=\"A" . $id . "\"";

                if (in_array("A" . $id, $appliesto)) {
                    echo " selected";
                }

                echo ">" . $aInt->lang("orders", "addon") . (" - " . $name . "</option>");
            }

            $result = select_query("tbldomainpricing", "DISTINCT extension", "", "extension", "ASC");

            while ($data = mysql_fetch_array($result)) {
                $tld = $data['extension'];
                echo "<option value=\"D" . $tld . "\"";

                if (in_array("D" . $tld, $appliesto)) {
                    echo " selected";
                }

                echo ">" . $aInt->lang("fields", "domain") . (" - " . $tld . "</option>");
            }

            echo "</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "requires");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"requires[]\" size=\"8\" style=\"width:90%\" multiple>
";
            $result = select_query("tblproducts", "tblproducts.id,tblproducts.gid,tblproducts.name,tblproductgroups.name AS groupname", "", "tblproductgroups`.`order` ASC,`tblproducts`.`order` ASC,`name", "ASC", "", "tblproductgroups ON tblproducts.gid=tblproductgroups.id");

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $groupname = $data['groupname'];
                $name = $data['name'];
                echo "<option value=\"" . $id . "\"";

                if (in_array($id, $requires)) {
                    echo " selected";
                }

                echo ">" . $groupname . " - " . $name . "</option>";
            }

            $result = select_query("tbladdons", "", "", "name", "ASC");

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $name = $data['name'];
                $description = $data['description'];
                echo "<option value=\"A" . $id . "\"";

                if (in_array("A" . $id, $requires)) {
                    echo " selected";
                }

                echo ">" . $aInt->lang("orders", "addon") . (" - " . $name . "</option>");
            }

            $result = select_query("tbldomainpricing", "DISTINCT extension", "", "extension", "ASC");

            while ($data = mysql_fetch_array($result)) {
                $tld = $data['extension'];
                echo "<option value=\"D" . $tld . "\"";

                if (in_array("D" . $tld, $requires)) {
                    echo " selected";
                }

                echo ">" . $aInt->lang("fields", "domain") . (" - " . $tld . "</option>");
            }

            echo "</select><br /><input type=\"checkbox\" name=\"requiresexisting\" value=\"1\"";

            if ($requiresexisting) {
                echo " checked";
            }

            echo " /> ";
            echo $aInt->lang("promos", "requiresexisting");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "cycles");
            echo "</td><td class=\"fieldarea\">

<b>";
            echo $aInt->lang("services", "title");
            echo "</b><br />
<input type=\"checkbox\" name=\"cycles[]\" value=\"One Time\" id=\"cycleonetime\"";

            if (in_array("One Time", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cycleonetime\">";
            echo $aInt->lang("billingcycles", "onetime");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Monthly\" id=\"cyclemonthly\"";

            if (in_array("Monthly", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cyclemonthly\">";
            echo $aInt->lang("billingcycles", "monthly");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Quarterly\" id=\"cyclequarterly\"";

            if (in_array("Quarterly", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cyclequarterly\">";
            echo $aInt->lang("billingcycles", "quarterly");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Semi-Annually\" id=\"cyclesemiannually\"";

            if (in_array("Semi-Annually", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cyclesemiannually\">";
            echo $aInt->lang("billingcycles", "semiannually");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Annually\" id=\"cycleannually\"";

            if (in_array("Annually", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cycleannually\">";
            echo $aInt->lang("billingcycles", "annually");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Biennially\" id=\"cyclebiennially\"";

            if (in_array("Biennially", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cyclebiennially\">";
            echo $aInt->lang("billingcycles", "biennially");
            echo "</label> <input type=\"checkbox\" name=\"cycles[]\" value=\"Triennially\" id=\"cycletriennially\"";

            if (in_array("Triennially", $cycles)) {
                echo " checked";
            }

            echo " /> <label for=\"cycletriennially\">";
            echo $aInt->lang("billingcycles", "triennially");
            echo "</label>
<br />
<b>";
            echo $aInt->lang("domains", "title");
            echo "</b><br />
";
            $domainyears = 1;

            while ($domainyears <= 10) {
                echo "<input type=\"checkbox\" name=\"cycles[]\" value=\"" . $domainyears . "Years\" id=\"Dom" . $domainyears . "Y\"";

                if (in_array($domainyears . "Years", $cycles)) {
                    echo " checked";
                }

                echo " /> <label for=\"Dom" . $domainyears . "Y\">" . $domainyears . " " . (1 < $domainyears ? $aInt->lang("domains", "years") : $aInt->lang("domains", "year")) . "</label> ";
                ++$domainyears;
            }

            echo "
</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("fields", "startdate");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"startdate\" value=\"";
            echo $startdate;
            echo "\" class=\"datepick\"> (";
            echo $aInt->lang("promos", "leaveblank");
            echo ")</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("fields", "expirydate");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"expirationdate\" value=\"";
            echo $expirationdate;
            echo "\" class=\"datepick\"> (";
            echo $aInt->lang("promos", "leaveblank");
            echo ")</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "maxuses");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"maxuses\" size=5 value=\"";
            echo $maxuses;
            echo "\"> (";
            echo $aInt->lang("promos", "unlimiteduses");
            echo ")</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "numuses");
            echo "</td><td class=\"fieldarea\">";
            echo $uses;
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "lifetimepromo");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"lifetimepromo\" id=\"lifetimepromo\" value=\"1\"";

            if ($lifetimepromo) {
                echo " checked";
            }

            echo "> <label for=\"lifetimepromo\">";
            echo $aInt->lang("promos", "lifetimepromodesc");
            echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "applyonce");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"applyonce\" id=\"applyonce\" value=\"1\"";

            if ($applyonce) {
                echo " checked";
            }

            echo "> <label for=\"applyonce\">";
            echo $aInt->lang("promos", "applyoncedesc");
            echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "newsignups");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"newsignups\" id=\"newsignups\" value=\"1\"";

            if ($newsignups) {
                echo " checked";
            }

            echo "> <label for=\"newsignups\">";
            echo $aInt->lang("promos", "newsignupsdesc");
            echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "onceperclient");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"onceperclient\" id=\"onceperclient\" value=\"1\"";

            if ($onceperclient) {
                echo " checked";
            }

            echo "> <label for=\"onceperclient\">";
            echo $aInt->lang("promos", "onceperclientdesc");
            echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "existingclient");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"existingclient\" id=\"existingclient\" value=\"1\"";

            if ($existingclient) {
                echo " checked";
            }

            echo "> <label for=\"existingclient\">";
            echo $aInt->lang("promos", "existingclientdesc");
            echo "</label></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "upgrades");
            echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"upgrades\" id=\"upgrades\" value=\"1\" onclick=\"$('#upgradeoptions').slideToggle()\"";

            if ($upgrades) {
                echo " checked";
            }

            echo "> <label for=\"upgrades\">";
            echo $aInt->lang("promos", "upgradesdesc");
            echo "</label>

<div id=\"upgradeoptions\"";

            if (!$upgrades) {
                echo " style=\"display:none;\"";
            }

            echo ">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td colspan=\"2\" class=\"fieldarea\"><b>";
            echo $aInt->lang("promos", "upgradesinstructions");
            echo "</b><br />";
            echo $aInt->lang("promos", "upgradesinstructionsinfo");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "upgradetype");
            echo "</td><td class=\"fieldarea\"><input type=\"radio\" name=\"upgradetype\" value=\"product\"";

            if ($upgradeconfig['type'] == "product") {
                echo " checked";
            }

            echo " /> ";
            echo $aInt->lang("services", "title");
            echo " <input type=\"radio\" name=\"upgradetype\" value=\"configoptions\"";

            if ($upgradeconfig['type'] == "configoptions") {
                echo " checked";
            }

            echo " /> ";
            echo $aInt->lang("setup", "configoptions");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "upgradediscount");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"upgradevalue\" size=\"10\" value=\"";
            echo $upgradeconfig['value'];
            echo "\" /> ";
            echo "<s";
            echo "elect name=\"upgradediscounttype\">
<option value=\"Percentage\"";

            if ($upgradeconfig['discounttype'] == "Percentage") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "percentage");
            echo "</option>
<option value=\"Fixed Amount\"";

            if ($upgradeconfig['discounttype'] == "Fixed Amount") {
                echo " selected";
            }

            echo ">";
            echo $aInt->lang("promos", "fixedamount");
            echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("promos", "configoptionsupgrades");
            echo "</td><td class=\"fieldarea\">
";
            echo "<s";
            echo "elect name=\"configoptionupgrades[]\" size=\"8\" style=\"width:90%\" multiple>
";
            $result = select_query("tblproductconfigoptions", "tblproductconfigoptions.id,name,optionname", "", "optionname", "ASC", "", "tblproductconfiggroups ON tblproductconfiggroups.id=tblproductconfigoptions.gid");

            while ($data = mysql_fetch_array($result)) {
                $configid = $data['id'];
                $groupname = $data['name'];
                $optionname = $data['optionname'];
                echo "<option value=\"" . $configid . "\"";

                if (in_array($configid, $upgradeconfig['configoptions'])) {
                    echo " selected";
                }

                echo ">" . $groupname . " - " . $optionname . "</option>";
            }

            echo "</select><br />";
            echo $aInt->lang("promos", "configoptionsupgradesdesc");
            echo "</td></tr>
</table>
</div>

</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("fields", "adminnotes");
            echo "</td><td class=\"fieldarea\"><textarea name=\"notes\" rows=\"4\" style=\"width:95%\">";
            echo $notes;
            echo "</textarea></td></tr>
</table>

<p align=\"center\"><input type=\"submit\" value=\"";
            echo $aInt->lang("global", "savechanges");
            echo "\" class=\"button\" /></p>

</form>

";
        }
    }
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jscode = $jscode;
$aInt->display();
?>