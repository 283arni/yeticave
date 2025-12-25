<?php
require_once("helpers.php");
require_once("functions.php");
require_once("connect.php");


$categories = [];
$cards = [];
$card = [];

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

$sql = "SELECT COUNT(*) AS cnt FROM lots WHERE lots.dt_end > NOW()";

$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $total = mysqli_fetch_assoc($result)['cnt'];
    $totalPages = ceil($total / $limit);
}

// если page больше максимальной
$page = min($page, $totalPages);
$offset = ($page - 1) * $limit;

$sql = "SELECT l.id, l.dt_add, l.dt_end, l.name_lot, l.image_lot, l.price, c.name_cat FROM lots l " .
    "JOIN categories c ON l.category_id = c.id " .
    "WHERE l.dt_end > NOW() " .
    "ORDER BY l.dt_add DESC LIMIT ? OFFSET ?";

$stmt = db_get_prepare_stmt($link, $sql, [$limit, $offset]);

if (!mysqli_stmt_execute($stmt)) {
    $content = connect_error();
} else {
    $result = mysqli_stmt_get_result($stmt);
    $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $content = include_template("main.php", [
        "cards" => $cards,
        "categories" => $categories,
        "page" => $page,
        "totalPages" => $totalPages,
    ]);
}

$layout = include_template("layout.php", [
    "title" => "Главная страница",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
