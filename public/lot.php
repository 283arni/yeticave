<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];
$card = [];
$page_error = include_template("404.php", ["categories" => $categories,]);

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if(!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if($id) {
    $sql = "SELECT l.id, l.dt_add, l.dt_end, l.desc_lot, l.name_lot, l.image_lot, l.price, c.name_cat FROM lots l " .
        "JOIN categories c ON l.category_id = c.id " .
        "WHERE l.id =" . $id;
} else {
    print($page_error);
    die();
}

$result = mysqli_query($link, $sql);

if(!mysqli_num_rows($result)) {
    $content = connect_error();
} else {
    $card = mysqli_fetch_assoc($result);
    $content = include_template("lot.php", [
        "categories" => $categories,
        "card" => $card,
    ]);
}


$layout = include_template("layout.php", [
    "title" => $card["name_lot"] ?? "Страница лота",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
?>
