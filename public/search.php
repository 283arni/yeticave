<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");


$categories = [];
$cards = [];
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

$text = trim((string)filter_input(INPUT_GET, "search"));

$sql = "
    SELECT COUNT(*) as cnt
    FROM lots l
    WHERE MATCH(l.name_lot, l.desc_lot) AGAINST (?)
";

$stmt = db_get_prepare_stmt($link, $sql, [$text]);

if (!mysqli_stmt_execute($stmt)) {
    $content = connect_error();
} else {
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($result)['cnt'];
    $totalPages = ceil($total / $limit);
}

// если page больше максимальной
$page = min($page, $totalPages);
$offset = ($page - 1) * $limit;

$sql = "
    SELECT
        l.id,
        l.dt_add,
        l.dt_end,
        l.name_lot,
        l.image_lot,
        l.price,
        c.name_cat,
        MATCH(l.name_lot, l.desc_lot) AGAINST (?) AS relevance
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE MATCH(l.name_lot, l.desc_lot) AGAINST (?)
    ORDER BY relevance DESC, l.dt_add DESC
    LIMIT ? OFFSET ?;
";

$stmt = db_get_prepare_stmt($link, $sql, [$text, $text, $limit, $offset]);


if (!mysqli_stmt_execute($stmt)) {
    $content = connect_error();
} else {
    $result = mysqli_stmt_get_result($stmt);
    $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $content = include_template("search.php", [
        "categories" => $categories,
        "cards" => $cards,
        "text" => $text,
        "page" => $page,
        "totalPages" => $totalPages,
    ]);
}

$layout = include_template("layout.php", [
    "title" => "Результат поиска",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
