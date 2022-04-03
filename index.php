<?php
    require_once 'helpers.php';
    require_once 'functions.php';
    //require_once 'data.php';

    date_default_timezone_set("Europe/Kiev");

    $title = 'readme: популярное';

    $is_auth = rand(0, 1);
    $user_name = 'Сергей Кравцов';

    /* Database */
    $db = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'readme'
    ];

    /* Database connect and charset */
    $db_connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($db_connect, 'utf8');

    if ($db_connect) {
        $sql = 'SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  ORDER BY views DESC';
        $result = mysqli_query($db_connect, $sql);

        if ($result) {
            $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($db_connect);
            print ("Ошибка базы данных" . $error);
        }

        $sql = 'SELECT * FROM types';
        $result = mysqli_query($db_connect, $sql);

        if ($result) {
            $post_types = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($db_connect);
            print ("Ошибка базы данных" . $error);
        }
    }

    $content = include_template('main.php', [
        'posts' => $posts,
        'types' => $post_types
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name
    ]);

    print($layout_content);

