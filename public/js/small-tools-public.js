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

            $backToTop.css({
                'background-color': smallTools.backToTopBgColor,
                'width': smallTools.backToTopSize + 'px',
                'height': smallTools.backToTopSize + 'px',
                'line-height': smallTools.backToTopSize + 'px'
            });

            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('show');
                } else {
                    $backToTop.removeClass('show');
                }
            });

            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        }

        // Prevent Copying
        if (smallTools.preventCopying) {
            
            // Disable keyboard shortcuts for copying
            $(document).on('keydown', function(e) {
                // Ctrl+C, Cmd+C, Ctrl+A, Cmd+A, Ctrl+X, Cmd+X
                if ((e.ctrlKey || e.metaKey) && (e.keyCode === 67 || e.keyCode === 65 || e.keyCode === 88)) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Disable copy event
            $(document).on('copy', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Disable cut event
            $(document).on('cut', function(e) {
                e.preventDefault();
                return false;
            });
        }
    });

})(jQuery); 