<?php
require_once '../foodDB.php';
session_start();

$orderId = $_GET['orderId'] ?? 0;

$stmt = $conn->prepare("SELECT totalAmount FROM orders WHERE orderId = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['totalAmount'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: Poppins, sans-serif;
        }

        body {
            background: #f6f6f6;
            margin: 0;
            padding: 40px 20px;
        }

        /* ===== container ===== */
        .payment-wrapper {
            max-width: 900px;
            margin: auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* ===== cards ===== */
        .card {
            background: white;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
        }

        /* ===== title ===== */
        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* ===== order summary ===== */
        .total-box {
            background: #fff7ef;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: red;
        }

        /* ===== payment buttons ===== */
        .pay-option {
            border: 2px solid #eee;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .pay-option:hover {
            border-color: red;
            background: #fff7ef;
        }

        .pay-option input {
            transform: scale(1.3);
        }

        /* ===== pay button ===== */
        .pay-btn {
            width: 100%;
            background: red;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
        }

        .pay-btn:hover {
            background: #e56700;
        }

        /* ===== responsive ===== */
        @media(max-width:768px) {
            .payment-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="payment-wrapper">

        <!-- LEFT : payment -->
        <div class="card">
            <div class="title">💳 Choose Payment Method</div>

            <form method="POST" action="complete-payment.php">
                <input type="hidden" name="orderId" value="<?= $orderId ?>">

                <label class="pay-option">
                    <input type="radio" name="method" value="Visa" required>
                    💳 Credit / Debit Card (Visa / Master)
                </label>

                <label class="pay-option">
                    <input type="radio" name="method" value="Ewallet">
                    📱 TNG / GrabPay / Boost
                </label>

                <label class="pay-option">
                    <input type="radio" name="method" value="Cash">
                    💵 Cash on Delivery
                </label>

                <button type="submit" class="pay-btn">
                    Pay RM <?= number_format($total, 2) ?>
                </button>
            </form>
        </div>


        <!-- RIGHT : summary -->
        <div class="card">
            <div class="title">🧾 Order Summary</div>

            <p>Order ID: <strong>#<?= $orderId ?></strong></p>

            <div class="total-box">
                <div>Total Amount</div>
                <div class="total-amount">
                    RM <?= number_format($total, 2) ?>
                </div>
            </div>

            <p style="font-size:13px;color:#888">
                Secure payment powered by your system.<br>
                All transactions are encrypted.
            </p>
        </div>

    </div>

</body>

</html>