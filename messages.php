<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Личные сообщения';
$active_page = 'messages';

$sql_messeges = "SELECT * FROM message WHERE sender = ? OR receiver = ?";
$messages = get_db_data($db_connect, $sql_messeges, [$current_user['id'], $current_user['id']]);
var_dump('<pre>');
var_dump($messages);
var_dump('</pre>');

$content = include_template('messages.php', []);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_page' => $active_page
]);

print($layout_content);
