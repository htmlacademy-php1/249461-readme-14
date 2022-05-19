<main class="page__main page__main--login">
    <div class="container">
        <h1 class="page__title page__title--login"><?= $title ?></h1>
    </div>
    <section class="login container">
        <h2 class="visually-hidden">Форма авторизации</h2>
        <form class="login__form form" action="sign-in.php" method="post">
            <div
                class="login__input-wrapper form__input-wrapper <?= isset($errors['login']) ? 'form__input-section--error' : '' ?>">
                <label class="login__label form__label" for="login-email">Электронная почта</label>
                <div class="form__input-section">
                    <input class="login__input form__input" id="login-email" type="text" name="login"
                           placeholder="Укажите эл.почту" value="<?= get_post_val('login') ?>">
                    <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['login']) ?></p>
                    </div>
                </div>
            </div>
            <div
                class="login__input-wrapper form__input-wrapper <?= isset($errors['password']) ? 'form__input-section--error' : '' ?>">
                <label class="login__label form__label" for="login-password">Пароль</label>
                <div class="form__input-section">
                    <input class="login__input form__input" id="login-password" type="password" name="password"
                           placeholder="Введите пароль">
                    <button class="form__error-button button button--main" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                    </button>
                    <div class="form__error-text">
                        <p class="form__error-desc"><?= htmlspecialchars($errors['password']) ?></p>
                    </div>
                </div>
            </div>
            <button class="login__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
