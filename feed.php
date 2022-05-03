<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Моя лента';
$active_page = 'feed';

$content = include_template('feed.php', [
        'title' => $title
]);

$layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'current_user' => $current_user,
        'active_page' => $active_page
]);

print($layout_content);
