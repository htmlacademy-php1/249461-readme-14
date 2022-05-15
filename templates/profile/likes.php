<section class="profile__likes tabs__content tabs__content--active">
    <h2 class="visually-hidden">Лайки</h2>
    <ul class="profile__likes-list">
        <?php foreach ($likes as $like) : ?>
        <li class="post-mini post-mini--<?=htmlspecialchars($like['class'])?> post user">
            <div class="post-mini__user-info user__info">
                <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="profile.php/user=<?=htmlspecialchars($like['author'])?>">
                        <?php if ($like['avatar'] != ''):?>
                            <img class="post-mini__picture user__picture" src="<?=$like['avatar']?>" alt="Аватар пользователя">
                        <?php endif;?>
                    </a>
                </div>
                <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php/user=<?=htmlspecialchars($like['author'])?>">
                        <span><?=htmlspecialchars($like['login'])?></span>
                    </a>
                    <div class="post-mini__action">
                        <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                        <time class="post-mini__time user__additional"
                              datetime="<?= $like['dt_add'] ?>"
                              title="<?= date('d.m.Y H:i', strtotime($like['dt_add'])) ?>">
                            <?= elapsed_time($like['dt_add']) ?>
                        </time>
                    </div>
                </div>
            </div>
            <div class="post-mini__preview">
                <a class="post-mini__link" href="post.php?id=<?=htmlspecialchars($like['post'])?>" title="Перейти на публикацию">
                    <?php if ($like['class'] == 'text'):?>
                        <span class="visually-hidden">Текст</span>
                        <svg class="post-mini__preview-icon" width="20" height="21">
                            <use xlink:href="#icon-filter-text"></use>
                        </svg>
                    <?php elseif ($like['class'] == 'link'):?>
                        <span class="visually-hidden">Ссылка</span>
                        <svg class="post-mini__preview-icon" width="21" height="18">
                            <use xlink:href="#icon-filter-link"></use>
                        </svg>
                    <?php elseif ($like['class'] == 'quote'):?>
                        <span class="visually-hidden">Цитата</span>
                        <svg class="post-mini__preview-icon" width="21" height="20">
                            <use xlink:href="#icon-filter-quote"></use>
                        </svg>
                    <?php elseif ($like['class'] == 'photo'):?>
                        <div class="post-mini__image-wrapper">
                            <img class="post-mini__image" src="<?=$like['image']?>" width="109" height="109" alt="Превью публикации">
                        </div>
                        <span class="visually-hidden">Фото</span>
                    <?php elseif ($like['class'] == 'video'):?>
                        <div class="post-mini__image-wrapper">
                            <?= embed_youtube_cover(htmlspecialchars($like['video'])) ?>
                            <span class="post-mini__play-big">
                            <svg class="post-mini__play-big-icon" width="12" height="13">
                              <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                          </span>
                        </div>
                        <span class="visually-hidden">Видео</span>
                    <?php endif; ?>
                </a>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</section>
