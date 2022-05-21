<?php
/*$errors = [];
if (isset($_SESSION['message'])) {
    $errors = $_SESSION['message'];
    unset($_SESSION['message']);
}
*/?>
<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <ul class="messages__contacts-list tabs__list">
                <?php if (!isset($no_message) && isset($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                    <li class="messages__contacts-item">
                        <a class="messages__contacts-tab tabs__item <?=($active_chat == $user['id'] ? 'messages__contacts-tab--active tabs__item--active' : '')?>" href="messages.php?chat=<?=htmlspecialchars($user['id'])?>">
                            <div class="messages__avatar-wrapper">
                                <img class="messages__avatar" src="<?=$user['avatar']?>" alt="Аватар пользователя">
                                <?php if ($user['not_read'] > 0) : ?>
                                    <i class="messages__indicator"><?=htmlspecialchars($user['not_read'])?></i>
                                <?php endif; ?>
                            </div>
                            <div class="messages__info">
                                  <span class="messages__contact-name">
                                    <?=htmlspecialchars($user['login'])?>
                                  </span>
                                <?php if ($user['message'] != '') :?>
                                <div class="messages__preview">
                                    <p class="messages__preview-text">
                                        <?php if (htmlspecialchars($user['message']['sender']) == $current_user['id']) : ?>
                                            Вы: <?=cut_text(htmlspecialchars($user['message']['message']), 10)?>
                                        <?php else: ?>
                                            <?=cut_text(htmlspecialchars($user['message']['message']), 10)?>
                                        <?php endif; ?>
                                    </p>
                                    <time class="messages__preview-time"
                                          datetime="<?= $user['message']['dt_add'] ?>"
                                          title="<?= date('d.m.Y H:i', strtotime($user['message']['dt_add'])) ?>">
                                        <?= date('d.m', strtotime($user['message']['dt_add'])) ?>
                                    </time>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>
                <?php endforeach;?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="messages__chat">
            <div class="messages__chat-wrapper">
                <ul class="messages__list tabs__content tabs__content--active">
                    <?php if (!isset($no_message) && isset($messages)) : ?>
                        <?php foreach ($messages as $message) : ?>
                            <?php $user = ($message['sender'] == $current_user['id']) ? $current_user : $active_chat_user ?>
                            <li class="messages__item <?=($message['sender'] == $current_user['id']) ? 'messages__item--my' : '' ?>">
                                <div class="messages__info-wrapper">
                                    <div class="messages__item-avatar">
                                        <a class="messages__author-link" href="profile.php?user=<?=htmlspecialchars($user['id'])?>">
                                            <img class="messages__avatar" src="<?=$user['avatar']?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="messages__item-info">
                                        <a class="messages__author" href="profile.php?user=<?=htmlspecialchars($user['id'])?>">
                                            <?=htmlspecialchars($user['login'])?>
                                        </a>
                                        <time class="messages__time"
                                              datetime="<?= $message['dt_add'] ?>"
                                              title="<?= date('d.m.Y H:i', strtotime($message['dt_add'])) ?>">
                                            <?= elapsed_time($message['dt_add']) ?>
                                        </time>
                                    </div>
                                </div>
                                <p class="messages__text">
                                    <?=htmlspecialchars($message['message'])?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="comments">
                <form class="comments__form form" action="messages.php?chat=<?=htmlspecialchars($active_chat)?>" method="post">
                    <div class="comments__my-avatar">
                        <?php if ($current_user['avatar']) : ?>
                            <img class="comments__picture" src="<?=$current_user['avatar']?>" alt="Аватар пользователя">
                        <?php endif; ?>
                    </div>
                    <div class="form__input-section <?= ($errors['message']) ? 'form__input-section--error' : '' ?>">
                        <textarea class="comments__textarea form__textarea form__input"
                          placeholder="Ваше сообщение" name="message"></textarea>
                        <label class="visually-hidden">Ваше сообщение</label>
                        <?php if (!empty($errors)) : ?>
                        <button class="form__error-button button" type="button">!</button>
                        <div class="form__error-text">
                            <p class="form__error-desc"><?= $errors['message'] ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form__input-section">
                        <input type="hidden" class="form__input" name="receiver" value="<?=htmlspecialchars($active_chat_user['id'])?>">
                        <label class="visually-hidden">Получатель сообщения</label>
                    </div>
                    <button class="comments__submit button button--green" type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </section>
</main>
