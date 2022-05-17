<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$user_id = $current_user['id'];
$post_id = $_GET['post'] ?? '';
$current_url = $_SERVER['HTTP_REFERER'];

if (empty($post_id)) {
    header("Location: {$current_url}");
}

$sql_post = "SELECT * FROM posts WHERE id = ?";
if (!get_db_data($db_connect, $sql_post, [$post_id])) {
    echo 'Лайк добавить нельзя. Указанный пост не существует!';
}

if (check_db_entry($db_connect, 'likes', 'author', $user_id, 'post', $post_id)) {
    $sql = "DELETE FROM likes WHERE (author = ? AND post = ?)";
    $stmt = db_get_prepare_stmt($db_connect, $sql, [$user_id, $post_id]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Location: {$current_url}");
        die();
    }
}

$sql_add_like = "INSERT INTO likes(author, post) VALUES (?,?)";
$stmt = db_get_prepare_stmt($db_connect, $sql_add_like, [$user_id, $post_id]);
$res = mysqli_stmt_execute($stmt);

if ($res) {
    header("Location: {$current_url}");
}






