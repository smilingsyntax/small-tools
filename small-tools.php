<?php
/**
 * Plugin Name: Small Tools
 * Plugin URI: https://smilingsyntax.com/plugins/small-tools
 * Description: A lightweight multipurpose plugin that provides essential tools for WordPress and WooCommerce users, eliminating the need for multiple plugins.
 * Version: 2.0.0
 * Author: smilingsyntax
 * Author URI: https://smilingsyntax.com
 * Text Domain: small-tools
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Documentation: https://smilingsyntax.com/small-tools/docs
 * Support: https://smilingsyntax.com/support
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('SMALL_TOOLS_VERSION', '2.0.0');
define('SMALL_TOOLS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMALL_TOOLS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SMALL_TOOLS_PLUGIN_FILE', __FILE__);

// Include required files
require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools.php';
require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools-activator.php';
require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools-deactivator.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, array('Small_Tools_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Small_Tools_Deactivator', 'deactivate'));

// Initialize the plugin
function small_tools_run() {
    $plugin = new Small_Tools();
    $plugin->run();
}
small_tools_run(); 