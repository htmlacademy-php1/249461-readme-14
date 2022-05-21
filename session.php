<?php

require_once "db_connect.php";
require_once "functions.php";

session_start();

if (!$_SESSION['user']) {
    header('Location: index.php');
}

$current_user = $_SESSION['user'];
$current_user['new_messages'] = count_not_read_messages($db_connect,$current_user['id']);
