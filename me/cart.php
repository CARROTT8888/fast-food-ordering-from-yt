<?php
require_once '../foodDB.php';
session_start();

$userId = $_SESSION['userId'];

$query = "
SELECT c.cartId, c.quantity, f.name, f.price, f.image
FROM carts c
JOIN foods f ON c.foodId = f.foodId
WHERE c.userId = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <!-- Poppins Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Boxicons CDN Link -->
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

  <!-- CSS File -->
  <link rel="stylesheet" href="/styles/homepage.css">

  <!-- Webpage Icon -->
  <link rel="Icon" href="img/youtube icon.png">

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <style>
    body {
      font-family: Poppins, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 40px;
    }

    /* layout */
    .container {
      max-width: 1100px;
      margin: auto;
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
    }

    /* CART ITEMS */
    .cart-box {
      background: white;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, .05);
    }

    .item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 12px;
    }

    .info {
      flex: 1;
    }

    .price {
      font-weight: 600;
      color: red;
    }

    .qty {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .qty button {
      width: 28px;
      height: 28px;
      border: none;
      background: #eee;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .qty button:hover {
      background: #ddd;
    }

    .subtotal {
      width: 100px;
      text-align: right;
      font-weight: 600;
      color: oklch(52.7% 0.154 150.069);
    }

    /* SUMMARY */
    .summary {
      background: white;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, .05);
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    textarea {
      width: 90%;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ddd;
      resize: none;
      margin-top: 10px;
    }

    .checkout {
      background: red;
      color: white;
      border: none;
      padding: 14px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
    }

    .checkout:hover {
      opacity: .9;
    }

    .total {
      font-size: 20px;
      font-weight: 700;
      text-align: center;
    }

    .badge {
      background-color: oklch(96.2% 0.044 156.743);
      padding-left: 10px;
      padding-right: 10px;
      border-radius: 20px;
      padding: 5px;
      font-size: 15px;
      border: 1px solid oklch(52.7% 0.154 150.069);
      color: oklch(52.7% 0.154 150.069);
    }

    .address-label {
      gap: 2px;
    }

    .required {
      color: red;
    }

    /* ===== grid layout ===== */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    /* ===== form group ===== */
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 16px;
    }

    .form-group label {
      font-size: 14px;
      font-weight: 500;
      color: #555;
    }

    /* ===== inputs ===== */
    input,
    select,
    textarea {
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #ddd;
      font-size: 14px;
      transition: .2s;
      background: #fafafa;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #ff7a00;
      box-shadow: 0 0 0 3px rgba(4, 4, 4, 0.15);
      background: white;
    }

    /* =========================
   TABLET
========================= */
    @media (max-width: 1024px) {

      .container {
        grid-template-columns: 1fr;
      }

      .summary {
        position: sticky;
        bottom: 0;
      }
    }


    /* =========================
   MOBILE
========================= */
    @media (max-width: 768px) {

      body {
        padding: 15px;
      }

      .item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .item img {
        width: 100%;
        height: 140px;
        border-radius: 12px;
      }

      .qty {
        width: 100%;
        justify-content: space-between;
      }

      .subtotal {
        width: 100%;
        text-align: right;
        font-size: 14px;
      }

      .summary {
        margin-top: 20px;
      }

      textarea {
        font-size: 14px;
      }

      .checkout {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <div class="container">

    <!-- LEFT : CART ITEMS -->
    <div class="cart-box">
      <h2>Your Cart</h2>

      <?php
      $grandTotal = 0;

      while ($item = $result->fetch_assoc()):
        $subtotal = $item['price'] * $item['quantity'];
        $grandTotal += $subtotal;

        $img = !empty($item['image'])
          ? "../uploads/menu/" . $item['image']
          : "https://via.placeholder.com/80";
        ?>

        <div class="item">
          <img src="<?= $img ?>">

          <div class="info">
            <div><?= htmlspecialchars($item['name']) ?></div>
            <div class="price">RM <?= number_format($item['price'], 2) ?></div>
          </div>

          <div class="qty">
            <button onclick="updateQty(<?= $item['cartId'] ?>,-1)">−</button>
            <?= $item['quantity'] ?>
            <button onclick="updateQty(<?= $item['cartId'] ?>,1)">+</button>
          </div>

          <div class="subtotal">
            RM <?= number_format($subtotal, 2) ?>
          </div>
        </div>

      <?php endwhile; ?>

    </div>


    <!-- RIGHT : CHECKOUT -->
    <form class="summary" action="process-checkout.php" method="POST">

      <h3>Order Summary</h3>

      <div class="total">
        Total: <span class="badge">RM <?= number_format($grandTotal, 2) ?></span>
      </div>

      <div class="form-group">
        <label for="address" class="address-label">Delivery Address <span class="required">*</span></label>
        <textarea id="address" name="address" class="form-control" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label for="extraNotes">Extra Notes</label>
        <textarea id="extraNotes" name="extraNotes" class="form-control" rows="3"></textarea>
      </div>

      <div class="form-grid">

        <!-- State -->
        <div class="form-group">
          <label for="state">Your State</label>
          <select id="state" name="state" required>
            <option value="">Select State</option>
            <option value="Johor">Johor</option>
            <option value="Kedah">Kedah</option>
            <option value="Kelantan">Kelantan</option>
            <option value="Melaka">Melaka</option>
            <option value="Negeri Sembilan">Negeri Sembilan</option>
            <option value="Pahang">Pahang</option>
            <option value="Perak">Perak</option>
            <option value="Perlis">Perlis</option>
            <option value="Pulau Pinang">Pulau Pinang</option>
            <option value="Sabah">Sabah</option>
            <option value="Sarawak">Sarawak</option>
            <option value="Selangor">Selangor</option>
            <option value="Terengganu">Terengganu</option>
            <option value="Kuala Lumpur">Kuala Lumpur</option>
            <option value="Labuan">Labuan</option>
            <option value="Putrajaya">Putrajaya</option>
          </select>
        </div>

        <!-- District -->
        <div class="form-group">
          <label for="district">District</label>
          <input id="district" type="text" name="district" placeholder="Petaling Jaya" required>
        </div>

      </div>

      <button class="checkout">Place Order</button>

    </form>

  </div>


  <script>
    function updateQty(cartId, delta) {
      fetch('update-cart-quantity.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cartId=${cartId}&delta=${delta}`
      }).then(() => location.reload());
    }
  </script>

</body>

</html>