<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';
require_once "post-types.php";

date_default_timezone_set("Europe/Kiev");

$title = 'Популярное';
$active_page = 'popular';
$filters = [
    'type' => $_GET['type'] ?? null,
    'sort' => $_GET['sort'] ?? 'popular',
    'sort_type' => !isset($_GET['sort_type']) || (isset($_GET['sort_type']) && $_GET['sort_type'] === 'ASC') ? 'DESC' : 'ASC',
    'page' => $_GET['page'] ?? null,
];

if (!$db_connect) {
    print ("Ошибка подключения базы данных" . mysqli_connect_error());
    die();
}

/* запрос постов */
$sql_posts = 'SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, u.login, u.avatar, t.class, count(l.author)
              FROM posts p
              JOIN users u ON u.id = p.post_author
              JOIN types t ON t.id = p.post_type
              LEFT JOIN likes l ON p.id = l.post GROUP BY p.id
              ORDER BY ';

$post_category = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);


if (isset($post_category)) {
    $sql_posts = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, u.login, u.avatar, t.class, count(l.author)
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  LEFT JOIN likes l ON p.id = l.post GROUP BY p.id
                  WHERE post_type = ?
                  ORDER BY ";
}


/* Пагинация + Сортировка */
$show_pagination = false;

$counter_posts = ($post_category)
    ? count_lines_db_table($db_connect, 'id', 'posts', 'post_type', $post_category)
    : count_lines_db_table($db_connect, 'id', 'posts');

if ($counter_posts > 6) {
    $show_pagination = true;
}

$posts_limit = 6;
$posts_pages_count = ceil($counter_posts / $posts_limit);
$filters['page'] = $_GET['page'] ?? 1;
$posts_offset = ($filters['page'] - 1) * $posts_limit;
$posts_pages = range(1, $posts_pages_count);

$filters = array_filter($filters);
//$sort = ' views ' . $filters['sort_type'];

/*
 * TODO - СДЕЛАТЬ ДОЛБАННУЮ СОРТИРОВКУ !!!
 */

if (!isset($filters['sort']) || $filters['sort'] == 'popular') {
    $sort = ' views ' . $filters['sort_type'];
}
if (isset($filters['sort']) && $filters['sort'] == 'likes') {
    $sort = ' count(l.author) ' . $filters['sort_type'];
}

if (isset($filters['sort']) && $filters['sort'] == 'date') {
    $sort = ' dt_add ' . $filters['sort_type'];
}

$sql_posts = $sql_posts . $sort;

if ($filters['page'] != '') {
    $sql_posts = $sql_posts . ' LIMIT ' . $posts_limit . ' OFFSET ' . $posts_offset;
}
/* Пагинация + Сортировка */

$posts = ($post_category) ? get_db_data($db_connect, $sql_posts, [$post_category]) : get_db_data($db_connect,
    $sql_posts);

foreach ($posts as $key => $post) {
    //$post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $post['id']);
    $post['comments'] = count_lines_db_table($db_connect, 'id', 'comments', 'post', $post['id']);
    $post['has_like'] = check_db_entry($db_connect, 'likes', 'author', $current_user['id'], 'post', $post['id']);
    $posts[$key] = $post;
}

$content = include_template('popular.php', [
    'title' => $title,
    'posts' => $posts,
    'types' => $post_types,
    'post_category' => $post_category,
    'show_pagination' => $show_pagination,

    'filters' => $filters,
    'pages' => $posts_pages_count
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_page' => $active_page
]);

print($layout_content);

