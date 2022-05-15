<h2 class="visually-hidden">Форма добавления цитаты</h2>
<form class="adding-post__form form" action="add.php?type=2" method="post">
    <div class="form__text-inputs-wrapper">
        <input type="hidden" name="post_type" value="2">
        <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['title']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="quote-heading">Заголовок <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="quote-heading" type="text" name="title"
                           placeholder="Введите заголовок" value="<?=get_post_val('title')?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['title']) ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__textarea-wrapper <?= isset($errors['text']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" name="text"
                        id="cite-text" placeholder="Текст цитаты"><?=get_post_val('text')?></textarea>
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['text']) ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__textarea-wrapper form__input-wrapper <?= isset($errors['quote_author']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="quote-author">Автор <span
                        class="form__input-required">*</span></label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="quote-author" type="text" name="quote_author" placeholder="Автор цитаты"  value="<?=get_post_val('quote_author')?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['quote_author']) ?></p>
                    </div>
                </div>
            </div>
            <div class="adding-post__input-wrapper form__input-wrapper <?= isset($errors['tags']) ? 'form__input-section--error' : '' ?>">
                <label class="adding-post__label form__label" for="post-tags">Теги</label>
                <div class="form__input-section">
                    <input class="adding-post__input form__input" id="post-tags" type="text" name="tags"
                           placeholder="Введите теги" value="<?= get_post_val('tags') ?>">
                    <button class="form__error-button button" type="button">!<span
                            class="visually-hidden">Информация об ошибке</span></button>
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
