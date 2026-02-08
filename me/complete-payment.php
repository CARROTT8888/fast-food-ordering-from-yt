<?php
require_once '../foodDB.php';
session_start();

// 1. Change $_GET to $_POST because your form uses method="POST"
if (!isset($_POST['orderId'])) {
    die("Invalid Order ID. No data received.");
}

$orderId = (int)$_POST['orderId'];
$method = $_POST['method'] ?? 'Unknown';

// 2. Prepare the statement
// If they chose "Cash", you might want the status to be 'pending_cash' instead of 'paid'
$newStatus = ($method === 'Cash') ? 'ordered_cash' : 'paid';

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE orderId = ?");
$stmt->bind_param("si", $newStatus, $orderId); // "s" for string status, "i" for int ID

// 3. Execute
if ($stmt->execute()) {
    echo "<script>
            alert('Payment Successful via " . $method . "! Your food is on the way.');
            window.location.href = 'menu.php';
          </script>";
} else {
    echo "Payment failed. Please contact support.";
}
?>