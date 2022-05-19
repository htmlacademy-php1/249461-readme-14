<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);

if (!$post_id) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print "Запись не найдена. Указан неверный id";
    die();
}

$sql_origin_post = "SELECT * FROM posts WHERE id = ?";

if (!get_db_data($db_connect, $sql_origin_post, [$post_id])) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print "Запись не найдена. Указан неверный или несуществующий id";
    die();
}


$origin_post = get_db_data($db_connect, $sql_origin_post, [$post_id])[0];

$sql_tags = "SELECT hashtag FROM hash_posts WHERE post = ?";
if (get_db_data($db_connect, $sql_tags, [$post_id])) {
    $post_tags = get_db_data($db_connect, $sql_tags, [$post_id]);
}

$post = $origin_post;

$post['origin_post'] = $origin_post['id'];
$post['origin_author'] = $origin_post['post_author'];
$post['post_author'] = $current_user['id'];
$post['views'] = 0;
$post['repost'] = 1;

foreach ($post as $key => $value) {
    if ($value == '') {
        $post[$key] = null;
    }
}

unset($post['id']);
unset($post['dt_add']);

$sql_repost = "INSERT INTO posts (dt_add, title, text, quote_author, image, video, link, views, post_author, post_type, origin_post, origin_author, repost)
                VALUES (NOW(),?,?,?,?,?,?,?,?,?,?,?,?)";

mysqli_begin_transaction($db_connect);

$stmt_post = db_get_prepare_stmt($db_connect, $sql_repost, $post);
$result_post = mysqli_stmt_execute($stmt_post);

if ($result_post) {
    $post_id = mysqli_insert_id($db_connect);
}

$result_tags = true;

if ($post_tags) {
    $sql_insert_tags = generate_sql_tags_repost_post($post_tags, $post_id);

    $stmt_tags = db_get_prepare_stmt($db_connect, $sql_insert_tags);
    $result_tags = mysqli_stmt_execute($stmt_tags);
}

if (!$result_post || !$result_tags) {
    mysqli_rollback($db_connect);
}
mysqli_commit($db_connect);
header("Location: profile.php?user=" . $current_user['id']);




