<?php
    define('MAX_TEXT_LENGTH', 300);

    /**
     * @param $text текст который необходимо обрезать
     * @param $letters_num максимальное число символов которое необходимо оставить без учета пробелов
     * @return string возвращает часть исходной строки, добавляя в конец "..."
     */
    function cut_text($text, $letters_num = MAX_TEXT_LENGTH)
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
