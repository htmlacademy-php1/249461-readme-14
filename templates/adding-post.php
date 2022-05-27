<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post"><?= $title ?></h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach ($types as $type) : ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a href="add.php?type=<?= htmlspecialchars($type['id']) ?>"
                                   class="adding-post__tabs-link filters__button filters__button--<?= htmlspecialchars($type['class']) ?> <?= $current_type_id === htmlspecialchars($type['id']) ? 'filters__button--active' : ''; ?> tabs__item tabs__item--active button">
                                    <svg class="filters__icon" width="22" height="18">
                                        <use xlink:href="#icon-filter-<?= htmlspecialchars($type['class']) ?>"></use>
                                    </svg>
                                    <span><?= htmlspecialchars($type['title']) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <section class="adding-post__text tabs__content tabs__content--active">
                        <?= $add_form ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
