<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];
$type_cat = $_GET["category"];

// Пагинация
$limit = 6;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


if (!$type_cat) {
    $content = connect_error();
} else {
    $sql = "
    SELECT COUNT(*) AS cnt
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE code_cat = ?
    ";

    $stmt = db_get_prepare_stmt($link, $sql, [$type_cat]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($result)['cnt'];

    $totalPages = ceil($total / $limit);

    // если page больше максимальной
    $page = min($page, $totalPages);
    $offset = ($page - 1) * $limit;
    $sql = "SELECT l.id, l.dt_add, l.dt_end, l.name_lot, l.image_lot, l.price, c.name_cat FROM lots l JOIN categories c ON l.category_id = c.id WHERE code_cat = ? LIMIT ? OFFSET ?";

    $stmt = db_get_prepare_stmt($link, $sql, [$type_cat, $limit, $offset]);


    if (!mysqli_stmt_execute($stmt)) {
        $content = include_template("all-lots.php", [
            "categories" => $categories,
            "type_cat" => $type_cat,
            "totalPages" => 0,
        ]);
    } else {
        $result = mysqli_stmt_get_result($stmt);
        $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $content = include_template("all-lots.php", [
            "categories" => $categories,
            "cards" => $cards,
            "type_cat" => $type_cat,
            "page" => $page,
            "totalPages" => $totalPages,
        ]);
    }
}

$layout = include_template("layout.php", [
    "title" => "Все лоты",
    "categories" => $categories,
    "content" => $content,
]);

print($layout);
