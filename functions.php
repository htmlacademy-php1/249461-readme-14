<?php

require_once 'helpers.php';

/**
 * Максимальная длина превью поста
 */
define('MAX_TEXT_LENGTH', 300);

/**
 * @param string $text Текст который необходимо обрезать
 * @param int $letters_num Максимальное число символов которое необходимо оставить без учета пробелов
 * @return string Возвращает часть исходной строки, добавляя в конец "..."
 */
function cut_text(string $text, int $letters_num = MAX_TEXT_LENGTH): string
{
    $words = explode(' ', $text);
    $length = 0;

    $short_text = [];
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
 * @param string $date Случайная дата в формате «ГГГГ-ММ-ДД ЧЧ: ММ: СС»
 * @return string Пройденное время к текущему моменту в относительном формате
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
 * @param mysqli $db_connect Ресурс соединения с БД
 * @param string $sql Запрос данных
 * @param array $data Если нужна выборка по условию
 * @return array|void Массив записей или ошибку.
 */
function get_db_data(mysqli $db_connect, string $sql, array $data = [])
{
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
 * @param mysqli $db_connect Ресурс соединения с БД
 * @param string $column Колонка для подсчета
 * @param string $table Таблица в которой ведется подсчет
 * @param string $sort_column Колонка для выборки по ключу
 * @param string $sort_key Ключ для выборки
 * @return mixed|string Ошибка БД или кол-во записей
 */
function count_lines_db_table(
    mysqli $db_connect,
    string $column,
    string $table,
    string $sort_column = '',
    string $sort_key = ''
) {
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

/**
 * Функция получения значений из POST запроса.
 * @param string $name Input[name] из которого необходимо получить значение
 * @return string Возвращает строку, введенную пользователем, если форма отправлена с ошибкой.
 */
function get_post_val(string $name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Функция получения значений из POST запроса.
 * @param string $name Input[name] из которого необходимо получить значение
 * @return string Возвращает строку, введенную пользователем, если форма отправлена с ошибкой.
 */
function get_get_val(string $name)
{
    return filter_input(INPUT_GET, $name);
}

/**
 * Получение класса типа поста на основе
 * @param int $id типа поста
 * @param array $types Массив типов постов
 * @return mixed|null NULL или класс типа поста
 */
function get_post_type_class(int $id, array $types)
{
    foreach ($types as $type) {
        if ($type['id'] == $id) {
            $type = $type['class'];
            break;
        }
    }

    if (!$type) {
        return null;
    }

    return $type;
}


/**
 * Функция подгрузки шаблона контента поста
 * @param string $path Подпапка в темплейтах
 * @param string $class Строка с типом поста
 * @param array $post Массив с данными поста
 * @return void
 */
function get_post_content($path, $class, $post)
{
    $post_content = include_template($path . $class . ".php", ['post' => $post]);
    print $post_content;
}


/**
 * Основная функция валидации полей
 * @param array $input_array Массив полей из формы
 * @param array $validation_rules ассоциативный массив с правилами валидации
 * @param mysqli $db_connect Данные соединения с базой
 * @return array Массив ошибок если они есть
 * @throws Exception Если указана несуществующая функция проверки
 */
function validate(array $input_array, array $validation_rules, $db_connect): array
{
    // Объявим массив ошибок, который в итоге вернем в ответ.
    $errors = [];

//    Пройдемся по всему списку валидаций. Он выглядит примерно так
//    [
//        'Поле' => ['проверка1', 'проверка 2']
//    ]
    foreach ($validation_rules as $field => $rules) {
        // Так как у нас условия - массив
        foreach ($rules as $rule) {
            $rule_parameters = explode(':', $rule);
            $rule_name = $rule_parameters[0];
            $rule_name = 'validate_' . $rule_name;
            $parameters = [];
            if (isset($rule_parameters[1])) {
                $parameters = explode(',', $rule_parameters[1]);
            }
            if (!function_exists($rule_name)) {
                throw new Exception("Валидации {$rule_name} не существует. Пожалуйста, не забудьте добавить ее");
            }

            $errors[$field] = call_user_func_array($rule_name,
                array_merge([$input_array, $field, $db_connect], $parameters));
            if (isset($errors[$field])) {
                break;
            }
        }
    }

    return array_filter($errors);
}

/**
 * Проверка обязательного поля для заполнения
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_required(array $input_array, string $field, $db_connect)
{
    if (empty($input_array[$field])) {
        return 'Поле должно быть заполнено';
    }

    return null;
}

/**
 * Проверка значения на длину
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @param int $min Минимальная длина текста
 * @param int $max Максимальная длинна текста
 * @return string|null Текст ошибки, или ничего
 */
function validate_length(array $input_array, string $field, $db_connect, int $min, int $max)
{
    if ($input_array[$field]) {
        $len = mb_strlen($input_array[$field]);

        if ($len < $min || $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

/**
 * Функция проверки тега
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_tags(array $input_array, string $field, $db_connect)
{
    if (empty($input_array[$field])) {
        return null;
    }
    $tags = explode(' ', trim($input_array[$field], ' '));

    foreach ($tags as $tag) {
        if (substr($tag, 0, 1) !== '#') {
            return 'Каждый тег должен начинаться со знака #';
        }
    }

    return null;
}

/**
 * Проверка значения, что оно является правильной ссылкой
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_link(array $input_array, string $field, $db_connect)
{
    if (!filter_var($input_array[$field], FILTER_VALIDATE_URL)) {
        return 'URL должен быть корректным';
    }
    return null;
}

/**
 * Проверка значения, что ссылка ведет на видео youtube, и оно доступно
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_video(array $input_array, string $field, $db_connect)
{
    $id = extract_youtube_id($input_array[$field]);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return null;
}

/**
 * Проверка расширения изображения
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_img_type(array $input_array, string $field, $db_connect)
{
    if (empty($input_array[$field])) {
        return null;
    }

    $valid_formats = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
    if (!$input_array[$field]) {
        return null;
    }
    $tmp_name = $input_array[$field]['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $tmp_name);

    foreach ($valid_formats as $valid_format) {
        if ($file_type === $valid_format) {
            return null;
        }
    }

    return "Неверный формат изображения.";
}

/**
 * Проверка изображения, что оно не превышает максимальный размер в 2Мб
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки, или ничего
 */
function validate_img_size(array $input_array, string $field, $db_connect)
{
    if (empty($input_array[$field])) {
        return null;
    }

    $size = $input_array[$field]['size'];
    $max_size = 1024 * 1024 * 2; // 2097152 = 2Мб
    if ($size > $max_size) {
        return 'Размер файла не может быть больше 2Мб';
    }
    return null;
}

/**
 * Получает расширение файла по ссылке на него
 * @param string $url Ссылка на удаленный файл
 * @return mixed|null
 */
function get_remote_mime_type($url)
{
    $url = filter_var($url, FILTER_VALIDATE_URL);
    if (!$url) {
        return null;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    # get the content type
    return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
}

/**
 * Проверка, что файл по ссылке является картинкой
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @return string|null Текст ошибки, или ничего
 */
function validate_url_content(array $input_array, string $field): ?string
{
    if (empty($input_array[$field])) {
        return null;
    }

    if (in_array(get_remote_mime_type($input_array[$field]), ['image/jpeg', 'image/png', 'image/gif'])) {
        return null;
    }

    return 'Ссылка должна быть корректной, файл должен быть в формате png, jpeg, gif';
}


/**
 * Функция загрузки изображения полученного из формы от пользователя
 * @param array $image Массив с данными загруженного изображения
 * @return mixed Путь к сохраненного изображения
 */
function upload_img(array $image)
{
    $image_name = $image['name'];
    $file_name = hash('sha256', $image_name);
    $image_format = pathinfo($image_name, PATHINFO_EXTENSION);

    $new_file_name = $file_name . '.' . $image_format;

    $upload_folder = 'uploads';
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777);
    }

    $image_path = __DIR__ . '/uploads/';
    $image_url = 'uploads/' . $new_file_name;

    move_uploaded_file($image['tmp_name'], $image_path . $new_file_name);

    return $image_url;
}

/**
 * Функция загрузки изображения по ссылке полученной от пользователя
 * @param string $link Ссылка на изображение
 * @return string Ссылку на сохранное изображение
 */
function download_img_from_link($link)
{
    $img = file_get_contents($link);
    $img_format = '.' . end(explode('.', $link));
    $img_name = hash('sha256', $img);

    $upload_folder = 'uploads';
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777);
    }

    $image_url = 'uploads/' . $img_name . $img_format;
    file_put_contents($image_url, $img);

    return $image_url;
}

/**
 * @param string $input Строка с тегами
 * @param $db_connect Подключение к базе
 * @param int $post_id Идентификатор поста к которому необходимо добавить теги
 * @return bool|null
 */
function add_post_tags(string $input, $db_connect, int $post_id)
{
    if (empty($input)) {
        return null;
    }

    $tags = explode(' ', trim($input, ' '));
    $tags_ids = [];

    foreach ($tags as $key => $tag) {
        $tag_new['0'] = $tag;

        $sql = "SELECT id FROM hashtags WHERE hashtag = ?";
        $check_tags = get_db_data($db_connect, $sql, $tag_new);
        if (!empty($check_tags)) {
            $tags_ids[$key]['hashtag'] = $check_tags[0]['id'];
            $tags_ids[$key]['post'] = $post_id;
        } else {
            $sql = "INSERT INTO hashtags (hashtag) VALUES (?)";
            $stmt = db_get_prepare_stmt($db_connect, $sql, $tag_new);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $tags_ids[$key]['hashtag'] = mysqli_insert_id($db_connect);
                $tags_ids[$key]['post'] = $post_id;
            }
        }
    }

    foreach ($tags_ids as $id) {
        $sql = "INSERT INTO has_posts (hashtag, post) VALUES (?, ?)";
        $stmt = db_get_prepare_stmt($db_connect, $sql, $id);
        $res = mysqli_stmt_execute($stmt);
    }

    return $res;
}

/**
 * Проверяет на корректность email
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки или ничего
 */
function validate_email(array $input_array, string $field, $db_connect)
{
    if (!filter_var($input_array[$field], FILTER_VALIDATE_EMAIL)) {
        return 'Укажите корректный email';
    }
    return null;
}

/**
 * Проверка на уникальность указанного значения
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @param string $column Колонка в которой необходимо проверить значение
 * @param string $table Таблица в которой проверяем
 * @param string $sort_column Колнка по которой будет искать
 * @return string|null Ошибка если значение уже существует.
 */
function validate_unique(
    array $inputArray,
    string $field,
    $db_connection,
    string $column,
    string $table,
    string $sort_column
): ?string {
    if (!isset($inputArray[$field])) {
        return null;
    }
    $counter = count_lines_db_table($db_connection, $column, $table, $sort_column, $inputArray[$field]);

    return $counter === 0 ? null : 'Данное значение уже присутствует в базе';

}

/**
 * Функция сравнения введенных паролей при регистрации.
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @param string $field2 Второе поле для ввода пароля
 * @return string|null Ошибка если значения не совпадают.
 */
function validate_password(array $input_array, string $field, $db_connect, string $field2)
{
    if ($input_array[$field] !== $input_array[$field2]) {
        return 'Пароли не совпадают';
    }

    return null;
}

/**
 * Функция генерации хэша для указаного значения
 * @param string $value Введенное значение
 * @return string Сгенерированный хэш
 */
function generate_password_hash(string $value)
{
    return password_hash($value, PASSWORD_BCRYPT);
}

/**
 * Проверка существование логина
 * @param array $input_array Массив полей из формы
 * @param string $field Имя поля, которое необходимо проверить
 * @param mysqli $db_connect Данные соединения с базой
 * @return string|null Текст ошибки или ничего
 */
function validate_login(array $input_array, string $field, $db_connect)
{
    $user = [];
    if (!isset($input_array[$field])) {
        return null;
    }

    $user['login'] = $input_array[$field];
    $sql = "SELECT * FROM users WHERE email = ?";

    $current_user = get_db_data($db_connect, $sql, $user);

    if (empty($current_user)) {
        return 'Пользователь с таким email не найден';
    }

    return null;
}

/**
 * Функция отрисовки страницы при отсутствии результатов поиска
 * @param string $query Поисковый запрос
 * @param array $current_user Массив с данными о текущем пользователе
 * @return void
 */
function no_search_results(string $query, array $current_user)
{
    $title = 'Страница результатов поиска (нет результатов)';
    $content = include_template('search-no-results.php', [
        'title' => $title,
        'query' => $query
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'current_user' => $current_user
    ]);

    print($layout_content);
}

/**
 * Проверка наличия записи в таблице по 2-м параметрам
 * @param $db_connect
 * @param int $follower Подписчик
 * @param int $host На кого подписон
 * @return bool
 */
function check_db_entry($db_connect, $table, $column1, int $def1, $column2, int $def2)
{
    $sql = "SELECT * FROM $table WHERE ($column1 = ? AND $column2 = ?)";

    if (get_db_data($db_connect, $sql, [$def1, $def2])) {
        return true;
    }

    return false;
}

/**
 * Подготовка sql запроса для копирования тегов при репосте поста
 * @param array $tags Массив id тегов полученых по id ооригинального поста
 * @param int $post_id ID нового поста
 * @return string
 */
function generate_sql_tags_repost_post(array $tags, int $post_id)
{
    foreach ($tags as $key => $value) {
        $tags[$key] = $value["hashtag"];
    }

    $sql_insert_tags = "INSERT INTO has_posts (hashtag,post) VALUES";

    foreach ($tags as $tag) {
        $new_tag = "($tag,$post_id)";
        $sql_insert_tags .= $new_tag . ",";
    }

    return trim($sql_insert_tags, ",");
}
