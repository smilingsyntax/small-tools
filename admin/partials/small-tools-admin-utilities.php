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
                Delete Post Revisions
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="autodrafts"> 
                Delete Auto Drafts
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="trash"> 
                Delete Trashed Posts
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="spam"> 
                Delete Spam Comments
            </label><br>
            
            <label>
                <input type="checkbox" name="cleanup_options[]" value="transients"> 
                Delete Expired Transients
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

    <div class="card">
        <h2>Custom Post Types</h2>
        <p>Create and manage custom post types.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('small_tools_cpt', 'small_tools_cpt_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="cpt_name">Post Type Name</label>
                    </th>
                    <td>
                        <input type="text" id="cpt_name" name="cpt_name" class="regular-text" 
                               placeholder="e.g., product, book, movie">
                        <p class="description">Lowercase letters and underscores only (e.g., my_post_type)</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="cpt_label">Label</label>
                    </th>
                    <td>
                        <input type="text" id="cpt_label" name="cpt_label" class="regular-text" 
                               placeholder="e.g., Products, Books, Movies">
                        <p class="description">The display name for your post type</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label>Supports</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" name="cpt_supports[]" value="title" checked> 
                            Title
                        </label><br>
                        <label>
                            <input type="checkbox" name="cpt_supports[]" value="editor" checked> 
                            Editor
                        </label><br>
                        <label>
                            <input type="checkbox" name="cpt_supports[]" value="thumbnail"> 
                            Featured Image
                        </label><br>
                        <label>
                            <input type="checkbox" name="cpt_supports[]" value="excerpt"> 
                            Excerpt
                        </label><br>
                        <label>
                            <input type="checkbox" name="cpt_supports[]" value="custom-fields"> 
                            Custom Fields
                        </label>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Create Post Type', 'primary', 'small_tools_create_cpt'); ?>
        </form>
    </div>
</div> 