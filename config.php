<?php

    return file_exists(__DIR__ . '/config.local.php') ? require_once __DIR__ . '/config.local.php' : require_once __DIR__ . '/config.default.php';
