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
 * @Last Modified time: 2014-03-23 00:17:03
 */

jQuery(document).ready(function($) {
    $('#contentarea .form tbody').append('<tr><td class="fieldlabel">Affiliates</td><td class="fieldarea"><input type="checkbox" name="affiliates" id="affiliates" value="1"> <label for="affiliates">Add as affiliate template (allow affiliates to use promo value and config to create custom promo codes)</label></td></tr>');
});