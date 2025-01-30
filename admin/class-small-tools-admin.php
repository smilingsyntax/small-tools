<?php

class Small_Tools_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
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
        $settings = array(
            'small_tools_disable_right_click' => 'boolean',
            'small_tools_remove_image_threshold' => 'boolean',
            'small_tools_disable_lazy_load' => 'boolean',
            'small_tools_disable_emojis' => 'boolean',
            'small_tools_remove_jquery_migrate' => 'boolean',
            'small_tools_back_to_top' => 'boolean',
            'small_tools_back_to_top_position' => 'string',
            'small_tools_back_to_top_icon' => 'url',
            'small_tools_back_to_top_bg_color' => 'string',
            'small_tools_back_to_top_size' => 'number',
            'small_tools_dark_mode_enabled' => 'boolean',
            'small_tools_admin_footer_text' => 'html'
        );

        foreach ($settings as $option => $type) {
            register_setting('small-tools-general', $option, array(
                'sanitize_callback' => array($this, 'sanitize_and_regenerate'),
                'type' => $type,
                'show_in_rest' => true
            ));
        }

        // Security Settings
        $security_settings = array(
            'small_tools_force_strong_passwords' => 'boolean',
            'small_tools_disable_xmlrpc' => 'boolean',
            'small_tools_hide_wp_version' => 'boolean'
        );

        foreach ($security_settings as $option => $type) {
            register_setting('small-tools-security', $option, array(
                'sanitize_callback' => array($this, 'sanitize_and_regenerate'),
                'type' => $type,
                'show_in_rest' => true
            ));
        }

        // WooCommerce Settings
        register_setting('small-tools-woocommerce', 'small_tools_wc_variation_threshold', array(
            'sanitize_callback' => array($this, 'sanitize_and_regenerate'),
            'type' => 'number',
            'show_in_rest' => true
        ));
    }

    public function sanitize_and_regenerate($value) {
        // Verify nonce based on the option being updated
        $option = current_filter();
        $nonce_name = '';
        $nonce_action = '';
        
        if (strpos($option, 'small_tools_wc_') !== false) {
            $nonce_name = 'small_tools_woocommerce_nonce';
            $nonce_action = 'small_tools_woocommerce_settings';
        } elseif (in_array($option, array(
            'pre_update_option_small_tools_force_strong_passwords',
            'pre_update_option_small_tools_disable_xmlrpc',
            'pre_update_option_small_tools_hide_wp_version'
        ))) {
            $nonce_name = 'small_tools_security_nonce';
            $nonce_action = 'small_tools_security_settings';
        } else {
            $nonce_name = 'small_tools_general_nonce';
            $nonce_action = 'small_tools_general_settings';
        }

        // Verify nonce
        if (!isset($_POST[$nonce_name]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_name])), $nonce_action)) {
            wp_die('Security check failed');
        }

        // Add a small delay to ensure all options are saved before regenerating
        add_action('shutdown', function() {
            Small_Tools_Settings::get_instance()->generate_settings_file();
        });

        // Sanitize based on option type
        $option = current_filter();
        
        switch ($option) {
            case 'pre_update_option_small_tools_back_to_top_bg_color':
                return sanitize_text_field($value);
                
            case 'pre_update_option_small_tools_back_to_top_size':
                $size = absint($value);
                return ($size >= 20 && $size <= 100) ? $size : 40;
                
            case 'pre_update_option_small_tools_back_to_top_icon':
                return esc_url_raw($value);
                
            case 'pre_update_option_small_tools_back_to_top_position':
                return in_array($value, array('left', 'right')) ? $value : 'right';
                
            case 'pre_update_option_small_tools_admin_footer_text':
                return wp_kses_post($value);
                
            case 'pre_update_option_small_tools_wc_variation_threshold':
                return absint($value);
                
            default:
                // For yes/no options
                if (in_array($option, array(
                    'pre_update_option_small_tools_disable_right_click',
                    'pre_update_option_small_tools_remove_image_threshold',
                    'pre_update_option_small_tools_disable_lazy_load',
                    'pre_update_option_small_tools_disable_emojis',
                    'pre_update_option_small_tools_remove_jquery_migrate',
                    'pre_update_option_small_tools_back_to_top',
                    'pre_update_option_small_tools_force_strong_passwords',
                    'pre_update_option_small_tools_disable_xmlrpc',
                    'pre_update_option_small_tools_hide_wp_version',
                    'pre_update_option_small_tools_dark_mode_enabled'
                ))) {
                    return in_array($value, array('yes', 'no')) ? $value : 'no';
                }
                return sanitize_text_field($value);
        }
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
        // Verify user capabilities first
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'small-tools'));
        }

        // Handle Database Cleanup
        if (isset($_POST['small_tools_cleanup_db'])) {
            if (!check_admin_referer('small_tools_db_cleanup', 'small_tools_db_nonce')) {
                wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
            }
            $this->handle_database_cleanup();
        }

        // Handle Settings Export
        if (isset($_POST['small_tools_export'])) {
            if (!check_admin_referer('small_tools_export_settings', 'small_tools_export_nonce')) {
                wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
            }
            $this->export_settings();
        }

        // Handle Settings Import
        if (isset($_POST['small_tools_import'])) {
            if (!check_admin_referer('small_tools_import_settings', 'small_tools_import_nonce')) {
                wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
            }
            $this->import_settings();
            // Regenerate settings file after import
            Small_Tools_Settings::get_instance()->generate_settings_file();
        }

        // Handle Settings File Regeneration
        if (isset($_POST['small_tools_regenerate_settings'])) {
            if (!check_admin_referer('small_tools_regenerate_settings', 'small_tools_regenerate_nonce')) {
                wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
            }
            if (Small_Tools_Settings::get_instance()->generate_settings_file()) {
                set_transient('small_tools_admin_notice', array(
                    'type' => 'success',
                    'message' => __('Settings file regenerated successfully.', 'small-tools')
                ), 45);
            }
        }

        // Handle Reset to Defaults
        if (isset($_POST['small_tools_reset_defaults'])) {
            if (!check_admin_referer('small_tools_reset_defaults', 'small_tools_reset_nonce')) {
                wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
            }
            if (Small_Tools_Settings::get_instance()->reset_to_defaults()) {
                set_transient('small_tools_admin_notice', array(
                    'type' => 'success',
                    'message' => __('Settings reset to defaults successfully.', 'small-tools')
                ), 45);
            }
        }
    }

    private function handle_database_cleanup() {
        global $wpdb;
        $cleaned = 0;

        // Verify nonce
        if (!isset($_POST['small_tools_db_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['small_tools_db_nonce'])), 'small_tools_db_cleanup')) {
            wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
        }

        if (!isset($_POST['cleanup_options']) || !is_array($_POST['cleanup_options'])) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => __('No cleanup options selected.', 'small-tools')
            ), 45);
            return;
        }

        // Sanitize the cleanup options
        $cleanup_options = array_map('sanitize_text_field', wp_unslash($_POST['cleanup_options']));
        
        // Valid cleanup options
        $valid_options = array('revisions', 'autodrafts', 'trash', 'spam', 'transients');
        
        foreach ($cleanup_options as $option) {
            if (!in_array($option, $valid_options, true)) {
                continue;
            }

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
            'message' => sprintf(
                // translators: %d is the number of items cleaned from the database.
                __('%d items cleaned from the database.', 'small-tools'), 
                $cleaned)
        ), 45);
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
        $json = wp_json_encode($settings, JSON_PRETTY_PRINT);
        if ($json === false) {
            wp_die(esc_html__('Error encoding settings', 'small-tools'));
        }
        
        // Force download
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="small-tools-settings.json"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo esc_js($json);
        exit;
    }

    private function import_settings() {
        // Verify nonce
        if (!isset($_POST['small_tools_import_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['small_tools_import_nonce'])), 'small_tools_import_settings')) {
            wp_die(esc_html__('Invalid security token sent.', 'small-tools'));
        }

        if (!isset($_FILES['small_tools_import_file'])) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => __('No file was uploaded.', 'small-tools')
            ), 45);
            return;
        }

        // Sanitize and validate file upload
        $file = array_map('sanitize_text_field', wp_unslash($_FILES['small_tools_import_file']));
        
        // Basic file validation
        if ($file['size'] > 1048576) { // 1MB limit
            wp_die(esc_html__('File size too large. Please upload a file smaller than 1MB.', 'small-tools'));
        }

        // Verify file upload
        if (!is_uploaded_file($file['tmp_name'])) {
            wp_die(esc_html__('Error uploading file.', 'small-tools'));
        }

        // Verify file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mime_type !== 'application/json') {
            wp_die(esc_html__('Invalid file type. Please upload a JSON file.', 'small-tools'));
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => __('Error uploading file.', 'small-tools')
            ), 45);
            return;
        }

        $json = file_get_contents($file['tmp_name']);
        $settings = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            set_transient('small_tools_admin_notice', array(
                'type' => 'error',
                'message' => __('Invalid JSON file.', 'small-tools')
            ), 45);
            return;
        }

        // Validate settings before importing
        $valid_options = array(
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

        foreach ($settings as $option => $value) {
            if (!in_array($option, $valid_options, true)) {
                continue;
            }
            update_option($option, $value);
        }

        set_transient('small_tools_admin_notice', array(
            'type' => 'success',
            'message' => __('Settings imported successfully.', 'small-tools')
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

    public function enqueue_admin_scripts($hook) {
        // Only enqueue on our plugin pages
        if (strpos($hook, 'small-tools') !== false) {
            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script(
                'small-tools-admin',
                SMALL_TOOLS_PLUGIN_URL . 'admin/js/small-tools-admin.js',
                array('jquery', 'wp-color-picker'),
                $this->version,
                true
            );
        }
    }
} 