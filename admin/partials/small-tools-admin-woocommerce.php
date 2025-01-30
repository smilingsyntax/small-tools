<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="notice notice-warning">
            <p>WooCommerce is not installed or activated. Please install and activate WooCommerce to use these features.</p>
        </div>
    </div>
    <?php
    return;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('small-tools-woocommerce');
        do_settings_sections('small-tools-woocommerce');
        wp_nonce_field('small_tools_woocommerce_settings', 'small_tools_woocommerce_nonce');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="small_tools_wc_variation_threshold">AJAX Variation Threshold</label>
                </th>
                <td>
                    <input type="number" id="small_tools_wc_variation_threshold" 
                           name="small_tools_wc_variation_threshold" 
                           value="<?php echo esc_attr(get_option('small_tools_wc_variation_threshold', '100')); ?>" 
                           min="30" 
                           class="small-text">
                    <p class="description">Set the maximum number of variations per product (default is 30).</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div> 