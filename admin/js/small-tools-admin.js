jQuery(document).ready(function($) {
    'use strict';

    // Initialize color picker
    $('.small-tools-color-picker').wpColorPicker({
        change: function(event, ui) {
            updatePreview();
        }
    });

    // Update preview when size changes
    $('#small_tools_back_to_top_size').on('input', function() {
        updatePreview();
    });

    function updatePreview() {
        var $preview = $('.small-tools-preview-button');
        var size = $('#small_tools_back_to_top_size').val() + 'px';
        var bgColor = $('.small-tools-color-picker').val();

        $preview.css({
            'width': size,
            'height': size,
            'background': bgColor
        });

        $preview.find('img').css({
            'width': (parseInt(size) * 0.6) + 'px',
            'height': (parseInt(size) * 0.6) + 'px'
        });
    }

    // Media uploader
    var mediaUploader;

    $('.small-tools-upload-btn').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var input = button.siblings('input');
        var preview = button.siblings('.small-tools-preview');
        var removeButton = button.siblings('.small-tools-remove-btn');

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Create the media uploader
        mediaUploader = wp.media({
            title: 'Choose Icon',
            button: {
                text: 'Use this icon'
            },
            multiple: false
        });

        // When an image is selected, run a callback
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.find('img').attr('src', attachment.url);
            preview.show();
            removeButton.show();
            updatePreview();
        });

        // Open the uploader dialog
        mediaUploader.open();
    });

    // Remove icon
    $('.small-tools-remove-btn').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var input = button.siblings('input');
        var preview = button.siblings('.small-tools-preview');
        
        input.val('');
        preview.hide();
        button.hide();
    });

    // Initialize accordions
    function initAccordions() {
        const accordionHeaders = document.querySelectorAll('.small-tools-accordion-header');
        
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const isActive = this.classList.contains('active');
                
                // Close all accordions in the current tab
                const currentTab = this.closest('.small-tools-tab-content');
                currentTab.querySelectorAll('.small-tools-accordion-header').forEach(h => {
                    if (h !== this) {
                        h.classList.remove('active');
                        h.nextElementSibling.classList.remove('active');
                    }
                });
                
                // Toggle current accordion
                this.classList.toggle('active');
                content.classList.toggle('active');
                
                // Smooth scroll to the header if opening
                if (!isActive) {
                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 300);
                }
            });
        });
    }

    // Open first accordion in active tab on page load
    function openFirstAccordion() {
        const activeTab = document.querySelector('.small-tools-tab-content.active');
        if (activeTab) {
            const firstHeader = activeTab.querySelector('.small-tools-accordion-header');
            const firstContent = activeTab.querySelector('.small-tools-accordion-content');
            if (firstHeader && firstContent) {
                firstHeader.classList.add('active');
                firstContent.classList.add('active');
            }
        }
    }

    // Initialize accordions when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        initAccordions();
        openFirstAccordion();
        
        // Re-initialize accordions when tab changes
        const tabLinks = document.querySelectorAll('.nav-tab');
        tabLinks.forEach(tab => {
            tab.addEventListener('click', function() {
                setTimeout(() => {
                    openFirstAccordion();
                }, 100);
            });
        });
    });
}); 