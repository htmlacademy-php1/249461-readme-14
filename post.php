<?php
    require_once 'helpers.php';
    require_once 'functions.php';
    require_once 'db_connect.php';

    $title = 'readme: Пост';

    $is_auth = rand(0, 1);
    $user_name = 'Сергей Кравцов';

    $posts_count = countDbTableRows($db_connect, 'posts');
    $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (!$post_id || $post_id > $posts_count[0]) {
        print "Запись не найдена. Указан неверный id";
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
    }

    $sql = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE p.id = ?";

    //$post = getDbData($db_connect, $sql, $post_id)[0];
    $ids[] = $post_id;
    function test_function($db_connect, $sql, $array = []) {
        $stmt = db_get_prepare_stmt($db_connect, $sql, $array);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            print ("Ошибка базы данных" . mysqli_error());
            die();
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
    };
    /*$stmt = db_get_prepare_stmt($db_connect, $sql, $ids);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);*/
    $post = test_function($db_connect, $sql, $ids);
    var_dump($post);

    $post_content = include_template("post-${post['class']}.php", ['post' => $post]);

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
