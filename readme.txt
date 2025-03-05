=== Small Tools ===
Contributors: smilingsyntax
Donate link: https://smilingsyntax.com
Tags: small tools, back to top, smiling syntax, Admin and Site Enhancements, svg upload, update control
Requires at least: 6.0
Tested up to: 6.7.2
Requires PHP: 7.4
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A collection of small but powerful tools to optimize and enhance your WordPress site.

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

**Frontend Enhancements**
    * Custom Colors for background and text on selecting text on frontend

**Updates Control**
    * Selectively disable WordPress core updates
    * Disable plugin updates
    * Disable theme updates
    * Disable translation updates
    * Disable update notification emails
    * Hide WordPress update page

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
* Media Management
    * Media replacement feature
    * SVG support
    * AVIF support

**Content Management**

* Gutenberg Control
    * Disable per post type
    * Global enable/disable
    * Custom post type support
* Content Duplication
    * One-click post/page duplication
    * Copy all metadata
    * Copy taxonomies

**Security Features**

* Access Control
    * Force strong passwords
    * Disable XML-RPC
    * Hide WordPress version
* Content Protection
    * Disable right-click functionality
    * Prevent unauthorized copying
    * Custom protection messages
    * Prevent Content copying
* Login Security
    * Custom login logo
    * Login/logout redirects
    * Role-based redirects

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

**User Management**

* Enhanced User Columns
    * Registration date display
    * Last login tracking
    * Role-based features

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

== Screenshots ==

1. General Settings Panel
2. Media Settings
3. Admin Tweaks and Settings
4. Back to Top Button Customization
5. WordPress Components settings
6. Performance Settings
7. Login page settings
8. Security Settings
9. WooCommerce Integration
10. Export/Import Settings, Settings Generation Page

== Changelog ==

= 2.2.0 =
* Added: Granular WordPress Updates Control - selectively disable different types of updates
* Added: New Updates tab in the settings page
* Improved: Settings organization and user interface
* Added: Prevent Content copying

= 2.1.0 =
* Added: Custom Colors for background and text on selecting text on frontend

= 2.0.0 =
* Added: Gutenberg editor control per post type
* Added: Media replacement functionality
* Added: SVG and AVIF file support
* Added: Content duplication feature
* Added: Role-based login/logout redirects
* Added: User registration date and last login columns
* Added: Custom login logo upload
* Added: Enhanced dark mode support
* Added: Nested settings UI with smooth animations
* Added: Performance improvements in settings management
* Added: Better form validation and error handling
* Added: Expanded WooCommerce compatibility
* Added: New security features and options
* Fixed: Various UI/UX improvements
* Fixed: Better handling of file-based settings
* Fixed: Enhanced error reporting
* Updated: Improved documentation
* Updated: Code optimization and cleanup

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 2.2.0 =
New feature: Granular WordPress Updates Control - selectively disable different types of updates including core, plugin, theme, and translation updates.

= 2.1.0 =
Feature added to Custom Colors for background and text on selecting text on frontend

= 2.0.0 =
Major update with new features including Gutenberg control, media management, and content duplication. Includes significant performance improvements and enhanced UI.

= 1.0.0 =
Initial release with core features.

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
