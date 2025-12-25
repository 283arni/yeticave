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
    $error = mysqli_connect_error();

    $content = include_template("error.php", ["error" => $error]);
}

$content = include_template("sign-up.php", [
    "categories" => $categories,
]);

if (isset($_SESSION["user"])) {
    $content = include_template("authorized.php", [
        "categories" => $categories,
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST;
    $rules = [
        "password" => function ($value) {
            return validate_text($value, 6, 20);
        },
        "name" => function ($value) {
            return validate_text($value, 3, 30);
        },
        "message" => function ($value) {
            return validate_text($value, 5, 1000);
        }
    ];

    $fields = [
        "email" => FILTER_VALIDATE_EMAIL,
        "name" => FILTER_DEFAULT,
        "message" => FILTER_DEFAULT,
        "password" => FILTER_DEFAULT,
    ];

    $user_checked = filter_input_array(INPUT_POST, $fields, true);
    $errors = filter_values($user_checked, $rules);

    if (isset($user_checked['email']) && $user_checked['email'] === false) {
        $errors['email'] = 'Некорректный Email';
    }

    $name = mysqli_real_escape_string($link, $user_checked["name"]);
    $email = mysqli_real_escape_string($link, $user_checked["email"]);

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $errors["email"] = "Пользователь с такой почтой уже есть";
    } else {
        if ($result === false) {
            $errors['db'] = mysqli_error($link);
        }
    }

    $sql = "SELECT id FROM users WHERE name_user = '$name'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $errors["name"] = "Пользователь с таким именем уже есть";
    } else {
        if ($result === false) {
            $errors['db'] = mysqli_error($link);
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $content = include_template("sign-up.php", [
            "categories" => $categories,
            "errors" => $errors,
        ]);
    } else {
        $user["password"] = password_hash($user["password"], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (dt_add, email, password_user, name_user, contact) VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, $user);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: /login.php");
            exit;
        } else {
            $content = mysqli_stmt_error($stmt);
        }
    }
}

$layout = include_template("layout.php", [
    "title" => "Регистрация",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
