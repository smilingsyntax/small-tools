<?php

class Small_Tools {
    protected $version;
    protected $plugin_name;

    public function __construct() {
        $this->version = SMALL_TOOLS_VERSION;
        $this->plugin_name = 'small-tools';
        
        $this->load_dependencies();
        // $this->setup_actions();
        add_action('plugins_loaded', array($this, 'setup_actions'));
    }

    private function load_dependencies() {
        // Load feature classes here
        
        // Load admin class
        require_once SMALL_TOOLS_PLUGIN_DIR . 'admin/class-small-tools-admin.php';
        
        // Initialize admin
        $plugin_admin = new Small_Tools_Admin($this->plugin_name, $this->version);
    }

    public function setup_actions() {
        // WordPress General Features
        if (get_option('small_tools_remove_image_threshold', 'yes') === 'yes') {
            add_filter('big_image_size_threshold', '__return_false');
        }

        if (get_option('small_tools_disable_lazy_load', 'no') === 'yes') {
            add_filter('wp_lazy_loading_enabled', '__return_false');
        }

        if (get_option('small_tools_disable_emojis', 'yes') === 'yes') {
            add_action('init', array($this, 'disable_emojis'));
        }

        if (get_option('small_tools_remove_jquery_migrate', 'no') === 'yes') {
            add_action('wp_default_scripts', array($this, 'remove_jquery_migrate'));
        }

        // External links in new tab
        add_filter('the_content', array($this, 'external_links_new_tab'));
        
        // Security Features
        if (get_option('small_tools_disable_xmlrpc', 'yes') === 'yes') {
            add_filter('xmlrpc_enabled', '__return_false');
        }

        if (get_option('small_tools_hide_wp_version', 'yes') === 'yes') {
            remove_action('wp_head', 'wp_generator');
            add_filter('the_generator', '__return_empty_string');
        }

        // Admin Features
        add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
        
        // WooCommerce Features
        if (function_exists('is_woocommerce')) {
            add_filter('woocommerce_ajax_variation_threshold', array($this, 'increase_wc_variation_threshold'), 10, 2);
        }

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
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

    public function external_links_new_tab($content) {
        $host = parse_url(get_site_url(), PHP_URL_HOST);
        return preg_replace_callback(
            '/<a([^>]*)href="([^"]*)"([^>]*)>/',
            function($matches) use ($host) {
                if (strpos($matches[2], 'http') === 0 && strpos($matches[2], $host) === false) {
                    return '<a' . $matches[1] . 'href="' . $matches[2] . '"' . $matches[3] . ' target="_blank" rel="noopener noreferrer">';
                }
                return $matches[0];
            },
            $content
        );
    }

    // WooCommerce Features
    public function increase_wc_variation_threshold( $qty, $product ) {
        return (int) get_option('small_tools_wc_variation_threshold', 30);
    }

    // Admin Features
    public function custom_admin_footer() {
        return get_option('small_tools_admin_footer_text', 'Thank you for using Small Tools');
    }

    // Asset Management
    public function enqueue_frontend_assets() {
        if (get_option('small_tools_disable_right_click', 'no') === 'yes') {
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
        if (get_option('small_tools_dark_mode_enabled', 'no') === 'yes') {
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