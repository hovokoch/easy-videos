<?php

class EasyVideos
{
    private $api_key;

    private $per_page = 12;

    /**
     * EasyVideos constructor.
     */
    function __construct()
    {
        // Youtube Data API key
        $this->api_key = get_option('easy_videos_api_key');

        add_action('init', [$this, 'easy_videos_post_type']);
        add_action('init', [$this, 'easy_videos_taxonomies']);
        add_action('admin_menu', [$this, 'easy_videos_admin_panel']);
        add_action('admin_enqueue_scripts', [$this, 'easy_videos_styles_and_scripts']);
    }

    /**
     * Register custom post types
     */
    function easy_videos_post_type()
    {
        register_post_type('video',
            array(
                'labels' => array(
                    'name' => __('Videos'),
                    'singular_name' => __('Video')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'video'),
                'show_in_rest' => true,
                'menu_icon' => 'dashicons-format-video',

            )
        );
    }

    /**
     * Register plugin taxonomies
     */
    function easy_videos_taxonomies()
    {

        $labels = array(
            'name' => _x('Categories', 'taxonomy general name'),
            'singular_name' => _x('Category', 'taxonomy singular name'),
            'search_items' => __('Search Categories'),
            'popular_items' => __('Popular Categories'),
            'all_items' => __('All Categories'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'separate_items_with_commas' => __('Separate categories with commas'),
            'add_or_remove_items' => __('Add or remove categories'),
            'choose_from_most_used' => __('Choose from the most used categories'),
            'menu_name' => __('Categories'),
        );

        // register the non-hierarchical taxonomy for videos
        register_taxonomy('video_categories', 'video', array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'category'),
        ));
    }

    /**
     * Add submenu pages
     */
    function easy_videos_admin_panel()
    {
        add_submenu_page(
            'edit.php?post_type=video',
            __('Import', 'easy-videos'),
            __('Import', 'easy-videos'),
            'manage_options',
            'import',
            [$this, 'easy_videos_import_callback']
        );

        add_submenu_page(
            'edit.php?post_type=video',
            __('Settings', 'easy-videos'),
            __('Settings', 'easy-videos'),
            'manage_options',
            'settings',
            [$this, 'easy_videos_settings_callback']
        );
    }

    /**
     * Styles and scripts
     */
    public function easy_videos_styles_and_scripts()
    {
        wp_register_style('easy_videos_settings', EASY_VIDEOS_URL . '/admin/css/easy-videos.css', array(), EASY_VIDEOS_VERSION);
        wp_enqueue_style('easy_videos_settings');

        wp_register_script('easy_videos_main', EASY_VIDEOS_URL . '/admin/js/easy-videos.js', array(), EASY_VIDEOS_VERSION);
        wp_enqueue_script('easy_videos_main');
    }

    /**
     * Import page
     */
    function easy_videos_import_callback()
    {

    }

    /**
     * Settings page
     */
    function easy_videos_settings_callback()
    {

    }

}

new EasyVideos();