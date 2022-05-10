<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Пост';
$post_ids = [];

$posts_count = count_lines_db_table($db_connect, 'id', 'posts');
$current_post = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$current_post) {
    print "Запись не найдена. Указан неверный id";
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

$sql = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, t.class
                  FROM posts p
                  JOIN types t ON t.id = p.post_type
                  WHERE p.id = ?";

$post_ids[] = $current_post;

if (!get_db_data($db_connect, $sql, $post_ids)) {
    print "Запись не найдена. Указан неверный или несуществующий id";
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

$post = get_db_data($db_connect, $sql, $post_ids)[0];

/* Лайки */
$post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $current_post);

/* Данные автора поста */
$user_id[] = $post['post_author'];
$sql_author = "SELECT reg_date, login, avatar FROM users WHERE id = ?";
$author = get_db_data($db_connect, $sql_author, $user_id)[0];
/* Кол-во записей у автора */
$author['counter_posts'] = count_lines_db_table($db_connect, 'id', 'posts', 'post_author', $post['post_author']);
$author['followers'] = count_lines_db_table($db_connect, 'id', 'subscribes', 'host', $post['post_author']);

/* Хэштеги поста */
$sql_tags = "SELECT h.hashtag FROM has_posts hp
                JOIN hashtags h ON h.id = hp.hashtag
                WHERE post = ?";
$tags = get_db_data($db_connect, $sql_tags, $post_ids);

$post_content = include_template("single-post/post-${post['class']}.php", ['post' => $post]);

$content = include_template('post.php', [
        'post' => $post,
        'post_content' => $post_content,
        'author' => $author,
        'tags' => $tags
]);

$layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'current_user' => $current_user
]);

print($layout_content);
