/*
 * Media uploader
 */

jQuery(document).ready(function($) {    

    $( '.st_upload_button' ).each(function(index) {
        var custom_uploader;
        $(this).on('click', function() {

            var targetfield = $(this).prev('.upload-url');
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: uploader.txt,
                button: {
                    text: uploader.txt
                },
                multiple: false
            });
            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                targetfield.val(attachment.url);
            });
            custom_uploader.open();
        });    
    });

});