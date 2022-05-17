<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Добавить публикацию';

$sql_types = 'SELECT * FROM types';
$post_types = get_db_data($db_connect, $sql_types);

/* Массив id типов постов */
$id_types = [];
foreach ($post_types as $type) {
    $id_types[] = $type['id'];
}

$current_type_id = '1';
$current_type_class = 'text';

if (isset($_GET['type'])) {
    $current_type_id = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);
    $current_type_class = get_post_type_class($current_type_id, $post_types);
}

if (isset($_GET['type']) && !in_array($_GET['type'], $id_types)) {
    print 'Указан не существующий тип поста';
    $current_type_id = $_GET['type'];
    $current_type_class = '';
}

$add_form = include_template("add-forms/add-form-${current_type_class}.php", ['type_id' => $current_type_id]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_post = $_POST;
    $current_type_id = $new_post['post_type'];
    $current_type_class = get_post_type_class($current_type_id, $post_types);

    $errors = [];
    switch ($current_type_class) {
        case 'text':
            $validation_rules = [
                'title' => ['required', 'length:3,25'],
                'text' => ['required', 'length:10,3000'],
                'tags' => ['tags'],
            ];
            $errors = validate($new_post ?? [], $validation_rules, $db_connect);
            $sql = "INSERT INTO posts (dt_add, post_type, title, text, post_author) VALUES (NOW(), ?, ?, ?, ?)";
            break;
        case 'quote':
            $validation_rules = [
                'title' => ['required', 'length:3,25'],
                'text' => ['required', 'length:10,70'],
                'quote_author' => ['required', 'length:3,30'],
                'tags' => ['tags'],
            ];
            $errors = validate($new_post ?? [], $validation_rules, $db_connect);
            $sql = "INSERT INTO posts (dt_add, post_type, title, text, quote_author, post_author) VALUES (NOW(), ?, ?, ?, ?, ?)";
            break;
        case 'photo':
            $validation_rules = [
                'title' => ['required', 'length:3,25'],
                'image' => ['img_type', 'img_size'],
                'image_link' => ['required', 'url_content'],
                'tags' => ['tags'],
            ];
            if (!empty($_FILES['image']['tmp_name'])) {
                unset($validation_rules['image_link']);
                unset($new_post['image_link']);
                $new_post = array_merge($new_post, $_FILES);
            } else {
                unset($validation_rules['image']);
            }

            $errors = validate($new_post ?? [], $validation_rules, $db_connect);

            if (empty($errors)) {
                if (!empty($new_post['image'])) {
                    $new_post['image'] = upload_img($new_post['image']);
                } else {
                    $new_post['image'] = download_img_from_link($new_post['image_link']);
                    unset($new_post['image_link']);
                }
            }
            $sql = "INSERT INTO posts (dt_add, post_type, title, image, post_author) VALUES (NOW(), ?, ?, ?, ?)";
            break;
        case 'video':
            $validation_rules = [
                'title' => ['required', 'length:3,25'],
                'video' => ['required', 'link', 'video'],
                'tags' => ['tags'],
            ];
            $errors = validate($new_post ?? [], $validation_rules, $db_connect);
            $sql = "INSERT INTO posts (dt_add, post_type, title, video, post_author) VALUES (NOW(), ?, ?, ?, ?)";
            break;
        case 'link':
            $validation_rules = [
                'title' => ['required', 'length:3,25'],
                'link' => ['required', 'link'],
                'tags' => ['tags'],
            ];
            $errors = validate($new_post ?? [], $validation_rules, $db_connect);
            $sql = "INSERT INTO posts (dt_add, post_type, title, link, post_author) VALUES (NOW(), ?, ?, ?, ?)";
            break;
    }

    if (count($errors)) {
        $add_form = include_template("add-forms/add-form-${current_type_class}.php",
            ['errors' => $errors]);
    } else {
        $new_post['post_author'] = $current_user['id'];
        $post_tags = $new_post['tags'];

        unset($new_post['tags']);

        $stmt = db_get_prepare_stmt($db_connect, $sql, $new_post);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $post_id = mysqli_insert_id($db_connect);
            add_post_tags($post_tags, $db_connect, $post_id);
            header("Location: post.php?id=" . $post_id);
        }
    }
}


$content = include_template('adding-post.php', [
    'title' => $title,
    'types' => $post_types,
    'current_type_id' => $current_type_id,
    'add_form' => $add_form
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user
]);

print($layout_content);
