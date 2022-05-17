<main class="page__main page__main--search-results">
    <h1 class="visually-hidden"><?= $title ?></h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $query ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $post) : ?>
                        <article class="search__post post post-<?= htmlspecialchars($post['class']) ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="#" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <?php if ($post['avatar']) : ?>
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
                                <h2>
                                    <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                                </h2>

                                <?php get_post_content("feed/", $post['class'], $post) ?>

                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                             height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= htmlspecialchars($post['likes']) ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#"
                                       title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span>25</span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
