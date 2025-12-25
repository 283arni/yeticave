<?php

require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $content = connect_error();
}

$content = include_template("login.php", [
    "categories" => $categories,
]);

if (isset($_SESSION["user"])) {
    $content = include_template("authorized.php", [
        "categories" => $categories,
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = [];
    $log = $_POST;
    $rules = [
        "password" => function ($value) {
            return validate_text($value, 6, 20);
        },
    ];

    $log_checked = filter_input_array(INPUT_POST, [
        "email" => FILTER_VALIDATE_EMAIL,
        "password" => FILTER_DEFAULT
    ], true);

    $errors = filter_values($log_checked, $rules);
    $errors = array_filter($errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($link, $log['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);

        if (!$result) {
            $content = connect_error();

        } else {
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                if (!$user || !password_verify($log['password'], $user['password_user'])) {
                    $errors["log"] = "Не верный логин или пароль";
                }
            } else {
                $errors["log"] = "Не верный логин или пароль";
            }
        }
    }

    if (count($errors)) {
        $content = include_template("login.php", [
            "errors" => $errors,
            "categories" => $categories,
        ]);
    } else {

        $_SESSION["user"] = $user;
        header("location: /");
        exit;
    }
}

$layout = include_template("layout.php", [
    "title" => "Вход на сайт",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
