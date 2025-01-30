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
            // Set CSS variables
            document.documentElement.style.setProperty('--back-to-top-size', smallTools.backToTopSize + 'px');
            document.documentElement.style.setProperty('--back-to-top-bg-color', smallTools.backToTopBgColor);

            // Create back to top button with custom icon or default arrow
            var buttonContent = smallTools.backToTopIcon ? 
                '<img src="' + smallTools.backToTopIcon + '" alt="Back to top">' :
                '<span class="default-arrow">↑</span>';
            
            var $backToTop = $('<a>', {
                href: '#',
                id: 'small-tools-back-to-top',
                class: 'position-' + smallTools.backToTopPosition,
                html: buttonContent
            }).appendTo('body');

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