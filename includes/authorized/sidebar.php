<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Boxicons CDN Link -->
<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

<!-- CSS File -->
<link rel="stylesheet" href="/styles/homepage.css">

<!-- Webpage Icon -->
<link rel="Icon" href=".../img/youtube icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
  rel="stylesheet">

<style>
  .layout {
    display: flex;
    min-height: 100vh;
    font-family: Poppins, sans-serif;
    background: #f6f7fb;
  }


  /* ===================== */
  /* Sidebar */
  /* ===================== */
  .sidebar {
    width: 250px;
    background: whitesmoke;
    color: black;
    padding: 25px 18px;
    position: fixed;
    height: 100%;
    box-shadow: 4px 0 20px rgba(0, 0, 0, .12);
    transition: .3s;
  }

  /* logo */
  .sidebar .logo {
    font-size: 22px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 35px;
    letter-spacing: 1px;
    color: red;
  }


  /* menu */
  .menu {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .menu li {
    margin-bottom: 10px;
  }


  /* link */
  .menu a {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 12px 16px;
    border-radius: 12px;

    color: black;
    text-decoration: none;
    font-size: 14px;

    transition: .25s;
  }


  /* hover */
  .menu a:hover {
    background: rgba(255, 255, 255, .15);
    transform: translateX(6px);
  }


  /* active */
  .menu a.active {
    background: white;
    color: #d32f2f;
    font-weight: 600;
    box-shadow: 0 6px 14px rgba(0, 0, 0, .15);
  }


  /* icons */
  .menu i {
    font-size: 18px;
  }


  /* ===================== */
  /* Content */
  /* ===================== */
  .content-area {
    margin-left: 250px;
    padding: 35px;
    width: 100%;
  }


  /* ===================== */
  /* Mobile */
  /* ===================== */
  @media(max-width:768px) {

    .sidebar {
      width: 200px;
      position: fixed;
      left: -200px;
    }

    .sidebar.open {
      left: 0;
    }

    .content-area {
      margin-left: 0;
    }

    .toggle-btn {
      display: block;
    }
  }


  /* ===================== */
  /* Toggle button */
  /* ===================== */
  .toggle-btn {
    display: none;
    position: fixed;
    top: 18px;
    left: 18px;
    background: #d32f2f;
    color: white;
    border: none;
    padding: 8px 10px;
    border-radius: 8px;
    z-index: 999;
    cursor: pointer;
  }
</style>


<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

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
          <i class='bx bxs-book-add'></i> Add Category
        </a>
      </li>

      <li>
        <a href="dashboard-orders.php" class="<?= $current == 'dashboard-orders.php' ? 'active' : '' ?>">
          <i class='bx bxs-bowl-hot' ></i> Orders
        </a>
      </li>

      <li>
        <a href="dashboard-users.php" class="<?= $current == 'dashboard-users.php' ? 'active' : '' ?>">
          <i class='bx bxs-user' ></i> Users
        </a>
      </li>

      <li>
        <a href="/web/me/sign-out.php" style="color: red; font-weight: bold;">
          <i class='bx bx-log-out' ></i> Logout
        </a>
      </li>

    </ul>
  </div>


  <div class="content-area">