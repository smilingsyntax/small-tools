<?php

class Small_Tools_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add handlers for utility actions
        add_action('admin_init', array($this, 'handle_utility_actions'));
        add_action('admin_notices', array($this, 'admin_notices'));
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
        register_setting('small-tools-general', 'small_tools_disable_right_click', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_remove_image_threshold', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_disable_lazy_load', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_disable_emojis', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_remove_jquery_migrate', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_back_to_top', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_dark_mode_enabled', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-general', 'small_tools_admin_footer_text', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));

        // Security Settings
        register_setting('small-tools-security', 'small_tools_force_strong_passwords', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-security', 'small_tools_disable_xmlrpc', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
        register_setting('small-tools-security', 'small_tools_hide_wp_version', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));

        // WooCommerce Settings
        register_setting('small-tools-woocommerce', 'small_tools_wc_variation_threshold', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate')
        ));
    }

    public function sanitize_and_regenerate($value) {
        // Add a small delay to ensure all options are saved before regenerating
        add_action('shutdown', function() {
            Small_Tools_Settings::get_instance()->generate_settings_file();
        });
        return $value;
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

    public function handle_utility_actions() {
        // Handle Database Cleanup
        if (isset($_POST['small_tools_cleanup_db']) && check_admin_referer('small_tools_db_cleanup', 'small_tools_db_nonce')) {
            $this->handle_database_cleanup();
        }

        // Handle Settings Export
        if (isset($_POST['small_tools_export']) && check_admin_referer('small_tools_export_settings', 'small_tools_export_nonce')) {
            $this->export_settings();
        }

        // Handle Settings Import
        if (isset($_POST['small_tools_import']) && check_admin_referer('small_tools_import_settings', 'small_tools_import_nonce')) {
            $this->import_settings();
            // Regenerate settings file after import
            Small_Tools_Settings::get_instance()->generate_settings_file();
        }

        // Handle Settings File Regeneration
        if (isset($_POST['small_tools_regenerate_settings']) && check_admin_referer('small_tools_regenerate_settings', 'small_tools_regenerate_nonce')) {
            if (Small_Tools_Settings::get_instance()->generate_settings_file()) {
                set_transient('small_tools_admin_notice', array(
                    'type' => 'success',
                    'message' => 'Settings file regenerated successfully.'
                ), 45);
            }
        }

        // Handle Reset to Defaults
        if (isset($_POST['small_tools_reset_defaults']) && check_admin_referer('small_tools_reset_defaults', 'small_tools_reset_nonce')) {
            if (Small_Tools_Settings::get_instance()->reset_to_defaults()) {
                set_transient('small_tools_admin_notice', array(
                    'type' => 'success',
                    'message' => 'Settings reset to defaults successfully.'
                ), 45);
            }
        }
    }

    private function handle_database_cleanup() {
        global $wpdb;
        $cleaned = 0;

        if (!empty($_POST['cleanup_options'])) {
            foreach ($_POST['cleanup_options'] as $option) {
                switch ($option) {
                    case 'revisions':
                        $cleaned += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");
                        break;

                    case 'autodrafts':
                        $cleaned += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'");
                        break;

                    case 'trash':
                        $cleaned += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'trash'");
                        break;

                    case 'spam':
                        $cleaned += $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'");
                        break;

                    case 'transients':
                        // Delete expired transients
                        $time = time();
                        $cleaned += $wpdb->query($wpdb->prepare("
                            DELETE FROM $wpdb->options 
                            WHERE option_name LIKE %s 
                            OR option_name LIKE %s 
                            AND option_value < %d",
                            $wpdb->esc_like('_transient_timeout_') . '%',
                            $wpdb->esc_like('_site_transient_timeout_') . '%',
                            $time
                        ));
                        break;
                }
            }
            
            // Optimize tables after cleanup
            $wpdb->query("OPTIMIZE TABLE $wpdb->posts, $wpdb->comments, $wpdb->options");
            
            set_transient('small_tools_admin_notice', array(
                'type' => 'success',
                'message' => sprintf('%d items cleaned from the database.', $cleaned)
            ), 45);
        }
    }

    private function export_settings() {
        $settings = array();
        
        // Get all plugin options
        $options = array(
            'small_tools_disable_right_click',
            'small_tools_remove_image_threshold',
            'small_tools_disable_lazy_load',
            'small_tools_disable_emojis',
            'small_tools_remove_jquery_migrate',
            'small_tools_force_strong_passwords',
            'small_tools_disable_xmlrpc',
            'small_tools_hide_wp_version',
            'small_tools_wc_variation_threshold',
            'small_tools_admin_footer_text',
            'small_tools_dark_mode_enabled'
        );

        foreach ($options as $option) {
            $settings[$option] = get_option($option);
        }

        // Generate JSON file
        $json = json_encode($settings, JSON_PRETTY_PRINT);
        
        // Force download
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="small-tools-settings.json"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo $json;
        exit;
    }

    private function import_settings() {
        if (!isset($_FILES['small_tools_import_file'])) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => 'No file was uploaded.'
            ), 45);
            return;
        }

        $file = $_FILES['small_tools_import_file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => 'Error uploading file.'
            ), 45);
            return;
        }

        $json = file_get_contents($file['tmp_name']);
        $settings = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => 'Invalid JSON file.'
            ), 45);
            return;
        }

        foreach ($settings as $option => $value) {
            update_option($option, $value);
        }

        set_transient('small_tools_admin_notice', array(
            'type' => 'success',
            'message' => 'Settings imported successfully.'
        ), 45);
    }

    public function admin_notices() {
        $notice = get_transient('small_tools_admin_notice');
        if ($notice) {
            printf(
                '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
                esc_attr($notice['type']),
                esc_html($notice['message'])
            );
            delete_transient('small_tools_admin_notice');
        }
    }
} 