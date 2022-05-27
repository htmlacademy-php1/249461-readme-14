<h2 class="visually-hidden">Форма добавления видео</h2>
<form class="adding-post__form form" action="add.php?type=4" method="post" enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
        <input type="hidden" name="post_type" value="4">
        <div class="form__text-inputs">
            <div
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['title']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="video-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="video-heading" type="text" name="title"
                           placeholder="Введите заголовок" value="<?= get_post_val('title') ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <?php if(isset($errors['title'])): ?>
                        <div class="form__error-text">
                            <p class="form__error-desc"><?= htmlspecialchars($errors['title']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['video']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="video-url" type="text" name="video"
                           placeholder="Введите ссылку" value="<?= get_post_val('video') ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <?php if(isset($errors['video'])): ?>
                        <div class="form__error-text">
                            <p class="form__error-desc"><?= htmlspecialchars($errors['video']) ?></p>
                        </div>
                    <?php endif; ?>
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
                    <?php if(isset($errors['tags'])): ?>
                        <div class="form__error-text">
                            <p class="form__error-desc"><?= htmlspecialchars($errors['tags']) ?></p>
                        </div>
                    <?php endif; ?>
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
