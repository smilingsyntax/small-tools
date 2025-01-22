(function($) {
    'use strict';

    // Disable right click
    $(document).on('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Back to top functionality
    $(document).ready(function() {
        // Create back to top button
        $('body').append('<a href="#" id="small-tools-back-to-top" style="display: none;"><span class="dashicons dashicons-arrow-up-alt2"></span></a>');

        var $backToTop = $('#small-tools-back-to-top');

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $backToTop.fadeIn();
            } else {
                $backToTop.fadeOut();
            }
        });

        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 800);
        });
    });

})(jQuery); 