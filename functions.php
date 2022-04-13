<?php
    require_once 'helpers.php';

    /* Temporary */
    $is_auth = rand(1, 1);
    $user_name = 'Сергей Кравцов';

    /**
     * Максимальная длина превью поста
     */
    define('MAX_TEXT_LENGTH', 300);

    /**
     * @param $text текст который необходимо обрезать
     * @param $letters_num максимальное число символов которое необходимо оставить без учета пробелов
     * @return string возвращает часть исходной строки, добавляя в конец "..."
     */
    function cut_text(string $text, int $letters_num = MAX_TEXT_LENGTH): string
    {
        $words = explode(' ', $text);
        $length = 0;

        foreach ($words as $word) {
            $length += strlen($word);

            if ($length <= $letters_num) {
                $short_text[] = $word;
            } else {
                break;
            }
        }

        $text = implode(' ', $short_text) . '...';

        return $text;
    }


    /**
     * @param $date случайная дата в формате «ГГГГ-ММ-ДД ЧЧ: ММ: СС»
     * @return string пройденное время к текущему моменту в относительном формате
     */
    function elapsed_time(string $date): string
    {
        $now_date = date_create('now');
        $post_date = date_create($date);

        $diff = date_diff($now_date, $post_date);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $periods = [
            'y' => ['год', 'года', 'лет'],
            'm' => ['месяц', 'месяца', 'месяцев'],
            'w' => ['неделю', 'недели', 'недель'],
            'd' => ['день', 'дня', 'дней'],
            'h' => ['час', 'часа', 'часов'],
            'i' => ['минута', 'минуты', 'минут'],
            's' => ['секунда', 'секунды', 'секунд']
        ];

        $passed_time = '';

        foreach ($periods as $key => $period) {
            if ($diff->$key) {
                $period = get_noun_plural_form($diff->$key, $period['0'], $period['1'], $period['2']);
                $passed_time = $diff->$key . ' ' . $period . ' назад';

                break;
            }
        }

        return $passed_time;
    }


    /**
     * Получение массива записей из базы.
     * @param $db_connect ресурс соединиея с БД
     * @param $sql запрос данных
     * @param $data если нужна выборка по условию
     * @return array|void Массив записей или ошибку.
     */
    function get_db_data($db_connect, $sql, $data = []) {
        $stmt = db_get_prepare_stmt($db_connect, $sql, $data);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            print ("Ошибка базы данных" . mysqli_stmt_error($stmt));
            die();
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * @param $db_connect ресурс соединения с БД
     * @param $column колонка для подсчета
     * @param $table таблица в которой ведется подсчет
     * @param $sort_column колонка для выборки по ключу
     * @param $sort_key ключ для выборки
     * @return mixed|string ошибка БД или кол-во записей
     */
    function count_lines_db_table($db_connect, $column, $table, $sort_column = '', $sort_key = '') {
        $ids = [];

        $sql = "SELECT COUNT($column) FROM $table";

        if ($sort_key != '') {
            $ids[] = $sort_key;
            $sql = "SELECT COUNT($column) FROM $table WHERE $sort_column = ?";
        }
        $stmt = db_get_prepare_stmt($db_connect, $sql, $ids);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $counter = mysqli_fetch_assoc($result);
        return $counter["COUNT($column)"];
    }
