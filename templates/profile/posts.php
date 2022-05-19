<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($posts as $post) : ?>
        <article class="profile__post post post-<?= htmlspecialchars($post['class']) ?>">
            <header class="post__header">
                <?php if ($post['repost'] == 1) : ?>
                    <div class="post__author">
                        <a class="post__author-link"
                           href="profile.php?user=<?= htmlspecialchars($post['origin_author']) ?>" title="Автор">
                            <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                <img class="post__author-avatar" src="<?= $post['origin_avatar'] ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name">Репост: <?= htmlspecialchars($post['origin_login']) ?></b>
                                <time class="post__time"
                                      datetime="<?= $post['origin_date'] ?>"
                                      title="<?= date('d.m.Y H:i', strtotime($post['origin_date'])) ?>">
                                    <?= elapsed_time($post['origin_date']) ?>
                                </time>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
                <h2>
                    <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                </h2>
            </header>
            <div class="post__main">
                <?php get_post_content("profile/post-", $post['class'], $post) ?>
            </div>
            <footer class="post__footer">
                <div class="post__indicators">
                    <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes <?= ($post['has_like'] == true) ? 'post__indicator--likes-active' : '' ?> button"
                           href="likes.php?post=<?= htmlspecialchars($post['id']) ?>" title="Лайк">
                            <?php
                            if ($post['has_like'] == true) : ?>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                     height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                            <?php else: ?>
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($post['likes']) ?></span>
                            <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--repost button"
                           href="repost.php?post=<?= htmlspecialchars($post['id']) ?>" title="Репост">
                            <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                            </svg>
                            <span><?= htmlspecialchars($post['repost_count']) ?></span>
                            <span class="visually-hidden">количество репостов</span>
                        </a>
                    </div>
                    <time class="post__time"
                          datetime="<?= $post['dt_add'] ?>"
                          title="<?= date('d.m.Y H:i', strtotime($post['dt_add'])) ?>">
                        <?= elapsed_time($post['dt_add']) ?>
                    </time>
                </div>
                <ul class="post__tags">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <li><a href="search.php?search=<?= urlencode($tag['hashtag']) ?>"><?= $tag['hashtag'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </footer>
            <div class="comments">
                <a class="comments__button button"
                   href="post.php?id=<?= htmlspecialchars($post['id']) ?>#comments__list">Показать комментарии</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>
