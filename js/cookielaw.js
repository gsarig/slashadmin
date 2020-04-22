/*
 * Set the Cookie Law cookie
 */

jQuery(document).ready(function($) {

    /* = Set the cookie
    ----------------------------------------------- */
    function SetCookie( c_name, value, expiredays ) {
        var exdate = new Date();
        exdate.setDate( exdate.getDate() + expiredays );
        document.cookie = c_name + "=" + encodeURI(value) + ";path=/" + ((expiredays === null) ? "" : ";expires="+exdate.toUTCString());
    }

    /* = Handle the cookie box
    ----------------------------------------------- */
    $(window).on('load', function() {
        var cookieBox = $("#slashadmin_eucookielaw");
        if( document.cookie.indexOf("eucookie") === -1 ) {
            cookieBox.addClass('cl_visible');
        }
        $("#slashadmin_removecookie").on( 'click', function() {
            SetCookie( 'eucookie', 'eucookie', 365*100 );
            cookieBox.fadeOut( 'fast', function() {
                $(this).remove();
            });
        });
    });

});