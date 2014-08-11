
$(document).ready(function() {
    var checkAffHeader = $( ".page-header .styled_title h1:contains('Affiliates')" )[0];
    var checkAffPath = $( location ).attr( 'pathname' );

    if ( checkAffHeader || ( checkAffPath.indexOf( "affiliates.php" ) >= 0 ) ) {
        $('<div>').load('/' + index_page + '?m=affcoupons #affcoupons-ajax').insertAfter('.whmcscontainer .contentpadded .pagination');
    }
});