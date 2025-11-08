<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");

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
?>
