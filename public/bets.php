<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];
$bets = [];
$id = $_SESSION['user']["id"] ?? null;

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


if (!$id) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT l.id, l.dt_add, l.dt_end, l.desc_lot, l.name_lot, l.image_lot, l.winner_id, c.name_cat, b.dt_add, b.price, u.contact FROM lots l " .
    "JOIN categories c ON l.category_id = c.id " .
    "JOIN bets b ON b.lot_id = l.id " .
    "JOIN users u ON u.id = l.author_id " .
    "WHERE b.user_id = " . $id .
    " ORDER BY b.dt_add DESC";


$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$content = include_template("my-bets.php", [
    "categories" => $categories,
    "bets" => $bets,
]);

$layout = include_template("layout.php", [
    "title" => "Мои ставки",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);
