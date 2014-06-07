/**
 * WHMCS Affiliate Coupons Client Area JS
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1.1
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 */
$(document).ready(function() {
    var checkAffHeader = $(".page-header .styled_title h1:contains('Affiliates')")[0];
    var checkAffPath = $(location).attr('pathname');
    var index_page = $("#index_page").val();

    if (checkAffHeader || checkAffPath == '/affiliates.php') {
        $('<div>').load( '/' + index_page + '?m=affcoupons #affcoupons-ajax').insertAfter('.whmcscontainer .contentpadded .pagination');
//        $('#landingpageForm').submit(function( event ){
//            // Stop form from submitting normally
//            event.preventDefault();
//
//            // Get some values from elements on the page:
//            var $form = $( this ),
//                landing_input = $form.find( "input[name='landing']" ).val();
//
//            // Send the data using post
//            var posting = $.post( 'index.php?m=affcoupons', { landing: landing_input } );
//
//            // Put the results in a div
//            posting.done(function( data ) {
//                var content = $( data ).find( '#affnotice' );
//                $( '#affnotice' ).html( content );
//            });
//        });
    }
});
