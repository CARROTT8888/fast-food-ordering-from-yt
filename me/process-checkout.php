<?php
require_once '../foodDB.php';
session_start();

if (!isset($_SESSION['userId']) || !isset($_POST['address'])) {
    header("Location: menu.php");
    exit();
}

$userId = $_SESSION['userId'];
$address = $_POST['address'];
$state = $_POST['state'];
$district = $_POST['district'];

// 1. Fetch all items in the user's cart to calculate the total
$query = "SELECT c.quantity, f.price 
          FROM carts c 
          JOIN foods f ON c.foodId = f.foodId 
          WHERE c.userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Your cart is empty!");
}

$totalAmount = 0;
while ($item = $result->fetch_assoc()) {
    $totalAmount += ($item['price'] * $item['quantity']);
}

// 2. Insert the main order record
// We use a transaction here to ensure that either everything succeeds or nothing happens
$conn->begin_transaction();
$extraNotes = isset($_POST['extraNotes']) ? $_POST['extraNotes'] : "";

try {
    // 1. Insert into orders
    $orderSql = "INSERT INTO orders (userId, address, totalAmount, extraNotes, state, district, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $orderStmt = $conn->prepare($orderSql);
    $orderStmt->bind_param("isdsss", $userId, $address, $totalAmount, $extraNotes, $state, $district);
    $orderStmt->execute();

    // Get the ID of the order we just created
    $newOrderId = $conn->insert_id;

    // 2. Move items from cart to order_items
    $moveItemsSql = "INSERT INTO order_items (orderId, foodId, quantity, priceAtPurchase)
                     SELECT ?, c.foodId, c.quantity, f.price 
                     FROM carts c 
                     JOIN foods f ON c.foodId = f.foodId 
                     WHERE c.userId = ?";
    $moveStmt = $conn->prepare($moveItemsSql);
    $moveStmt->bind_param("ii", $newOrderId, $userId);
    $moveStmt->execute();

    // 3. Clear the cart
    $clearStmt = $conn->prepare("DELETE FROM carts WHERE userId = ?");
    $clearStmt->bind_param("i", $userId);
    $clearStmt->execute();

    $conn->commit();

    // Redirect to payment instead of menu
    header("Location: payment.php?orderId=" . $newOrderId);

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}