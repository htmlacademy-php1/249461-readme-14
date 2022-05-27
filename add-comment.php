<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$post_id = $_GET['post'] ?? '';

$current_url = $_SERVER['HTTP_REFERER'];

if (!$post_id) {
    header("Location: {$current_url}");
    die();
}

$sql_check_post = "SELECT * FROM posts WHERE id = ?";
if (!get_db_data($db_connect, $sql_check_post, [$post_id])) {
    header("Location: {$current_url}");
    die();
}

$validation_rules = [
    'comment' => ['required', 'length:4,2000'],
];

$comment = $_POST;
$comment['comment'] = trim($comment['comment'], ' ');
$errors = validate($comment ?? [], $validation_rules, $db_connect);

if (count($errors) !== 0) {
    $_SESSION['comment_errors'] = $errors;
    header("Location: {$current_url}");
    die();
}

$comment['author'] = $current_user['id'];
$comment['post'] = $post_id;

$sql = "INSERT INTO comments (dt_add, comment, author, post) VALUES (NOW(), ?, ?, ?)";
$stmt = db_get_prepare_stmt($db_connect, $sql, $comment);
$res = mysqli_stmt_execute($stmt);
if ($res) {
    header("Location: {$current_url}");
}
