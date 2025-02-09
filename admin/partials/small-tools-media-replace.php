<?php
/**
 * Template for media replacement page
 *
 * @package Small_Tools
 */

if (!defined('ABSPATH')) {
    exit;
}

$attachment_id = isset($_GET['attachment_id']) ? absint($_GET['attachment_id']) : 0;
$attachment = get_post($attachment_id);

if (!$attachment) {
    wp_die(__('Invalid attachment ID.', 'small-tools'));
}

$file = get_attached_file($attachment_id);
$filename = basename($file);
$filetype = wp_check_filetype($filename);
$attachment_url = wp_get_attachment_url($attachment_id);
?>

<div class="wrap">
    <h1><?php esc_html_e('Replace Media', 'small-tools'); ?></h1>

    <div class="small-tools-media-replace-container">
        <div class="current-media">
            <h2><?php esc_html_e('Current Media', 'small-tools'); ?></h2>
            <div class="media-preview">
                <?php if (wp_attachment_is_image($attachment_id)) : ?>
                    <?php echo wp_get_attachment_image($attachment_id, 'medium'); ?>
                <?php else : ?>
                    <div class="media-info">
                        <span class="dashicons dashicons-media-default"></span>
                        <span class="filename"><?php echo esc_html($filename); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="media-details">
                <p><strong><?php esc_html_e('Filename:', 'small-tools'); ?></strong> <?php echo esc_html($filename); ?></p>
                <p><strong><?php esc_html_e('File type:', 'small-tools'); ?></strong> <?php echo esc_html($filetype['type']); ?></p>
                <p><strong><?php esc_html_e('URL:', 'small-tools'); ?></strong> <a href="<?php echo esc_url($attachment_url); ?>" target="_blank"><?php echo esc_html($attachment_url); ?></a></p>
            </div>
        </div>

        <div class="replacement-media">
            <h2><?php esc_html_e('Upload Replacement', 'small-tools'); ?></h2>
            <p class="description"><?php esc_html_e('Choose a file to replace the current media. The new file must be of the same type.', 'small-tools'); ?></p>
            
            <form method="post" enctype="multipart/form-data" id="small-tools-media-replace-form">
                <?php wp_nonce_field('small_tools_replace_media_upload', 'small_tools_media_replace_nonce'); ?>
                <input type="hidden" name="attachment_id" value="<?php echo esc_attr($attachment_id); ?>">
                
                <div class="upload-area">
                    <input type="file" name="replacement_file" id="replacement_file" required>
                    <p class="max-upload-size">
                        <?php
                        $max_upload_size = wp_max_upload_size();
                        printf(
                            /* translators: %s: Maximum upload size */
                            esc_html__('Maximum upload file size: %s', 'small-tools'),
                            esc_html(size_format($max_upload_size))
                        );
                        ?>
                    </p>
                </div>

                <div class="options">
                    <label>
                        <input type="checkbox" name="update_thumbnails" value="1" checked>
                        <?php esc_html_e('Update all thumbnail sizes', 'small-tools'); ?>
                    </label>
                </div>

                <div class="submit-button">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Replace Media', 'small-tools'); ?>">
                    <a href="<?php echo esc_url(admin_url('upload.php')); ?>" class="button button-secondary"><?php esc_html_e('Cancel', 'small-tools'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div> 