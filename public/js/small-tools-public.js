(function($) {
    'use strict';

    // Disable right click if enabled
    if (typeof smallTools !== 'undefined' && smallTools.disableRightClick) {
        $(document).on('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
    }

    // Back to top functionality
    $(document).ready(function() {
        if (typeof smallTools !== 'undefined' && smallTools.backToTop) {
            // Create back to top button
            $('body').append('<a href="#" id="small-tools-back-to-top"><span class="dashicons dashicons-arrow-up-alt2"></span></a>');

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
        }
    });

})(jQuery); 