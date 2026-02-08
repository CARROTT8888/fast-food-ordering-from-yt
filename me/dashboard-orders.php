<?php
require_once '../foodDB.php';
session_start();

// In a real app, check if user is an admin here
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Order Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* ===== grid layout ===== */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        /* ===== card ===== */
        .order-card {
            background: white;
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, .06);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: .2s;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
        }

        /* ===== header ===== */
        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-id {
            font-weight: 600;
            font-size: 18px;
        }

        /* ===== info ===== */
        .order-info p {
            margin: 3px 0;
            font-size: 14px;
            color: #555;
        }

        /* ===== status badge ===== */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .paid {
            background: #43a047;
        }

        .pending {
            background: #fb8c00;
        }

        .cancelled {
            background: #e53935;
        }

        /* ===== button ===== */
        .btn-view {
            margin-top: auto;
            text-align: center;
            background: #e53935;
            color: white;
            padding: 8px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-view:hover {
            opacity: .9;
        }
    </style>
</head>

<body style="font-family:Poppins,sans-serif;background:#f6f7fb;padding:30px;">

    <h2 style="margin-bottom:20px;">📦 Order Management</h2>

    <div class="orders-grid">

        <?php
        $sql = "SELECT * FROM orders ORDER BY createdAt DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()):

            $status = strtolower($row['status']);
            ?>

            <div class="order-card">

                <div class="order-top">
                    <div class="order-id">#<?= $row['orderId'] ?></div>
                    <div class="badge status-<?= $status ?>">
                        <?= strtoupper($row['status']) ?>
                    </div>
                </div>

                <div class="order-info">
                    <p><strong>User:</strong> <?= $row['userId'] ?></p>
                    <p><strong>Total:</strong> RM <?= number_format($row['totalAmount'], 2) ?></p>
                    <p><strong>Location:</strong> <?= $row['district'] ?>, <?= $row['state'] ?></p>
                    <p><strong>Date:</strong> <?= $row['createdAt'] ?></p>
                </div>

                <a href="order-details.php?id=<?= $row['orderId'] ?>" class="btn-view">
                    View Details →
                </a>

            </div>

        <?php endwhile; ?>

    </div>
</body>

</html>