<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular"><?= $title ?></h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link
                            <?= isset($filters['sort']) && $filters['sort'] == 'popular' ? 'sorting__link--active' : '' ?>
                            <?= isset($filters['sort_type']) && $filters['sort_type'] == 'DESC' ? '' : 'sorting__link--reverse' ?>"
                           href="<?= '/popular.php?' . http_build_query(array_merge($filters, ['sort' => 'popular'],
                               ['sort_type' => $filters['sort_type']], ['page' => 1])) ?>">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link
                            <?= isset($filters['sort']) && $filters['sort'] == 'likes' ? 'sorting__link--active' : '' ?>
                            <?= isset($filters['sort_type']) && $filters['sort_type'] == 'DESC' ? '' : 'sorting__link--reverse' ?>"
                           href="<?= '/popular.php?' . http_build_query(array_merge($filters, ['sort' => 'likes'],
                               ['sort_type' => $filters['sort_type']], ['page' => 1])) ?>">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link
                            <?= isset($filters['sort']) && $filters['sort'] == 'date' ? 'sorting__link--active' : '' ?>
                            <?= isset($filters['sort_type']) && $filters['sort_type'] == 'DESC' ? '' : 'sorting__link--reverse' ?>"
                           href="<?= '/popular.php?' . http_build_query(array_merge($filters, ['sort' => 'date'],
                               ['sort_type' => $filters['sort_type']], ['page' => 1])) ?>">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all
                            <?= !isset($post_category) ? 'filters__button--active' : ''; ?>" href="popular.php">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($types as $type): ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= htmlspecialchars($type['class']) ?> <?= htmlspecialchars($type['id']) === $post_category ? 'filters__button--active' : '' ?>
                         button" href="popular.php?type=<?= htmlspecialchars($type['id']) ?>">
                                <span class="visually-hidden"><?= htmlspecialchars($type['title']) ?></span>
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= htmlspecialchars($type['class']) ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $post) : ?>
                <article class="popular__post post post-<?= htmlspecialchars($post['class']) ?>">
                    <header class="post__header">
                        <h2>
                            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                        </h2>
                    </header>
                    <div class="post__main">

                        <?php get_post_content("popular-post/", $post['class'], $post) ?>

                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="profile.php?user=<?= $post['post_author'] ?>"
                               title="Автор">
                                <div class="post__avatar-wrapper">
                                    <?php if ($post['avatar']) : ?>
                                        <img class="post__author-avatar" src="<?= $post['avatar'] ?>"
                                             alt="Аватар пользователя">
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
                        </div>
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
                                    <span><?= htmlspecialchars($post['count(l.author)']) ?></span>
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
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if ($show_pagination == true) : ?>
            <div class="popular__page-links">
                <a class="popular__page-link popular__page-link--prev button button--gray"
                   href="<?= '/popular.php?' . http_build_query(array_merge($filters,
                       ['page' => $filters['page'] <= 1 ? $filters['page'] : $filters['page'] - 1])) ?>">Предыдущая
                    страница</a>
                <a class="popular__page-link popular__page-link--next button button--gray"
                   href="<?= '/popular.php?' . http_build_query(array_merge($filters,
                       ['page' => $filters['page'] >= $pages ? $filters['page'] : $filters['page'] + 1])) ?>">Следующая
                    страница</a>
            </div>
        <?php endif; ?>
    </div>
</section>
