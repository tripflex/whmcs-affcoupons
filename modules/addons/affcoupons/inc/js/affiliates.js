/**
 * WHMCS Affiliate Coupons Client Area JS
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-23 03:57:06
 */
$(document).ready(function() {
    var checkAff = $(".page-header .styled_title h1:contains('Affiliates')")[0];

    if (checkAff) {
        $('#whmcscontainer .contentpadded').append($('<div>').load('modules/addons/affcoupons/inc/clientarea.php'));
    }

});