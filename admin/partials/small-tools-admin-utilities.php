<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="card">
        <h2>Settings File Management</h2>
        <p>Manage the settings file that stores your configuration for better performance.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('small_tools_regenerate_settings', 'small_tools_regenerate_nonce'); ?>
            <p>
                <strong>Current Settings File:</strong><br>
                <?php 
                $settings_file = Small_Tools_Settings::get_instance()->get_settings_file_path();
                echo esc_html($settings_file);
                echo file_exists($settings_file) ? ' (Exists)' : ' (Not Generated)';
                ?>
            </p>
            <?php submit_button('Regenerate Settings File', 'secondary', 'small_tools_regenerate_settings'); ?>
        </form>

        <hr>

        <form method="post" action="">
            <?php wp_nonce_field('small_tools_reset_defaults', 'small_tools_reset_nonce'); ?>
            <p>Reset all settings to their default values and regenerate the settings file.</p>
            <?php submit_button('Reset to Defaults', 'secondary', 'small_tools_reset_defaults', true, array(
                'onclick' => 'return confirm("Are you sure you want to reset all settings to their default values? This cannot be undone.");'
            )); ?>
        </form>
    </div>

    <div class="card">
        <h2>Database Optimization</h2>
        <p>Clean up your database by removing unnecessary data.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('small_tools_db_cleanup', 'small_tools_db_nonce'); ?>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="revisions"> 
                Delete ALL Post Revisions
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="autodrafts"> 
                Delete ALL Auto Drafts
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="trash"> 
                Delete ALL Trash
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="spam"> 
                Delete ALL Spam Comments
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="transients"> 
                Delete ALL Expired Transients
            </label><br>
            
            <?php submit_button('Clean Database', 'primary', 'small_tools_cleanup_db'); ?>
        </form>
    </div>

    <div class="card">
        <h2>Export/Import Settings</h2>
        <p>Export your Small Tools settings to use on another site or backup.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('small_tools_export_settings', 'small_tools_export_nonce'); ?>
            <?php submit_button('Export Settings', 'secondary', 'small_tools_export'); ?>
        </form>

        <hr>

        <p>Import settings from another Small Tools installation.</p>
        <form method="post" action="" enctype="multipart/form-data">
            <?php wp_nonce_field('small_tools_import_settings', 'small_tools_import_nonce'); ?>
            <input type="file" name="small_tools_import_file" accept=".json">
            <?php submit_button('Import Settings', 'secondary', 'small_tools_import'); ?>
        </form>
    </div>
</div> 