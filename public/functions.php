<?php


function set_price($price) {
    $int_price = ceil($price);

    return number_format($int_price, 0, '', ' ');
};

function set_time_lot($str_date) {
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


