<?php
/**
 * Plugin Name: Easy Videos
 * Plugin URI: https://wordpress.org/plugins/easy-videos/
 * Description: Easy Videos description
 * Author: Easy Videos
 * Author URI: https://easy-videos.com/
 * Version: 1.0.0
 * Text Domain: easy-videos
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Required constants
 */
define( 'EASY_VIDEOS_VERSION', '1.0.0' );
define( 'EASY_VIDEOS_MAIN_FILE', __FILE__ );
define( 'EASY_VIDEOS_URL', untrailingslashit( plugins_url( '', EASY_VIDEOS_MAIN_FILE ) ));

/**
 * Initialization
 */
function easy_videos_init()
{
    require_once 'easy_videos.php';
}
add_action('plugins_loaded', 'easy_videos_init');

/**
 * Plugin action links
 * @param $links
 * @return array
 */
function easy_videos_action_links($links)
{
    $plugin_links = array(
        '<a href="' . admin_url('edit.php?post_type=video&page=settings') . '">' . esc_html__('Settings', 'easy_videos') . '</a>',
    );

    return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'easy_videos_action_links');