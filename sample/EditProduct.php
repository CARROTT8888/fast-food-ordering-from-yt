<?php
require_once './sample/includes/config.php';

$error_msg = "";
$success_msg = "";
$product = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM product WHERE Product_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("<div class=''>Error: Product ID not found. <a href='Lab 09 Q1.php'>Go Back</a></div>");
    }
    $stmt->close();
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("<div class='alert alert-danger'>Error: Invalid Request. <a href='Lab 09 Q1.php'>Go Back</a></div>");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category']);
    $description = trim($_POST['description']);

    if (empty($name) || $price < 0 || $stock < 0 || empty($category_id)) {
        $error_msg = "Validation Error: Please check your inputs.";
        $product['Product_Name'] = $name;
        $product['Product_Price'] = $price;
        $product['Product_Stock'] = $stock;
        $product['Category_ID'] = $category_id;
        $product['Product_Description'] = $description;
    } else {
        $image_name = $_POST['current_image'];

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
                $destination = "uploads/products/" . $new_filename;

                if (!is_dir('uploads/products'))
                    mkdir('uploads/products', 0777, true);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_name = $new_filename;
                } else {
                    $error_msg = "Failed to upload image.";
                }
            }
        }

        if (empty($error_msg)) {
            $sql = "UPDATE product SET Product_Name=?, Product_Price=?, Product_Stock=?, Category_ID=?, Product_Description=?, Product_Picture=? WHERE Product_ID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdiissi", $name, $price, $stock, $category_id, $description, $image_name, $product_id);

            if ($stmt->execute()) {
                $success_msg = "Product updated successfully!";
                $product['Product_Name'] = $name;
                $product['Product_Price'] = $price;
                $product['Product_Stock'] = $stock;
                $product['Category_ID'] = $category_id;
                $product['Product_Description'] = $description;
                $product['Product_Picture'] = $image_name;
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
    <title>Edit Product | Lakeshow Grocery</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

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

            <?php if ($product): ?>
                <form action="EditProduct.php?id=<?php echo $product['Product_ID']; ?>" method="POST"
                    enctype="multipart/form-data" class="product-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['Product_ID']; ?>">
                    <input type="hidden" name="current_image"
                        value="<?php echo htmlspecialchars($product['Product_Picture'] ?? ''); ?>">

                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?php echo htmlspecialchars($product['Product_Name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $cat_sql = "SELECT * FROM category ORDER BY Category_Name";
                            $cat_result = $conn->query($cat_sql);
                            if ($cat_result) {
                                while ($cat = $cat_result->fetch_assoc()) {
                                    $selected = ($product['Category_ID'] == $cat['Category_ID']) ? 'selected' : '';
                                    echo "<option value='" . $cat['Category_ID'] . "' $selected>" . htmlspecialchars($cat['Category_Name']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="price">Price (RM) *</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                                value="<?php echo htmlspecialchars($product['Product_Price']); ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="stock">Stock Quantity *</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0"
                                value="<?php echo htmlspecialchars($product['Product_Stock']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            rows="4"><?php echo htmlspecialchars($product['Product_Description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif">
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Max file size: 2MB (JPG, PNG, GIF)</div>

                        <?php if (!empty($product['Product_Picture'])): ?>
                            <div id="imagePreview">
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <p style="margin: 0; font-weight: bold;">Current Image:</p>
                                    <img src="uploads/products/<?php echo htmlspecialchars($product['Product_Picture']); ?>"
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