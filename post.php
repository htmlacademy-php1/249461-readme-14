<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Пост';

$posts_count = count_lines_db_table($db_connect, 'id', 'posts');
$current_post = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$current_post) {
    print "Запись не найдена. Указан неверный id";
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

/* Просмотры */
$sql_views = "UPDATE posts SET views = views + 1 where id = ?";
$stmt = db_get_prepare_stmt($db_connect, $sql_views, [$current_post]);
mysqli_stmt_execute($stmt);

$sql = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, t.class
                  FROM posts p
                  JOIN types t ON t.id = p.post_type
                  WHERE p.id = ?";


if (!get_db_data($db_connect, $sql, [$current_post])) {
    print "Запись не найдена. Указан неверный или несуществующий id";
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

$post = get_db_data($db_connect, $sql, [$current_post])[0];

/* Лайки */
$post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $current_post);
$post['comments'] = count_lines_db_table($db_connect, 'id', 'comments', 'post', $post['id']);
$post['repost_count'] = count_lines_db_table($db_connect, 'origin_post', 'posts', 'origin_post', $post['id']);
$post['has_like'] = check_db_entry($db_connect, 'likes', 'author', $current_user['id'], 'post', $current_post);

/* Данные автора поста */
$user_id[] = $post['post_author'];
$sql_author = "SELECT id, reg_date, login, avatar FROM users WHERE id = ?";
$author = get_db_data($db_connect, $sql_author, $user_id)[0];
/* Кол-во записей у автора */
$author['counter_posts'] = count_lines_db_table($db_connect, 'id', 'posts', 'post_author', $post['post_author']);
$author['followers'] = count_lines_db_table($db_connect, 'id', 'subscribes', 'host', $post['post_author']);

/* Хэштеги поста */
$sql_tags = "SELECT h.hashtag FROM has_posts hp
                JOIN hashtags h ON h.id = hp.hashtag
                WHERE post = ?";
$tags = get_db_data($db_connect, $sql_tags, [$current_post]);

$post_content = include_template("single-post/post-${post['class']}.php", ['post' => $post]);

/* Кнопка подписаться */
$subscribe_button = [
    'class' => 'main',
    'text' => 'Подписаться'
];

if (isset($author['id'])) {
    $user_id = $author['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $user = get_db_data($db_connect, $sql, [$user_id])[0];

    if (check_db_entry($db_connect, 'subscribes', 'follower', $current_user['id'], 'host', $user_id)) {
        $subscribe_button['class'] = 'quartz';
        $subscribe_button['text'] = 'Отписаться';
    }
}
/* Кнопка подписаться */

/* Список комментариев к посту */
$sql_comments = "SELECT c.dt_add, c.comment, u.id, u.login, u.avatar, c.post
        FROM comments c
        JOIN users u ON u.id = c.author
        WHERE c.post = ?
        ORDER BY dt_add DESC";

$comments = get_db_data($db_connect, $sql_comments, [$current_post]);


$content = include_template('post.php', [
    'post' => $post,
    'post_content' => $post_content,
    'author' => $author,
    'tags' => $tags,
    'subscribe_button' => $subscribe_button,
    'current_user' => $current_user,
    'comments' => $comments
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user
]);

print($layout_content);
