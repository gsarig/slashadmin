/*
 * Old IE Warning
 */

jQuery(document).ready(function($) {
'use strict';

    $('body').prepend( oldIE.text );

    // The close button
    $( "span.oldie-close" ).on( 'click', function() {
        $( "#oldie" ).slideUp('fast');
    });

});