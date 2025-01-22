<?php

class Small_Tools_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'Small Tools Settings',
            'Small Tools',
            'manage_options',
            'small-tools-settings',
            array($this, 'display_plugin_admin_page'),
            'dashicons-admin-tools',
            80
        );

        // Add submenu pages
        add_submenu_page(
            'small-tools-settings',
            'General Settings',
            'General Settings',
            'manage_options',
            'small-tools-settings'
        );

        add_submenu_page(
            'small-tools-settings',
            'Security Settings',
            'Security',
            'manage_options',
            'small-tools-security',
            array($this, 'display_security_page')
        );

        add_submenu_page(
            'small-tools-settings',
            'WooCommerce Settings',
            'WooCommerce',
            'manage_options',
            'small-tools-woocommerce',
            array($this, 'display_woocommerce_page')
        );

        add_submenu_page(
            'small-tools-settings',
            'Tools & Utilities',
            'Tools',
            'manage_options',
            'small-tools-utilities',
            array($this, 'display_utilities_page')
        );
    }

    public function register_settings() {
        // General Settings
        register_setting('small-tools-general', 'small_tools_disable_right_click');
        register_setting('small-tools-general', 'small_tools_remove_image_threshold');
        register_setting('small-tools-general', 'small_tools_disable_lazy_load');
        register_setting('small-tools-general', 'small_tools_disable_emojis');
        register_setting('small-tools-general', 'small_tools_remove_jquery_migrate');
        register_setting('small-tools-general', 'small_tools_dark_mode_enabled');
        register_setting('small-tools-general', 'small_tools_admin_footer_text');

        // Security Settings
        register_setting('small-tools-security', 'small_tools_force_strong_passwords');
        register_setting('small-tools-security', 'small_tools_disable_xmlrpc');
        register_setting('small-tools-security', 'small_tools_hide_wp_version');

        // WooCommerce Settings
        register_setting('small-tools-woocommerce', 'small_tools_wc_variation_threshold');
    }

    public function display_plugin_admin_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/small-tools-admin-display.php';
    }

    public function display_security_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/small-tools-admin-security.php';
    }

    public function display_woocommerce_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/small-tools-admin-woocommerce.php';
    }

    public function display_utilities_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/small-tools-admin-utilities.php';
    }
} 