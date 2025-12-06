<?php
require_once("function.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];

if (!$link) {
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
}

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
}

$content = include_template("sign-up.php", [
    "categories" => $categories,
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST;
    $rules = [
        "password" => function () {
            return validateText($value, 6, 20);
        },
        "name" => function ($value) {
            return validateText($value, 3, 30);
        },
        "message" => function ($value) {
            return validateText($value, 5, 1000);
        }
    ];

    $fields = [
        "email" => FILTER_VALIDATE_EMAIL,
        "name" => FILTER_DEFAULT,
        "message" => FILTER_DEFAULT,
        "password" => FILTER_DEFAULT,
    ];

}


$layout = include_template("layout.php", [
    "title" => "Авторизация",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
