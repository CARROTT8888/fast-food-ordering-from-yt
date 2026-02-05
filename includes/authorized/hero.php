<section style="position: relative; margin-top: 10rem;">
    <div class="hero-text" style="display: flex; justify-content: center; flex-direction: column; align-items: center; text-align: center;">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['fullName']) ?>!</span>
        <h2 style="font-weight: 500;">You may start order foods now!</h2>
        <a href="menu.php">
            <button style="background-color:red; padding: 15px; width: 150px; color: white; font-weight: 500; position: relative; top: 10px; font-size: 15px; cursor: pointer; border-radius: 10px; border: none;">Order Now!</button>
        </a>
    </div>
</section>