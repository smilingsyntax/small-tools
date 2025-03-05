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
        <a href="#" class="nav-tab" data-tab="login"><?php esc_html_e('Login', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="components"><?php esc_html_e('Components', 'small-tools'); ?></a>
        <a href="#" class="nav-tab" data-tab="updates"><?php esc_html_e('Updates', 'small-tools'); ?></a>
    </h2>

    <form method="post" action="" class="small-tools-settings-form" id="small-tools-settings-form">
        <?php wp_nonce_field('small_tools_general_settings', 'small_tools_general_nonce'); ?>

        <!-- General Tab -->
        <div id="general" class="small-tools-tab-content">
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Content Protection', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e('Right Click', 'small-tools'); ?></th>
                            <td>
                                <label for="small_tools_disable_right_click">
                                    <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_right_click" name="small_tools_disable_right_click" value="yes" <?php checked(get_option('small_tools_disable_right_click'), 'yes'); ?>>
                                    <?php esc_html_e('Disable right click on the website', 'small-tools'); ?>
                                </label>
                                <p class="description"><?php esc_html_e('This will prevent users from right-clicking on your website.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Content Copying', 'small-tools'); ?></th>
                            <td>
                                <label for="small_tools_prevent_copying">
                                    <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_prevent_copying" name="small_tools_prevent_copying" value="yes" <?php checked(get_option('small_tools_prevent_copying'), 'yes'); ?>>
                                    <?php esc_html_e('Prevent unauthorized copying', 'small-tools'); ?>
                                </label>
                                <p class="description"><?php esc_html_e('This will disable keyboard shortcuts / mouse events for copying content (Ctrl+C, Cmd+C, etc.).', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Colors', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="small_tools_selection_color"><?php esc_html_e('Selection Background Color', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="small_tools_selection_color" 
                                       name="small_tools_selection_color" 
                                       value="<?php echo esc_attr(get_option('small_tools_selection_color', '#ACCEF7')); ?>" 
                                       class="small-tools-color-picker" 
                                       data-default-color="#ACCEF7" />
                                <p class="description"><?php esc_html_e('Choose the background color for selected text on your website.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_selection_text_color"><?php esc_html_e('Selection Text Color', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="small_tools_selection_text_color" 
                                       name="small_tools_selection_text_color" 
                                       value="<?php echo esc_attr(get_option('small_tools_selection_text_color', '#000000')); ?>" 
                                       class="small-tools-color-picker" 
                                       data-default-color="#000000" />
                                <p class="description"><?php esc_html_e('Choose the background color for selected text on your website.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Content Management', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
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
        </div>

        <!-- Components Tab -->
        <div id="components" class="small-tools-tab-content">
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('WordPress Components', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_gutenberg"><?php esc_html_e('Disable Gutenberg', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_gutenberg" 
                                       name="small_tools_disable_gutenberg" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_gutenberg')); ?>>
                                <p class="description"><?php esc_html_e('Disable the Gutenberg editor globally and restore the classic editor.', 'small-tools'); ?></p>
                                
                                <div class="small-tools-post-types-wrapper" style="margin-top: 20px;">
                                    <h4><?php esc_html_e('Disable Gutenberg for Specific Post Types:', 'small-tools'); ?></h4>
                                    <?php
                                    // Get post types that support the editor feature
                                    $post_types = get_post_types(['show_ui' => true], 'objects');
                                    $disabled_post_types = (array) get_option('small_tools_gutenberg_disabled_post_types', array());
                                    if (!is_array($disabled_post_types)) {
                                        $disabled_post_types = array();
                                    }
                                    
                                    // List of post types that should not be included
                                    $excluded_types = array(
                                        'attachment',
                                        'revision',
                                        'nav_menu_item',
                                        'custom_css',
                                        'customize_changeset',
                                        'oembed_cache',
                                        'user_request',
                                        'wp_block',
                                        'wp_template',
                                        'wp_template_part',
                                        'wp_global_styles',
                                        'wp_navigation'
                                    );
                                    
                                    foreach ($post_types as $post_type) :
                                        // Skip if post type is in excluded list or doesn't support editor
                                        if (in_array($post_type->name, $excluded_types) || !post_type_supports($post_type->name, 'editor')) {
                                            continue;
                                        }
                                    ?>
                                        <div class="small-tools-post-type-toggle" style="margin: 10px 0;">
                                            <label>
                                                <input type="checkbox" 
                                                       name="small_tools_gutenberg_disabled_post_types[]" 
                                                       value="<?php echo esc_attr($post_type->name); ?>"
                                                       class="smiling_syntax_toggle"
                                                       <?php checked(in_array($post_type->name, $disabled_post_types)); ?>>
                                                <?php echo esc_html($post_type->labels->singular_name); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="description"><?php esc_html_e('Select post types where you want to disable Gutenberg editor. These settings will only apply if global Gutenberg disable is turned off.', 'small-tools'); ?></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_comments"><?php esc_html_e('Disable Comments', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_comments" 
                                       name="small_tools_disable_comments" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_comments')); ?>>
                                <p class="description"><?php esc_html_e('Completely disable comments functionality across the site.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_rest_api"><?php esc_html_e('Disable REST API', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_rest_api" 
                                       name="small_tools_disable_rest_api" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_rest_api')); ?>>
                                <p class="description"><?php esc_html_e('Disable the WordPress REST API functionality.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_feeds"><?php esc_html_e('Disable Feeds', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_feeds" 
                                       name="small_tools_disable_feeds" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_feeds')); ?>>
                                <p class="description"><?php esc_html_e('Disable all RSS, Atom and RDF feeds.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_jquery_migrate"><?php esc_html_e('Disable jQuery Migrate', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_jquery_migrate" 
                                       name="small_tools_disable_jquery_migrate" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_jquery_migrate')); ?>>
                                <p class="description"><?php esc_html_e('Remove jQuery Migrate script from loading on the frontend.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Updates Tab -->
        <div id="updates" class="small-tools-tab-content">
            <h2><?php esc_html_e('WordPress Updates Control', 'small-tools'); ?></h2>
            <p><?php esc_html_e('Control which WordPress updates are enabled or disabled on your site.', 'small-tools'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Core Updates', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_core_updates">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_core_updates" name="small_tools_disable_core_updates" value="yes" <?php checked(get_option('small_tools_disable_core_updates'), 'yes'); ?>>
                            <?php esc_html_e('Disable WordPress core updates', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will prevent WordPress from updating to new versions.', 'small-tools'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Plugin Updates', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_plugin_updates">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_plugin_updates" name="small_tools_disable_plugin_updates" value="yes" <?php checked(get_option('small_tools_disable_plugin_updates'), 'yes'); ?>>
                            <?php esc_html_e('Disable plugin updates', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will prevent plugins from showing update notifications and updating.', 'small-tools'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Theme Updates', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_theme_updates">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_theme_updates" name="small_tools_disable_theme_updates" value="yes" <?php checked(get_option('small_tools_disable_theme_updates'), 'yes'); ?>>
                            <?php esc_html_e('Disable theme updates', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will prevent themes from showing update notifications and updating.', 'small-tools'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Translation Updates', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_translation_updates">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_translation_updates" name="small_tools_disable_translation_updates" value="yes" <?php checked(get_option('small_tools_disable_translation_updates'), 'yes'); ?>>
                            <?php esc_html_e('Disable translation updates', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will prevent WordPress from updating translations.', 'small-tools'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Update Emails', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_update_emails">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_update_emails" name="small_tools_disable_update_emails" value="yes" <?php checked(get_option('small_tools_disable_update_emails'), 'yes'); ?>>
                            <?php esc_html_e('Disable update emails', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will prevent WordPress from sending emails about available updates.', 'small-tools'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Update Page', 'small-tools'); ?></th>
                    <td>
                        <label for="small_tools_disable_update_page">
                            <input class="smiling_syntax_toggle" type="checkbox" id="small_tools_disable_update_page" name="small_tools_disable_update_page" value="yes" <?php checked(get_option('small_tools_disable_update_page'), 'yes'); ?>>
                            <?php esc_html_e('Hide WordPress update page', 'small-tools'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('This will hide the WordPress update page from the admin menu.', 'small-tools'); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Media Tab -->
        <div id="media" class="small-tools-tab-content">
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Media Replacement', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
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
            </div>

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Upload Settings', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
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
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Admin Interface', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
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
            </div>

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Admin Bar Cleanup', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
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

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Admin Interface Enhancements', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="small_tools_hide_admin_notices"><?php esc_html_e('Hide Admin Notices', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_hide_admin_notices" 
                                       name="small_tools_hide_admin_notices" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_hide_admin_notices')); ?>>
                                <p class="description"><?php esc_html_e('Hide all admin notices to reduce clutter in the admin area.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_hide_admin_bar"><?php esc_html_e('Hide Admin Bar', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_hide_admin_bar" 
                                       name="small_tools_hide_admin_bar" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_hide_admin_bar')); ?>>
                                <p class="description"><?php esc_html_e('Hide the admin bar on the frontend for logged-in users.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_wider_admin_menu"><?php esc_html_e('Wider Admin Menu', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_wider_admin_menu" 
                                       name="small_tools_wider_admin_menu" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_wider_admin_menu')); ?>>
                                <p class="description"><?php esc_html_e('Make the admin menu wider for better readability.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Dashboard Widgets', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_welcome"><?php esc_html_e('Welcome Panel', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_welcome" 
                                       name="small_tools_disable_dashboard_welcome" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_welcome')); ?>>
                                <p class="description"><?php esc_html_e('Remove the WordPress Welcome panel.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_site_health"><?php esc_html_e('Site Health Status', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_site_health" 
                                       name="small_tools_disable_dashboard_site_health" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_site_health')); ?>>
                                <p class="description"><?php esc_html_e('Remove the Site Health Status dashboard widget.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_at_a_glance"><?php esc_html_e('At a Glance', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_at_a_glance" 
                                       name="small_tools_disable_dashboard_at_a_glance" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_at_a_glance')); ?>>
                                <p class="description"><?php esc_html_e('Remove the At a Glance dashboard widget.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_activity"><?php esc_html_e('Activity Widget', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_activity" 
                                       name="small_tools_disable_dashboard_activity" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_activity')); ?>>
                                <p class="description"><?php esc_html_e('Remove the Activity dashboard widget.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_quick_press"><?php esc_html_e('Quick Draft Widget', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_quick_press" 
                                       name="small_tools_disable_dashboard_quick_press" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_quick_press')); ?>>
                                <p class="description"><?php esc_html_e('Remove the Quick Draft dashboard widget.', 'small-tools'); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="small_tools_disable_dashboard_news"><?php esc_html_e('WordPress News Widget', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" 
                                       id="small_tools_disable_dashboard_news" 
                                       name="small_tools_disable_dashboard_news" 
                                       class="smiling_syntax_toggle"
                                       value="yes"
                                       <?php checked('yes', get_option('small_tools_disable_dashboard_news')); ?>>
                                <p class="description"><?php esc_html_e('Remove the WordPress News dashboard widget.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Login Tab -->
        <div id="login" class="small-tools-tab-content">
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('Login Page Customization', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="small_tools_login_logo"><?php esc_html_e('Custom Login Logo', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <div class="small-tools-media-upload">
                                    <input type="text" 
                                           id="small_tools_login_logo" 
                                           name="small_tools_login_logo" 
                                           value="<?php echo esc_url(get_option('small_tools_login_logo')); ?>" 
                                           class="regular-text">
                                    <button type="button" class="button small-tools-upload-btn"><?php esc_html_e('Upload Logo', 'small-tools'); ?></button>
                                    <button type="button" class="button small-tools-remove-btn" <?php echo !get_option('small_tools_login_logo') ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Remove Logo', 'small-tools'); ?></button>
                                    <div class="small-tools-preview" <?php echo !get_option('small_tools_login_logo') ? 'style="display:none;"' : ''; ?>>
                                        <img src="<?php echo esc_url(get_option('small_tools_login_logo')); ?>" alt="<?php esc_attr_e('Login logo preview', 'small-tools'); ?>" style="max-width: 320px; height: auto;">
                                    </div>
                                    <p class="description"><?php esc_html_e('Upload a custom logo for the WordPress login page. Recommended size: 320px wide.', 'small-tools'); ?></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_login_logo_url"><?php esc_html_e('Logo URL', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <input type="url" 
                                       id="small_tools_login_logo_url" 
                                       name="small_tools_login_logo_url" 
                                       value="<?php echo esc_url(get_option('small_tools_login_logo_url', home_url())); ?>" 
                                       class="regular-text">
                                <p class="description"><?php esc_html_e('Enter the URL where users will be directed when clicking the login logo.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="small-tools-accordion">
                <div class="small-tools-accordion-header">
                    <h3><?php esc_html_e('User Columns', 'small-tools'); ?></h3>
                </div>
                <div class="small-tools-accordion-content">
                    <table class="form-table">
                        
                    <tr>
                            <th scope="row">
                                <label for="small_tools_enable_user_columns"><?php esc_html_e('User List Columns', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <label class="small-tools-toggle">
                                    <input type="checkbox" 
                                           id="small_tools_enable_user_columns" 
                                           name="small_tools_enable_user_columns" 
                                           value="yes" 
                                           <?php checked('yes', get_option('small_tools_enable_user_columns', 'yes')); ?>>
                                    <span class="slider"></span>
                                </label>
                                <p class="description"><?php esc_html_e('Add Registration Date column to users list.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="small_tools_enable_last_login"><?php esc_html_e('Last Login Column', 'small-tools'); ?></label>
                            </th>
                            <td>
                                <label class="small-tools-toggle">
                                    <input type="checkbox" 
                                           id="small_tools_enable_last_login" 
                                           name="small_tools_enable_last_login" 
                                           value="yes" 
                                           <?php checked('yes', get_option('small_tools_enable_last_login', 'yes')); ?>>
                                    <span class="slider"></span>
                                </label>
                                <p class="description"><?php esc_html_e('Add Last Login column to users list and track login times.', 'small-tools'); ?></p>
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
        <div class="small-tools-accordion">
            <div class="small-tools-accordion-header">
                <h3><?php esc_html_e('Login/Logout Redirects', 'small-tools'); ?></h3>
            </div>
            <div class="small-tools-accordion-content">
                <table class="form-table">
                    <!-- Login Redirect Settings -->
                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Login Redirects', 'small-tools'); ?>
                        </th>
                        <td>
                            <div class="small-tools-role-redirect-wrapper">
                                <div class="small-tools-role-default">
                                    <label>
                                        <strong><?php esc_html_e('Default Login Redirect URL', 'small-tools'); ?></strong>
                                        <input type="url" 
                                               name="small_tools_login_redirect_default_url" 
                                               value="<?php echo esc_url(get_option('small_tools_login_redirect_default_url')); ?>" 
                                               class="regular-text"
                                               placeholder="<?php esc_attr_e('Enter URL where users will be redirected after login', 'small-tools'); ?>">
                                    </label>
                                    <p class="description"><?php esc_html_e('Default URL where users will be redirected after login if no role-specific URL is set.', 'small-tools'); ?></p>
                                </div>
                                <div class="small-tools-role-list">
                                    <h4><?php esc_html_e('Role-Specific Login Redirects', 'small-tools'); ?></h4>
                                    <?php
                                    $roles = wp_roles()->get_names();
                                    $login_redirect_roles = get_option('small_tools_login_redirect_roles', array());
                                    foreach ($roles as $role_key => $role_name) :
                                        $role_url = isset($login_redirect_roles[$role_key]) ? $login_redirect_roles[$role_key] : '';
                                    ?>
                                    <div class="small-tools-role-redirect-row">
                                        <label>
                                            <strong><?php echo esc_html($role_name); ?></strong>
                                            <input type="url" 
                                                   name="small_tools_login_redirect_roles[<?php echo esc_attr($role_key); ?>]" 
                                                   value="<?php echo esc_url($role_url); ?>" 
                                                   class="regular-text"
                                                   placeholder="<?php esc_attr_e('Leave empty to use default URL', 'small-tools'); ?>">
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Logout Redirect Settings -->
                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Logout Redirects', 'small-tools'); ?>
                        </th>
                        <td>
                            <div class="small-tools-role-redirect-wrapper">
                                <div class="small-tools-role-default">
                                    <label>
                                        <strong><?php esc_html_e('Default Logout Redirect URL', 'small-tools'); ?></strong>
                                        <input type="url" 
                                               name="small_tools_logout_redirect_default_url" 
                                               value="<?php echo esc_url(get_option('small_tools_logout_redirect_default_url')); ?>" 
                                               class="regular-text"
                                               placeholder="<?php esc_attr_e('Enter URL where users will be redirected after logout', 'small-tools'); ?>">
                                    </label>
                                    <p class="description"><?php esc_html_e('Default URL where users will be redirected after logout if no role-specific URL is set.', 'small-tools'); ?></p>
                                </div>
                                <div class="small-tools-role-list">
                                    <h4><?php esc_html_e('Role-Specific Logout Redirects', 'small-tools'); ?></h4>
                                    <?php
                                    $logout_redirect_roles = get_option('small_tools_logout_redirect_roles', array());
                                    foreach ($roles as $role_key => $role_name) :
                                        $role_url = isset($logout_redirect_roles[$role_key]) ? $logout_redirect_roles[$role_key] : '';
                                    ?>
                                    <div class="small-tools-role-redirect-row">
                                        <label>
                                            <strong><?php echo esc_html($role_name); ?></strong>
                                            <input type="url" 
                                                   name="small_tools_logout_redirect_roles[<?php echo esc_attr($role_key); ?>]" 
                                                   value="<?php echo esc_url($role_url); ?>" 
                                                   class="regular-text"
                                                   placeholder="<?php esc_attr_e('Leave empty to use default URL', 'small-tools'); ?>">
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div> 