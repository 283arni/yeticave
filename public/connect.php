<?php
session_start();

$link = mysqli_connect("MySQL-8.0:3306", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

function connect_error(): string
{
    $error = mysqli_connect_error();

    return include_template("error.php", ["error" => $error]);
}

if (!$link) {
    $connect = connect_error();
}
