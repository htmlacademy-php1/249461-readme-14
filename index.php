<?php
    require_once 'helpers.php';
    require_once 'functions.php';
    require_once 'data.php';

    $title = 'readme: популярное';

    $is_auth = rand(0, 1);
    $user_name = 'Сергей Кравцов';

    $content = include_template('main.php', [
        'posts' => $posts
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name
    ]);

    print($layout_content);

