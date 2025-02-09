jQuery(document).ready(function($) {
    'use strict';

    let mediaFrame = null;
    const modal = $('#small-tools-media-replace-modal');
    const form = $('#small-tools-media-replace-form');
    const submitButton = $('#small-tools-replace-submit');
    const selectButton = $('#small-tools-select-media');
    const selectedPreview = $('#small-tools-selected-preview');

    // Initialize the modal
    function initModal() {
        // Ensure the modal is hidden initially
        $('#small-tools-media-replace-modal').hide();
        
        // Reset form and button states
        resetForm();
        
        // Initialize media frame if wp.media is available
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            mediaFrame = null;
        }
    }

    // Initialize on page load
    initModal();

    // Open modal when clicking "Replace Media"
    $(document).on('click', '.small-tools-replace-media', function(e) {
        e.preventDefault();
        const attachmentId = $(this).data('id');
        openModal(attachmentId);
    });

    // Close modal when clicking close button or backdrop
    $('.small-tools-modal-close, .small-tools-modal-backdrop').on('click', function() {
        closeModal();
    });

    // Close modal on escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27 && modal.is(':visible')) {
            closeModal();
        }
    });

    // Handle media selection
    selectButton.on('click', function(e) {
        e.preventDefault();
        openMediaFrame();
    });

    // Handle form submission
    submitButton.on('click', function(e) {
        e.preventDefault();
        replaceMedia();
    });

    function openModal(attachmentId) {
        // Reset form and preview
        form[0].reset();
        selectedPreview.empty();
        submitButton.prop('disabled', true);
        
        // Set attachment ID
        $('#attachment_id').val(attachmentId);
        
        // Get current attachment details
        $.ajax({
            url: smallToolsMediaReplace.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_attachment_details',
                id: attachmentId,
                security: smallToolsMediaReplace.nonce
            },
            beforeSend: function() {
                $('.small-tools-media-preview, .small-tools-media-details').html('<div class="spinner is-active"></div>');
            },
            success: function(response) {
                if (response.success) {
                    $('.small-tools-media-preview').html(response.data.preview);
                    $('.small-tools-media-details').html(response.data.details);
                    modal.show();
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert(smallToolsMediaReplace.strings.error);
            }
        });
    }

    function closeModal() {
        modal.hide();
        resetForm();
    }

    function resetForm() {
        form[0].reset();
        $('#attachment_id, #replacement_id').val('');
        selectedPreview.empty();
        submitButton.prop('disabled', true);
    }

    function openMediaFrame() {
        // If the frame already exists, reopen it
        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        // Create the media frame
        mediaFrame = wp.media({
            title: smallToolsMediaReplace.strings.title,
            button: {
                text: smallToolsMediaReplace.strings.button
            },
            multiple: false
        });

        // When an image is selected in the media frame...
        mediaFrame.on('select', function() {
            const attachment = mediaFrame.state().get('selection').first().toJSON();
            handleMediaSelection(attachment);
        });

        // Open the modal
        mediaFrame.open();
    }

    function handleMediaSelection(attachment) {
        $('#replacement_id').val(attachment.id);
        
        // Update preview
        let preview = '';
        if (attachment.type === 'image') {
            preview = $('<img>', {
                src: attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url,
                class: 'small-tools-preview-image',
                alt: attachment.alt
            });
        } else {
            preview = $('<div>', {
                class: 'small-tools-media-info'
            }).append(
                $('<span>', { class: 'dashicons dashicons-media-default' }),
                $('<span>', { class: 'filename', text: attachment.filename })
            );
        }
        
        selectedPreview.empty().append(preview).show();
        submitButton.prop('disabled', false);
    }

    function replaceMedia() {
        const data = {
            action: 'small_tools_replace_media',
            attachment_id: $('#attachment_id').val(),
            replacement_id: $('#replacement_id').val(),
            update_thumbnails: $('input[name="update_thumbnails"]').is(':checked') ? 1 : 0,
            security: smallToolsMediaReplace.nonce
        };

        submitButton.prop('disabled', true).text(smallToolsMediaReplace.strings.replacing);

        $.ajax({
            url: smallToolsMediaReplace.ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    closeModal();
                    // Reload the page to show updated media
                    window.location.reload();
                } else {
                    alert(response.data.message);
                    submitButton.prop('disabled', false).text(smallToolsMediaReplace.strings.button);
                }
            },
            error: function() {
                alert(smallToolsMediaReplace.strings.error);
                submitButton.prop('disabled', false).text(smallToolsMediaReplace.strings.button);
            }
        });
    }

    // Helper functions
    function showNotice(message, type) {
        var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        $('.wrap h1').after($notice);
        
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
}); 