<?php if (mb_strlen(htmlspecialchars($post['text'])) > MAX_TEXT_LENGTH): ?>
    <p><?= htmlspecialchars(cut_text($post['text'])) ?></p>
    <div class="post-text__more-link-wrapper">
        <a class="post-text__more-link" href="post.php?id=<?= htmlspecialchars($post['id']) ?>">Читать далее</a>
    </div>
<?php else: ?>
    <p><?= htmlspecialchars($post['text']) ?></p>
<?php endif ?>
