<h2 class="visually-hidden">Форма добавления фото</h2>
<form class="adding-post__form form" action="add.php?type=3" method="post" enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
        <input type="hidden" name="post_type" value="3">
        <div class="form__text-inputs">
            <div
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['title']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="photo-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="photo-heading" type="text" name="title"
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
                class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['image_link']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="photo-url" type="text" name="image_link"
                           placeholder="Введите ссылку" value="<?= get_post_val('image_link') ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <?php if(isset($errors['image_link'])): ?>
                        <div class="form__error-text">
                            <p class="form__error-desc"><?= htmlspecialchars($errors['image_link']) ?></p>
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
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
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
    <div class="adding-post__input-file-container form__input-container form__input-container--file">
        <div class="adding-post__input-file-wrapper form__input-file-wrapper <?= isset($errors['tags']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input-file form__input-file2" id="userpic-file-photo" type="file" name="image"
                   title=" " value="<?= get_post_val('tags') ?>">
        </div>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
</form>
