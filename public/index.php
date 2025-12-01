<?php
require_once("helpers.php");
require_once("functions.php");
require_once("connect.php");


$categories = [];
$cards = [];
$card = [];

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

$sql = "SELECT l.id, l.dt_add, l.dt_end, l.name_lot, l.image_lot, l.price, c.name_cat FROM lots l " .
        "JOIN categories c ON l.category_id = c.id " .
        "WHERE l.dt_end > NOW() " .
        "ORDER BY l.dt_add DESC LIMIT 6";

$result = mysqli_query($link, $sql);

if(!$result) {
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
} else {
    $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$content = include_template("main.php", [
    "cards" => $cards,
    "categories" => $categories,
]);

$layout = include_template("layout.php", [
    "title" => "Главная страница",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
