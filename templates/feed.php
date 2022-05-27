<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed"><?= $title ?></h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="feed__post post post-<?= htmlspecialchars($post['class']) ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link"
                                       href="profile.php?user=<?= htmlspecialchars($post['post_author']) ?>"
                                       title="Автор">
                                        <div class="post__avatar-wrapper">
                                            <?php if (isset($post['avatar'])) : ?>
                                                <img class="post__author-avatar" src="<?= $post['avatar'] ?>"
                                                     alt="Аватар пользователя" width="60" height="60">
                                            <?php endif; ?>
                                        </div>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                            <time class="post__time"
                                                  datetime="<?= htmlspecialchars($post['dt_add']) ?>"
                                                  title="<?= date('d.m.Y H:i',
                                                      strtotime(htmlspecialchars($post['dt_add']))) ?>">
                                                <?= elapsed_time(htmlspecialchars($post['dt_add'])) ?>
                                            </time>
                                        </div>
                                    </a>
                                </header>
                                <div class="post__main">
                                    <?php if ($post['class'] === 'text' || $post['class'] === 'photo') : ?>
                                        <h2>
                                            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                                        </h2>
                                    <?php endif; ?>

                                    <?php get_post_content("feed/", $post['class'], $post) ?>

                                </div>
                                <footer class="post__footer post__indicators">
                                    <div class="post__buttons">
                                        <a class="post__indicator post__indicator--likes <?= ($post['has_like'] === true) ? 'post__indicator--likes-active' : '' ?> button"
                                           href="likes.php?post=<?= htmlspecialchars($post['id']) ?>" title="Лайк">
                                            <?php
                                            if ($post['has_like'] === true) : ?>
                                                <svg class="post__indicator-icon post__indicator-icon--like-active"
                                                     width="20" height="17">
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
                                        <a class="post__indicator post__indicator--comments button"
                                           href="post.php?id=<?= htmlspecialchars($post['id']) ?>"
                                           title="Комментарии">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-comment"></use>
                                            </svg>
                                            <span><?= htmlspecialchars($post['comments']) ?></span>
                                            <span class="visually-hidden">количество комментариев</span>
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
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?= !isset($post_category) ? 'filters__button--active' : ''; ?>"
                       href="feed.php">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($types as $type): ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--<?= htmlspecialchars($type['class']) ?> <?= htmlspecialchars($type['id']) === $post_category ? 'filters__button--active' : '' ?>
                    button" href="feed.php?type=<?= htmlspecialchars($type['id']) ?>">
                            <span class="visually-hidden"><?= htmlspecialchars($type['title']) ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= htmlspecialchars($type['class']) ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
