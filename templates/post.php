<?php
$errors = [];
if (isset($_SESSION['comment_errors'])) {
    $errors = $_SESSION['comment_errors'];
    unset($_SESSION['comment_errors']);
}
?>
<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= $post['title'] ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-photo">
                <div class="post-details__main-block post post--details">

                    <?= $post_content ?>

                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes <?=($post['has_like'] == true) ? 'post__indicator--likes-active' : ''?> button" href="likes.php?post=<?=htmlspecialchars($post['id'])?>" title="Лайк">
                                <?php
                                if ($post['has_like'] == true) : ?>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                <?php else: ?>
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                <?php endif;?>
                                <span><?=htmlspecialchars($post['likes'])?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#comments__list"
                               title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?=htmlspecialchars($post['comments'])?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="repost.php?post=<?=htmlspecialchars($post['id'])?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?=htmlspecialchars($post['repost_count'])?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view">
                            <?= htmlspecialchars($post['views']) . ' ' . get_noun_plural_form(htmlspecialchars($post['views']), 'просмотр','просмотра','просмотров') ?>
                        </span>
                    </div>
                    <ul class="post__tags">
                        <?php foreach ($tags as $tag) : ?>
                            <li><a href="search.php?search=<?= urlencode($tag['hashtag']) ?>"><?= htmlspecialchars($tag['hashtag']) ?></a></li>
                        <?php endforeach ?>
                    </ul>
                    <div class="comments">
                        <form class="comments__form form" action="add-comment.php?post=<?=htmlspecialchars($post['id'])?>" method="post">
                            <div class="comments__my-avatar">
                                <?php if ($current_user['avatar'] != '') :?>
                                    <img class="comments__picture" src="<?=$current_user['avatar']?>" alt="Аватар пользователя">
                                <?php endif; ?>
                            </div>
                            <div class="form__input-section <?= (count($errors) && $errors['comment']) ? 'form__input-section--error' : ''?>">
                                <textarea class="comments__textarea form__textarea form__input"
                                          placeholder="Ваш комментарий" name="comment"></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <p class="form__error-desc"><?=$errors['comment'] ?></p>
                                </div>
                            </div>
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list" id="comments__list">
                                <?php foreach ($comments as $comment) : ?>
                                    <li class="comments__item user">
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="profile.php?user=<?=htmlspecialchars($comment['id'])?>">
                                                <?php if ($comment['avatar'] != '') : ?>
                                                <img class="comments__picture" src="<?=$comment['avatar']?>"
                                                     alt="Аватар пользователя">
                                                <?php endif;?>
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="profile.php?user=<?=htmlspecialchars($comment['id'])?>">
                                                    <span><?=htmlspecialchars($comment['login'])?></span>
                                                </a>
                                                <time class="comments__time"
                                                      datetime="<?= $comment['dt_add'] ?>"
                                                      title="<?= date('d.m.Y H:i', strtotime($comment['dt_add'])) ?>">
                                                    <?= elapsed_time($comment['dt_add']) ?>
                                                </time>
                                            </div>
                                            <p class="comments__text">
                                                <?=htmlspecialchars($comment['comment'])?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <!--<a class="comments__more-link" href="#">
                                <span>Показать все комментарии</span>
                                <sup class="comments__amount">45</sup>
                            </a>-->
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="profile.php?user=<?=$author['id']?>">
                                <?php if ($author['avatar']) : ?>
                                    <img class="post-details__picture user__picture" src="<?=$author['avatar']?>" alt="Аватар пользователя">
                                <?php endif;?>
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="profile.php?user=<?=$author['id']?>">
                                <span><?= htmlspecialchars($author['login']) ?></span>
                            </a>
                            <time class="post__time"
                                  datetime="<?= $author['reg_date'] ?>"
                                  title="<?= date('d.m.Y H:i', strtotime($author['reg_date'])) ?>">
                                <?= elapsed_time($author['reg_date']) ?>
                            </time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?=htmlspecialchars($author['followers'])?></span>
                            <span class="post-details__rating-text user__rating-text">
                                <?=get_noun_plural_form(htmlspecialchars($author['counter_posts']), 'подписчик','подписчика','подписчиков')?>
                            </span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount"><?=htmlspecialchars($author['counter_posts'])?></span>
                            <span class="post-details__rating-text user__rating-text">
                                <?=get_noun_plural_form(htmlspecialchars($author['counter_posts']), 'публикация','публикации','публикаций')?>
                            </span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <a href="subscribe.php?host=<?=$author['id']?>" class="profile__user-button user__button user__button--subscription button button--<?=$subscribe_button['class']?>"><?=$subscribe_button['text']?></a>
                        <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
