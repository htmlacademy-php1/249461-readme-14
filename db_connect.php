<?php

/* Database */
$config = require_once __DIR__ . '/config.php';
$db_connect = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
mysqli_set_charset($db_connect, 'utf8');
