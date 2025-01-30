<?php

class Small_Tools_Enqueue {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_styles($hook) {
        // Only enqueue on our plugin pages
        if (strpos($hook, 'small-tools') !== false) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style(
                'small-tools-admin',
                SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-admin.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'small-tools-preview',
                SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-preview.css',
                array(),
                $this->version,
                'all'
            );
        }

        // Enqueue dark mode styles if enabled
        if (get_option('small_tools_dark_mode_enabled') === 'yes') {
            wp_enqueue_style(
                'small-tools-dark-mode',
                SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-dark-mode.css',
                array(),
                $this->version,
                'all'
            );
        }
    }

    public function enqueue_admin_scripts($hook) {
        // Only enqueue on our plugin pages
        if (strpos($hook, 'small-tools') !== false) {
            wp_enqueue_media();
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
