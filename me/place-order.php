<?php
require_once '../foodDB.php';
session_start();

// Get the JSON data from the fetch request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in or invalid data']);
    exit;
}

$userId = $_SESSION['userId'];
$total = $input['total'];
$address = $input['address'];
$orderDate = date('Y-m-d H:i:s');
$status = "pending";

// 1. Insert into 'orders' table
$sqlOrder = "INSERT INTO orders (total, orderDate, status, address, userId) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlOrder);
$stmt->bind_param("dsssi", $total, $orderDate, $status, $address, $userId);

if ($stmt->execute()) {
    $orderId = $conn->insert_id; // Get the ID of the order we just created

    // 2. Insert each item into 'order_items' table
    $sqlItems = "INSERT INTO order_items (orderId, foodId, quantity, price) VALUES (?, ?, ?, ?)";
    $itemStmt = $conn->prepare($sqlItems);

    foreach ($input['items'] as $item) {
        $itemStmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
        $itemStmt->execute();
    }

    echo json_encode(['success' => true, 'orderId' => $orderId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>