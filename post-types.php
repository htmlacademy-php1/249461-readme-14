<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';

$sql_types = 'SELECT * FROM types';
$post_types = get_db_data($db_connect, $sql_types);
