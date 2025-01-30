<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="<?php echo esc_url(admin_url('options.php')); ?>">
        <?php
        settings_fields('small-tools-general');
        do_settings_sections('small-tools-general');
        ?>
        
        <table class="form-table" role="presentation">
            <?php foreach (array(
                'disable_right_click' => __('Disable Right Click', 'small-tools'),
                'remove_image_threshold' => __('Remove Image Threshold', 'small-tools'),
                'disable_lazy_load' => __('Disable Lazy Loading', 'small-tools'),
                'disable_emojis' => __('Disable Emojis', 'small-tools'),
                'remove_jquery_migrate' => __('Remove jQuery Migrate', 'small-tools'),
                'back_to_top' => __('Back to Top Button', 'small-tools')
            ) as $key => $label): 
                $option_name = 'small_tools_' . $key;
            ?>
            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr($option_name); ?>"><?php echo esc_html($label); ?></label>
                </th>
                <td>
                    <input type="checkbox" 
                           id="<?php echo esc_attr($option_name); ?>" 
                           name="<?php echo esc_attr($option_name); ?>" 
                           value="yes"
                           <?php checked('yes', get_option($option_name)); ?>>
                    <p class="description"><?php echo esc_html($this->get_setting_description($key)); ?></p>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_position"><?php esc_html_e('Back to Top Position', 'small-tools'); ?></label>
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
                    <p class="description"><?php esc_html_e('Choose the position of the back to top button.', 'small-tools'); ?></p>
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
                    <p class="description"><?php esc_html_e('Choose the background color for the back to top button.', 'small-tools'); ?></p>
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
                    <p class="description"><?php esc_html_e('Set the size of the back to top button in pixels (20-100).', 'small-tools'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_icon"><?php esc_html_e('Back to Top Icon', 'small-tools'); ?></label>
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
                            <a href="#" class="small-tools-preview-button">
                                <img src="<?php echo esc_url(get_option('small_tools_back_to_top_icon')); ?>" alt="<?php esc_attr_e('Icon preview', 'small-tools'); ?>">
                            </a>
                        </div>
                        <p class="description"><?php esc_html_e('Upload a custom icon for the back to top button (recommended size: 24x24px). Leave empty to use default arrow icon.', 'small-tools'); ?></p>
                    </div>
                    <style>
                        .small-tools-preview-button {
                            display: inline-block;
                            width: <?php echo absint(get_option('small_tools_back_to_top_size', '40')); ?>px;
                            height: <?php echo absint(get_option('small_tools_back_to_top_size', '40')); ?>px;
                            background: <?php echo esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)')); ?>;
                            border-radius: 50%;
                            position: relative;
                            text-decoration: none;
                            margin: 10px 0;
                        }
                        .small-tools-preview-button img {
                            width: <?php echo absint(get_option('small_tools_back_to_top_size', '40') * 0.6); ?>px;
                            height: <?php echo absint(get_option('small_tools_back_to_top_size', '40') * 0.6); ?>px;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        }
                        .small-tools-preview-button:hover {
                            background: <?php echo esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.9)')); ?>;
                        }
                    </style>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_dark_mode_enabled"><?php esc_html_e('Enable Dark Mode', 'small-tools'); ?></label>
                </th>
                <td>
                    <input type="checkbox" 
                           id="small_tools_dark_mode_enabled" 
                           name="small_tools_dark_mode_enabled" 
                           value="yes"
                           <?php checked('yes', get_option('small_tools_dark_mode_enabled')); ?>>
                    <p class="description"><?php esc_html_e('Enable dark mode for WordPress admin dashboard.', 'small-tools'); ?></p>
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
                    <p class="description"><?php esc_html_e('Custom text to display in the admin footer.', 'small-tools'); ?></p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div> 