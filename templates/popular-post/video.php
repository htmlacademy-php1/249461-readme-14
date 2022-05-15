<!-- пост-видео -->
<div class="post-details__image-wrapper post-photo__image-wrapper">
    <div class="post-video__block">
        <div class="post-video__preview">
            <?= embed_youtube_cover(htmlspecialchars($post['video'])) ?>
        </div>
        <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>" class="post-video__play-big button">
            <svg class="post-video__play-big-icon" width="14" height="14">
                <use xlink:href="#icon-video-play-big"></use>
            </svg>
            <span class="visually-hidden">Запустить проигрыватель</span>
        </a>
    </div>
</div>
