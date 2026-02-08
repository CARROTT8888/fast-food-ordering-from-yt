<?php
require_once '../foodDB.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['userId']) || !isset($_POST['foodId'])) {
    echo json_encode(['success' => false]);
    exit();
}

$userId = $_SESSION['userId'];
$foodId = (int) $_POST['foodId'];

/* ⭐ 讀取 quantity（預設 1） */
$qty = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

if ($qty < 1)
    $qty = 1;


/* ===== check exists ===== */
$check = $conn->prepare("
    SELECT cartId, quantity 
    FROM carts 
    WHERE userId=? AND foodId=?
");
$check->bind_param("ii", $userId, $foodId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    /* + quantity */
    $newQty = $row['quantity'] + $qty;

    $update = $conn->prepare("
        UPDATE carts 
        SET quantity=? 
        WHERE cartId=?
    ");
    $update->bind_param("ii", $newQty, $row['cartId']);
    $update->execute();

} else {

    /* insert quantity */
    $insert = $conn->prepare("
        INSERT INTO carts (userId, foodId, quantity)
        VALUES (?, ?, ?)
    ");
    $insert->bind_param("iii", $userId, $foodId, $qty);
    $insert->execute();
}


/* ===== new count ===== */
$stmt = $conn->prepare("
    SELECT SUM(quantity) total 
    FROM carts 
    WHERE userId=?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

echo json_encode([
    'success' => true,
    'newCount' => $total
]);

