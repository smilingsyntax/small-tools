=== Small Tools ===
Contributors: smilingsyntax
Donate link: https://smilingsyntax.com
Tags: optimization, performance, security, back-to-top
Requires at least: 6.0
Tested up to: 6.7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight multipurpose plugin that provides essential tools for WordPress and WooCommerce users, eliminating the need for multiple plugins.

== Description ==

Small Tools is a comprehensive WordPress plugin that combines multiple essential features into one lightweight package. Unlike other plugins, Small Tools stores settings in a file instead of the database, ensuring zero database queries on load for better performance.

= Key Features =

**Performance Optimization**

* Image Handling
    * Remove image size threshold
    * Control WordPress image scaling
    * Disable default lazy loading
* Script Optimization
    * Remove emoji scripts
    * Remove jQuery Migrate
    * Conditional asset loading

**Back to Top Button**

* Customizable Appearance
    * Adjustable button size (20-100px)
    * Custom background color with opacity
    * Custom icon upload support
    * Default arrow icon fallback
* Position Control
    * Left or right placement
    * Fixed position at bottom
    * Smooth scrolling animation
* Responsive Design
    * Mobile-friendly
    * Adaptive sizing
    * Touch-compatible

**Admin Interface**

* Dark Mode
    * Complete admin panel dark theme
    * Eye-friendly color scheme
    * Automatic theme switching
* Custom Branding
    * Customizable admin footer text
    * Support for HTML in footer
    * Brand-specific messaging

**Security Features**

* Access Control
    * Force strong passwords
    * Disable XML-RPC
    * Hide WordPress version
* Content Protection
    * Disable right-click functionality
    * Prevent unauthorized copying
    * Custom protection messages

**WooCommerce Integration**

* Performance
    * Customizable variation threshold
    * AJAX optimization
    * Enhanced loading times

**Settings Management**

* Import/Export
    * JSON-based settings export
    * Settings backup support
    * Easy site migration
* File Management
    * Automatic settings file generation
    * Performance-optimized loading
    * Secure file storage

= Zero Database Queries =

Unlike other plugins that constantly query the database for settings, Small Tools stores all settings in a PHP file located at `/uploads/small-tools/small-settings.php`. This means:

* Zero database queries on load
* Faster page load times
* Reduced server load
* Better scalability

= WooCommerce Compatible =

Enhance your WooCommerce store with optimized variation handling and improved performance, all without additional database queries.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/small-tools/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin via 'Small Tools' menu in admin panel

== Frequently Asked Questions ==

= Is this plugin compatible with my theme? =

Yes, Small Tools is designed to work with any WordPress theme. It uses standard WordPress hooks and follows best practices for compatibility.

= Will this slow down my site? =

No, the plugin is optimized for performance and actually improves site speed by:
* Storing settings in a file instead of database
* Loading assets only when needed
* Optimizing script and style loading

= Can I customize the back to top button? =

Yes, you can customize:
* Button size (20-100px)
* Background color and opacity
* Custom icon upload
* Position (left or right)
* Default arrow icon is included

= Is the dark mode customizable? =

The dark mode theme is carefully designed for optimal readability and applies automatically to the WordPress admin panel. The color scheme is optimized for reduced eye strain.

== Screenshots ==

1. General Settings Panel
2. Back to Top Button Customization
3. Security Settings
4. WooCommerce Integration
5. Export/Import Settings
6. Settings Generation Page. This will generate the settings file and there will be no **Database Calls**

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release with all core features. Includes file-based settings storage for zero database queries.

== Additional Information ==

**Requirements**
* WordPress 6.0 or higher
* PHP 7.4 or higher
* MySQL 5.6 or higher
* WooCommerce 6.0+ (for WooCommerce features)

For support, bug reports, or feature requests:
* Create an issue on GitHub
* Visit our [support forum](https://smilingsyntax.com/support)
* Email: support@smilingsyntax.com
