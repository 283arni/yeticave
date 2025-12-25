<?php


require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$content = include_template("add-lot.php", [
    "categories" => $categories,
]);

if (!isset($_SESSION["user"])) {
    $content = include_template("403.php", [
        "categories" => $categories,
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lot = $_POST;
    $rules = [
        "category" => function ($value) use ($categories) {
            return validate_cat($value, $categories);
        },
        "lot-name" => function ($value) {
            return validate_text($value, 5, 100);
        },
        "lot-rate" => function ($value) {
            return validate_price($value);
        },
        "lot-step" => function ($value) {
            return validate_step($value);
        },
        "message" => function ($value) {
            return validate_text($value, 10, 1000);
        },
        "lot-date" => function ($value) {
            $is_formated = is_date_valid($value);

            if (!$is_formated) {
                return "Не верный формат даты. Формат: ГГГГ-ММ-ДД";
            }

            return validate_date($value);
        },
    ];

    $fields = [
        "category" => FILTER_DEFAULT,
        "lot-name" => FILTER_DEFAULT,
        "message" => FILTER_DEFAULT,
        "lot-rate" => FILTER_DEFAULT,
        "lot-step" => FILTER_DEFAULT,
        "lot-date" => FILTER_DEFAULT,
    ];

    $lot_checked = filter_input_array(INPUT_POST, $fields, true);
    $errors = filter_values($lot_checked, $rules);

    $errors = array_filter($errors);

    if (!empty($_FILES["lot-img"]["name"])) {
        $tmp_name = $_FILES["lot-img"]["tmp_name"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_name);
        $type = '';

        if ($mime_type == "image/jpeg") {
            $type = '.jpg';
        }

        if ($mime_type == "image/png") {
            $type = '.png';
        }

        if ($type) {
            $filename = uniqid() . $type;
            $lot["image_lot"] = $filename;
            move_uploaded_file($tmp_name, "uploads/" . $filename);
        } else {
            $errors["lot-img"] = "Не верный формат. Используйте jpg, png, jpeg";
        }

    } else {
        $errors["lot-img"] = "Файл не загружен";
    }

    if (count($errors)) {
        $content = include_template("add-lot.php", [
            "categories" => $categories,
            "errors" => $errors,
        ]);
    } else {
        $lot["author_id"] = $_SESSION["user"]["id"];
        $sql = "INSERT INTO lots (dt_add, name_lot, category_id, desc_lot, price, step, dt_end, image_lot, author_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, $lot);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $content = connect_error();
        }
    }
}


$layout = include_template("layout.php", [
    "title" => "Добавление лота",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
