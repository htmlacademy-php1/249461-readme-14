<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

date_default_timezone_set("Europe/Kiev");

$title = 'Популярное';
$active_page = 'popular';
$filters = [];

if (!$db_connect) {
    print ("Ошибка подключения базы данных" . mysqli_connect_error());
    die();
}

/* запрос постов */
$sql_posts = 'SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
              FROM posts p
              JOIN users u ON u.id = p.post_author
              JOIN types t ON t.id = p.post_type
              ORDER BY views DESC
              LIMIT 9';

/* запрос категорий постов */
$sql_types = 'SELECT * FROM types';

$post_types = get_db_data($db_connect, $sql_types);
$posts = get_db_data($db_connect, $sql_posts);

$script_path = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_category = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);

if (isset($post_category)) {
    $filters['category'] = $post_category;

    $sql_posts = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE post_type = ?
                  ORDER BY views DESC
                  LIMIT 90";

    $posts = get_db_data($db_connect, $sql_posts, $filters);
}

foreach ($posts as $key => $post) {
    $post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $post['id']);
    $posts[$key] = $post;
}

function get_post_content($class, $post) {
    $post_content = include_template("popular-post/popular-post-$class.php", ['post' => $post]);
    print $post_content;
}

$content = include_template('popular.php', [
        'title' => $title,
        'posts' => $posts,
        'types' => $post_types,
        'script_path' => $script_path,
        'post_category' => $post_category
]);

$layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'current_user' => $current_user,
        'active_page' => $active_page
]);

print($layout_content);

