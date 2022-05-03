<?php

session_start();

if (!$_SESSION['user']) {
    header('Location: index.php');
}

$current_user = $_SESSION['user'];
