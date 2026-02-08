<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

<style>
  /* =========================
   Layout
========================= */
  .layout {
    display: flex;
    min-height: 100vh;
    background: #f6f7fb;
    font-family: Poppins, sans-serif;
  }

  /* =========================
   Sidebar
========================= */
  .sidebar {
    width: 260px;
    background: #ffffff;
    padding: 25px 18px;
    position: fixed;
    height: 100%;
    left: 0;
    top: 0;
    box-shadow: 4px 0 18px rgba(0, 0, 0, .08);
    transition: .3s ease;
    z-index: 1000;
  }

  /* logo */
  .logo {
    font-size: 20px;
    font-weight: bold;
    text-align: left;
    margin-bottom: 35px;
    color: #e53935;
  }

  /* menu */
  .menu {
    list-style: none;
    padding: 0;
  }

  .menu li {
    margin-bottom: 8px;
  }

  .menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 12px;
    text-decoration: none;
    color: #444;
    font-size: 14px;
    transition: .2s;
  }

  /* hover */
  .menu a:hover {
    background: #f2f2f2;
    transform: translateX(4px);
  }

  /* active */
  .menu a.active {
    background: #e53935;
    color: white;
    font-weight: 600;
  }

  /* icons */
  .menu i {
    font-size: 18px;
  }

  /* =========================
   Content
========================= */
  .content-area {
    margin-left: 260px;
    padding: 30px;
    width: 100%;
    transition: .3s;
  }

  /* =========================
   Toggle Button
========================= */
  .toggle-btn {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    background: #e53935;
    color: white;
    border: none;
    padding: 8px 10px;
    border-radius: 8px;
    z-index: 1100;
    cursor: pointer;
  }

  /* =========================
   Overlay (mobile)
========================= */
  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .35);
    display: none;
    z-index: 900;
  }

  /* =========================
   Mobile Responsive
========================= */
  @media(max-width:768px) {

    .toggle-btn {
      display: block;
    }

    .sidebar {
      left: -260px;
    }

    .sidebar.open {
      left: 0;
    }

    .content-area {
      margin-left: 0;
    }

    .overlay.show {
      display: block;
    }
  }
</style>


<button class="toggle-btn" onclick="toggleSidebar()">
  <i class='bx bx-menu'></i>
</button>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="layout">

  <div class="sidebar" id="sidebar">

    <div class="logo">YouTube Food</div>

    <ul class="menu">

      <li>
        <a href="dashboard.php" class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">
          <i class='bx bxs-dashboard'></i> Dashboard
        </a>
      </li>

      <li>
        <a href="menu-list.php" class="<?= $current == 'menu-list.php' ? 'active' : '' ?>">
          <i class='bx bxs-food-menu'></i> Food List
        </a>
      </li>

      <li>
        <a href="dashboard-category.php" class="<?= $current == 'dashboard-category.php' ? 'active' : '' ?>">
          <i class='bx bxs-category'></i> Categories
        </a>
      </li>

      <li>
        <a href="add-category.php" class="<?= $current == 'add-category.php' ? 'active' : '' ?>">
          <i class='bx bx-plus'></i> Add Category
        </a>
      </li>

      <li>
        <a href="add-food-item.php" class="<?= $current == 'add-food-item.php' ? 'active' : '' ?>">
          <i class='bx bxs-book-add'></i> Add Food Item
        </a>
      </li>

      <li>
        <a href="dashboard-orders.php" class="<?= $current == 'dashboard-orders.php' ? 'active' : '' ?>">
          <i class='bx bxs-receipt'></i> Orders
        </a>
      </li>

      <li>
        <a href="dashboard-users.php" class="<?= $current == 'dashboard-users.php' ? 'active' : '' ?>">
          <i class='bx bxs-user'></i> Users
        </a>
      </li>

      <li>
        <a href="/web/me/sign-out.php" style="color:#e53935;font-weight:600;">
          <i class='bx bx-log-out'></i> Logout
        </a>
      </li>

    </ul>
  </div>

  <div class="content-area">


    <script>
      function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('show');
      }
    </script>