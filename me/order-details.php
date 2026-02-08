<?php
require_once '../foodDB.php';
$orderId = (int)$_GET['id'];

/* ===== update status ===== */
if (isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $uStmt = $conn->prepare("UPDATE orders SET status = ? WHERE orderId = ?");
    $uStmt->bind_param("si", $newStatus, $orderId);
    $uStmt->execute();
}

/* ===== fetch order ===== */
$orderStmt = $conn->prepare("SELECT * FROM orders WHERE orderId = ?");
$orderStmt->bind_param("i", $orderId);
$orderStmt->execute();
$order = $orderStmt->get_result()->fetch_assoc();

/* ===== fetch items ===== */
$itemsStmt = $conn->prepare("
    SELECT oi.*, f.name, f.image
    FROM order_items oi
    JOIN foods f ON oi.foodId = f.foodId
    WHERE oi.orderId = ?
");
$itemsStmt->bind_param("i", $orderId);
$itemsStmt->execute();
$items = $itemsStmt->get_result();
?>


<!DOCTYPE html>
<html>
<head>
<title>Order #<?= $orderId ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body{
  background:#f6f7fb;
  font-family:Poppins,sans-serif;
  padding:30px;
}

.container{
  max-width:900px;
  margin:auto;
}

/* ===== card ===== */
.card{
  background:white;
  padding:22px;
  border-radius:18px;
  box-shadow:0 6px 16px rgba(0,0,0,.08);
  margin-bottom:25px;
}

/* ===== header ===== */
.order-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.badge{
  padding:6px 14px;
  border-radius:20px;
  color:white;
  font-size:12px;
  font-weight:600;
}
.paid{background:#43a047;}
.progress{background:#fb8c00;}
.shipping{background:#1e88e5;}
.delivered{background:#9e9e9e;}

/* ===== items ===== */
.item{
  display:flex;
  align-items:center;
  gap:15px;
  padding:12px 0;
  border-bottom:1px solid #eee;
}

.item img{
  width:70px;
  height:55px;
  object-fit:cover;
  border-radius:10px;
}

.item-info{
  flex:1;
}

.price{
  font-weight:600;
  color:#e53935;
}

/* ===== status form ===== */
select,button{
  padding:10px 14px;
  border-radius:10px;
  border:1px solid #ddd;
  font-family:Poppins;
}

button{
  background:#e53935;
  color:white;
  border:none;
  cursor:pointer;
}

button:hover{opacity:.9;}
</style>
</head>

<body>

<div class="container">

  <!-- ===== order summary ===== -->
  <div class="card">
    <div class="order-header">
      <h2>Order #<?= $orderId ?></h2>
      <span class="badge <?= $order['status'] ?>">
        <?= strtoupper($order['status']) ?>
      </span>
    </div>

    <p><strong>📍 Address:</strong><br>
      <?= $order['address'] ?>, <?= $order['district'] ?>, <?= $order['state'] ?>
    </p>

    <?php if($order['extraNotes']): ?>
    <p><strong>📝 Notes:</strong><br><?= htmlspecialchars($order['extraNotes']) ?></p>
    <?php endif; ?>
  </div>

  <!-- ===== items ===== -->
  <div class="card">
    <h3>🍽 Items Ordered</h3>

    <?php while($item = $items->fetch_assoc()): 
      $img = $item['image']
        ? "../uploads/menu/".$item['image']
        : "https://via.placeholder.com/80";
    ?>
    <div class="item">
      <img src="<?= $img ?>">
      <div class="item-info">
        <strong><?= htmlspecialchars($item['name']) ?></strong><br>
        Qty: <?= $item['quantity'] ?>
      </div>
      <div class="price">
        RM <?= number_format($item['priceAtPurchase'],2) ?>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

  <!-- ===== status update ===== -->
  <div class="card">
    <h3>⚙️ Update Order Status</h3>

    <form method="POST" style="display:flex;gap:12px;align-items:center;">
      <select name="status">
        <option value="paid" <?= $order['status']=='paid'?'selected':'' ?>>Paid</option>
        <option value="progress" <?= $order['status']=='progress'?'selected':'' ?>>Preparing</option>
        <option value="done" <?= $order['status']=='done'?'selected':'' ?>>Done</option>
        <option value="shipping" <?= $order['status']=='shipping'?'selected':'' ?>>Delivering</option>
        <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Completed</option>
        <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
      </select>
      <button name="update_status">Update Status</button>
    </form>
  </div>

</div>

</body>
</html>
