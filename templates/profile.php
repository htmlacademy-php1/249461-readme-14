<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <?php if ($user['avatar']) : ?>
                            <img class="profile__picture user__picture" src="<?= $user['avatar'] ?>"
                                 alt="Аватар пользователя">
                        <?php endif; ?>
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= htmlspecialchars($user['login']) ?></span>
                        <time class="post__time"
                              datetime="<?= $user['reg_date'] ?>"
                              title="<?= date('d.m.Y H:i', strtotime($user['reg_date'])) ?>">
                            <?= elapsed_time($user['reg_date']) ?>
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= htmlspecialchars($user['counter_posts']) ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(htmlspecialchars($user['counter_posts']), 'публикация',
                                'публикации', 'публикаций') ?>
                        </span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= htmlspecialchars($user['followers']) ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(htmlspecialchars($user['counter_posts']), 'подписчик',
                                'подписчика', 'подписчиков') ?>
                        </span>
                    </p>
                </div>
                <div class="profile__user-buttons user__buttons">
                    <?php if ($user['id'] !== $current_user['id']) : ?>
                        <a href="subscribe.php?host=<?= $user['id'] ?>"
                           class="profile__user-button user__button user__button--subscription button button--<?= $subscribe_button['class'] ?>"><?= $subscribe_button['text'] ?></a>
                        <a class="profile__user-button user__button user__button--writing button button--green"
                           href="#">Сообщение</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <?php foreach ($filter_tabs as $key => $tab): ?>
                            <li class="profile__tabs-item filters__item">
                                <a class="profile__tabs-link filters__button tabs__item button <?= ($active_tab == $key) ? 'filters__button--active' : '' ?>"
                                   href="<?= $_SERVER['REQUEST_URI'] . '&tab=' . $key ?>"><?= $tab ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="profile__tab-content">

                    <?= $tab_content ?>

                </div>
            </div>
        </div>
    </div>
</main>
