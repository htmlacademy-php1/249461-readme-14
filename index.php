<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';

session_start();

$title = 'блог, каким он должен быть';

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $layout_content = include_template('guest.php', [
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
    $layout_content = include_template('guest.php', [
        'title' => $title,
        'errors' => $errors
    ]);

    print($layout_content);
    die();
}

$user['login'] = $_POST['login'];
$sql = "SELECT * FROM users WHERE email = ?";
$current_user = get_db_data($db_connect, $sql, $user)[0];

if (!password_verify($_POST['password'], $current_user['user_pass'])) {
    $errors['password'] = 'Указан неверный пароль';
    $layout_content = include_template('guest.php', [
        'title' => $title,
        'errors' => $errors
    ]);

    print($layout_content);
    die();
}

$_SESSION['user'] = $current_user;
header("Location: /feed.php");
