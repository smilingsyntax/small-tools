<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <div class="small-tools-settings-header">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="small-tools-header-actions">
            <button type="submit" class="button button-primary" form="small-tools-settings-form"><?php esc_html_e('Save Changes', 'small-tools'); ?></button>
            <span class="spinner"></span>
        </div>
    </div>

    <div class="small-tools-save-notice"></div>

    <h2 class="nav-tab-wrapper small-tools-tabs">
        <a href="#" class="nav-tab" data-tab="general"><?php esc_html_e('General', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="media"><?php esc_html_e('Media', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="performance"><?php esc_html_e('Performance', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="back-to-top"><?php esc_html_e('Back to Top', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="admin"><?php esc_html_e('Admin', 'small-tools'); ?></a>
    </h2>

    <form method="post" action="" class="small-tools-settings-form" id="small-tools-settings-form">
        <?php wp_nonce_field('small_tools_general_settings', 'small_tools_general_nonce'); ?>

        <!-- General Tab -->
        <div id="general" class="small-tools-tab-content">
            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Content Protection', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_disable_right_click"><?php esc_html_e('Disable Right Click', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_disable_right_click" 
                                   name="small_tools_disable_right_click" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_disable_right_click')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('disable_right_click')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Content Management', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_enable_duplication"><?php esc_html_e('Enable Content Duplication', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_enable_duplication" 
                                   name="small_tools_enable_duplication" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_enable_duplication')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('enable_duplication')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Media Tab -->
        <div id="media" class="small-tools-tab-content">
            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Media Replacement', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_enable_media_replace"><?php esc_html_e('Enable Media Replacement', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_enable_media_replace" 
                                   name="small_tools_enable_media_replace" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_enable_media_replace')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('enable_media_replace')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Upload Settings', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_enable_svg_upload"><?php esc_html_e('Enable SVG Upload', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_enable_svg_upload" 
                                   name="small_tools_enable_svg_upload" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_enable_svg_upload')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('enable_svg_upload')); ?></p>
                            <p class="notice notice-warning" style="margin-top: 10px;">
                                <?php esc_html_e('Note: SVG files will be sanitized for security before upload.', 'small-tools'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="small_tools_enable_avif_upload"><?php esc_html_e('Enable AVIF Upload', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_enable_avif_upload" 
                                   name="small_tools_enable_avif_upload" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_enable_avif_upload')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('enable_avif_upload')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Performance Tab -->
        <div id="performance" class="small-tools-tab-content">
            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Image Optimization', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_image_threshold"><?php esc_html_e('Remove Image Threshold', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_image_threshold" 
                                   name="small_tools_remove_image_threshold" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_image_threshold')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('remove_image_threshold')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_disable_lazy_load"><?php esc_html_e('Disable Lazy Loading', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_disable_lazy_load" 
                                   name="small_tools_disable_lazy_load" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_disable_lazy_load')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('disable_lazy_load')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Script Optimization', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_disable_emojis"><?php esc_html_e('Disable Emojis', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_disable_emojis" 
                                   name="small_tools_disable_emojis" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_disable_emojis')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('disable_emojis')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_jquery_migrate"><?php esc_html_e('Remove jQuery Migrate', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_jquery_migrate" 
                                   name="small_tools_remove_jquery_migrate" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_jquery_migrate')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('remove_jquery_migrate')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Back to Top Tab -->
        <div id="back-to-top" class="small-tools-tab-content">
            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Back to Top Button', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_back_to_top"><?php esc_html_e('Enable Back to Top', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_back_to_top" 
                                   name="small_tools_back_to_top" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_back_to_top')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('back_to_top')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_back_to_top_position"><?php esc_html_e('Button Position', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <select id="small_tools_back_to_top_position" name="small_tools_back_to_top_position">
                                <?php
                                $positions = array(
                                    'left' => __('Left', 'small-tools'),
                                    'right' => __('Right', 'small-tools')
                                );
                                foreach ($positions as $value => $label) {
                                    printf(
                                        '<option value="%s" %s>%s</option>',
                                        esc_attr($value),
                                        selected($value, get_option('small_tools_back_to_top_position'), false),
                                        esc_html($label)
                                    );
                                }
                                ?>
                            </select>
                            <p class="description"><?php echo esc_html($this->get_setting_description('back_to_top_position')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_back_to_top_bg_color"><?php esc_html_e('Background Color', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="small_tools_back_to_top_bg_color" 
                                   name="small_tools_back_to_top_bg_color" 
                                   value="<?php echo esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)')); ?>" 
                                   class="small-tools-color-picker">
                            <p class="description"><?php echo esc_html($this->get_setting_description('back_to_top_bg_color')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_back_to_top_size"><?php esc_html_e('Button Size', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="small_tools_back_to_top_size" 
                                   name="small_tools_back_to_top_size" 
                                   value="<?php echo esc_attr(get_option('small_tools_back_to_top_size', '40')); ?>" 
                                   min="20" 
                                   max="100" 
                                   step="1" 
                                   class="small-text">
                            <p class="description"><?php echo esc_html($this->get_setting_description('back_to_top_size')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_back_to_top_icon"><?php esc_html_e('Custom Icon', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <div class="small-tools-media-upload">
                                <input type="text" 
                                       id="small_tools_back_to_top_icon" 
                                       name="small_tools_back_to_top_icon" 
                                       value="<?php echo esc_url(get_option('small_tools_back_to_top_icon')); ?>" 
                                       class="regular-text">
                                <button type="button" class="button small-tools-upload-btn"><?php esc_html_e('Upload Icon', 'small-tools'); ?></button>
                                <button type="button" class="button small-tools-remove-btn" <?php echo !get_option('small_tools_back_to_top_icon') ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Remove Icon', 'small-tools'); ?></button>
                                <div class="small-tools-preview" <?php echo !get_option('small_tools_back_to_top_icon') ? 'style="display:none;"' : ''; ?>>
                                    <a href="#" class="small-tools-preview-button" 
                                       style="<?php 
                                            echo sprintf('--preview-size: %dpx; --preview-bg-color: %s;',
                                                absint(get_option('small_tools_back_to_top_size', '40')),
                                                esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)'))
                                            );
                                        ?>">
                                        <img src="<?php echo esc_url(get_option('small_tools_back_to_top_icon')); ?>" alt="<?php esc_attr_e('Icon preview', 'small-tools'); ?>">
                                    </a>
                                </div>
                                <p class="description"><?php echo esc_html($this->get_setting_description('back_to_top_icon')); ?></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Admin Tab -->
        <div id="admin" class="small-tools-tab-content">
            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Admin Interface', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_dark_mode_enabled"><?php esc_html_e('Enable Dark Mode', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_dark_mode_enabled" 
                                   name="small_tools_dark_mode_enabled" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_dark_mode_enabled')); ?>>
                            <p class="description"><?php echo esc_html($this->get_setting_description('dark_mode_enabled')); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_admin_footer_text"><?php esc_html_e('Admin Footer Text', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="small_tools_admin_footer_text" 
                                   name="small_tools_admin_footer_text" 
                                   value="<?php echo esc_attr(get_option('small_tools_admin_footer_text')); ?>" 
                                   class="regular-text">
                            <p class="description"><?php echo esc_html($this->get_setting_description('admin_footer_text')); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="small-tools-section">
                <h3 class="small-tools-section-title"><?php esc_html_e('Admin Bar Cleanup', 'small-tools'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_wp_logo"><?php esc_html_e('Remove WordPress Logo', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_wp_logo" 
                                   name="small_tools_remove_wp_logo" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_wp_logo')); ?>>
                            <p class="description"><?php esc_html_e('Remove WordPress logo and menu from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_site_name"><?php esc_html_e('Remove Site Name', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_site_name" 
                                   name="small_tools_remove_site_name" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_site_name')); ?>>
                            <p class="description"><?php esc_html_e('Remove home icon and site name from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_customize_menu"><?php esc_html_e('Remove Customize Menu', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_customize_menu" 
                                   name="small_tools_remove_customize_menu" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_customize_menu')); ?>>
                            <p class="description"><?php esc_html_e('Remove customize menu from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_updates_menu"><?php esc_html_e('Remove Updates Menu', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_updates_menu" 
                                   name="small_tools_remove_updates_menu" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_updates_menu')); ?>>
                            <p class="description"><?php esc_html_e('Remove updates counter and link from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_comments_menu"><?php esc_html_e('Remove Comments Menu', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_comments_menu" 
                                   name="small_tools_remove_comments_menu" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_comments_menu')); ?>>
                            <p class="description"><?php esc_html_e('Remove comments counter and link from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_new_content"><?php esc_html_e('Remove New Content Menu', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_new_content" 
                                   name="small_tools_remove_new_content" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_new_content')); ?>>
                            <p class="description"><?php esc_html_e('Remove new content menu from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_howdy"><?php esc_html_e('Remove Howdy', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_howdy" 
                                   name="small_tools_remove_howdy" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_howdy')); ?>>
                            <p class="description"><?php esc_html_e('Remove the Howdy greeting from the admin bar', 'small-tools'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="small_tools_remove_help"><?php esc_html_e('Remove Help', 'small-tools'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="small_tools_remove_help" 
                                   name="small_tools_remove_help" 
                                   class="smiling_syntax_toggle"
                                   value="yes"
                                   <?php checked('yes', get_option('small_tools_remove_help')); ?>>
                            <p class="description"><?php esc_html_e('Remove the Help tab and drawer from admin pages', 'small-tools'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div> 