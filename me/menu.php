<?php
require_once '../foodDB.php';
session_start();

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu</title>
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS File -->
    <link rel="stylesheet" href="/styles/homepage.css">

    <!-- Webpage Icon -->
    <link rel="Icon" href="../img/youtube icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Poppins, sans-serif
        }

        body {
            background: #f6f6f6
        }

        /* HEADER */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 40px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 22px;
            font-weight: 600
        }

        .search {
            width: 40%;
        }

        .search input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 30px;
            border: 1px solid #ddd
        }

        .cart-btn {
            background: #ff7a00;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 30px;
            cursor: pointer
        }

        /* CATEGORY */
        .categories {
            display: flex;
            gap: 12px;
            padding: 20px 40px;
            overflow-x: auto;
        }

        .categories a {
            border: none;
            background: lightgray;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            color: black;
            text-decoration: none;
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
            padding: 30px 40px 80px;
            align-items: stretch;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .06);
            transition: .2s;
            height: 300px;
        }

        .card:hover {
            transform: translateY(-5px)
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover
        }

        .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            /* Stack title, desc, and button vertically */
            flex: 1;
            /* This makes the body fill the remaining space of the card */
        }

        .card-desc {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 10px;
        }

        .card-body {
            padding: 16px
        }

        .price {
            color: #ff7a00;
            font-weight: 600;
            margin: 8px 0
        }

        .add-btn {
            background: #ff7a00;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 12px;
            cursor: pointer
        }

        .cart {
            position: fixed;
            right: -350px;
            top: 0;
            width: 350px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 15px rgba(0, 0, 0, .15);
            padding: 20px;
            transition: .3s;
            display: flex;
            flex-direction: column
        }

        .cart.open {
            right: 0
        }

        .cart-items {
            flex: 1;
            overflow: auto;
            margin-top: 15px
        }

        .checkout {
            background: #ff7a00;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">🍔 FastFood</div>
        <div class="search">
            <input type="text" placeholder="Search food..." oninput="searchFood(this.value)">
        </div>
        <button class="cart-btn" onclick="toggleCart()">Cart (<span id="cartCount">0</span>)</button>
    </header>

    <div class="categories" id="categoryTabs">
        <a href="menu.php" class="cat-link <?= !isset($_GET['cat']) ? 'active' : '' ?>">All</a>
        <?php
        $catQuery = "SELECT * FROM food_categories";
        $catResult = $conn->query($catQuery);
        while ($cat = $catResult->fetch_assoc()):
            $isActive = (isset($_GET['cat']) && $_GET['cat'] == $cat['categoryId']) ? 'active' : '';
            ?>
            <a href="menu.php?cat=<?= $cat['categoryId'] ?>" class="cat-link <?= $isActive ?>">
                <?= htmlspecialchars($cat['title']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="grid" id="foodGrid">
        <?php
        // Filter logic
        $catFilter = isset($_GET['cat']) ? " AND categoryId = " . (int) $_GET['cat'] : "";
        $menuQuery = "SELECT * FROM foods WHERE active = 'active'" . $catFilter . " ORDER BY foodId DESC";
        $menuResult = $conn->query($menuQuery);

        if ($menuResult->num_rows > 0):
            while ($data = $menuResult->fetch_assoc()):
                ?>
                <div class="card">
                    <?php
                    $imgPath = !empty($data['image']) ? "../uploads/menu/" . $data['image'] : "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYXJnaGVyaXRhJTIwcGl6emF8ZW58MXx8fHwxNzcwMTEzNjk2fDA&ixlib=rb-4.1.0&q=80&w=1080";
                    ?>
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($data['name']) ?>">

                    <div class="card-body">
                        <h3><?= htmlspecialchars($data['name']) ?></h3>

                        

                        <div class="card-footer">
                            <div class="price">RM <?= number_format($data['price'], 2) ?></div>
                            <button class="add-btn"
                                onclick="addToCart(<?= $data['foodId'] ?>, '<?= addslashes($data['name']) ?>', <?= $data['price'] ?>)">
                                + Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        else:
            echo "<p style=''>No items found in this category.</p>";
        endif;
        ?>
    </div>

</body>

</html>