<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$query = $_GET['search'] ?? '';

if (empty($query)) {
    no_search_results($query, $current_user);
    die();
}

if (!empty($query)) {
    $sql_posts = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE MATCH(p.title, p.text, p.quote_author) AGAINST(?)";

    $posts = get_db_data($db_connect, $sql_posts, [$query]);
}

if (!empty($query) && substr($query, 0, 1) === '#') {
    $sql_tag = "SELECT id FROM hashtags WHERE MATCH(hashtag) AGAINST(?)";

    if (!get_db_data($db_connect, $sql_tag, [$query])) {
        no_search_results($query, $current_user);
        die();
    }

    $hashtag = get_db_data($db_connect, $sql_tag, [$query])[0];

    $sql_hashtag_posts = "SELECT post FROM hash_posts WHERE hashtag = ?";
    $hashtag_posts = get_db_data($db_connect, $sql_hashtag_posts, $hashtag);

    $posts_id = [];
    foreach ($hashtag_posts as $post) {
        $posts_id[] = $post['post'];
    }

    krsort($posts_id);

    $sql_post = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, u.avatar, t.class
                  FROM posts p
                  JOIN users u ON u.id = p.post_author
                  JOIN types t ON t.id = p.post_type
                  WHERE p.id = ?";

    $posts = [];

    foreach ($posts_id as $id) {
        $posts[] = get_db_data($db_connect, $sql_post, [$id])[0];
    }
}

if (empty($posts)) {
    no_search_results($query, $current_user);
    die();
}

foreach ($posts as $key => $post) {
    $post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $post['id']);
    $posts[$key] = $post;
}

$title = 'Страница результатов поиска';
$content = include_template('search-results.php', [
    'title' => $title,
    'query' => $query,
    'posts' => $posts
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user
]);

print($layout_content);
