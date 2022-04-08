<?php
    require_once 'helpers.php';
    require_once 'functions.php';
    require_once 'db_connect.php';

    date_default_timezone_set("Europe/Kiev");

    $title = 'readme: популярное';

    $is_auth = rand(0, 1);
    $user_name = 'Сергей Кравцов';

    if (!$db_connect) {
        print ("Ошибка подключения базы данных" . mysqli_connect_error());
        die();
    }

    /* запрос постов */
    $sql_posts = 'SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  ORDER BY views DESC';

    /* запрос категорий постов */
    $sql_types = 'SELECT * FROM types';

    $post_types = getDbData($db_connect, $sql_types);

    $script_path = pathinfo(__FILE__, PATHINFO_BASENAME);
    $post_category = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);

    if (isset($post_category)) {
        $sql_posts = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
          FROM posts p
          JOIN users u ON u.id = p.post_author
          JOIN types t ON t.id = p.post_type
          WHERE post_type = ?
          ORDER BY views DESC";
    }

    $posts = getDbData($db_connect, $sql_posts, $post_category);

    $content = include_template('main.php', [
        'posts' => $posts,
        'types' => $post_types,
        'script_path' => $script_path,
        'post_category' => $post_category
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name
    ]);

    print($layout_content);

