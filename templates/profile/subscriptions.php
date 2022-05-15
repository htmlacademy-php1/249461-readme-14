<section class="profile__subscriptions tabs__content tabs__content--active">
    <h2 class="visually-hidden">Подриски</h2>
    <ul class="profile__subscriptions-list">
        <?php foreach ($subscribes_list as $subscribe) : ?>
            <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="profile.php?user=<?=$subscribe['id']?>">
                            <img class="post-mini__picture user__picture" src="<?=$subscribe['avatar']?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="profile.php?user=<?=$subscribe['id']?>">
                            <span><?=htmlspecialchars($subscribe['login'])?></span>
                        </a>
                        <time class="post-mini__time user__additional"
                              datetime="<?= $subscribe['reg_date'] ?>"
                              title="<?= date('d.m.Y H:i', strtotime($subscribe['reg_date'])) ?>">
                            <?= elapsed_time($subscribe['reg_date']) ?>
                        </time>
                    </div>
                </div>
                <div class="post-mini__rating user__rating">
                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                        <span class="post-mini__rating-amount user__rating-amount"><?=htmlspecialchars($subscribe['counter_posts'])?></span>
                        <span class="post-mini__rating-text user__rating-text">
                            <?=get_noun_plural_form(htmlspecialchars($subscribe['counter_posts']), 'публикация','публикации','публикаций')?>
                        </span>
                    </p>
                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="post-mini__rating-amount user__rating-amount"><?=htmlspecialchars($subscribe['followers'])?></span>
                        <span class="post-mini__rating-text user__rating-text">
                            <?=get_noun_plural_form(htmlspecialchars($subscribe['counter_posts']), 'подписчик','подписчика','подписчиков')?>
                        </span>
                    </p>
                </div>
                <div class="post-mini__user-buttons user__buttons">
                    <a href="subscribe.php?host=<?=$subscribe['id']?>" class="profile__user-button user__button user__button--subscription button button--<?=$subscribe_button['class']?>"><?=$subscribe_button['text']?></a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
