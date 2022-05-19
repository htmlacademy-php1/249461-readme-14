<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require 'vendor/autoload.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_connect.php';
require_once 'session.php';


function email_new_subscriber($db_connect, $host_id, $subscriber_id, $sender_login = 'readme.project.22@gmail.com', $sender_pass = 'Readme2022') {
    $dsn = "gmail+smtp://{$sender_login}:{$sender_pass}@default";
    $transport = Transport::fromDsn($dsn);

    $sql_host = "SELECT login, email FROM users WHERE id = ?";
    $sql_subscriber = "SELECT id, login FROM users WHERE id = ?";

    $host = get_db_data($db_connect, $sql_host, [$host_id])[0];
    $subscriber = get_db_data($db_connect, $sql_subscriber, [$subscriber_id])[0];

    $subject = 'У вас новый подписчик';
    $text = "Здравствуйте, {$host['login']}.\nНа вас подписался новый пользователь {$subscriber['login']}.\nВот ссылка на его профиль: http://readme/profile.php?user={$subscriber['id']}";

    $messege_subscribe = (new Email())
        ->to($host['email'])
        ->from($sender_login)
        ->subject($subject)
        ->text($text);

    $mailer = new Mailer($transport);
    $mailer->send($messege_subscribe);
}

/*
 * TODO - Узнать как лучше поступить при отправке нескольких emails !!!
 * */

function email_new_post($db_connect, $post_author = 2, $post = '', $sender_login = 'readme.project.22@gmail.com', $sender_pass = 'Readme2022')  {
    $dsn = "gmail+smtp://{$sender_login}:{$sender_pass}@default";
    $transport = Transport::fromDsn($dsn);

    $sql_post_author = "SELECT id, login FROM users WHERE id = ?";
    $author = get_db_data($db_connect, $sql_post_author, [$post_author])[0];

    $sql_subscribers = "SELECT s.follower, u.login, u.email
                        FROM subscribes s
                        JOIN users u ON s.follower = u.id
                        WHERE host = ?";
    $subscribers = get_db_data($db_connect, $sql_subscribers, [$post_author]);

    $subject = "Новая публикация от пользователя {$author['login']}}";

    foreach ($subscribers as $subscriber) {
        $text = "Здравствуйте, {$subscriber['login']}.
        Пользователь {$author['login']} только что опубликовал новую запись „{$post['title']}“.
        Посмотрите её на странице пользователя: http://readme/profile.php?user={$author['id']}";

        $messege_post = (new Email())
            ->to($host['email'])
            ->from($subscriber['email'])
            ->subject($subject)
            ->text($text);

        $mailer = new Mailer($transport);
        $mailer->send($messege_post);
    }
}



