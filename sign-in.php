<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';

session_start();

$title = 'Вход';

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $content = include_template('sign-in.php', [
        'title' => $title
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title
    ]);

    print($layout_content);
    die();
}

$validation_rules = [
    'login' => ['required', 'email', 'login'],
    'password' => ['required'],
];

$errors = validate($_POST ?? [], $validation_rules, $db_connect);

if (count($errors)) {
    $content = include_template('sign-in.php', [
        'title' => $title,
        'errors' => $errors
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title
    ]);

    print($layout_content);
    die();
}

$user['login'] = $_POST['login'];
$sql = "SELECT * FROM users WHERE email = ?";
$current_user = get_db_data($db_connect, $sql, $user)[0];

if (!password_verify($_POST['password'], $current_user['user_pass'])) {
    $errors['password'] = 'Указан неверный пароль';
    $content = include_template('sign-in.php', [
        'title' => $title,
        'errors' => $errors
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title
    ]);

    print($layout_content);
    die();
}

$_SESSION['user'] = $current_user;
header("Location: /feed.php");
