<?php
require_once 'includes/config.php';

// Initialize variables to prevent "Undefined variable" notices
$nameError = '';
$checkResult = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productname = trim($_POST['name']);
    $categoryId = $_POST['categoryId'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = trim($_POST['description']);

    // 1. Check for duplicate product name
    $checkQuery = "SELECT Product_ID FROM product WHERE Product_Name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('s', $productname);
    $checkStmt->execute();
    $checkStmt->store_result(); // Needed to count rows

    if ($checkStmt->num_rows > 0) {
        $nameError = 'A product with this name already exists.';
        $checkStmt->close();
    } else {
        $checkStmt->close();

        // 2. Insert new product (Fixed column/value count)
        $query = "INSERT INTO product (Product_Name, Category_ID, Product_Price, Product_Stock, Product_Description, status) VALUES (?, ?, ?, ?, ?, 'Active')";
        $addproductstmt = $conn->prepare($query);

        // Corrected bind_param types: s = string, i = integer, d = double/decimal
        $addproductstmt->bind_param('sidis', $productname, $categoryid, $price, $stock, $description);

        if ($addproductstmt->execute()) {
            header("Location: Product.php?message=success");
            exit();
        } else {
            $nameError = "Error: " . $conn->error;
        }
        $addproductstmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Lakeshow Grocery</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include_once 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <h1>Add New Product</h1>
            <div class="header-right">
                <div class="user-info">
                    <img src="img/AdminLogo.webp" alt="Admin Profile">
                    <span></span>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="action-bar">
                <a href="Product.php" class="btn btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_GET['message']; ?>
                </div>
            <?php endif; ?>

            <form id="productForm" action="AddProduct.php" method="POST" enctype="multipart/form-data"
                class="product-form">
                <div class="form-group">
                    <label for="product_name">Product Name *</label>
                    <input type="text" id="product_name" name="product_name"
                        class="form-control <?php echo $nameError ? 'is-invalid' : ''; ?>" value="" required>
                    <div class="invalid-feedback"><?php echo $nameError; ?></div>
                </div>

                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $catQuery = "SELECT Category_ID, Category_Name FROM category";
                        $result = $conn->query($catQuery);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['Category_ID']}'>{$row['Category_Name']}</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">Please select a category.</div>
                </div>

                <div class="form-group">
                    <label for="price">Price (RM) *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0.01" value=""
                        required>
                    <div class="invalid-feedback">Please provide a valid price.</div>
                </div>

                <div class="form-group">
                    <label for="stock">Initial Stock Quantity *</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0" value="" required>
                    <div class="invalid-feedback">Please provide a valid stock quantity.</div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
<?php
?>