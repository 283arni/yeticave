<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];


if(!$link) {
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
}


$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if(!$result) {
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$errors = [];

$content = include_template("add-lot.php", [
    "categories" => $categories,
]);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $lot = $_POST;
    $rules = [
        "category" => function($value) use ($categories) {
            return validateCat($value, $categories);
        },
        "lot-name" => function($value) {
            return validateText($value, 5, 100);
        },
        "lot-rate" => function($value) {
            return validatePrice($value);
        },
        "lot-step" => function($value) {
            return validateStep($value);
        },
        "message" => function($value) {
            return validateText($value, 10, 1000);
        },
        "lot-date" => function($value) {
            $is_formated = is_date_valid($value);

            if(!$is_formated) {
                return "Не верный формат даты. Формат: ГГГГ-ММ-ДД";
            }

            return validateDate($value);
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

    foreach ($lot_checked as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (!$value && empty($errors[$key])) {
            $errors[$key] = 'Заполните поле';
        }
    }

    $errors = array_filter($errors);



    if(!empty($_FILES["lot-img"]["name"])) {
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

        if($type) {
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
        $sql = "INSERT INTO lots (dt_add, name_lot, author_id, category_id, desc_lot, price, step, dt_end, image_lot) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, $lot);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $error = mysqli_error($link);
            $content = include_template("error.php", ["error" => $error]);
        }
    }
}


$layout = include_template("layout.php", [
    "title" => "Добавление лота",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
