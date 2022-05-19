<h2 class="visually-hidden">Форма добавления текста</h2>
<form class="adding-post__form form" action="add.php?type=1" method="post">
    <div class="form__text-inputs-wrapper">
        <input type="hidden" name="post_type" value="1">
        <div class="form__text-inputs">
            <div
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['title']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="text-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="text-heading" type="text" name="title"
                           value="<?= get_post_val('title') ?>" placeholder="Введите заголовок">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['title']) ?></p>
                    </div>
                </div>
            </div>
            <div
                class="adding-post__textarea-wrapper form__textarea-wrapper <?= isset($errors['text']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="post-text">Текст поста <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <textarea class="adding-post__textarea form__textarea form__input" name="text" id="post-text"
                              placeholder="Введите текст публикации"><?= get_post_val('text') ?></textarea>
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['text']) ?></p>
                    </div>
                </div>
            </div>
            <div
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['tags']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="post-tags">Теги</label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="post-tags" type="text" name="tags"
                           placeholder="Введите теги" value="<?= get_post_val('tags') ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['tags']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($errors)) : ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php foreach ($errors as $error) : ?>
                        <li class="form__invalid-item"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
