<?php

class Small_Tools {
    protected $version;
    protected $plugin_name;

    public function __construct() {
            $this->version = SMALL_TOOLS_VERSION;
        $this->plugin_name = 'small-tools';
        
        $this->load_dependencies();
        add_action('plugins_loaded', array($this, 'setup_actions'));
    }

    private function load_dependencies() {
        // Load feature classes here
        require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools-settings.php';
        
        // Load admin class
        require_once SMALL_TOOLS_PLUGIN_DIR . 'admin/class-small-tools-admin.php';

        // Load enqueue class
        require_once SMALL_TOOLS_PLUGIN_DIR . 'includes/class-small-tools-enqueue.php';
        
        // Initialize admin
        $plugin_admin = new Small_Tools_Admin($this->plugin_name, $this->version);
        $small_tools_enqueue = new Small_Tools_Enqueue($this->plugin_name, $this->version);
    }

    public function setup_actions() {
        // Load hooks file if it exists
        $settings = Small_Tools_Settings::get_instance();
        $hooks_file = $settings->get_settings_file_path();
        
        if (!file_exists($hooks_file)) {
            $settings->generate_settings_file();
        }
        
        if (file_exists($hooks_file)) {
            require_once $hooks_file;
        }

        // Media Replacement hooks
        if (get_option('small_tools_enable_media_replace') === 'yes') {
            add_filter('media_row_actions', array($this, 'add_media_action'), 10, 2);
            add_action('admin_footer', array($this, 'add_media_replace_popup'));
            add_action('wp_ajax_small_tools_replace_media', array($this, 'handle_media_replacement'));
            add_action('wp_ajax_get_attachment_details', array($this, 'get_attachment_details'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_media_replace_scripts'));
        }

        // Content Duplication hooks
        add_action('admin_action_small_tools_duplicate', array($this, 'duplicate_post_as_draft'));
        add_filter('post_row_actions', array($this, 'duplicate_post_link'), 10, 2);
        add_filter('page_row_actions', array($this, 'duplicate_post_link'), 10, 2);

        // WordPress General Features
        if (get_option('small_tools_disable_emojis') === 'yes') {
            add_action('init', array($this, 'disable_emojis'));
        }

        if (get_option('small_tools_remove_jquery_migrate') === 'yes') {
            add_action('wp_default_scripts', array($this, 'remove_jquery_migrate'));
        }

        // WooCommerce Features
        if (function_exists('is_woocommerce')) {
            add_filter('woocommerce_ajax_variation_threshold', array($this, 'increase_wc_variation_threshold'), 10, 2);
        }

        // Admin Features
        if (!empty(get_option('small_tools_admin_footer_text'))) {
            add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
        }

        // Asset Management
        if (get_option('small_tools_disable_right_click') === 'yes') {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        }
    }

    public function run() {
        // The main plugin logic
    }

    // Content Duplication Feature
    public function duplicate_post_as_draft() {
        // Check if duplication is enabled
        if (get_option('small_tools_enable_duplication') !== 'yes') {
            wp_die(__('Content duplication is not enabled.', 'small-tools'));
        }

        // Check if post ID has been provided and action is duplicate
        if (empty($_GET['post']) || empty($_GET['action']) || $_GET['action'] !== 'small_tools_duplicate') {
            return;
        }

        // Verify nonce
        if (!isset($_GET['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'small_tools_duplicate_post_' . absint($_GET['post']))) {
            wp_die(__('Security check failed.', 'small-tools'));
        }

        // Get the original post
        $post_id = absint($_GET['post']);
        $post = get_post($post_id);

        // Verify post exists and user has permission
        if (!$post || !current_user_can('edit_post', $post_id)) {
            wp_die(__('You do not have permission to duplicate this content.', 'small-tools'));
        }

        // Get all current post attributes
        $args = array(
            'post_author'    => wp_get_current_user()->ID,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name . '-copy',
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title . ' ' . __('(Copy)', 'small-tools'),
            'post_type'      => $post->post_type,
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        // Insert the post
        $new_post_id = wp_insert_post($args);

        if ($new_post_id) {
            // Copy post taxonomies
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            // Copy post metadata
            $post_meta = get_post_meta($post_id);
            if ($post_meta) {
                foreach ($post_meta as $meta_key => $meta_values) {
                    if ('_wp_old_slug' === $meta_key) { // Skip old slug
                        continue;
                    }
                    foreach ($meta_values as $meta_value) {
                        add_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value));
                    }
                }
            }

            // Redirect to the edit post screen for the new draft
            wp_safe_redirect(
                add_query_arg(
                    array(
                        'action' => 'edit',
                        'post' => $new_post_id
                    ),
                    admin_url('post.php')
                )
            );
            exit;
        } else {
            wp_die(__('Post creation failed.', 'small-tools'));
        }
    }

    // Add duplicate action link
    public function duplicate_post_link($actions, $post) {
        // Check if duplication is enabled
        if (get_option('small_tools_enable_duplication') !== 'yes') {
            return $actions;
        }

        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = sprintf(
                '<a href="%s" title="%s">%s</a>',
                esc_url(wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'small_tools_duplicate',
                            'post' => $post->ID
                        ),
                        admin_url('admin.php')
                    ),
                    'small_tools_duplicate_post_' . $post->ID,
                    'nonce'
                )),
                esc_attr__('Duplicate this item', 'small-tools'),
                esc_html__('Duplicate', 'small-tools')
            );
        }
        return $actions;
    }

    // WordPress General Features
    public function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    public function remove_jquery_migrate($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, array('jquery-migrate'));
            }
        }
    }

    // WooCommerce Features
    public function increase_wc_variation_threshold($qty, $product) {
        return (int) get_option('small_tools_wc_variation_threshold', 30);
    }

    // Admin Features
    public function custom_admin_footer() {
        return get_option('small_tools_admin_footer_text', '');
    }

    // Asset Management
    public function enqueue_frontend_assets() {
        if (get_option('small_tools_disable_right_click') === 'yes') {
            wp_enqueue_script(
                'small-tools-frontend',
                SMALL_TOOLS_PLUGIN_URL . 'public/js/small-tools-public.js',
                array('jquery'),
                $this->version,
                true
            );
        }
    }

    // Media Replacement Feature
    public function add_media_action($actions, $post) {
        if (!current_user_can('upload_files')) {
            return $actions;
        }

        $actions['replace_media'] = sprintf(
            '<a href="#" class="small-tools-replace-media" data-id="%d">%s</a>',
            $post->ID,
            esc_html__('Replace Media', 'small-tools')
        );

        return $actions;
    }

    public function add_media_replace_popup() {
        ?>
        <div id="small-tools-media-replace-modal" class="small-tools-modal-wrapper" style="display:none;">
            <div class="small-tools-modal">
                <div class="small-tools-modal-content">
                    <div class="small-tools-modal-header">
                        <h1><?php esc_html_e('Replace Media', 'small-tools'); ?></h1>
                        <button type="button" class="small-tools-modal-close">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                    
                    <div class="small-tools-modal-body">
                        <div class="small-tools-media-replace-container">
                            <div class="small-tools-current-media">
                                <h2><?php esc_html_e('Current Media', 'small-tools'); ?></h2>
                                <div class="small-tools-media-preview"></div>
                                <div class="small-tools-media-details"></div>
                            </div>
                            
                            <div class="small-tools-replacement-media">
                                <h2><?php esc_html_e('Select Replacement', 'small-tools'); ?></h2>
                                <p class="description"><?php esc_html_e('Choose a file to replace the current media. The new file must be of the same type.', 'small-tools'); ?></p>
                                
                                <form id="small-tools-media-replace-form">
                                    <input type="hidden" name="attachment_id" id="attachment_id" value="">
                                    <input type="hidden" name="replacement_id" id="replacement_id" value="">
                                    
                                    <div class="small-tools-upload-area">
                                        <button type="button" class="button button-hero" id="small-tools-select-media">
                                            <?php esc_html_e('Select or Upload Media', 'small-tools'); ?>
                                        </button>
                                        <div id="small-tools-selected-preview"></div>
                                    </div>
                                    
                                    <div class="small-tools-options">
                                        <label>
                                            <input type="checkbox" name="update_thumbnails" value="1" checked>
                                            <?php esc_html_e('Update all thumbnail sizes', 'small-tools'); ?>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="small-tools-modal-footer">
                        <div class="small-tools-modal-actions">
                            <button type="button" class="button small-tools-modal-close">
                                <?php esc_html_e('Cancel', 'small-tools'); ?>
                            </button>
                            <button type="button" class="button button-primary" id="small-tools-replace-submit" disabled>
                                <?php esc_html_e('Replace Media', 'small-tools'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="small-tools-modal-backdrop"></div>
        </div>
        <?php
    }

    public function enqueue_media_replace_scripts($hook) {
        if ($hook !== 'upload.php' && $hook !== 'post.php') {
            return;
        }

        wp_enqueue_media();
        
        wp_enqueue_style(
            'small-tools-media-replace',
            SMALL_TOOLS_PLUGIN_URL . 'admin/css/small-tools-media-replace.css',
            array(),
            $this->version
        );

        wp_enqueue_script(
            'small-tools-media-replace',
            SMALL_TOOLS_PLUGIN_URL . 'admin/js/small-tools-media-replace.js',
            array('jquery', 'media-upload'),
            $this->version,
            true
        );

        wp_localize_script(
            'small-tools-media-replace',
            'smallToolsMediaReplace',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('small_tools_media_replace'),
                'strings' => array(
                    'title' => __('Select Replacement Media', 'small-tools'),
                    'button' => __('Use this media', 'small-tools'),
                    'replacing' => __('Replacing...', 'small-tools'),
                    'success' => __('Media replaced successfully.', 'small-tools'),
                    'error' => __('Error replacing media.', 'small-tools')
                )
            )
        );
    }

    public function get_attachment_details() {
        check_ajax_referer('small_tools_media_replace', 'security');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'small-tools')));
        }

        $attachment_id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        if (!$attachment_id) {
            wp_send_json_error(array('message' => __('Invalid attachment ID.', 'small-tools')));
        }

        $attachment = get_post($attachment_id);
        if (!$attachment) {
            wp_send_json_error(array('message' => __('Attachment not found.', 'small-tools')));
        }

        $file = get_attached_file($attachment_id);
        $filename = basename($file);
        $filetype = wp_check_filetype($filename);
        $attachment_url = wp_get_attachment_url($attachment_id);

        // Prepare preview
        if (wp_attachment_is_image($attachment_id)) {
            $preview = wp_get_attachment_image($attachment_id, 'medium', false, array('class' => 'small-tools-preview-image'));
        } else {
            $preview = sprintf(
                '<div class="small-tools-media-info"><span class="dashicons dashicons-media-default"></span><span class="filename">%s</span></div>',
                esc_html($filename)
            );
        }

        // Prepare details
        $details = sprintf(
            '<div class="small-tools-media-info-list">
                <p><strong>%s</strong> %s</p>
                <p><strong>%s</strong> %s</p>
                <p><strong>%s</strong> <a href="%s" target="_blank">%s</a></p>
            </div>',
            esc_html__('Filename:', 'small-tools'),
            esc_html($filename),
            esc_html__('File type:', 'small-tools'),
            esc_html($filetype['type']),
            esc_html__('URL:', 'small-tools'),
            esc_url($attachment_url),
            esc_html($attachment_url)
        );

        wp_send_json_success(array(
            'preview' => $preview,
            'details' => $details
        ));
    }

    public function handle_media_replacement() {
        check_ajax_referer('small_tools_media_replace', 'security');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'small-tools')));
        }

        // Get attachment IDs
        $original_id = isset($_POST['attachment_id']) ? absint($_POST['attachment_id']) : 0;
        $replacement_id = isset($_POST['replacement_id']) ? absint($_POST['replacement_id']) : 0;

        if (!$original_id || !$replacement_id) {
            wp_send_json_error(array('message' => __('Invalid attachment IDs.', 'small-tools')));
        }

        // Get file paths
        $original_file = get_attached_file($original_id);
        $replacement_file = get_attached_file($replacement_id);

        if (!$original_file || !$replacement_file || !file_exists($replacement_file)) {
            wp_send_json_error(array('message' => __('Could not locate one or both files.', 'small-tools')));
        }

        // Check file types match
        $original_type = wp_check_filetype(basename($original_file));
        $replacement_type = wp_check_filetype(basename($replacement_file));

        if ($original_type['type'] !== $replacement_type['type']) {
            wp_send_json_error(array(
                'message' => sprintf(
                    __('File type mismatch. Original: %1$s, Replacement: %2$s', 'small-tools'),
                    $original_type['type'],
                    $replacement_type['type']
                )
            ));
        }

        // Get upload directory info
        $uploads = wp_upload_dir();
        $original_rel_path = _wp_get_attachment_relative_path($original_file);
        $original_filename = wp_basename($original_file);
        $new_file_path = $uploads['basedir'] . '/' . $original_rel_path;

        // Ensure target directory exists
        if (!wp_mkdir_p($new_file_path)) {
            wp_send_json_error(array('message' => __('Failed to create target directory.', 'small-tools')));
        }

        $new_file = trailingslashit($new_file_path) . $original_filename;

        // Copy the replacement file
        if (!copy($replacement_file, $new_file)) {
            wp_send_json_error(array('message' => __('Failed to copy replacement file.', 'small-tools')));
        }

        // Update the attachment metadata
        update_attached_file($original_id, $new_file);

        // Update thumbnails if requested
        if (isset($_POST['update_thumbnails']) && $_POST['update_thumbnails'] === '1') {
            $metadata = wp_generate_attachment_metadata($original_id, $new_file);
            wp_update_attachment_metadata($original_id, $metadata);
        }

        // Clean up the temporary replacement attachment
        wp_delete_attachment($replacement_id, true);

        wp_send_json_success(array(
            'message' => __('Media replaced successfully.', 'small-tools')
        ));
    }
}