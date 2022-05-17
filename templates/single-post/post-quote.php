<!-- пост-цитата -->
<div class="post-details__image-wrapper post-quote">
    <div class="post__main">
        <blockquote>
            <p>
                <?= htmlspecialchars($post['text']); ?>
            </p>
            <cite><?= htmlspecialchars($post['quote_author']); ?></cite>
        </blockquote>
    </div>
</div>
