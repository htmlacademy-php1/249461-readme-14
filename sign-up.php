<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';

$is_auth = 0;
$title = 'Регистрация';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $new_user = $_POST;

    $validation_rules = [
            'email' => ['required', 'email', 'unique:email,users,email'],
            'login' => ['required', 'unique:login,users,login'],
            'user_pass' => ['required', 'length:8,16', 'password:password_repeat'],
            'avatar' => ['img_type', 'img_size'],
    ];

    if (!empty($_FILES['avatar']['tmp_name'])) {
        $new_user = array_merge($new_user, $_FILES);
    }

    $errors = validate($new_user ?? [], $validation_rules, $db_connect);

    if (isset($errors['user_pass'])) {
        $errors['password_repeat'] = $errors['user_pass'];
    }

    if (count($errors)) {
        $content = include_template('sign-up.php', [
            'title' => $title,
            'errors' => $errors
        ]);
    } else {
        unset($new_user['password_repeat']);
        $new_user['user_pass'] = generate_password_hash($new_user['user_pass']);
        $new_user['avatar'] = upload_img($new_user['avatar']);

        $sql = "INSERT INTO users (reg_date, email, login, user_pass, avatar) VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($db_connect, $sql, $new_user);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: index.php");
        }
    }
} else {
    $content = include_template('sign-up.php', [
            'title' => $title
    ]);
}

$layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'is_auth' => $is_auth
]);

print($layout_content);
