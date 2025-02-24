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

        // Add AJAX handlers
        add_action('wp_ajax_small_tools_save_settings', array($this, 'ajax_save_settings'));
        add_action('wp_ajax_small_tools_replace_media', array($this, 'ajax_replace_media'));

        // Add mime type handlers
        add_filter('upload_mimes', array($this, 'add_custom_mime_types'), 10, 1);
        add_filter('wp_check_filetype_and_ext', array($this, 'check_filetype'), 10, 5);
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

        add_submenu_page(
            'small-tools-settings',
            'Components Settings',
            'Components',
            'manage_options',
            'small-tools-components',
            array($this, 'display_components_page')
        );
    }

    public function register_settings() {
        // Register settings
        $settings = array(
            'small_tools_disable_right_click' => 'boolean',
            'small_tools_remove_image_threshold' => 'boolean',
            'small_tools_disable_lazy_load' => 'boolean',
            'small_tools_disable_emojis' => 'boolean',
            'small_tools_remove_jquery_migrate' => 'boolean',
            'small_tools_back_to_top' => 'boolean',
            'small_tools_back_to_top_position' => 'string',
            'small_tools_back_to_top_icon' => 'string',
            'small_tools_back_to_top_bg_color' => 'string',
            'small_tools_back_to_top_size' => 'number',
            'small_tools_force_strong_passwords' => 'boolean',
            'small_tools_disable_xmlrpc' => 'boolean',
            'small_tools_hide_wp_version' => 'boolean',
            'small_tools_wc_variation_threshold' => 'number',
            'small_tools_admin_footer_text' => 'string',
            'small_tools_dark_mode_enabled' => 'boolean',
            'small_tools_enable_media_replace' => 'boolean',
            'small_tools_enable_svg_upload' => 'boolean',
            'small_tools_enable_avif_upload' => 'boolean',
            'small_tools_enable_duplication' => 'boolean',
            'small_tools_remove_wp_logo' => 'boolean',
            'small_tools_remove_site_name' => 'boolean',
            'small_tools_remove_customize_menu' => 'boolean',
            'small_tools_remove_updates_menu' => 'boolean',
            'small_tools_remove_comments_menu' => 'boolean',
            'small_tools_remove_new_content' => 'boolean',
            'small_tools_remove_howdy' => 'boolean',
            'small_tools_remove_help' => 'boolean',
            'small_tools_hide_admin_notices' => 'boolean',
            'small_tools_disable_dashboard_welcome' => 'boolean',
            'small_tools_disable_dashboard_activity' => 'boolean',
            'small_tools_disable_dashboard_quick_press' => 'boolean',
            'small_tools_disable_dashboard_news' => 'boolean',
            'small_tools_disable_dashboard_site_health' => 'boolean',
            'small_tools_disable_dashboard_at_a_glance' => 'boolean',
            'small_tools_hide_admin_bar' => 'boolean',
            'small_tools_wider_admin_menu' => 'boolean',
            'small_tools_login_logo' => 'string',
            'small_tools_login_logo_url' => 'string',
            'small_tools_login_redirect_default_url' => 'string',
            'small_tools_logout_redirect_default_url' => 'string',
            'small_tools_login_redirect_roles' => 'array',
            'small_tools_logout_redirect_roles' => 'array',
            'small_tools_gutenberg_disabled_post_types' => 'array'
        );

        foreach ($settings as $setting => $type) {
            register_setting('small-tools-settings', $setting);
        }
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
                    'pre_update_option_small_tools_dark_mode_enabled',
                    'pre_update_option_small_tools_enable_duplication'
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
        // if (!current_user_can('manage_options')) {
        //     wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'small-tools'));
        // }

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
                    // Get all revision IDs
                    $revisions = get_posts(array(
                        'post_type' => 'revision',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ));
                    
                    foreach ($revisions as $revision_id) {
                        if (wp_delete_post($revision_id, true)) {
                            $cleaned++;
                        }
                    }
                    break;

                case 'autodrafts':
                    // Get all auto-draft IDs
                    $autodrafts = get_posts(array(
                        'post_status' => 'auto-draft',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ));
                    
                    foreach ($autodrafts as $draft_id) {
                        if (wp_delete_post($draft_id, true)) {
                            $cleaned++;
                        }
                    }
                    break;

                case 'trash':
                    // Get all trashed posts
                    $trash_posts = get_posts(array(
                        'post_status' => 'trash',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ));
                    
                    foreach ($trash_posts as $trash_id) {
                        if (wp_delete_post($trash_id, true)) {
                            $cleaned++;
                        }
                    }
                    break;

                case 'spam':
                    // Get all spam comments
                    $spam_comments = get_comments(array(
                        'status' => 'spam',
                        'fields' => 'ids',
                    ));
                    
                    foreach ($spam_comments as $comment_id) {
                        if (wp_delete_comment($comment_id, true)) {
                            $cleaned++;
                        }
                    }
                    break;

                case 'transients':
                    global $wpdb;
                    $time = time();
                    
                    // Try to get cached list of expired transients
                    $expired_transients = wp_cache_get('small_tools_expired_transients');
                    
                    if (false === $expired_transients) {
                        // Get expired transients using WordPress functions
                        $expired_transients = $wpdb->get_col(
                            $wpdb->prepare(
                                "SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name 
                                FROM {$wpdb->options} 
                                WHERE option_name LIKE %s 
                                AND option_value < %d",
                                $wpdb->esc_like('_transient_timeout_') . '%',
                                $time
                            )
                        );
                        
                        // Cache the results for 5 minutes
                        wp_cache_set('small_tools_expired_transients', $expired_transients, '', 300);
                    }
                    
                    foreach ($expired_transients as $transient) {
                        if (delete_transient($transient)) {
                            $cleaned++;
                            // Delete this transient from cache as well
                            wp_cache_delete($transient, 'transient');
                        }
                    }

                    // Also clean site-wide transients in multisite
                    if (is_multisite()) {
                        // Try to get cached list of expired site transients
                        $expired_site_transients = wp_cache_get('small_tools_expired_site_transients');
                        
                        if (false === $expired_site_transients) {
                            $expired_site_transients = $wpdb->get_col(
                                $wpdb->prepare(
                                    "SELECT REPLACE(option_name, '_site_transient_timeout_', '') AS transient_name 
                                    FROM {$wpdb->options} 
                                    WHERE option_name LIKE %s 
                                    AND option_value < %d",
                                    $wpdb->esc_like('_site_transient_timeout_') . '%',
                                    $time
                                )
                            );
                            
                            // Cache the results for 5 minutes
                            wp_cache_set('small_tools_expired_site_transients', $expired_site_transients, '', 300);
                        }
                        
                        foreach ($expired_site_transients as $transient) {
                            if (delete_site_transient($transient)) {
                                $cleaned++;
                                // Delete this transient from cache as well
                                wp_cache_delete($transient, 'site-transient');
                            }
                        }
                    }
                    
                    // Clear our cached lists after cleanup
                    wp_cache_delete('small_tools_expired_transients');
                    wp_cache_delete('small_tools_expired_site_transients');
                    break;
            }
        }

        // Note: Database optimization is handled automatically by WordPress
        // through its built-in maintenance routines
        
        set_transient('small_tools_admin_notice', array(
            'type' => 'success',
            'message' => sprintf(
                // translators: %d is the number of items cleaned from the database.
                __('%d items cleaned from the database.', 'small-tools'), 
                $cleaned
            )
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

    public function get_setting_description($key) {
        $descriptions = array(
            'disable_right_click' => __('Prevent users from right-clicking on images and content.', 'small-tools'),
            'remove_image_threshold' => __('Remove WordPress image size threshold limit.', 'small-tools'),
            'disable_lazy_load' => __('Disable WordPress default lazy loading for images.', 'small-tools'),
            'disable_emojis' => __('Remove emoji scripts and styles from loading.', 'small-tools'),
            'remove_jquery_migrate' => __('Remove jQuery Migrate script from loading.', 'small-tools'),
            'back_to_top' => __('Add a back to top button on your website.', 'small-tools'),
            'back_to_top_position' => __('Choose the position of the back to top button.', 'small-tools'),
            'back_to_top_icon' => __('Upload a custom icon for the back to top button.', 'small-tools'),
            'back_to_top_bg_color' => __('Set the background color and opacity for the back to top button.', 'small-tools'),
            'back_to_top_size' => __('Set the size of the back to top button (20-100px).', 'small-tools'),
            'dark_mode_enabled' => __('Enable dark mode for WordPress admin dashboard.', 'small-tools'),
            'admin_footer_text' => __('Customize the text shown in the admin footer.', 'small-tools'),
            'enable_duplication' => __('Add a duplicate option to quickly clone posts, pages, and custom post types.', 'small-tools'),
            'enable_media_replace' => __('Enable the ability to replace media files while maintaining the same URL and attachment ID.', 'small-tools'),
            'enable_svg_upload' => __('Allow SVG file uploads with sanitization for enhanced security.', 'small-tools'),
            'enable_avif_upload' => __('Enable support for AVIF image format uploads.', 'small-tools')
        );

        return isset($descriptions[$key]) ? $descriptions[$key] : '';
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

    public function ajax_save_settings() {
        check_ajax_referer('small_tools_settings_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'small-tools')));
        }

        $settings = array();
        $posted_data = $_POST;
        unset($posted_data['action'], $posted_data['security']);

        foreach ($posted_data as $key => $value) {
            // Handle arrays (like role redirects and post types)
            if (is_array($value) || strpos($key, '[]') !== false) {
                // Remove array notation from key if present
                $clean_key = str_replace('[]', '', $key);
                
                // If it's not already an array, make it one
                if (!is_array($value)) {
                    $value = array($value);
                }
                
                // Sanitize the array values
                $settings[$clean_key] = $this->sanitize_setting($value, $clean_key);
                update_option($clean_key, $settings[$clean_key]);
                continue;
            }

            // Handle regular settings
            $settings[$key] = $this->sanitize_setting($value, $key);
            update_option($key, $settings[$key]);
        }

        // Regenerate settings file
        $settings_instance = Small_Tools_Settings::get_instance();
        $file_generated = $settings_instance->generate_settings_file();

        if ($file_generated) {
            wp_send_json_success(array(
                'message' => __('Settings saved successfully.', 'small-tools'),
                'settings' => $settings
            ));
        } else {
            wp_send_json_error(array('message' => __('Error generating settings file.', 'small-tools')));
        }
    }

    private function sanitize_setting($value, $option) {
        switch ($option) {
            case 'small_tools_login_logo':
            case 'small_tools_back_to_top_icon':
            case 'small_tools_login_logo_url':
            case 'small_tools_login_redirect_default_url':
            case 'small_tools_logout_redirect_default_url':
                return esc_url_raw($value);
            
            case 'small_tools_back_to_top_bg_color':
                return sanitize_text_field($value);
            
            case 'small_tools_back_to_top_size':
            case 'small_tools_wc_variation_threshold':
                return absint($value);
            
            case 'small_tools_back_to_top_position':
                return in_array($value, array('left', 'right')) ? $value : 'right';

            case 'small_tools_login_redirect_roles':
            case 'small_tools_logout_redirect_roles':
                if (!is_array($value)) {
                    return array();
                }
                $sanitized = array();
                foreach ($value as $role => $url) {
                    if (!empty($url)) {
                        $sanitized[sanitize_text_field($role)] = wp_strip_all_tags(stripslashes($url));
                    }
                }
                return $sanitized;

            case 'small_tools_gutenberg_disabled_post_types':
                if (!is_array($value)) {
                    return array();
                }
                $sanitized = array();
                foreach ($value as $post_type) {
                    $post_type = sanitize_key($post_type);
                    if (!empty($post_type) && post_type_exists($post_type) && post_type_supports($post_type, 'editor')) {
                        $sanitized[] = $post_type;
                    }
                }
                return $sanitized;
            
            default:
                // For checkbox/toggle settings
                if (strpos($option, 'small_tools_enable_') === 0 ||
                    strpos($option, 'small_tools_disable_') === 0 ||
                    strpos($option, 'small_tools_remove_') === 0 ||
                    strpos($option, 'small_tools_hide_') === 0) {
                    return $value === 'yes' ? 'yes' : 'no';
                }
                
                return sanitize_text_field($value);
        }
    }

    public function ajax_replace_media() {
        // Check nonce
        if (!check_ajax_referer('small_tools_media_replace', 'security', false)) {
            wp_send_json_error(array('message' => __('Security check failed.', 'small-tools')));
        }

        // Check user capabilities
        if (!current_user_can('upload_files')) {
            wp_send_json_error(array('message' => __('You do not have permission to upload files.', 'small-tools')));
        }

        // Check if file was uploaded
        if (!isset($_POST['replacement_id'])) {
            wp_send_json_error(array('message' => __('No file was selected.', 'small-tools')));
        }

        // Get attachment ID
        $attachment_id = isset($_POST['attachment_id']) ? absint($_POST['attachment_id']) : 0;
        if (!$attachment_id) {
            wp_send_json_error(array('message' => __('Invalid attachment ID.', 'small-tools')));
        }

        // Get original file path
        $original_file = get_attached_file($attachment_id);
        if (!$original_file) {
            wp_send_json_error(array('message' => __('Original file not found.', 'small-tools')));
        }

        // Get replacement file path
        $replacement_file = get_attached_file(sanitize_text_field(wp_unslash($_POST['replacement_id'])));
        if (!$replacement_file) {
            wp_send_json_error(array('message' => __('Replacement file not found.', 'small-tools')));
        }

        // Get file info
        $original_info = wp_check_filetype(basename($original_file));
        $replacement_info = wp_check_filetype(basename($replacement_file));

        // Check if file type is allowed
        $allowed_mime_types = get_allowed_mime_types();
        if (!in_array($replacement_info['type'], $allowed_mime_types)) {
            wp_send_json_error(array(
                'message' => sprintf(
                    /* translators: %s: File type */
                    __('File type not allowed: %s', 'small-tools'),
                    $replacement_info['type']
                )
            ));
        }

        // Get attachment data
        $original_attachment = get_post($attachment_id);
        $replacement_id = isset($_POST['replacement_id']) ? absint($_POST['replacement_id']) : 0;
        $replacement_attachment = get_post($replacement_id);

        if (!$original_attachment || !$replacement_attachment) {
            wp_send_json_error(array(
                'message' => esc_html__('One or both attachments not found.', 'small-tools')
            ));
        }

        // Store original attachment data
        $original_data = array(
            'post_title' => sanitize_text_field($original_attachment->post_title),
            'post_content' => wp_kses_post($original_attachment->post_content),
            'post_excerpt' => wp_kses_post($original_attachment->post_excerpt),
            'post_status' => sanitize_text_field($original_attachment->post_status),
            'post_mime_type' => sanitize_text_field($original_attachment->post_mime_type),
            'guid' => esc_url_raw($original_attachment->guid),
            'meta' => get_post_meta($attachment_id)
        );

        // Store replacement attachment data
        $replacement_data = array(
            'post_title' => sanitize_text_field($replacement_attachment->post_title),
            'post_content' => wp_kses_post($replacement_attachment->post_content),
            'post_excerpt' => wp_kses_post($replacement_attachment->post_excerpt),
            'post_status' => sanitize_text_field($replacement_attachment->post_status),
            'post_mime_type' => sanitize_text_field($replacement_attachment->post_mime_type),
            'guid' => esc_url_raw($replacement_attachment->guid),
            'meta' => get_post_meta($replacement_id)
        );

        // Update original attachment with replacement data
        $original_update = wp_update_post(array(
            'ID' => $attachment_id,
            'post_title' => $replacement_data['post_title'],
            'post_content' => $replacement_data['post_content'],
            'post_excerpt' => $replacement_data['post_excerpt'],
            'post_mime_type' => $replacement_data['post_mime_type'],
            'guid' => $replacement_data['guid']
        ), true);

        if (is_wp_error($original_update)) {
            wp_send_json_error(array(
                'message' => esc_html__('Failed to update original attachment.', 'small-tools')
            ));
        }

        // Update replacement attachment with original data
        $replacement_update = wp_update_post(array(
            'ID' => $replacement_id,
            'post_title' => $original_data['post_title'],
            'post_content' => $original_data['post_content'],
            'post_excerpt' => $original_data['post_excerpt'],
            'post_mime_type' => $original_data['post_mime_type'],
            'guid' => $original_data['guid']
        ), true);

        if (is_wp_error($replacement_update)) {
            wp_send_json_error(array(
                'message' => esc_html__('Failed to update replacement attachment.', 'small-tools')
            ));
        }

        // Swap metadata
        foreach ($original_data['meta'] as $meta_key => $meta_values) {
            delete_post_meta($attachment_id, sanitize_key($meta_key));
            foreach ($meta_values as $meta_value) {
                add_post_meta($attachment_id, sanitize_key($meta_key), maybe_unserialize($meta_value));
            }
        }

        foreach ($replacement_data['meta'] as $meta_key => $meta_values) {
            delete_post_meta($replacement_id, sanitize_key($meta_key));
            foreach ($meta_values as $meta_value) {
                add_post_meta($replacement_id, sanitize_key($meta_key), maybe_unserialize($meta_value));
            }
        }

        // Swap file paths
        $original_file_update = update_attached_file($attachment_id, sanitize_text_field($replacement_file));
        $replacement_file_update = update_attached_file($replacement_id, sanitize_text_field($original_file));

        if (!$original_file_update || !$replacement_file_update) {
            wp_send_json_error(array(
                'message' => esc_html__('Failed to update file paths.', 'small-tools')
            ));
        }

        // Update thumbnails if requested
        $update_thumbnails = isset($_POST['update_thumbnails']) && sanitize_text_field(wp_unslash($_POST['update_thumbnails'])) === '1';
        if ($update_thumbnails) {
            $original_metadata = wp_generate_attachment_metadata($attachment_id, $replacement_file);
            $replacement_metadata = wp_generate_attachment_metadata($replacement_id, $original_file);

            if (!wp_update_attachment_metadata($attachment_id, $original_metadata) || 
                !wp_update_attachment_metadata($replacement_id, $replacement_metadata)) {
                wp_send_json_error(array(
                    'message' => esc_html__('Failed to update attachment metadata.', 'small-tools')
                ));
            }
        }

        // Send success response
        wp_send_json_success(array(
            'message' => esc_html__('Media replaced successfully. Original media preserved with new attachment ID.', 'small-tools'),
            'redirect' => esc_url_raw(admin_url('upload.php'))
        ));
    }

    /**
     * Add custom mime types based on settings
     *
     * @param array $mimes Existing mime types.
     * @return array Modified mime types.
     */
    public function add_custom_mime_types($mimes) {
        // Add SVG support if enabled
        if (get_option('small_tools_enable_svg_upload') === 'yes') {
            $mimes['svg'] = 'image/svg+xml';
            $mimes['svgz'] = 'image/svg+xml';
        }

        // Add AVIF support if enabled
        if (get_option('small_tools_enable_avif_upload') === 'yes') {
            $mimes['avif'] = 'image/avif';
        }

        return $mimes;
    }

    /**
     * Additional filetype and extension checking
     *
     * @param array  $data     Values for the extension, mime type, and corrected filename.
     * @param string $file     Full path to the file.
     * @param string $filename The name of the file.
     * @param array  $mimes    Array of mime types keyed by their file extension regex.
     * @param string $real_mime Real mime type of the uploaded file.
     * @return array Modified data array
     */
    public function check_filetype($data, $file, $filename, $mimes, $real_mime = null) {
        if (!$data['type']) {
            $wp_filetype = wp_check_filetype($filename, $mimes);
            $ext = $wp_filetype['ext'];
            $type = $wp_filetype['type'];

            // Handle SVG
            if (get_option('small_tools_enable_svg_upload') === 'yes') {
                if (in_array($ext, array('svg', 'svgz'))) {
                    // Validate SVG content
                    $file_content = file_get_contents($file);
                    if ($this->validate_svg($file_content)) {
                        $data['ext'] = $ext;
                        $data['type'] = $type;
                    }
                }
            }

            // Handle AVIF
            if (get_option('small_tools_enable_avif_upload') === 'yes') {
                if ($ext === 'avif') {
                    $data['ext'] = $ext;
                    $data['type'] = $type;
                }
            }
        }

        return $data;
    }

    /**
     * Validate SVG content for security
     *
     * @param string $content SVG file content
     * @return boolean Whether the SVG content is safe
     */
    private function validate_svg($content) {
        // Basic security checks for SVG content
        if (!$content) {
            return false;
        }

        // Check for PHP tags
        if (stripos($content, '<?php') !== false) {
            return false;
        }

        // Check for script tags
        if (preg_match('/<script[^>]*>[^<]*<\/script>/i', $content)) {
            return false;
        }

        // Check for suspicious attributes
        $suspicious_attributes = array('onload', 'onclick', 'onmouseover', 'onerror', 'onmouseout', 'onmousedown', 'onmouseup');
        foreach ($suspicious_attributes as $attr) {
            if (stripos($content, $attr) !== false) {
                return false;
            }
        }

        return true;
    }
} 