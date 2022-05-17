<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$user = $current_user;
$subscribe_button = [
    'class' => 'main',
    'text' => 'Подписаться'
];

if (isset($_GET['user'])) {
    $user_id = $_GET['user'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $user = get_db_data($db_connect, $sql, [$user_id])[0];

    if (check_db_entry($db_connect, 'subscribes', 'follower', $current_user['id'], 'host', $user_id)) {
        $subscribe_button['class'] = 'quartz';
        $subscribe_button['text'] = 'Отписаться';
    }
}

$title = $user['login'];

$user['counter_posts'] = count_lines_db_table($db_connect, 'id', 'posts', 'post_author', $user['id']);
$user['followers'] = count_lines_db_table($db_connect, 'id', 'subscribes', 'host', $user['id']);

$filter_tabs = [
    'posts' => 'Посты',
    'likes' => 'Лайки',
    'subscriptions' => 'Подписки'
];

$active_tab = $_GET['tab'] ?? 'posts';

function get_origin_post_info($db_connect, $post_id)
{
    $sql = "SELECT p.dt_add, u.login, u.avatar
            FROM posts p
            JOIN users u ON u.id = p.post_author
            WHERE p.id = ?";
    return get_db_data($db_connect, $sql, [$post_id])[0];
}

switch ($active_tab) {
    case 'posts':
        $sql_post = "SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, t.class, p.origin_post, p.origin_author, p.repost
                    FROM posts p
                    JOIN types t ON t.id = p.post_type
                    WHERE post_author = ?
                    ORDER BY dt_add DESC ";
        $posts = get_db_data($db_connect, $sql_post, [$user['id']]);


        foreach ($posts as $key => $post) {
            $post['likes'] = count_lines_db_table($db_connect, 'id', 'likes', 'post', $post['id']);
            $post['repost_count'] = count_lines_db_table($db_connect, 'origin_post', 'posts', 'origin_post',
                $post['id']);
            $post['has_like'] = check_db_entry($db_connect, 'likes', 'author', $current_user['id'], 'post',
                $post['id']);

            if ($post['origin_post']) {
                $origin_post = get_origin_post_info($db_connect, $post['origin_post']);

                $post['origin_date'] = $origin_post['dt_add'];
                $post['origin_login'] = $origin_post['login'];
                $post['origin_avatar'] = $origin_post['avatar'];
            }


            $sql_tags = "SELECT h.hashtag FROM hash_posts hp
                JOIN hashtags h ON h.id = hp.hashtag
                WHERE post = ?";
            $post['tags'] = get_db_data($db_connect, $sql_tags, [$post['id']]);

            $posts[$key] = $post;
        }

        $tab_content = include_template("profile/posts.php", [
            'posts' => $posts
        ]);
        break;

    case 'likes':
        $sql = "SELECT l.dt_add, l.author, l.post, u.login, u.avatar, t.class, p.image, p.video
                FROM likes l
                JOIN users u ON u.id = l.author
                JOIN posts p ON p.id = l.post
                JOIN types t ON t.id = p.post_type
                WHERE post IN (SELECT id FROM posts WHERE post_author = ?)";

        $likes = get_db_data($db_connect, $sql, [$user['id']]);

        $tab_content = include_template("profile/likes.php", ['likes' => $likes]);
        break;

    case 'subscriptions':
        $subscribes_list = [];

        $sql = "SELECT host FROM subscribes WHERE follower = ?";
        $subscribes_id = get_db_data($db_connect, $sql, [$user['id']]);

        $sql_host = "SELECT id, reg_date, login, avatar FROM users WHERE id = ?";

        foreach ($subscribes_id as $id) {
            $subscribe = get_db_data($db_connect, $sql_host, [$id['host']])[0];
            $subscribe['counter_posts'] = count_lines_db_table($db_connect, 'id', 'posts', 'post_author', $id['host']);
            $subscribe['followers'] = count_lines_db_table($db_connect, 'id', 'subscribes', 'host', $id['host']);
            $subscribes_list[] = $subscribe;

            if (check_db_entry($db_connect, 'subscribes', 'follower', $current_user['id'], 'host', $id['host'])) {
                $subscribe_button['class'] = 'quartz';
                $subscribe_button['text'] = 'Отписаться';
            }
        }

        $tab_content = include_template("profile/subscriptions.php", [
            'subscribes_list' => $subscribes_list,
            'subscribe_button' => $subscribe_button,
        ]);

        break;
}

$content = include_template('profile.php', [
    'title' => $title,
    'user' => $user,
    'current_user' => $current_user,
    'subscribe_button' => $subscribe_button,
    'filter_tabs' => $filter_tabs,
    'active_tab' => $active_tab,
    'tab_content' => $tab_content
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
]);

print($layout_content);
