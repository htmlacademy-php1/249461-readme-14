<?php
    require_once 'helpers.php';
    require_once 'functions.php';
    require_once 'db_connect.php';

    $title = 'readme: Пост';

    $is_auth = rand(0, 1);
    $user_name = 'Сергей Кравцов';

    $posts_count = countDbTableRows($db_connect, 'posts');

    $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (!$post_id || $post_id > $posts_count) {
        print "Запись не найдена. Указан неверный id";
        die();
    }
    $sql = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE p.id = ?";

    $post = getDbSingleRow($db_connect, $sql, $post_id);

    if ($post['class'] === 'quote') {
        $post_content = include_template('post-quote.php', ['post' => $post]);
    } elseif ($post['class'] === 'photo') {
        $post_content = include_template('post-image.php', ['post' => $post]);
    } elseif ($post['class'] === 'link') {
        $post_content = include_template('post-link.php', ['post' => $post]);
    } elseif ($post['class'] === 'video') {
        $post_content = include_template('post-video.php', ['post' => $post]);
    } else {
        $post_content = include_template('post-text.php', ['post' => $post]);
    }

    $content = include_template('post.php', [
        'post' => $post,
        'post_content' => $post_content
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name
    ]);

    print($layout_content);
