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

}

new EasyVideos();