<?php

class Small_Tools {
    protected $version;
    protected $plugin_name;

    public function __construct() {
        $this->version = SMALL_TOOLS_VERSION;
        $this->plugin_name = 'small-tools';
        
        $this->load_dependencies();
        add_action('plugins_loaded', array($this, 'setup_actions'));
    }

    private function load_dependencies() {
        // Load feature classes here
        require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools-settings.php';
        
        // Load admin class
        require_once SMALL_TOOLS_PLUGIN_DIR . 'admin/class-small-tools-admin.php';
        
        // Initialize admin
        $plugin_admin = new Small_Tools_Admin($this->plugin_name, $this->version);
    }

    public function setup_actions() {
        // Load hooks file if it exists
        $settings = Small_Tools_Settings::get_instance();
        $hooks_file = $settings->get_settings_file_path();
        
        if (!file_exists($hooks_file)) {
            $settings->generate_settings_file();
        }
        
        if (file_exists($hooks_file)) {
            require_once $hooks_file;
        }
    }

    public function run() {
        // The main plugin logic
    }

    // WordPress General Features
    public function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    public function remove_jquery_migrate($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, array('jquery-migrate'));
            }
        }
    }

    // WooCommerce Features
    public function increase_wc_variation_threshold($qty = 30, $product) {
        $settings = Small_Tools_Settings::get_instance()->get_settings();
        return (int) $settings['small_tools_wc_variation_threshold'];
    }

    // Admin Features
    public function custom_admin_footer() {
        $settings = Small_Tools_Settings::get_instance()->get_settings();
        return $settings['small_tools_admin_footer_text'];
    }

    // Asset Management
    public function enqueue_frontend_assets() {
        $settings = Small_Tools_Settings::get_instance()->get_settings();
        if ($settings['small_tools_disable_right_click'] === 'yes') {
            wp_enqueue_script(
                'small-tools-frontend',
                SMALL_TOOLS_PLUGIN_URL . 'public/js/small-tools-public.js',
                array('jquery'),
                $this->version,
                true
            );
        }
    }

    public function enqueue_admin_assets() {
        $settings = Small_Tools_Settings::get_instance()->get_settings();
        if ($settings['small_tools_dark_mode_enabled'] === 'yes') {
            wp_enqueue_style(
                'small-tools-admin',
                SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-admin.css',
                array(),
                $this->version,
                'all'
            );
        }
    }
}