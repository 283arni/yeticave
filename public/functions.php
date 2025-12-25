<?php

/**
 * Отделяет тысячи пробелом
 *
 * @param int $price Цена
 * @return string Отформатированная цена
 */
function set_price($price): string
{
    $int_price = ceil($price);

    return number_format($int_price, 0, '', ' ');
}

;

/**
 * Получение массива чисел с часом и минутами до окончания срока лота
 *
 * @param string $str_date Дата
 * @return array|string[] Минуты и часы ['12', '34']
 */
function set_time_lot($str_date): array
{
    $now = date_create();
    $end_time = date_create($str_date);

    $diff = date_diff($now, $end_time);

    if ($diff->invert) {
        return ['00', '00'];
    }

    $format_date = date_interval_format($diff, "%d %H %I");

    $arr = explode(" ", $format_date);

    $hours = $arr[0] * 24 + $arr[1];
    $minutes = intval($arr[2]);

    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

    $res[] = $hours;
    $res[] = $minutes;

    return $res;
}

/**
 * Проверяет существование категории
 *
 * @param int $id Идентификатор категории
 * @param array $categories Категории
 * @return string|null Текст ошибки или null
 */
function validate_cat($id, $categories): null|string
{
    $ids_categories = array_column($categories, 'id');

    if (!in_array($id, $ids_categories)) {
        return "Нет такой категории";
    }

    return null;
}

/**
 * Проверка ставки на положительное число
 *
 * @param int $price Цена
 * @return string|null Текст ошибки или null
 */
function validate_price($price): null|string
{
    if (!is_numeric($price)) {
        return "Введите корректную сумму";
    } else {
        if ($price <= 0) {
            return "Сумма не должна быть меньше 0";
        } else {
            return null;
        }
    }
}

/**
 * Проверка ставки на положительное целое число
 *
 * @param int $step Сумма ставки
 * @return string|null Текст ошибки или null
 */
function validate_step($step): null|string
{
    if (filter_var($step, FILTER_VALIDATE_INT) === false || $step <= 0) {
        return "Ставка должна быть целым числом и больше 0";
    }

    return null;
}

/**
 * Проверка текста на максимальное и минимальное кол-во символов
 *
 * @param string $txt Текст поля
 * @param int $min Минимальное кол-во символов
 * @param int $max  Максимальное кол-во символов
 * @return string|null Текст ошибки или null
 */
function validate_text($txt, $min, $max): null|string
{
    $strlen = strlen($txt);

    if ($strlen < $min || $strlen > $max) {
        return "Заполните поле не меньше $min и не больше $max";
    }

    return null;
}


/**
 * Функция проверяет выбранная дата больше на 24 часа от текущей
 *
 * @param string $date Дата
 * @return string|null Текст ошибки или null
 */
function validate_date($date): null|string
{
    $date_now = date_create();
    $date_select = date_create($date);
    $date_tomorrow = date_add($date_now, date_interval_create_from_date_string("1 day"));

    if ($date_select < $date_tomorrow) {
        return "Дата должна быть больше минимум на 1 день";
    }

    return null;
}

/**
 * Фильтрует поля формы по заданным параметрам
 *
 * @param array $values Значения формы
 * @param array $rules Правила задаются в сценарии для конкретного значения
 * @return array Массив ошибок с текстом самой ошибки
 */
function filter_values($values, $rules): array
{
    $errors = [];

    foreach ($values as $key => $value) {
        if (empty($value)) {
            $errors[$key] = 'Заполните поле';
            continue;
        }

        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $error = $rule($value);

            if ($error) {
                $errors[$key] = $error;
            }
        }
    }

    return $errors;
}
