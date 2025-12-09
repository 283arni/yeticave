<?php


function set_price($price): string {
    $int_price = ceil($price);

    return number_format($int_price, 0, '', ' ');
};

function set_time_lot($str_date): array {
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

function validate_cat($id, $categories): null|string {
    $ids_categories = array_column($categories, 'id');

    if(!in_array($id, $ids_categories)) {
        return "Нет такой категории";
    }

    return null;
}

function validate_price($price): null|string {
    if(!is_numeric($price)) {
        return "Введите корректную сумму";
    } else if($price <= 0) {
        return "Сумма не должна быть меньше 0";
    } else {
        return null;
    }
}

function validate_step($step): null|string {
    if(filter_var($step, FILTER_VALIDATE_INT) === false || $step <= 0) {
        return "Ставка должна быть целым числом и больше 0";
    }

    return null;
}

function validate_text($txt, $min, $max): null|string {
    $strlen = strlen($txt);

    if($strlen < $min || $strlen > $max) {
        return "Заполните поле не меньше $min и не больше $max";
    }

    return null;
}

function validate_date($date): null|string {
    $date_now = date_create();
    $date_select = date_create($date);
    $date_tomorrow = date_add($date_now, date_interval_create_from_date_string("1 day"));

    if($date_select < $date_tomorrow) {
        return "Дата должна быть больше минимум на 1 день";
    }

    return null;
}

function filter_values($values, $rules): array {
    $errors = [];

    foreach ($values as $key => $value) {
        if (empty($value)) {
            $errors[$key] = 'Заполните поле';
            continue;
        }

        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $error = $rule($value);

            if ($error) {
                $errors[$key] = $error;
            }
        }
    }

    return $errors;
}
