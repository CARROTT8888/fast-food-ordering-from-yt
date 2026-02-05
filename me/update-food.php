<?php
require_once '../foodDB.php';

$error_msg = "";
$success_msg = "";
$food = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM foods WHERE foodId = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $food = $result->fetch_assoc();
    } else {
        die("<div class=''>Error: Food ID not found. <a href='dashboard.php'>Go Back</a></div>");
    }
    $stmt->close();
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("<div class=''>Error: Invalid Request. <a href='dashboard.php'>Go Back</a></div>");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodId = intval($_POST['foodId']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $categoryId = intval($_POST['category']);
    $description = trim($_POST['description']);
    $active = $_POST['active'];

    if (empty($name) || $price < 0 || empty($categoryId)) {
        $error_msg = "Validation Error: Please check your inputs.";
        $product['name'] = $name;
        $product['price'] = $price;
        $product['active'] = $active;
        $product['categoryId'] = $categoryId;
        $product['description'] = $description;
    } else {
        $image = $_POST['image'];

        if (isset($_POST['remove_image'])) {
            $image_name = NULL;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filesize = $_FILES['image']['size'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error_msg = "Invalid file format. Only JPG, PNG, GIF allowed.";
            } elseif ($filesize > 2 * 1024 * 1024) {
                $error_msg = "File size exceeds 2MB limit.";
            } else {
                $new_filename = uniqid() . "." . $ext;
                $destination = "../uploads/menu/" . $new_filename;

                if (!is_dir('../uploads/menu'))
                    mkdir('../uploads/menu', 0777, true);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image = $new_filename;
                } else {
                    $error_msg = "Failed to upload image.";
                }
            }
        }

        if (empty($error_msg)) {
            $sql = "UPDATE foods SET name=?, price=?, active=?, categoryId=?, description=?, image=? WHERE foodId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdiissi", $name, $price, $active, $categoryId, $description, $image, $foodId);

            if ($stmt->execute()) {
                $success_msg = "The food updated successfully!";
                $product['name'] = $name;
                $product['price'] = $price;
                $product['active'] = $active;
                $product['categoryId'] = $categoryId;
                $product['description'] = $description;
                $product['image'] = $image;
            } else {
                $error_msg = "Database Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Menu</title>
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS File -->
    <link rel="stylesheet" href="/styles/homepage.css">

    <!-- Webpage Icon -->
    <link rel="Icon" href="img/youtube icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="main-content">
        <div class="header">
            <h1>Edit Product</h1>
            <div class="header-right">
                <div class="user-info"></div>
            </div>
        </div>

        <div class="content">
            <div class="action-bar">
                <a href="Lab 09 Q1.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <?php if ($food): ?>
                <form action="update-food.php?id=<?php echo $food['foodId']; ?>" method="POST" enctype="multipart/form-data"
                    class="product-form">
                    <input type="hidden" name="foodId" value="<?php echo $food['foodId']; ?>">
                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($food['image'] ?? ''); ?>">

                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?php echo htmlspecialchars($food['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $cat_sql = "SELECT * FROM food_categories ORDER BY title";
                            $cat_result = $conn->query($cat_sql);
                            if ($cat_result) {
                                while ($cat = $cat_result->fetch_assoc()) {
                                    $selected = ($food['categoryId'] == $cat['categoryId']) ? 'selected' : '';
                                    echo "<option value='" . $cat['categoryId'] . "' $selected>" . htmlspecialchars($cat['title']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="price">Price (RM) *</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                                value="<?php echo htmlspecialchars($food['price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="active">Select Status</label>
                            <select name="active" class="form-control" required>
                                <option value="active" <?= $active == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $active == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            rows="4"><?php echo htmlspecialchars($food['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif">
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Max file size: 2MB (JPG, PNG, GIF)</div>

                        <?php if (!empty($food['image'])): ?>
                            <div id="imagePreview">
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <p style="margin: 0; font-weight: bold;">Current Image:</p>
                                    <img src="uploads/products/<?php echo htmlspecialchars($food['image']); ?>"
                                        alt="Current Product Image"
                                        style="max-width: 150px; border-radius: 4px; border: 1px solid #ddd;">
                                    <div>
                                        <input type="checkbox" name="remove_image" id="remove_image">
                                        <label for="remove_image" style="display: inline;">Remove current image</label>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="margin-top: 10px; color: #888; font-style: italic;">No image currently uploaded.</div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" id="submitBtn" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> UPDATE PRODUCT
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>