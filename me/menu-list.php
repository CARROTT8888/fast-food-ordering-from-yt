<?php
require_once '../foodDB.php';
session_start();

if (!isset($_SESSION['userId'])) {
  header("Location: sign-in.php");
  exit();
}

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
  header("Location: warning.php");
  exit();
}

/* ===== COUNT DATA ===== */
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$totalAdmins = $conn->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetch_row()[0];
$totalStaffs = $conn->query("SELECT COUNT(*) FROM users WHERE role='staff'")->fetch_row()[0];
$totalCustomers = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];

$usersResult = $conn->query("SELECT userId, fullName, email, address, contactNumber, role FROM users ORDER BY userId");
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      font-family: Poppins, sans-serif;
    }

    body {
      background: #f5f6fa;
      margin: 0;
      padding: 30px;
    }

    /* ===== header ===== */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .logout-btn {
      background: #e53935;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
    }

    /* ===== cards ===== */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      padding: 20px;
      border-radius: 16px;
      color: white;
      box-shadow: 0 6px 14px rgba(0, 0, 0, .08);
    }

    .red {
      background: #e53935;
    }

    .orange {
      background: #fb8c00;
    }

    .blue {
      background: #1e88e5;
    }

    .green {
      background: #43a047;
    }

    .card h2 {
      margin: 0;
      font-size: 28px;
    }

    .card p {
      margin: 6px 0 0;
      font-size: 14px;
    }

    /* ===== table ===== */
    .table-wrapper {
      background: white;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 6px 14px rgba(0, 0, 0, .05);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: #e53935;
      color: white;
      padding: 12px;
      text-align: left;
    }

    td {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }

    tr:hover {
      background: #fafafa;
    }

    /* ===== role badge ===== */
    .badge {
      padding: 4px 10px;
      border-radius: 8px;
      font-size: 12px;
      color: white;
    }

    .admin {
      background: #e53935;
    }

    .staff {
      background: #fb8c00;
    }

    .user {
      background: #43a047;
    }

    /* ===== action buttons ===== */
    .action-btn {
      background: #e53935;
      color: white;
      padding: 6px 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
    }

    /* responsive */
    @media(max-width:600px) {
      body {
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <?php require_once '../includes/authorized/sidebar.php'; ?>
   <div class="header">
    <h2>Food List</h2>
  </div>

  <div class="table-wrapper">
    <table>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Active</th>
        <th>Action</th>
      </tr>

      <?php while($food = $foodsResult->fetch_assoc()): ?>
        <tr>
          <td>
            <img src="<?= htmlspecialchars($food['image']) ?>" style="width:70px;height:50px;object-fit:cover;border-radius:8px;">
          </td>
          <td><?= htmlspecialchars($food['name']) ?></td>
          <td>RM <?= number_format((float)$food['price'], 2) ?></td>
          <td><?= htmlspecialchars($food['categoryTitle'] ?? '-') ?></td>
          <td><?= htmlspecialchars($food['featured']) ?></td>
          <td><?= htmlspecialchars($food['active']) ?></td>
          <td>
            <a class="action-btn" href="edit-food.php?id=<?= (int)$food['foodId'] ?>">Edit</a>
            <a class="action-btn" style="background:#444;"
               onclick="return confirm('Delete this item?')"
               href="delete-food.php?id=<?= (int)$food['foodId'] ?>">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>