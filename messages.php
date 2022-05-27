<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';


$sql_chats = "SELECT sender,receiver FROM messages WHERE sender = ? OR receiver = ? ORDER BY dt_add DESC";
$user_chats = get_db_data($db_connect, $sql_chats, [$current_user['id'], $current_user['id']]);

$users_id = [];
foreach ($user_chats as $user_chat) {
    if ($user_chat['sender'] != $current_user['id']) {
        $users_id[] = $user_chat['sender'];
    }
    if ($user_chat['receiver'] != $current_user['id']) {
        $users_id[] = $user_chat['receiver'];
    }
}

$users_id = array_values(array_unique($users_id));

$active_chat = 0;

if (isset($_GET['chat']) || !empty($users_id)) {
    $active_chat = $_GET['chat'] ?? $users_id[0];
}
if ($active_chat === 0) {
    $content_data = [
        'no_chats' => true,
        'current_user' => $current_user,
        'active_chat' => $active_chat
    ];

    print_messages_page($content_data, $current_user);
    die();
}

if ($active_chat !== 0 && !in_array($active_chat, $users_id)) {
    array_unshift($users_id, $active_chat);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "UPDATE messages SET is_read = 1 WHERE sender = ? AND receiver = ?";
    $stmt = db_get_prepare_stmt($db_connect, $sql, [$active_chat, $current_user['id']]);
    mysqli_stmt_execute($stmt);
}

$current_user['new_messages'] = count_not_read_messages($db_connect, $current_user['id']);

$sql_users = "SELECT id, login, avatar FROM users WHERE id = ?";
$users = [];
foreach ($users_id as $key => $user_id) {
    if (empty(get_db_data($db_connect, $sql_users, [$user_id]))) {
        header('HTTP/1.1 404 Not Found', true, 404);
        print "Пользователя с таким id не найдено";
        die();
    }

    $users[$key] = get_db_data($db_connect, $sql_users, [$user_id])[0];
    $users[$key]['message'] = get_last_message($db_connect, $user_id, $current_user['id']);
    $users[$key]['not_read'] = count_not_read_messages($db_connect, $user_id, $current_user['id']);
}

$sql_messeges = "SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (receiver = ? AND sender = ?) ORDER BY dt_add ASC";
$messages = get_db_data($db_connect, $sql_messeges,
    [$active_chat, $current_user['id'], $active_chat, $current_user['id']]);

$sql_active_chat_user = "SELECT id, login, avatar FROM users WHERE id = ?";

$active_chat_user = '';
if (get_db_data($db_connect, $sql_active_chat_user, [$active_chat])) {
    $active_chat_user = get_db_data($db_connect, $sql_active_chat_user, [$active_chat])[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_url = $_SERVER['HTTP_REFERER'];
    $new_message = $_POST;

    $new_message['message'] = trim($new_message['message'], ' ');

    $validation_rules = [
        'message' => ['required']
    ];

    $errors = validate($new_message ?? [], $validation_rules, $db_connect);

    if (!count_lines_db_table($db_connect, 'id', 'users', 'id', $new_message['receiver'])) {
        $errors['message'] = 'Пользователю нельзя отправить сообщение или его не существует';
    };

    if ($new_message['receiver'] === $current_user['id']) {
        $errors['message'] = 'Вы не можете отправить сообщение самому себе';
    }

    if (count($errors) !== 0) {
        $content_data = [
            'users' => $users,
            'active_chat' => $active_chat,
            'current_user' => $current_user,
            'messages' => $messages,
            'active_chat_user' => $active_chat_user,
            'errors' => $errors
        ];

        print_messages_page($content_data, $current_user);
        die();
    }

    $new_message['sender'] = $current_user['id'];

    $sql = "INSERT INTO messages (dt_add, message, receiver, sender) VALUES (NOW(), ?,?,?)";
    $stmt = db_get_prepare_stmt($db_connect, $sql, $new_message);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Location: {$current_url}");
    }
}

$content_data = [
    'users' => $users,
    'active_chat' => $active_chat,
    'current_user' => $current_user,
    'messages' => $messages,
    'active_chat_user' => $active_chat_user
];

print_messages_page($content_data, $current_user);

