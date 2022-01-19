<?php
/**
 * Easy Videos settings page view
 *
 * @link       easy-videos.com
 * @since      1.0.0
 *
 * @package    EasyVideos
 * @subpackage easy-videos/admin/templates
 */
?>

<div id="easy-videos-settings">
    <div class="container">
        <form method="post">
            <h2><?= __('Easy Videos settings', 'easy-videos') ?></h2>
            <div class="form-group">
                <input type="text" name="easy_videos_api_key" class="<?= get_option('easy_videos_api_key') ? 'active' : ''?>" value="<?= get_option('easy_videos_api_key'); ?>">
                <label for="input" class="control-label"><?= __('Youtube Data API Key', 'easy-videos') ?></label>
                <i class="bar"></i>
            </div>
            <div class="button-container">
                <?php
                wp_nonce_field('easy-videos-settings-save', 'easy_videos_nonce_field');
                ?>
                <button class="button"><span><?= __('Save', 'easy-videos') ?></span></button>
            </div>
        </form>
    </div>
</div>