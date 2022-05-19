<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';
require_once "post-types.php";

$title = 'Моя лента';
$active_page = 'feed';
$post_category = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);

$sql_hosts = "SELECT host FROM subscribes WHERE follower = ?";

if (!get_db_data($db_connect, $sql_hosts, [$current_user['id']])) {
    $posts = [];
    $content = include_template('feed.php', [
        'title' => $title,
        'types' => $post_types,
        'post_category' => $post_category,
        'posts' => $posts
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'current_user' => $current_user,
        'active_page' => $active_page
    ]);

    print($layout_content);
    die();
}

$hosts = get_db_data($db_connect, $sql_hosts, [$current_user['id']]);


$hosts_id = [];
foreach ($hosts as $host) {
    $hosts_id[] = $host['host'];
}

$sql_posts = sprintf("SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE post_author IN (%s)
                  ORDER BY dt_add DESC",
    implode(', ', array_fill(0, count($hosts_id), '?'))
);

$posts = get_db_data($db_connect, $sql_posts, $hosts_id);

if (isset($post_category)) {
    $filters[] = $post_category;
    $filters = array_merge($filters, $hosts_id);

    $sql_posts = sprintf('SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, p.post_author, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE post_type IN (%1$s) AND post_author IN (%2$s)
                  ORDER BY dt_add DESC',
        '?', implode(', ', array_fill(0, count($hosts_id), '?'))
    );

    $posts = get_db_data($db_connect, $sql_posts, $filters);
}

foreach ($posts as $key => $post) {
    $post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $post['id']);
    $post['comments'] = count_lines_db_table($db_connect, 'id', 'comments', 'post', $post['id']);
    $post['repost_count'] = count_lines_db_table($db_connect, 'origin_post', 'posts', 'origin_post', $post['id']);
    $post['has_like'] = check_db_entry($db_connect, 'likes', 'author', $current_user['id'], 'post', $post['id']);
    $posts[$key] = $post;
}

$content = include_template('feed.php', [
    'title' => $title,
    'types' => $post_types,
    'post_category' => $post_category,
    'posts' => $posts
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_page' => $active_page
]);

print($layout_content);
