<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';

$title = 'Личные сообщения';
$active_page = 'messages';

// Адрес чата на который идет редирект после успешной отправки сообщения
$current_url = $_SERVER['HTTP_REFERER'];

/**
 * Получает последнее сообщение в чате между указаными пользователями
 * @param mysqli $db_connect Данные подключения к БД
 * @param int $user1 Пользователь с которым ведется переписка
 * @param int $user2 Активный пользователь
 * @return mixed Ошибка БД, или массив с данными последнего сообщения в переписке
 */
function get_last_message(mysqli $db_connect, int $user1, int $user2) {
    $sql = "SELECT dt_add, message, sender FROM messages WHERE (sender = ? AND receiver = ?) OR (receiver = ? AND sender = ?) ORDER BY dt_add DESC";
    return get_db_data($db_connect, $sql, [$user1, $user2, $user1, $user2])[0];
}

// Получение всех пользователей из таблицы с сообщениями
$sql_chats = "SELECT sender,receiver FROM messages WHERE sender = ? OR receiver = ? ORDER BY dt_add DESC";
$user_chats = get_db_data($db_connect, $sql_chats, [$current_user['id'], $current_user['id']]);

// Формарование массива со списком id пользователей с которыми велась переписка ранее; id могут повторятся
$users_id = [];
foreach ($user_chats as $user_chat) {
    if ($user_chat['sender'] != $current_user['id']) {
        $users_id[] = $user_chat['sender'];
    }
    if ($user_chat['receiver'] != $current_user['id']) {
        $users_id[] = $user_chat['receiver'];
    }
}

// Убираем дублированные id без сохранения ключей исходного массива
$users_id = array_values(array_unique($users_id));

// id активного чата = id пользователя с которым велась переписка; Или 0 если у пользователя нет чатов
$active_chat = 0;

// Если есть гет параметр с ID чата или список пользователей с перепиской не пустой получаем id активного чата
if (isset($_GET['chat']) || !empty($users_id)) {
    $active_chat = $_GET['chat'] ?? $users_id[0];
}

// При открытии чата отмечаем все непрочитанные сообщения прочитанными для этого чата
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "UPDATE messages SET is_read = 1 WHERE sender = ? AND receiver = ?";
    $stmt = db_get_prepare_stmt($db_connect, $sql, [$active_chat, $current_user['id']]);
    mysqli_stmt_execute($stmt);
}

// Обновляем кол-во непрочитанных сообщений user menu в шапке сайта
$current_user['new_messages'] = count_not_read_messages($db_connect,$current_user['id']);

// Спиок чатов которые велись ранее с данными пользователей, последним сообщением, и кол-вом непрочитанных, если такие есть
$sql_users = "SELECT id, login, avatar FROM users WHERE id = ?";
$users = [];
foreach ($users_id as $key => $user_id) {
    $users[$key] = get_db_data($db_connect,$sql_users,[$user_id])[0];
    $users[$key]['message'] = get_last_message($db_connect, $user_id, $current_user['id']);
    $users[$key]['not_read'] = count_not_read_messages($db_connect,$user_id,$current_user['id']);
}

// Список всех сообщений в рамках одного активного чата
$sql_messeges = "SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (receiver = ? AND sender = ?) ORDER BY dt_add ASC";
$messages = get_db_data($db_connect, $sql_messeges, [$active_chat, $current_user['id'], $active_chat, $current_user['id']]);
// Данные пользователя с которым ведется переписка в рамках чата
$sql_active_chat_user = "SELECT id, login, avatar FROM users WHERE id = ?";
$active_chat_user = get_db_data($db_connect, $sql_active_chat_user, [$active_chat])[0];

// Если отправлена форма с сообщением
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_message  = $_POST;

    // Очищаем сообщение от пробелом в начале, конце строки
    $new_message['message'] = trim($new_message['message'], ' ');
    // Проверяем что поле не пустое
    $validation_rules = [
        'message' => ['required']
    ];
    $errors = validate($new_message ?? [], $validation_rules, $db_connect);


    // Если есть ошибки возвращаем их на исходную страницу
    if (count($errors)) {
        $_SESSION['message'] = $errors;
    }
    // Если ошибок нет добавляем сообщение в бд и обновляем страницу с чатом
    if (!count($errors)) {
        $new_message['sender'] = $current_user['id'];

        $sql = "INSERT INTO messages (dt_add, message, receiver, sender) VALUES (NOW(), ?,?,?)";
        $stmt = db_get_prepare_stmt($db_connect, $sql, $new_message);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: {$current_url}");
        }
    }
}


$content = include_template('messages.php', [
    'users' => $users,
    'active_chat' => $active_chat,
    'current_user' => $current_user,
    'messages' => $messages,
    'active_chat_user' => $active_chat_user
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'current_user' => $current_user,
    'active_page' => $active_page
]);

print($layout_content);
