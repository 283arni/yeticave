<?php
require_once("functions.php");
require_once("helpers.php");
require_once("connect.php");

$categories = [];
$card = [];
$bets = [];
$errors = [];
$page_error = include_template("404.php", ["categories" => $categories,]);

$sql = "SELECT * FROM categories";

$result = mysqli_query($link, $sql);

if (!$result) {
    $content = connect_error();
} else {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "SELECT l.id, l.dt_add, l.dt_end, l.desc_lot, l.name_lot, l.image_lot, l.price, l.step, l.author_id, c.name_cat FROM lots l " .
        "JOIN categories c ON l.category_id = c.id " .
        "WHERE l.id =" . $id;
    $sql_bets = "SELECT b.price, b.dt_add, u.name_user FROM bets b JOIN  users u ON b.user_id = u.id WHERE b.lot_id =" .
        $id .
        " ORDER BY b.dt_add DESC";
} else {
    print($page_error);
    die();
}

$result = mysqli_query($link, $sql);
$result_bets = mysqli_query($link, $sql_bets);

if (!mysqli_num_rows($result)) {
    $content = connect_error();
} else {
    $card = mysqli_fetch_assoc($result);
    $bets = mysqli_fetch_all($result_bets, MYSQLI_ASSOC);
    $content = include_template("lot.php", [
        "categories" => $categories,
        "card" => $card,
        "bets" => $bets,
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION["user"]["id"];
    $min_bet = $card["step"];

    $cost = filter_input(INPUT_POST, "cost", FILTER_SANITIZE_NUMBER_INT);

    if (!$cost) {
        $errors["cost"] = "Введите сумму";

    } else {
        if ($cost < $min_bet) {
            $errors["cost"] = "Ставка должна быть '$min_bet' или больше.";
        }
    }

    if (empty($errors)) {
        $new_price = $card["price"] + $cost;
        $data = [
            $new_price,
            $id,
            $id_user
        ];

        $data2 = [
            $new_price,
            $id,
        ];

        $sql = "INSERT INTO bets (dt_add, price, lot_id, user_id) VALUES (NOW(), ?, ?, ?)";
        $sql2 = "UPDATE lots SET price = ? WHERE id = ?";

        mysqli_begin_transaction($link);

        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $stmt2 = db_get_prepare_stmt($link, $sql2, $data2);
        $result = mysqli_stmt_execute($stmt);
        $result2 = mysqli_stmt_execute($stmt2);

        if (!$result || !$result2) {
            mysqli_rollback($link);
            $content = mysqli_stmt_error($stmt);
        } else {
            mysqli_commit($link);
            header('Location: lot.php?id=' . $id);
            exit;
        }
    } else {
        $content = include_template("lot.php", [
            "categories" => $categories,
            "card" => $card,
            "errors" => $errors,
        ]);
    }
}


$layout = include_template("layout.php", [
    "title" => $card["name_lot"] ?? "Страница лота",
    "content" => $content,
    "categories" => $categories,
]);

print($layout);

