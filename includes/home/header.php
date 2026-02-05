<style>
img {
    width: 50px;
    height: 50px;
}
.dropdown-icon {
    width: 30px;
    height: 30px;
}
</style>

<header>
    <a href="#" class="logo" style="display: flex; align-items: center; gap: 10px;">
        <?php $imageName = "youtube icon.png"; $imagePath = "img/" . $imageName; echo '<img src="' . $imagePath . '" alt="logo">';?>
        Foods
    </a>
    <div class="bx bx-menu" id="yt-icon" style="color: #fff;"></div>
    <ul class="navbar" style="align-items: center;">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#today-menu">Menu</a></li>
        <li><a href="#services">Service</a></li>
        <li><a href="#contact">Content</a></li>
        <li><a href="https://youtube.com">Video</a></li>
        <li><a href="https://music.youtube.com">Music</a></li>
        <li><a href="https://www.youtube.com/creators/top-questions/">Questions</a></li>
        <li><a href="/web/sign-in" style="font-weight: bold;">Login</a></li>
        <details class="dropdown">
            <summary role="button">
                <a class="button"
                    style="background-color: red; align-items: center; display: flex; justify-content: center;">
                    <?php $imageName = "menu.png"; $imagePath = "img/" . $imageName; echo '<img src="' . $imagePath . '" alt="dropdown menu" class="dropdown-icon">';?>
                </a>
            </summary>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#today-menu">Menu</a></li>
                <li><a href="#services">Service</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="/web/me/home">Dashboard</a></li>
                <li><a href="/web/sign-in">Login</a></li>
            </ul>
        </details>
</header>