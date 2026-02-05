<?php
require_once 'includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inactive_product'])) {
    $productId = $_POST['product_id'];
    $updateStmt = $conn->prepare("UPDATE product SET status = 'inactive' WHERE Product_ID = ?");
    $updateStmt->bind_param("i", $productId);
    if ($updateStmt->execute()) {
        header("Location: Lab 09 Q1.php?message=deactivated");
        ;
    } else {
        header("Location: Lab 09 Q1.php?message=error");r;
    }
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['active_product'])) {
    $productId = $_POST['product_id'];
    $updateStmt = $conn->prepare("UPDATE product SET status = 'active' WHERE Product_ID = ?");
    $updateStmt->bind_param("i", $productId);
    if ($updateStmt->execute()) {
        header("Location: Lab 09 Q1.php?message=activated");
        ;
    } else {
        header("Location: Lab 09 Q1.php?message=error");r;
    }
    exit();
}
?>
