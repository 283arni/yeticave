<?php
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

$content = include_template("add-lot.php", [
    "categories" => $categories,
]);


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $lot = $_POST;
    $filename = uniqid() . '.jpg';
    $lot["image_lot"] = $filename;
    move_uploaded_file($_FILES["lot-img"]["tmp_name"], "uploads/" . $filename);

    $sql = "INSERT INTO lots (dt_add, name_lot, author_id, category_id, desc_lot, price, step, dt_end, image_lot) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $result = mysqli_stmt_execute($stmt);

    if(!$result) {
        $error = mysqli_error($link);
        $content = include_template("error.php", ["error" => $error]);
    } else {
        $lot_id = mysqli_insert_id($link);

        header("Location: lot.php?id=" . $lot_id);
    }

}

$layout = include_template("layout.php", [
    "title" => "Добавление лота",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
