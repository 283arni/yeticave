<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");


$categories = [];
$cards = [];


$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if(!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$text = trim((string) filter_input(INPUT_GET, "search"));
$text = mysqli_real_escape_string($link, $text);

$sql = "
    SELECT
        l.id,
        l.dt_add,
        l.dt_end,
        l.name_lot,
        l.image_lot,
        l.price,
        c.name_cat,
        MATCH(l.name_lot, l.desc_lot) AGAINST ('$text') AS relevance
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE MATCH(l.name_lot, l.desc_lot) AGAINST ('$text')
    ORDER BY relevance DESC, l.dt_add DESC
";

$result = mysqli_query($link, $sql);

if(!$result) {
    $content = connect_error();
} else {
    $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $content = include_template("search.php", [
        "categories" => $categories,
        "cards" => $cards,
        "text" => $text,
    ]);
}

$layout = include_template("layout.php", [
    "title" => "Результат поиска",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
