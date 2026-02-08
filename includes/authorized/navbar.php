<style>
img {
    width: 50px;
    height: 50px;
}
.dropdown-icon {
    width: 30px;
    height: 30px;
}
.logout {
    font-weight: bold;
}
</style>

<header>
    <a href="#" class="logo" style="display: flex; align-items: center; gap: 10px;">
        <?php $imageName = "youtube icon.png"; $imagePath = "../img/" . $imageName; echo '<img src="' . $imagePath . '" alt="logo">';?>
        Foods
    </a>
    <div class="bx bx-menu" id="yt-icon" style="color: #fff;"></div>
    <ul class="navbar" style="align-items: center;">
        <li><a href="menu.php">Menu</a></li>
        <li><a href="/web/me/cart">Cart</a></li>
        <li><a href="/web/me/profile">Profile</a></li>
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
            <li><a href="/web/me/dashboard">Dashboard</a></li>
        <?php endif; ?>
        <li><a style="font-weight: bold; color: red;" href="/web/me/sign-out.php">Logout</a></li>
        <details class="dropdown">
            <summary role="button">
                <a class="button"
                    style="background-color: red; align-items: center; display: flex; justify-content: center;">
                    <?php $imageName = "menu.png"; $imagePath = "../img/" . $imageName; echo '<img src="' . $imagePath . '" alt="dropdown menu" class="dropdown-icon">';?>
                </a>
            </summary>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#today-menu">Menu</a></li>
                <li><a href="/web/me/profile">Profile</a></li>
                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                    <li><a href="/web/me/dashboard">Dashboard</a></li>
                <?php endif; ?>
                <li><a class="logout" style="" href="/web/me/sign-out.php">Logout</a></li>
            </ul>
        </details>
</header>