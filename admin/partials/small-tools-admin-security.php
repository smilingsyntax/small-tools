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
        settings_fields('small-tools-security');
        do_settings_sections('small-tools-security');
        wp_nonce_field('small_tools_security_settings', 'small_tools_security_nonce');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="small_tools_force_strong_passwords">Force Strong Passwords</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_force_strong_passwords" 
                           name="small_tools_force_strong_passwords" value="yes"
                           <?php checked('yes', get_option('small_tools_force_strong_passwords')); ?>>
                    <p class="description">Enforce strong password requirements for all users.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_disable_xmlrpc">Disable XML-RPC</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_disable_xmlrpc" 
                           name="small_tools_disable_xmlrpc" value="yes"
                           <?php checked('yes', get_option('small_tools_disable_xmlrpc')); ?>>
                    <p class="description">Disable XML-RPC functionality to prevent potential security vulnerabilities.</p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="small_tools_hide_wp_version">Hide WordPress Version</label>
                </th>
                <td>
                    <input type="checkbox" id="small_tools_hide_wp_version" 
                           name="small_tools_hide_wp_version" value="yes"
                           <?php checked('yes', get_option('small_tools_hide_wp_version')); ?>>
                    <p class="description">Remove WordPress version number from HTML source code.</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div> 