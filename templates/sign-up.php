<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration"><?= $title ?></h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="sign-up.php" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <div
                        class=" registration__input-wrapper form__input-wrapper <?= isset($errors['email']) ? 'form__input-section--error' : '' ?>">
                        <label class="registration__label form__label" for="registration-email">Электронная почта <span
                                class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-email" type="email"
                                   name="email" placeholder="Укажите эл.почту" value="<?= get_post_val('email') ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <?php if (isset($errors['email'])):?>
                                <div class="form__error-text">
                                    <p class="form__error-desc"><?= htmlspecialchars($errors['email']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div
                        class=" registration__input-wrapper form__input-wrapper <?= isset($errors['login']) ? 'form__input-section--error' : '' ?>">
                        <label class="registration__label form__label" for="registration-login">Логин <span
                                class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-login" type="text"
                                   name="login" placeholder="Укажите логин" value="<?= get_post_val('login') ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <?php if (isset($errors['login'])):?>
                                <div class="form__error-text">
                                    <p class="form__error-desc"><?= htmlspecialchars($errors['login']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div
                        class=" registration__input-wrapper form__input-wrapper <?= isset($errors['user_pass']) ? 'form__input-section--error' : '' ?>">
                        <label class="registration__label form__label" for="registration-password">Пароль<span
                                class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-password" type="password"
                                   name="user_pass" placeholder="Придумайте пароль"
                                   value="<?= get_post_val('user_pass') ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <?php if (isset($errors['user_pass'])):?>
                                <div class="form__error-text">
                                    <p class="form__error-desc"><?= htmlspecialchars($errors['user_pass']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div
                        class=" registration__input-wrapper form__input-wrapper <?= isset($errors['password_repeat']) ? 'form__input-section--error' : '' ?>">
                        <label class="registration__label form__label" for="registration-password-repeat">Повтор
                            пароля<span class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-password-repeat"
                                   type="password" name="password_repeat" placeholder="Повторите пароль"
                                   value="<?= get_post_val('password_repeat') ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                            </button>
                            <?php if (isset($errors['password_repeat'])):?>
                                <div class="form__error-text">
                                    <p class="form__error-desc"><?= htmlspecialchars($errors['password_repeat']) ?></p>
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
            <div class="registration__input-file-container form__input-container form__input-container--file">
                <div class="registration__input-file-wrapper form__input-file-wrapper">
                    <input class="registration__input-file form__input-file2" id="userpic-file" type="file"
                           name="avatar" title=" ">
                </div>
            </div>
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
