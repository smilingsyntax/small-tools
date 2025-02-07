# Small Tools

A lightweight multipurpose WordPress plugin that provides essential tools for WordPress and WooCommerce users, eliminating the need for multiple plugins, and bloated DB rows that comes with those.

**Although this plugin stores settings on DataBase, on Load not a single hit is done on Database. Instead it stores the settings on file /uploads/small-tools/small-settings.php**

## Features

### Performance Optimization
- **Image Handling**
  - Remove image size threshold
  - Control WordPress image scaling
  - Disable default lazy loading
- **Script Optimization**
  - Remove emoji scripts
  - Remove jQuery Migrate
  - Conditional asset loading

### Back to Top Button
- **Customizable Appearance**
  - Adjustable button size (20-100px)
  - Custom background color with opacity
  - Custom icon upload support
  - Default arrow icon fallback
- **Position Control**
  - Left or right placement
  - Fixed position at bottom
  - Smooth scrolling animation
- **Responsive Design**
  - Mobile-friendly
  - Adaptive sizing
  - Touch-compatible

### Admin Interface
- **Dark Mode**
  - Complete admin panel dark theme
  - Eye-friendly color scheme
  - Automatic theme switching
- **Custom Branding**
  - Customizable admin footer text
  - Support for HTML in footer
  - Brand-specific messaging

### Security Features
- **Access Control**
  - Force strong passwords
  - Disable XML-RPC
  - Hide WordPress version
- **Content Protection**
  - Disable right-click functionality
  - Prevent unauthorized copying
  - Custom protection messages

### WooCommerce Integration
- **Performance**
  - Customizable variation threshold
  - AJAX optimization
  - Enhanced loading times

### Settings Management
- **Import/Export**
  - JSON-based settings export
  - Settings backup support
  - Easy site migration
- **File Management**
  - Automatic settings file generation
  - Performance-optimized loading
  - Secure file storage

## Installation

1. Upload the plugin files to `/wp-content/plugins/small-tools/`
2. Activate through WordPress plugins menu
3. Configure via 'Small Tools' admin menu

## Configuration

### General Settings
- Enable/disable right-click protection
- Configure image handling options
- Set up back to top button
- Manage performance features

### Security Settings
- Configure password requirements
- Manage XML-RPC access
- Control version information display

### WooCommerce Settings
- Set variation threshold
- Optimize product loading

### Utilities
- Database cleanup options
- Settings import/export
- File management tools

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0+ (for WooCommerce features)

## Frequently Asked Questions

**Q: Is this plugin compatible with my theme?**  
A: Yes, Small Tools is designed to work with any WordPress theme.

**Q: Will this slow down my site?**  
A: No, the plugin is optimized for performance and only loads features when needed.

**Q: Can I customize the back to top button?**  
A: Yes, you can customize the size, color, position, and icon of the button.

**Q: Is the dark mode customizable?**  
A: The dark mode theme is carefully designed for optimal readability and applies automatically.

## Support

For support, bug reports, or feature requests:
- Create an issue on GitHub
- Visit our [support forum](https://smilingsyntax.com/support)
- Email: support@smilingsyntax.com

## Contributing

Contributions are welcome! Please read our contributing guidelines before submitting pull requests.

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Credits

Developed by [smilingsyntax](https://smilingsyntax.com) 