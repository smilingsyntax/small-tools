jQuery(document).ready(function($) {
    'use strict';

    // Check if settings object exists
    if (typeof window.smallToolsSettings === 'undefined') {
        console.error('Small Tools Settings: Required settings object is not defined');
        return;
    }

    // Tab handling
    function initTabs() {
        // Initially hide all tab contents
        $('.small-tools-tab-content').hide();
        
        $('.small-tools-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('tab');
            
            // Update active tab
            $('.small-tools-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Show target content
            $('.small-tools-tab-content').hide();
            $('#' + target).fadeIn();

            // Store active tab in localStorage
            localStorage.setItem('smallToolsActiveTab', target);
        });

        // Restore last active tab or show first tab
        var lastActiveTab = localStorage.getItem('smallToolsActiveTab');
        if (lastActiveTab && $('#' + lastActiveTab).length) {
            $('.small-tools-tabs [data-tab="' + lastActiveTab + '"]').trigger('click');
        } else {
            // Activate first tab by default
            $('.small-tools-tabs .nav-tab:first').trigger('click');
        }
    }

    // Initialize tabs
    initTabs();

    // AJAX form submission
    $('.small-tools-settings-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $submitButton = $('.small-tools-header-actions button[type="submit"]');
        var $spinner = $('.small-tools-header-actions .small-tools-spinner');
        
        // Show spinner
        $spinner.css('display', 'inline-block');
        $submitButton.prop('disabled', true);

        // Collect form data
        var formData = new FormData();
        
        // Handle all checkboxes first
        $form.find('input[type="checkbox"]').each(function() {
            var $checkbox = $(this);
            formData.append($checkbox.attr('name'), $checkbox.is(':checked') ? 'yes' : 'no');
        });

        // Add all other form fields
        var formFields = $form.serializeArray();
        $.each(formFields, function(i, field) {
            if (!field.name.match(/^small_tools_.*_enabled$/) && 
                !field.name.match(/^small_tools_disable_.*$/) && 
                !field.name.match(/^small_tools_remove_.*$/) && 
                !field.name.match(/^small_tools_back_to_top$/)) {
                formData.append(field.name, field.value);
            }
        });

        // Add action and security nonce
        formData.append('action', 'small_tools_save_settings');
        formData.append('security', window.smallToolsSettings.nonce);

        // Send AJAX request
        $.ajax({
            url: window.smallToolsSettings.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Response:', response); // Debug log
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    // Update form values if needed
                    if (response.data && response.data.settings) {
                        updateFormValues(response.data.settings);
                    }
                } else {
                    showNotice(response.data ? response.data.message : 'Error saving settings.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                showNotice('Error saving settings.', 'error');
            },
            complete: function() {
                $spinner.hide();
                $submitButton.prop('disabled', false);
            }
        });
    });

    // Function to update form values after successful save
    function updateFormValues(settings) {
        $.each(settings, function(key, value) {
            var $field = $('[name="' + key + '"]');
            if ($field.is(':checkbox')) {
                $field.prop('checked', value === 'yes');
            } else {
                $field.val(value);
            }
        });
        // Update color picker if it exists
        if ($('.small-tools-color-picker').length) {
            $('.small-tools-color-picker').wpColorPicker('color', settings.small_tools_back_to_top_bg_color);
        }
        // Update preview
        updatePreview();
    }

    // Notice handling
    function showNotice(message, type = 'success') {
        var $notice = $('.small-tools-save-notice');
        
        $notice.removeClass('success error').addClass(type);
        $notice.text(message).fadeIn();
        
        setTimeout(function() {
            $notice.fadeOut();
        }, 3000);
    }

    // Initialize color pickers
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

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Choose Icon',
            button: {
                text: 'Use this icon'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.find('img').attr('src', attachment.url);
            preview.show();
            removeButton.show();
            updatePreview();
        });

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
}); 