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
  <!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Boxicons CDN Link -->
<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

<!-- CSS File -->
<link rel="stylesheet" href="/styles/homepage.css">
<title>Dashboard - User List</title>

<!-- Webpage Icon -->
<link rel="Icon" href="../img/youtube icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
  rel="stylesheet">

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
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
  </div>


  <!-- ===== STAT CARDS ===== -->
  <div class="cards">
    <div class="card red">
      <h2><?= $totalUsers ?></h2>
      <p>Total Users</p>
    </div>

    <div class="card orange">
      <h2><?= $totalAdmins ?></h2>
      <p>Admins</p>
    </div>

    <div class="card blue">
      <h2><?= $totalStaffs ?></h2>
      <p>Staffs</p>
    </div>

    <div class="card green">
      <h2><?= $totalCustomers ?></h2>
      <p>Customers</p>
    </div>
  </div>


  <!-- ===== USERS TABLE ===== -->
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Address</th>
          <th>Contact</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php while ($user = $usersResult->fetch_assoc()): ?>
          <tr>
            <td><?= $user['userId'] ?></td>
            <td><?= htmlspecialchars($user['fullName']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['address'] ?: '-' ?></td>
            <td><?= $user['contactNumber'] ?></td>
            <td>
              <span class="badge <?= $user['role'] ?>">
                <?= $user['role'] ?>
              </span>
            </td>
            <td>
              <button class="action-btn">Edit</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>

</html>