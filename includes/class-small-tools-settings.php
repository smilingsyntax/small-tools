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
            'small_tools_dark_mode_enabled' => 'no',
            'small_tools_enable_media_replace' => 'yes',
            'small_tools_enable_svg_upload' => 'no',
            'small_tools_enable_avif_upload' => 'no',
            'small_tools_enable_duplication' => 'yes',
            'small_tools_remove_wp_logo' => 'no',
            'small_tools_remove_site_name' => 'no',
            'small_tools_remove_customize_menu' => 'no',
            'small_tools_remove_updates_menu' => 'no',
            'small_tools_remove_comments_menu' => 'no',
            'small_tools_remove_new_content' => 'no',
            'small_tools_remove_howdy' => 'no',
            'small_tools_remove_help' => 'no',
            'small_tools_hide_admin_notices' => 'no',
            'small_tools_disable_dashboard_welcome' => 'no',
            'small_tools_disable_dashboard_activity' => 'no',
            'small_tools_disable_dashboard_quick_press' => 'no',
            'small_tools_disable_dashboard_news' => 'no',
            'small_tools_disable_dashboard_site_health' => 'no',
            'small_tools_disable_dashboard_at_a_glance' => 'no',
            'small_tools_hide_admin_bar' => 'no',
            'small_tools_wider_admin_menu' => 'no',
            'small_tools_login_logo' => '',
            'small_tools_login_logo_url' => '',
            'small_tools_enable_user_columns' => 'yes',
            'small_tools_enable_last_login' => 'yes',
            'small_tools_login_redirect_roles' => array(),
            'small_tools_logout_redirect_roles' => array(),
            'small_tools_login_redirect_default_url' => '',
            'small_tools_logout_redirect_default_url' => '',
            // Component Settings
            'small_tools_disable_gutenberg' => 'no',
            'small_tools_disable_comments' => 'no',
            'small_tools_disable_rest_api' => 'no',
            'small_tools_disable_feeds' => 'no',
            'small_tools_disable_jquery_migrate' => 'no',
            'small_tools_gutenberg_disabled_post_types' => array(),
            'small_tools_selection_color' => '',
            'small_tools_selection_text_color' => '',
            'small_tools_disable_core_updates' => 'no',
            'small_tools_disable_plugin_updates' => 'no',
            'small_tools_disable_theme_updates' => 'no',
            'small_tools_disable_translation_updates' => 'no',
            'small_tools_disable_update_emails' => 'no',
            'small_tools_disable_update_page' => 'no'
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
        // Get all settings
        $settings = $this->get_all_settings();
        
        // Start building the PHP file content
        $php_content = "<?php\n";
        $php_content .= "// Small Tools Settings File - DO NOT EDIT DIRECTLY\n";
        $php_content .= "// Generated on: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Add settings as constants
        foreach ($settings as $key => $value) {
            // Skip empty values
            if (empty($value)) {
                continue;
            }
            
            // Format the constant name
            $constant_name = strtoupper($key);
            
            // Format the value based on type
            if (is_array($value)) {
                $serialized_value = serialize($value);
                $php_content .= "define('$constant_name', '" . addslashes($serialized_value) . "');\n";
            } else {
                $php_content .= "define('$constant_name', '" . addslashes($value) . "');\n";
            }
        }
        
        $php_content .= "\n// Apply settings\n";
        
        // Add frontend CSS for selection color
        if (!empty($settings['small_tools_selection_color'])) {
            $php_content .= "add_action('wp_head', function() {\n";
            $php_content .= "    echo '<style>\n";
            $php_content .= "    ::selection {\n";
            $php_content .= "        background-color: " . esc_attr($settings['small_tools_selection_color']) . ";\n";
            $php_content .= "        color: " . esc_attr($settings['small_tools_selection_text_color']) . ";\n";
            $php_content .= "    }\n";
            $php_content .= "    ::-moz-selection {\n";
            $php_content .= "        background-color: " . esc_attr($settings['small_tools_selection_color']) . ";\n";
            $php_content .= "        color: " . esc_attr($settings['small_tools_selection_text_color']) . ";\n";
            $php_content .= "    }\n";
            $php_content .= "    </style>';\n";
            $php_content .= "});\n\n";
        }

        // Add Gutenberg disable functionality
        $php_content .= "// Gutenberg Settings\n";
        if ($settings['small_tools_disable_gutenberg'] === 'yes') {
            $php_content .= "add_filter('use_block_editor_for_post_type', '__return_false', 100);\n";
        } else {
            $disabled_post_types = (array) get_option('small_tools_gutenberg_disabled_post_types', array());
            if (!empty($disabled_post_types)) {
                $php_content .= "add_filter('use_block_editor_for_post_type', function(\$use_block_editor, \$post_type) {\n";
                $php_content .= "    \$disabled_types = " . var_export(array_values($disabled_post_types), true) . ";\n";
                $php_content .= "    return in_array(\$post_type, \$disabled_types) ? false : \$use_block_editor;\n";
                $php_content .= "}, 100, 2);\n\n";
            }
        }

        // WordPress General Features
        $php_content .= "\n// WordPress General Features\n";
        if ($settings['small_tools_remove_image_threshold'] === 'yes') {
            $php_content .= "add_filter('big_image_size_threshold', '__return_false');\n";
        }

        if ($settings['small_tools_disable_lazy_load'] === 'yes') {
            $php_content .= "add_filter('wp_lazy_loading_enabled', '__return_false');\n";
        }

        if ($settings['small_tools_disable_emojis'] === 'yes') {
            $php_content .= $this->get_emoji_disable_code();
        }

        if ($settings['small_tools_remove_jquery_migrate'] === 'yes') {
            $php_content .= $this->get_jquery_migrate_code();
        }

        // Media Features
        $php_content .= "\n// Media Features\n";
        if ($settings['small_tools_enable_media_replace'] === 'yes') {
            $php_content .= "add_filter('media_row_actions', function(\$actions, \$post) {
                if (current_user_can('upload_files')) {
                    \$actions['replace_media'] = sprintf(
                        '<a href=\"#\" class=\"small-tools-replace-media\" data-id=\"%d\">%s</a>',
                        \$post->ID,
                        esc_html__('Replace Media', 'small-tools')
                    );
                }
                return \$actions;
            }, 10, 2);\n";
        }

        // SVG and AVIF Support
        if ($settings['small_tools_enable_svg_upload'] === 'yes' || $settings['small_tools_enable_avif_upload'] === 'yes') {
            $php_content .= "add_filter('upload_mimes', function(\$mimes) {\n";
            if ($settings['small_tools_enable_svg_upload'] === 'yes') {
                $php_content .= "    \$mimes['svg'] = 'image/svg+xml';\n";
                $php_content .= "    \$mimes['svgz'] = 'image/svg+xml';\n";
            }
            if ($settings['small_tools_enable_avif_upload'] === 'yes') {
                $php_content .= "    \$mimes['avif'] = 'image/avif';\n";
            }
            $php_content .= "    return \$mimes;\n";
            $php_content .= "});\n";
        }

        // Content Duplication
        if ($settings['small_tools_enable_duplication'] === 'yes') {
            $php_content .= "\n// Content Duplication\n";
            $php_content .= "add_filter('post_row_actions', function(\$actions, \$post) {
                if (current_user_can('edit_posts')) {
                    \$actions['duplicate'] = sprintf(
                        '<a href=\"%s\" title=\"%s\">%s</a>',
                        esc_url(wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'small_tools_duplicate',
                                    'post' => \$post->ID
                                ),
                                admin_url('admin.php')
                            ),
                            'small_tools_duplicate_post_' . \$post->ID,
                            'nonce'
                        )),
                        esc_attr__('Duplicate this item', 'small-tools'),
                        esc_html__('Duplicate', 'small-tools')
                    );
                }
                return \$actions;
            }, 10, 2);\n";
            $php_content .= "add_filter('page_row_actions', function(\$actions, \$post) {
                return apply_filters('post_row_actions', \$actions, \$post);
            }, 10, 2);\n";
        }

        // Security Features
        $php_content .= "\n// Security Features\n";
        if ($settings['small_tools_disable_xmlrpc'] === 'yes') {
            $php_content .= "add_filter('xmlrpc_enabled', '__return_false');\n";
        }

        if ($settings['small_tools_hide_wp_version'] === 'yes') {
            $php_content .= "remove_action('wp_head', 'wp_generator');\n";
            $php_content .= "add_filter('the_generator', '__return_empty_string');\n";
        }

        // Admin Features
        $php_content .= "\n// Admin Features\n";
        if ($settings['small_tools_dark_mode_enabled'] === 'yes') {
            $php_content .= "add_filter('admin_body_class', function(\$classes) {
                return \$classes . ' small-tools-dark-mode';
            });\n";
        }

        if (!empty($settings['small_tools_admin_footer_text'])) {
            $php_content .= sprintf(
                "add_filter('admin_footer_text', function() {\n    return '%s';\n});\n",
                wp_kses_post($settings['small_tools_admin_footer_text'])
            );
        }

        // Hide Admin Notices
        if ($settings['small_tools_hide_admin_notices'] === 'yes') {
            $php_content .= "add_action('admin_head', function() {
    echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none !important; }</style>';
});\n";
        }

        // Hide Admin Bar
        if ($settings['small_tools_hide_admin_bar'] === 'yes') {
            $php_content .= "add_filter('show_admin_bar', '__return_false');\n";
        }

        // Wider Admin Menu
        if ($settings['small_tools_wider_admin_menu'] === 'yes') {
            $php_content .= "add_action('admin_head', function() {
    echo '<style>
        #wpcontent, #wpfooter {
            margin-left: 240px;
        }
        #adminmenu, #adminmenuback, #adminmenuwrap {
            width: 220px;
        }
        #adminmenu .wp-submenu {
            left: 220px;
        }
    </style>';
});\n";
        }

        // Dashboard Widgets
        if ($settings['small_tools_disable_dashboard_welcome'] === 'yes' ||
            $settings['small_tools_disable_dashboard_activity'] === 'yes' ||
            $settings['small_tools_disable_dashboard_quick_press'] === 'yes' ||
            $settings['small_tools_disable_dashboard_news'] === 'yes' ||
            $settings['small_tools_disable_dashboard_site_health'] === 'yes' ||
            $settings['small_tools_disable_dashboard_at_a_glance'] === 'yes') {
            
            $php_content .= "add_action('wp_dashboard_setup', function() {\n";
            
            if ($settings['small_tools_disable_dashboard_welcome'] === 'yes') {
                $php_content .= "    remove_action('welcome_panel', 'wp_welcome_panel');\n";
            }
            if ($settings['small_tools_disable_dashboard_activity'] === 'yes') {
                $php_content .= "    remove_meta_box('dashboard_activity', 'dashboard', 'normal');\n";
            }
            if ($settings['small_tools_disable_dashboard_quick_press'] === 'yes') {
                $php_content .= "    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');\n";
            }
            if ($settings['small_tools_disable_dashboard_news'] === 'yes') {
                $php_content .= "    remove_meta_box('dashboard_primary', 'dashboard', 'side');\n";
            }
            if ($settings['small_tools_disable_dashboard_site_health'] === 'yes') {
                $php_content .= "    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');\n";
            }
            if ($settings['small_tools_disable_dashboard_at_a_glance'] === 'yes') {
                $php_content .= "    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');\n";
            }
            
            $php_content .= "});\n";
        }

        // Admin Bar Cleanup
        $php_content .= "\n// Admin Bar Cleanup\n";
        $php_content .= "function small_tools_clean_admin_bar() {\n";
        $php_content .= "    global \$wp_admin_bar;\n\n";
        
        if ($settings['small_tools_remove_wp_logo'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('wp-logo');\n";
        }
        
        if ($settings['small_tools_remove_site_name'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('site-name');\n";
        }
        
        if ($settings['small_tools_remove_customize_menu'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('customize');\n";
        }
        
        if ($settings['small_tools_remove_updates_menu'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('updates');\n";
        }
        
        if ($settings['small_tools_remove_comments_menu'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('comments');\n";
        }
        
        if ($settings['small_tools_remove_new_content'] === 'yes') {
            $php_content .= "    \$wp_admin_bar->remove_menu('new-content');\n";
        }
        
        $php_content .= "}\n";
        $php_content .= "add_action('wp_before_admin_bar_render', 'small_tools_clean_admin_bar');\n\n";

        // Add login customization code
        $php_content .= "\n// Login Customization\n";
        if (!empty($settings['small_tools_login_logo'])) {
            $php_content .= "add_action('login_head', function() {\n";
            $php_content .= "    echo '<style type=\"text/css\">\n";
            $php_content .= "        .login h1 a {\n";
            $php_content .= "            background-image: url(" . esc_url($settings['small_tools_login_logo']) . ") !important;\n";
            $php_content .= "            background-size: contain !important;\n";
            $php_content .= "            width: 320px !important;\n";
            $php_content .= "            height: 80px !important;\n";
            $php_content .= "        }\n";
            $php_content .= "    </style>';\n";
            $php_content .= "});\n";
        }

        if (!empty($settings['small_tools_login_logo_url'])) {
            $php_content .= "add_filter('login_headerurl', function() {\n";
            $php_content .= "    return '" . esc_url($settings['small_tools_login_logo_url']) . "';\n";
            $php_content .= "});\n";
        }

        // Add Registration Date and Last Login columns to users list
        if ($settings['small_tools_enable_user_columns'] === 'yes') {
            $php_content .= "\n// User Columns\n";
            $php_content .= "add_filter('manage_users_columns', function(\$columns) {
    \$columns['registered'] = __('Registration Date', 'small-tools');
    if (defined('SMALL_TOOLS_ENABLE_LAST_LOGIN') && SMALL_TOOLS_ENABLE_LAST_LOGIN === 'yes') {
        \$columns['last_login'] = __('Last Login', 'small-tools');
    }
    return \$columns;
});\n\n";

            $php_content .= "add_action('manage_users_custom_column', function(\$value, \$column_name, \$user_id) {
    switch (\$column_name) {
        case 'registered':
            \$user = get_userdata(\$user_id);
            return date_i18n(get_option('date_format'), strtotime(\$user->user_registered));
        case 'last_login':
            if (defined('SMALL_TOOLS_ENABLE_LAST_LOGIN') && SMALL_TOOLS_ENABLE_LAST_LOGIN === 'yes') {
                \$last_login = get_user_meta(\$user_id, 'last_login', true);
                return \$last_login ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), \$last_login) : __('Never', 'small-tools');
            }
    }
    return \$value;
}, 10, 3);\n\n";

            if ($settings['small_tools_enable_last_login'] === 'yes') {
                $php_content .= "// Update last login timestamp when user logs in
add_action('wp_login', function(\$user_login, \$user) {
    update_user_meta(\$user->ID, 'last_login', time());
}, 10, 2);\n\n";
            }
        }

        // Remove Howdy
        if ($settings['small_tools_remove_howdy'] === 'yes') {
            $php_content .= "add_filter('admin_bar_menu', function(\$wp_admin_bar) {
    \$my_account = \$wp_admin_bar->get_node('my-account');
    \$newtext = str_replace('Howdy,', '', \$my_account->title);
    \$wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => \$newtext
    ));
}, 25);\n\n";
        }

        // Remove Help Tabs
        if ($settings['small_tools_remove_help'] === 'yes') {
            $php_content .= "add_action('admin_head', function() {
    \$screen = get_current_screen();
    \$screen->remove_help_tabs();
});\n\n";
        }

        // Add login/logout redirect functionality
        $php_content .= "\n// Login/Logout Redirects\n";
        
        // Login redirect
        $php_content .= "add_filter('login_redirect', function(\$redirect_to, \$requested_redirect_to, \$user) {
    if (!\$user || is_wp_error(\$user)) {
        return \$redirect_to;
    }

    \$user_roles = \$user->roles;
    if (!empty(\$user_roles)) {
        \$primary_role = \$user_roles[0];
        \$role_redirects = maybe_unserialize(stripslashes(SMALL_TOOLS_LOGIN_REDIRECT_ROLES));
        
        // Check if role redirects is an array and has the user's role
        if (is_array(\$role_redirects) && !empty(\$role_redirects[\$primary_role])) {
            return stripslashes(\$role_redirects[\$primary_role]);
        }
        
        // If no role-specific URL is set, use the default URL
        if (defined('SMALL_TOOLS_LOGIN_REDIRECT_DEFAULT_URL') && !empty(SMALL_TOOLS_LOGIN_REDIRECT_DEFAULT_URL)) {
            return stripslashes(SMALL_TOOLS_LOGIN_REDIRECT_DEFAULT_URL);
        }
    }

    return \$redirect_to;
}, 10, 3);\n\n";

        // Logout redirect
        $php_content .= "add_filter('logout_redirect', function(\$redirect_to, \$requested_redirect_to, \$user) {
    if (\$user instanceof WP_User) {
        \$user_roles = \$user->roles;
        if (!empty(\$user_roles)) {
            \$primary_role = \$user_roles[0];
            \$role_redirects = maybe_unserialize(stripslashes(SMALL_TOOLS_LOGOUT_REDIRECT_ROLES));
            
            // Check if role redirects is an array and has the user's role
            if (is_array(\$role_redirects) && !empty(\$role_redirects[\$primary_role])) {
                return stripslashes(\$role_redirects[\$primary_role]);
            }
            
            // If no role-specific URL is set, use the default URL
            if (defined('SMALL_TOOLS_LOGOUT_REDIRECT_DEFAULT_URL') && !empty(SMALL_TOOLS_LOGOUT_REDIRECT_DEFAULT_URL)) {
                return stripslashes(SMALL_TOOLS_LOGOUT_REDIRECT_DEFAULT_URL);
            }
        }
    }

    return \$redirect_to;
}, 10, 3);\n\n";

        // Frontend Features
        $php_content .= "\n// Frontend Features\n";
        if ($settings['small_tools_disable_right_click'] === 'yes' || $settings['small_tools_back_to_top'] === 'yes') {
            $php_content .= $this->get_frontend_assets_code($settings);
        }

        // WooCommerce Features
        if (function_exists('is_woocommerce')) {
            $php_content .= "\n// WooCommerce Features\n";
            $php_content .= $this->get_woocommerce_code($settings);
        }

        // Add WordPress Components functionality
        $php_content .= "\n// WordPress Components\n";
        
        // Disable Comments
        if ($settings['small_tools_disable_comments'] === 'yes') {
            $php_content .= "// Disable Comments
add_action('admin_init', function () {
    // Disable support for comments and trackbacks in post types
    \$post_types = get_post_types();
    foreach (\$post_types as \$post_type) {
        if (post_type_supports(\$post_type, 'comments')) {
            remove_post_type_support(\$post_type, 'comments');
            remove_post_type_support(\$post_type, 'trackbacks');
        }
    }
});
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
// Remove comments page from admin menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});\n";
        }

        // Disable REST API
        if ($settings['small_tools_disable_rest_api'] === 'yes') {
            $php_content .= "// Disable REST API
add_filter('rest_authentication_errors', function(\$result) {
    if (!empty(\$result)) {
        return \$result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 
            __('You are not currently logged in.', 'small-tools'), 
            array('status' => 401)
        );
    }
    return \$result;
});\n";
        }

        // Disable Feeds
        if ($settings['small_tools_disable_feeds'] === 'yes') {
            $php_content .= "// Disable Feeds
function small_tools_disable_feed() {
    wp_die(__('No feed available, please visit the <a href=\"' . esc_url(home_url('/')) . '\">homepage</a>.', 'small-tools'));
}
add_action('do_feed', 'small_tools_disable_feed', 1);
add_action('do_feed_rdf', 'small_tools_disable_feed', 1);
add_action('do_feed_rss', 'small_tools_disable_feed', 1);
add_action('do_feed_rss2', 'small_tools_disable_feed', 1);
add_action('do_feed_atom', 'small_tools_disable_feed', 1);
add_action('do_feed_rss2_comments', 'small_tools_disable_feed', 1);
add_action('do_feed_atom_comments', 'small_tools_disable_feed', 1);\n";
        }

        // Disable jQuery Migrate
        if ($settings['small_tools_disable_jquery_migrate'] === 'yes') {
            $php_content .= "// Disable jQuery Migrate
add_action('wp_default_scripts', function(\$scripts) {
    if (!is_admin() && isset(\$scripts->registered['jquery'])) {
        \$script = \$scripts->registered['jquery'];
        if (\$script->deps) {
            \$script->deps = array_diff(\$script->deps, array('jquery-migrate'));
        }
    }
});\n";
        }

        // Updates Control
        if (isset($settings['small_tools_disable_core_updates']) && $settings['small_tools_disable_core_updates'] === 'yes') {
            $php_content .= "// Disable WordPress Core Updates\n";
            $php_content .= "add_filter('auto_update_core', '__return_false');\n";
            $php_content .= "add_filter('pre_site_transient_update_core', function() {\n";
            $php_content .= "    // Return empty object with all required properties to prevent errors\n";
            $php_content .= "    return (object) array(\n";
            $php_content .= "        'last_checked' => time(),\n";
            $php_content .= "        'version_checked' => get_bloginfo('version'),\n";
            $php_content .= "        'updates' => array(),\n";
            $php_content .= "        'response' => array()\n";
            $php_content .= "    );\n";
            $php_content .= "});\n";
            
            // Prevent update checks
            $php_content .= "remove_action('admin_init', '_maybe_update_core');\n";
            $php_content .= "remove_action('wp_version_check', 'wp_version_check');\n";
            $php_content .= "remove_action('upgrader_process_complete', 'wp_version_check', 10);\n";
            
            // Disable auto updates
            $php_content .= "add_filter('allow_dev_auto_core_updates', '__return_false');\n";
            $php_content .= "add_filter('allow_minor_auto_core_updates', '__return_false');\n";
            $php_content .= "add_filter('allow_major_auto_core_updates', '__return_false');\n";
            
            // Disable update nag
            $php_content .= "add_action('admin_init', function() {\n";
            $php_content .= "    remove_action('admin_notices', 'update_nag', 3);\n";
            $php_content .= "    remove_action('network_admin_notices', 'update_nag', 3);\n";
            $php_content .= "});\n\n";
        }
        
        if (isset($settings['small_tools_disable_plugin_updates']) && $settings['small_tools_disable_plugin_updates'] === 'yes') {
            $php_content .= "// Disable Plugin Updates\n";
            $php_content .= "add_filter('auto_update_plugin', '__return_false');\n";
            $php_content .= "add_filter('pre_site_transient_update_plugins', function() {\n";
            $php_content .= "    // Return empty object instead of null to prevent errors\n";
            $php_content .= "    return (object) array('last_checked' => time(), 'response' => array(), 'checked' => array());\n";
            $php_content .= "});\n";
            $php_content .= "remove_action('load-update-core.php', 'wp_update_plugins');\n";
            $php_content .= "add_filter('plugins_auto_update_enabled', '__return_false');\n\n";
        }
        
        if (isset($settings['small_tools_disable_theme_updates']) && $settings['small_tools_disable_theme_updates'] === 'yes') {
            $php_content .= "// Disable Theme Updates\n";
            $php_content .= "add_filter('auto_update_theme', '__return_false');\n";
            $php_content .= "add_filter('pre_site_transient_update_themes', function() {\n";
            $php_content .= "    // Return empty object instead of null to prevent errors\n";
            $php_content .= "    return (object) array('last_checked' => time(), 'response' => array(), 'checked' => array());\n";
            $php_content .= "});\n";
            $php_content .= "remove_action('load-update-core.php', 'wp_update_themes');\n";
            $php_content .= "add_filter('themes_auto_update_enabled', '__return_false');\n\n";
        }
        
        if (isset($settings['small_tools_disable_translation_updates']) && $settings['small_tools_disable_translation_updates'] === 'yes') {
            $php_content .= "// Disable Translation Updates\n";
            $php_content .= "add_filter('auto_update_translation', '__return_false');\n";
            $php_content .= "add_filter('pre_site_transient_update_translations', function() {\n";
            $php_content .= "    // Return empty object instead of null to prevent errors\n";
            $php_content .= "    return (object) array('last_checked' => time(), 'translations' => array());\n";
            $php_content .= "});\n";
            $php_content .= "add_filter('async_update_translation', '__return_false');\n\n";
        }
        
        if (isset($settings['small_tools_disable_update_emails']) && $settings['small_tools_disable_update_emails'] === 'yes') {
            $php_content .= "// Disable Update Emails\n";
            $php_content .= "add_filter('auto_core_update_send_email', '__return_false');\n";
            $php_content .= "add_filter('send_core_update_notification_email', '__return_false');\n";
            $php_content .= "add_filter('automatic_updates_send_debug_email', '__return_false');\n\n";
        }
        
        // Disable Update Page
        if (isset($settings['small_tools_disable_update_page']) && $settings['small_tools_disable_update_page'] === 'yes') {
            $php_content .= "// Hide WordPress Update Page\n";
            $php_content .= "add_action('admin_menu', function() {\n";
            $php_content .= "    remove_submenu_page('index.php', 'update-core.php');\n";
            $php_content .= "});\n\n";
            
            // Add a safe redirect for direct access to update-core.php
            $php_content .= "add_action('admin_init', function() {\n";
            $php_content .= "    global \$pagenow;\n";
            $php_content .= "    if (\$pagenow === 'update-core.php' && !isset(\$_GET['force-check'])) {\n";
            $php_content .= "        wp_safe_redirect(admin_url('index.php'));\n";
            $php_content .= "        exit;\n";
            $php_content .= "    }\n";
            $php_content .= "});\n\n";
        }

        // Add a function to safely handle the update-core.php page
        if (isset($settings['small_tools_disable_core_updates']) && $settings['small_tools_disable_core_updates'] === 'yes') {
            $php_content .= "// Safely handle the update-core.php page\n";
            $php_content .= "add_action('admin_head-update-core.php', function() {\n";
            $php_content .= "    // Fix for the 'Attempt to read property current on bool' error\n";
            $php_content .= "    add_filter('pre_option_update_core', function() {\n";
            $php_content .= "        return (object) array(\n";
            $php_content .= "            'last_checked' => time(),\n";
            $php_content .= "            'version_checked' => get_bloginfo('version'),\n";
            $php_content .= "            'updates' => array()\n";
            $php_content .= "        );\n";
            $php_content .= "    });\n\n";
            
            $php_content .= "    echo '<style>\n";
            $php_content .= "        .wrap h2, .wrap .update-php {\n";
            $php_content .= "            display: block !important;\n";
            $php_content .= "        }\n";
            $php_content .= "        .wrap .update-php div.notice, .wrap .update-php div.updated, .wrap .update-php div.error {\n";
            $php_content .= "            display: none !important;\n";
            $php_content .= "        }\n";
            $php_content .= "        .wrap .update-php p, .wrap .update-php h2 + p, .wrap .update-php h3 + p {\n";
            $php_content .= "            display: none !important;\n";
            $php_content .= "        }\n";
            $php_content .= "        .core-updates-table, .plugins-updates-table, .themes-updates-table, .translations-updates-table {\n";
            $php_content .= "            display: none !important;\n";
            $php_content .= "        }\n";
            $php_content .= "    </style>';\n";
            $php_content .= "    echo '<div class=\"notice notice-info\"><p>" . esc_html__('Updates have been disabled by the Small Tools plugin.', 'small-tools') . "</p></div>';\n";
            $php_content .= "});\n\n";
            
            // Override the list_core_update function to prevent errors
            $php_content .= "// Override core update functions to prevent errors\n";
            $php_content .= "add_action('admin_head-update-core.php', function() {\n";
            $php_content .= "    if (!function_exists('small_tools_override_list_core_update')) {\n";
            $php_content .= "        function small_tools_override_list_core_update() {\n";
            $php_content .= "            return false;\n";
            $php_content .= "        }\n";
            $php_content .= "    }\n";
            $php_content .= "    add_filter('list_core_update', 'small_tools_override_list_core_update', 10, 0);\n";
            $php_content .= "});\n\n";
        }

        return (bool) file_put_contents($this->settings_file, $php_content);
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

    public function get_default_settings() {
        return $this->default_settings;
    }

    public function get_settings_file_path() {
        return $this->settings_file;
    }

    private function sanitize_setting($value, $option) {
        switch ($option) {
            case 'small_tools_gutenberg_disabled_post_types':
                if (!is_array($value)) {
                    return array();
                }
                $sanitized = array();
                foreach ($value as $post_type) {
                    $post_type = sanitize_key($post_type);
                    // Only add non-empty values that are valid post types and support the editor
                    if (!empty($post_type) && post_type_exists($post_type) && post_type_supports($post_type, 'editor')) {
                        $sanitized[] = $post_type;
                    }
                }
                return $sanitized;
            default:
                return $value;
        }
    }

    private function get_all_settings() {
        $settings = array();
        foreach ($this->default_settings as $key => $default) {
            $settings[$key] = get_option($key, $default);
        }
        return $settings;
    }
} 