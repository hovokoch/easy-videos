<?php
/**
 * Easy Videos import page search section view
 *
 * @link       easy-videos.com
 * @since      1.0.0
 *
 * @package    EasyVideos
 * @subpackage easy-videos/admin/templates
 */
?>

<form method="get" id="easy-videos-search-form">
    <h2><?= __('Find videos', 'easy-videos') ?></h2>

    <input type="hidden" name="post_type" value="video">
    <input type="hidden" name="page" value="import">
    <input type="text" placeholder="Channel" name="channel" value="<?= $channel?>" required>

    <button><?= __('Find', 'easy-videos') ?></button>

</form>