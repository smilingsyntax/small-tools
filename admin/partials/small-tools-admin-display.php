<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('small-tools-general');
        do_settings_sections('small-tools-general');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="small_tools_disable_right_click">Disable Right Click</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_disable_right_click" 
                           name="small_tools_disable_right_click" value="yes"
                           <?php checked('yes', get_option('small_tools_disable_right_click')); ?>>
                    <p class="description">Prevent users from right-clicking on your website content.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_remove_image_threshold">Remove Image Threshold</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_remove_image_threshold" 
                           name="small_tools_remove_image_threshold" value="yes"
                           <?php checked('yes', get_option('small_tools_remove_image_threshold')); ?>>
                    <p class="description">Allow uploading images in their original size without WordPress scaling them down.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_disable_lazy_load">Disable Lazy Loading</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_disable_lazy_load" 
                           name="small_tools_disable_lazy_load" value="yes"
                           <?php checked('yes', get_option('small_tools_disable_lazy_load')); ?>>
                    <p class="description">Disable WordPress default lazy loading of images.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_disable_emojis">Disable Emojis</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_disable_emojis" 
                           name="small_tools_disable_emojis" value="yes"
                           <?php checked('yes', get_option('small_tools_disable_emojis')); ?>>
                    <p class="description">Remove WordPress emoji scripts to improve site speed.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_remove_jquery_migrate">Remove jQuery Migrate</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_remove_jquery_migrate" 
                           name="small_tools_remove_jquery_migrate" value="yes"
                           <?php checked('yes', get_option('small_tools_remove_jquery_migrate')); ?>>
                    <p class="description">Remove jQuery Migrate script for cleaner script loading.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top">Back to Top Button</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_back_to_top" 
                           name="small_tools_back_to_top" value="yes"
                           <?php checked('yes', get_option('small_tools_back_to_top')); ?>>
                    <p class="description">Add a back to top button to your website.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_position">Back to Top Position</label>
                </th>
                <td>
                    <select id="small_tools_back_to_top_position" name="small_tools_back_to_top_position">
                        <option value="left" <?php selected('left', get_option('small_tools_back_to_top_position')); ?>>Left</option>
                        <option value="right" <?php selected('right', get_option('small_tools_back_to_top_position')); ?>>Right</option>
                    </select>
                    <p class="description">Choose the position of the back to top button.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_bg_color">Background Color</label>
                </th>
                <td>
                    <input type="text" id="small_tools_back_to_top_bg_color" 
                           name="small_tools_back_to_top_bg_color" 
                           value="<?php echo esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)')); ?>" 
                           class="small-tools-color-picker">
                    <p class="description">Choose the background color for the back to top button.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_size">Button Size</label>
                </th>
                <td>
                    <input type="number" id="small_tools_back_to_top_size" 
                           name="small_tools_back_to_top_size" 
                           value="<?php echo esc_attr(get_option('small_tools_back_to_top_size', '40')); ?>" 
                           min="20" 
                           max="100" 
                           step="1" 
                           class="small-text">
                    <p class="description">Set the size of the back to top button in pixels (20-100).</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_back_to_top_icon">Back to Top Icon</label>
                </th>
                <td>
                    <div class="small-tools-media-upload">
                        <input type="text" id="small_tools_back_to_top_icon" 
                               name="small_tools_back_to_top_icon" 
                               value="<?php echo esc_attr(get_option('small_tools_back_to_top_icon')); ?>" 
                               class="regular-text">
                        <button type="button" class="button small-tools-upload-btn">Upload Icon</button>
                        <button type="button" class="button small-tools-remove-btn" <?php echo !get_option('small_tools_back_to_top_icon') ? 'style="display:none;"' : ''; ?>>Remove Icon</button>
                        <div class="small-tools-preview" <?php echo !get_option('small_tools_back_to_top_icon') ? 'style="display:none;"' : ''; ?>>
                            <a href="#" class="small-tools-preview-button">
                                <img src="<?php echo esc_url(get_option('small_tools_back_to_top_icon')); ?>" alt="Icon preview">
                            </a>
                        </div>
                        <p class="description">Upload a custom icon for the back to top button (recommended size: 24x24px). Leave empty to use default arrow icon.</p>
                    </div>
                    <style>
                        .small-tools-preview-button {
                            display: inline-block;
                            width: <?php echo esc_attr(get_option('small_tools_back_to_top_size', '40')); ?>px;
                            height: <?php echo esc_attr(get_option('small_tools_back_to_top_size', '40')); ?>px;
                            background: <?php echo esc_attr(get_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)')); ?>;
                            border-radius: 50%;
                            position: relative;
                            text-decoration: none;
                            margin: 10px 0;
                        }
                        .small-tools-preview-button img {
                            width: 24px;
                            height: 24px;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        }
                        .small-tools-preview-button:hover {
                            background: rgba(0, 0, 0, 0.9);
                        }
                    </style>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_dark_mode_enabled">Enable Dark Mode</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_dark_mode_enabled" 
                           name="small_tools_dark_mode_enabled" value="yes"
                           <?php checked('yes', get_option('small_tools_dark_mode_enabled')); ?>>
                    <p class="description">Enable dark mode for WordPress admin dashboard.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_admin_footer_text">Admin Footer Text</label>
                </th>
                <td>
                    <input type="text" id="small_tools_admin_footer_text" 
                           name="small_tools_admin_footer_text" 
                           value="<?php echo esc_attr(get_option('small_tools_admin_footer_text')); ?>" 
                           class="regular-text">
                    <p class="description">Custom text to display in the admin footer.</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div> 