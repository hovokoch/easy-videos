<?php
/**
 * Easy Videos import page results view
 *
 * @link       easy-videos.com
 * @since      1.0.0
 *
 * @package    EasyVideos
 * @subpackage easy-videos/admin/templates
 */

$terms = get_terms([
    'taxonomy' => 'video_categories',
    'hide_empty' => false,
]);
?>


<form method="post" id="easy-videos-import-form">

    <h2><?= sprintf(__('Total videos - %u', 'easy-videos'), $data->pageInfo->totalResults) ?></h2>

    <ul id="easy-videos-import">
        <?php foreach ($data->items as $i => $item) {
            if (isset($item->id->videoId)) { ?>
                <li>
                    <div class="list-item-overlay">
                        <input type="checkbox" id="video-<?= $i ?>" class="easy-video-checkbox-item"
                               data-name="<?= $item->snippet->title ?>" data-id="<?= $item->id->videoId ?>"/>
                        <label for="video-<?= $i ?>" class="video-label">
                            <figure class="youtube-frame-overlay">
                                <iframe width="100%" height="200"
                                        src="https://www.youtube.com/embed/<?= $item->id->videoId ?>"
                                        class="easy-video-iframe"></iframe>
                                <img src="https://img.youtube.com/vi/<?= $item->id->videoId ?>/0.jpg"
                                     class="easy-video-image"/>
                            </figure>
                            <h4><?= $item->snippet->title ?></h4>
                        </label>
                        <?php if (!empty($terms)): ?>
                            <select multiple data-multi-select-plugin name="videos[<?= $item->id->videoId ?>][categories][]">
                                <?php
                                foreach ($terms as $term) {
                                    echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                                }
                                ?>
                            </select>
                        <?php endif; ?>
                        <label for="easy-videos-preview-<?= $i ?>">
                            <input type="checkbox" id="easy-videos-preview-<?= $i ?>" class="easy-videos-preview">
                            <?= __('Preview', 'easy-videos') ?>
                        </label>
                    </div>
                </li>
            <?php }
        } ?>
    </ul>

    <label for="easy-videos-select-all">
        <input type="checkbox" id="easy-videos-select-all">
        <?= __('Select All', 'easy-videos') ?>
    </label>

    <?= $this->easy_videos_pagination($data) ?>

    <button><?= __('Import', 'easy-videos') ?></button>

</form>