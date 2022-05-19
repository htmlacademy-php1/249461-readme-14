<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$follower = $current_user['id'];
$host = $_GET['host'];

$sql_host = "SELECT * FROM users WHERE id = ?";

if (!get_db_data($db_connect, $sql_host, [$host])) {
    echo 'Пользователь на которого вы хотите подписаться не существует';
}

$current_url = $_SERVER['HTTP_REFERER'];

if (check_db_entry($db_connect, 'subscribes', 'follower', $follower, 'host', $host)) {
    $sql = "DELETE FROM subscribes WHERE (follower = ? AND host = ?)";
    $stmt = db_get_prepare_stmt($db_connect, $sql, [$follower, $host]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Location: {$current_url}");
        die();
    }
}

$sql = "INSERT INTO subscribes (follower, host) VALUES (?,?)";
$stmt = db_get_prepare_stmt($db_connect, $sql, [$follower, $host]);
$res = mysqli_stmt_execute($stmt);
if ($res) {
    header("Location: {$current_url}");
}

