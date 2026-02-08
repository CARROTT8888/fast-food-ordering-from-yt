<?php
require_once '../foodDB.php';
session_start();

if (!isset($_SESSION['userId']) || !isset($_POST['cartId'])) {
    exit('Unauthorized');
}

$cartId = (int)$_POST['cartId'];
$delta = (int)$_POST['delta'];
$userId = $_SESSION['userId'];

// 1. Update the quantity, but only for the logged-in user (Security!)
$query = "UPDATE carts SET quantity = quantity + ? WHERE cartId = ? AND userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $delta, $cartId, $userId);
$stmt->execute();

// 2. Cleanup: If quantity reaches 0 or less, remove the item from cart
$cleanup = $conn->prepare("DELETE FROM carts WHERE quantity <= 0 AND userId = ?");
$cleanup->bind_param("i", $userId);
$cleanup->execute();

echo json_encode(['success' => true]);