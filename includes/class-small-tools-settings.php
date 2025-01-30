<?php

class Small_Tools_Settings {
    private static $instance = null;
    private $settings_dir;
    private $settings_file;
    private $default_settings;

    private function __construct() {
        $upload_dir = wp_upload_dir();
        $this->settings_dir = $upload_dir['basedir'] . '/small-tools';
        $this->settings_file = $this->settings_dir . '/small-settings.php';
        
        $this->default_settings = array(
            'small_tools_disable_right_click' => 'no',
            'small_tools_remove_image_threshold' => 'no',
            'small_tools_disable_lazy_load' => 'no',
            'small_tools_disable_emojis' => 'no',
            'small_tools_remove_jquery_migrate' => 'no',
            'small_tools_back_to_top' => 'yes',
            'small_tools_back_to_top_position' => 'right',
            'small_tools_back_to_top_icon' => '',
            'small_tools_back_to_top_bg_color' => 'rgba(0, 0, 0, 0.7)',
            'small_tools_back_to_top_size' => '40',
            'small_tools_force_strong_passwords' => 'yes',
            'small_tools_disable_xmlrpc' => 'yes',
            'small_tools_hide_wp_version' => 'yes',
            'small_tools_wc_variation_threshold' => '30',
            'small_tools_admin_footer_text' => 'Thank you for using Small Tools',
            'small_tools_dark_mode_enabled' => 'no'
        );

        // Create directory if it doesn't exist
        if (!file_exists($this->settings_dir)) {
            wp_mkdir_p($this->settings_dir);
            
            // Create .htaccess to prevent direct access
            $htaccess = $this->settings_dir . '/.htaccess';
            if (!file_exists($htaccess)) {
                file_put_contents($htaccess, 'deny from all');
            }
        }
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generate_settings_file() {
        $settings = array();
        foreach ($this->default_settings as $key => $default) {
            $settings[$key] = get_option($key, $default);
        }

        // Start building the PHP file content
        $content = "<?php\n";
        $content .= "defined('ABSPATH') || exit;\n\n";
        
        // WordPress General Features
        if ($settings['small_tools_remove_image_threshold'] === 'yes') {
            $content .= "add_filter('big_image_size_threshold', '__return_false');\n";
        }

        if ($settings['small_tools_disable_lazy_load'] === 'yes') {
            $content .= "add_filter('wp_lazy_loading_enabled', '__return_false');\n";
        }

        if ($settings['small_tools_disable_emojis'] === 'yes') {
            $content .= $this->get_emoji_disable_code();
        }

        if ($settings['small_tools_remove_jquery_migrate'] === 'yes') {
            $content .= $this->get_jquery_migrate_code();
        }

        // Security Features
        if ($settings['small_tools_disable_xmlrpc'] === 'yes') {
            $content .= "add_filter('xmlrpc_enabled', '__return_false');\n";
        }

        if ($settings['small_tools_hide_wp_version'] === 'yes') {
            $content .= "remove_action('wp_head', 'wp_generator');\n";
            $content .= "add_filter('the_generator', '__return_empty_string');\n";
        }

        // Admin Features
        if (!empty($settings['small_tools_admin_footer_text'])) {
            $content .= sprintf(
                "function small_tools_custom_admin_footer() {\n    return '%s';\n}\n",
                wp_kses_post($settings['small_tools_admin_footer_text'])
            );
            $content .= "add_filter('admin_footer_text', 'small_tools_custom_admin_footer');\n\n";
        }

        // WooCommerce Features
        if (function_exists('is_woocommerce')) {
            $content .= $this->get_woocommerce_code($settings);
        }

        // Asset Management
        if ($settings['small_tools_disable_right_click'] === 'yes' || $settings['small_tools_back_to_top'] === 'yes') {
            $content .= $this->get_frontend_assets_code($settings);
        }

        if ($settings['small_tools_dark_mode_enabled'] === 'yes') {
            $content .= $this->get_admin_assets_code();
        }

        return (bool) file_put_contents($this->settings_file, $content);
    }

    private function get_emoji_disable_code() {
        return "function small_tools_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'small_tools_disable_emojis');\n\n";
    }

    private function get_jquery_migrate_code() {
        return "function small_tools_remove_jquery_migrate(\$scripts) {
    if (!is_admin() && isset(\$scripts->registered['jquery'])) {
        \$script = \$scripts->registered['jquery'];
        if (\$script->deps) {
            \$script->deps = array_diff(\$script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'small_tools_remove_jquery_migrate');\n\n";
    }

    private function get_woocommerce_code($settings) {
        return sprintf(
            "function small_tools_wc_variation_threshold() {\n    return %d;\n}\n" .
            "add_filter('woocommerce_ajax_variation_threshold', 'small_tools_wc_variation_threshold');\n\n",
            absint($settings['small_tools_wc_variation_threshold'])
        );
    }

    private function get_frontend_assets_code($settings) {
        $content = "function small_tools_enqueue_frontend_assets() {\n";
        $content .= sprintf(
            "    wp_enqueue_script('small-tools-frontend', '%s', array('jquery'), '%s', true);\n",
            esc_url(SMALL_TOOLS_PLUGIN_URL . 'public/js/small-tools-public.js'),
            esc_attr(SMALL_TOOLS_VERSION)
        );
        
        $content .= "    wp_localize_script('small-tools-frontend', 'smallTools', array(\n";
        $content .= sprintf(
            "        'backToTop' => %s,\n" .
            "        'disableRightClick' => %s,\n" .
            "        'backToTopPosition' => '%s',\n" .
            "        'backToTopIcon' => '%s',\n" .
            "        'backToTopBgColor' => '%s',\n" .
            "        'backToTopSize' => %d\n",
            $settings['small_tools_back_to_top'] === 'yes' ? 'true' : 'false',
            $settings['small_tools_disable_right_click'] === 'yes' ? 'true' : 'false',
            esc_js($settings['small_tools_back_to_top_position']),
            esc_url($settings['small_tools_back_to_top_icon']),
            esc_js($settings['small_tools_back_to_top_bg_color']),
            absint($settings['small_tools_back_to_top_size'])
        );
        $content .= "    ));\n";

        if ($settings['small_tools_back_to_top'] === 'yes') {
            $content .= sprintf(
                "    wp_enqueue_style('small-tools-backtotop', '%s', array(), '%s');\n",
                esc_url(SMALL_TOOLS_PLUGIN_URL . 'public/css/small-tools-backtotop.css'),
                esc_attr(SMALL_TOOLS_VERSION)
            );
        }
        
        $content .= "}\n";
        $content .= "add_action('wp_enqueue_scripts', 'small_tools_enqueue_frontend_assets');\n\n";
        
        return $content;
    }

    private function get_admin_assets_code() {
        return sprintf(
            "function small_tools_enqueue_admin_assets() {\n" .
            "    wp_enqueue_style('small-tools-admin', '%s', array(), '%s', 'all');\n" .
            "}\n" .
            "add_action('admin_enqueue_scripts', 'small_tools_enqueue_admin_assets');\n",
            esc_url(SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-admin.css'),
            esc_attr(SMALL_TOOLS_VERSION)
        );
    }

    public function reset_to_defaults() {
        // Update options in database
        foreach ($this->default_settings as $key => $value) {
            update_option($key, $value);
        }

        // Generate settings file with defaults
        return $this->generate_settings_file();
    }

    public function get_settings_file_path() {
        return $this->settings_file;
    }
} 