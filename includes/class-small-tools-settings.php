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
        $content .= "if (!defined('ABSPATH')) exit;\n\n";
        
        // WordPress General Features
        if ($settings['small_tools_remove_image_threshold'] === 'yes') {
            $content .= "add_filter('big_image_size_threshold', '__return_false');\n";
        }

        if ($settings['small_tools_disable_lazy_load'] === 'yes') {
            $content .= "add_filter('wp_lazy_loading_enabled', '__return_false');\n";
        }

        if ($settings['small_tools_disable_emojis'] === 'yes') {
            $content .= "function small_tools_disable_emojis() {\n";
            $content .= "    remove_action('wp_head', 'print_emoji_detection_script', 7);\n";
            $content .= "    remove_action('admin_print_scripts', 'print_emoji_detection_script');\n";
            $content .= "    remove_action('wp_print_styles', 'print_emoji_styles');\n";
            $content .= "    remove_action('admin_print_styles', 'print_emoji_styles');\n";
            $content .= "    remove_filter('the_content_feed', 'wp_staticize_emoji');\n";
            $content .= "    remove_filter('comment_text_rss', 'wp_staticize_emoji');\n";
            $content .= "    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');\n";
            $content .= "}\n";
            $content .= "add_action('init', 'small_tools_disable_emojis');\n\n";
        }

        if ($settings['small_tools_remove_jquery_migrate'] === 'yes') {
            $content .= "function small_tools_remove_jquery_migrate(\$scripts) {\n";
            $content .= "    if (!is_admin() && isset(\$scripts->registered['jquery'])) {\n";
            $content .= "        \$script = \$scripts->registered['jquery'];\n";
            $content .= "        if (\$script->deps) {\n";
            $content .= "            \$script->deps = array_diff(\$script->deps, array('jquery-migrate'));\n";
            $content .= "        }\n";
            $content .= "    }\n";
            $content .= "}\n";
            $content .= "add_action('wp_default_scripts', 'small_tools_remove_jquery_migrate');\n\n";
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
            $content .= "function small_tools_custom_admin_footer() {\n";
            $content .= "    return " . var_export($settings['small_tools_admin_footer_text'], true) . ";\n";
            $content .= "}\n";
            $content .= "add_filter('admin_footer_text', 'small_tools_custom_admin_footer');\n\n";
        }

        // WooCommerce Features
        if (function_exists('is_woocommerce')) {
            $content .= 'function small_tools_wc_variation_threshold($qty, $product) {';
            $content .= "\n";
            $content .= "    return " . (int)$settings['small_tools_wc_variation_threshold'] . ";\n";
            $content .= "}\n";
            $content .= "add_filter('woocommerce_ajax_variation_threshold', 'small_tools_wc_variation_threshold', 10, 2);\n\n";
        }

        // Asset Management
        $back_to_top_size = $settings['small_tools_back_to_top_size'] ? $settings['small_tools_back_to_top_size'] : '60';
        if ($settings['small_tools_disable_right_click'] === 'yes' || $settings['small_tools_back_to_top'] === 'yes') {
            $content .= "function small_tools_enqueue_frontend_assets() {\n";
            $content .= "    wp_enqueue_script('small-tools-frontend', '" . SMALL_TOOLS_PLUGIN_URL . "public/js/small-tools-public.js', array('jquery'), '" . SMALL_TOOLS_VERSION . "', true);\n";
            $content .= "    wp_localize_script('small-tools-frontend', 'smallTools', array(\n";
            $content .= "        'backToTop' => " . ($settings['small_tools_back_to_top'] === 'yes' ? 'true' : 'false') . ",\n";
            $content .= "        'disableRightClick' => " . ($settings['small_tools_disable_right_click'] === 'yes' ? 'true' : 'false') . ",\n";
            $content .= "        'backToTopPosition' => '" . esc_js($settings['small_tools_back_to_top_position']) . "',\n";
            $content .= "        'backToTopIcon' => '" . esc_js($settings['small_tools_back_to_top_icon']) . "',\n";
            $content .= "        'backToTopBgColor' => '" . esc_js($settings['small_tools_back_to_top_bg_color']) . "',\n";
            $content .= "        'backToTopSize' => " . (int)$back_to_top_size . "\n";
            $content .= "    ));\n";
            if ($settings['small_tools_back_to_top'] === 'yes') {
                $content .= "    wp_enqueue_style('dashicons');\n";
                $content .= "    wp_enqueue_style('small-tools-backtotop', '" . SMALL_TOOLS_PLUGIN_URL . "public/css/small-tools-backtotop.css', array(), '" . SMALL_TOOLS_VERSION . "');\n";
            }
            $content .= "}\n";
            $content .= "add_action('wp_enqueue_scripts', 'small_tools_enqueue_frontend_assets');\n\n";
        }

        if ($settings['small_tools_dark_mode_enabled'] === 'yes') {
            $content .= "function small_tools_enqueue_admin_assets() {\n";
            $content .= "    wp_enqueue_style('small-tools-admin', '" . SMALL_TOOLS_PLUGIN_URL . "admin/css/small-tools-admin.css', array(), '" . SMALL_TOOLS_VERSION . "', 'all');\n";
            $content .= "}\n";
            $content .= "add_action('admin_enqueue_scripts', 'small_tools_enqueue_admin_assets');\n";
        }

        // Save the file
        file_put_contents($this->settings_file, $content);
        return true;
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