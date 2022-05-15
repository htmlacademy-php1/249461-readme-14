<div class="post-link__wrapper">
    <a class="post-link__external" href="<?=htmlspecialchars($post['link'])?>" title="Перейти по ссылке">
        <div class="post-link__icon-wrapper">
            <img src="https://www.google.com/s2/favicons?domain=<?=htmlspecialchars($post['link'])?>" alt="Иконка">
        </div>
        <div class="post-link__info">
            <h3><?=htmlspecialchars($post['title'])?></h3>
            <span><?=htmlspecialchars($post['link'])?></span>
        </div>
        <svg class="post-link__arrow" width="11" height="16">
            <use xlink:href="#icon-arrow-right-ad"></use>
        </svg>
    </a>
</div>
