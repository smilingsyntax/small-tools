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