/**
 * WHMCS Affiliate Coupons Client Area JS
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1.2
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-06-07 18:41:37
 */
$(document).ready(function() {
    var checkAffHeader = $(".page-header .styled_title h1:contains('Affiliates')")[0];
    var checkAffPath = $(location).attr('pathname');
    var index_page = $("#index_page").val();
    var script_name = $("#script_name").val();

    if (checkAffHeader || (checkAffPath.indexOf("affiliates.php") >= 0) || (script_name.indexOf("affiliates.php") >= 0)) {
        $('<div>').load('/' + index_page + '?m=affcoupons #affcoupons-ajax').insertAfter('.whmcscontainer .contentpadded .pagination');
    }
});