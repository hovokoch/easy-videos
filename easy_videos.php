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
        // Create posts
        if ($_POST['videos']) {
            foreach ($_POST['videos'] as $id => $video) {
                if(isset($video['title'])){
                    $post_data = array(
                        'post_type' => 'video',
                        'post_title' => sanitize_text_field($video['title']),
                        'post_content' => '<iframe width="720" height="360" src="https://www.youtube.com/embed/' . $id . '"></iframe>',
                        'post_status' => 'publish',
                        'post_author' => 1,
                    );

                    $post_id = wp_insert_post($post_data);
                    $taxonomy = 'video_categories';
                    if(isset($video['categories'])){
                        $categories = array_map(
                            function($value) { return (int)$value; },
                            $video['categories']
                        );
                        wp_set_object_terms($post_id, $categories, $taxonomy);
                    }
                }
            }
        }

        // Channel id
        $channel = $_GET['channel'];

        // Find videos view
        require_once 'includes/templates/partials/import.php';

        if (isset($channel)) {
            $parameters = [
                'order' => 'date',
                'part' => 'snippet',
                'channelId' => $channel,
                'maxResults' => $this->per_page,
                'key' => $this->api_key,
            ];

            $page_token = $_GET['pageToken'];
            if (isset($page_token)) {
                $parameters['pageToken'] = $page_token;
            }

            $url = add_query_arg($parameters, 'https://www.googleapis.com/youtube/v3/search');

            $api_request = wp_remote_get($url);
            $response = wp_remote_retrieve_body($api_request);
            $data = json_decode($response);

            if (!empty($data->items)) {
                require_once 'includes/templates/partials/videos.php';
            } else {
                echo '<p class="error">' . __('Invalid API key or channel ID.', 'easy-videos') . '</p>';
            }
        }
    }
    /**
     * Settings page
     */
    function easy_videos_settings_callback()
    {
        if ($_POST) {
            if (empty($_POST) || !wp_verify_nonce($_POST['easy_videos_nonce_field'], 'easy-videos-settings-save')) {
                print 'Sorry, verification data is not provided.';
                exit;
            } else {
                $api_key = $_POST['easy_videos_api_key'];
                if ($api_key) {
                    update_option('easy_videos_api_key', $api_key);
                }
            }
        }

        require_once 'includes/templates/settings-page.php';
    }

    /**
     * Pagination for videos
     * @param $data
     * @return string
     */
    function easy_videos_pagination($data)
    {
        $pagination = '';

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ($data->prevPageToken) {
            $previous_page = add_query_arg([
                'pageToken' => $data->prevPageToken,
            ], $url);

            $pagination .= '<a href="' . $previous_page . '">'.__('Previous', 'easy-videos').'</a>';
        }

        if ($data->nextPageToken) {
            $next_page = add_query_arg([
                'pageToken' => $data->nextPageToken,
            ], $url);

            $pagination .= '<a href="' . $next_page . '">'.__('Next', 'easy-videos').'</a>';
        }

        return $pagination;

    }

}

new EasyVideos();