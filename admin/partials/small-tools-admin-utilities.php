<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
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