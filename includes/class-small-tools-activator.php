<?php

/**
 * Fired during plugin activation
 */
class Small_Tools_Activator {

    public static function activate() {
        // Set default options
        self::set_default_options();
    }

    private static function set_default_options() {
        // General settings
        add_option('small_tools_disable_right_click', 'no');
        add_option('small_tools_remove_image_threshold', 'no');
        add_option('small_tools_disable_lazy_load', 'no');
        add_option('small_tools_disable_emojis', 'no');
        add_option('small_tools_remove_jquery_migrate', 'no');
        add_option('small_tools_back_to_top', 'no');
        add_option('small_tools_back_to_top_position', 'right');
        add_option('small_tools_back_to_top_icon', '');
        add_option('small_tools_back_to_top_bg_color', 'rgba(0, 0, 0, 0.7)');
        add_option('small_tools_back_to_top_size', '40');
        
        // Security settings
        add_option('small_tools_force_strong_passwords', 'no');
        add_option('small_tools_disable_xmlrpc', 'no');
        add_option('small_tools_hide_wp_version', 'no');
        
        // WooCommerce settings
        add_option('small_tools_wc_variation_threshold', '30');
        
        // Admin settings
        add_option('small_tools_admin_footer_text', 'Thank you for using <a href="https://smalltools.io" target="_blank">Small Tools</a>');
        add_option('small_tools_dark_mode_enabled', 'no');

        // Login settings
        add_option('small_tools_login_logo', '');
        add_option('small_tools_login_logo_url', home_url());
    }
} 