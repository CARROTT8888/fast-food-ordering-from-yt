<?php
require_once '../foodDB.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: warning.php");
    exit();
}

// Initialize variables to prevent "Undefined variable" notices
$nameError = '';
$checkResult = 0;
$active = 'active';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $categoryId = $_POST['categoryId'];
    $price = $_POST['price'];
    $active = $_POST['active'];
    $image = "";

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

    // 1. Check for duplicate food name
    $checkQuery = "SELECT foodId FROM foods WHERE name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('s', $name);
    $checkStmt->execute();
    $checkStmt->store_result(); // Needed to count rows

    if ($checkStmt->num_rows > 0) {
        $nameError = 'A food with this name already exists.';
        $checkStmt->close();
    } else {
        $checkStmt->close();

        // 2. Insert new food (Fixed column/value count)
        $query = "INSERT INTO foods (name, description, price, categoryId, active, image) VALUES (?, ?, ?, ?, ?, ?)";
        $addfoodstmt = $conn->prepare($query);

        // Corrected bind_param types: s = string, i = integer, d = double/decimal
        $addfoodstmt->bind_param('ssdiss', $name, $description, $price, $categoryId, $active, $image);
        if ($addfoodstmt->execute()) {
            header("Location: menu.php?message=success");
            exit();
        } else {
            $nameError = "Error: " . $conn->error;
        }
        $addfoodstmt->close();
    }

    if (empty($nameError)) {
        $checkStmt = $conn->prepare("SELECT foodId FROM foods WHERE name = ?");
        $checkStmt->bind_param('s', $name);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            $nameError = 'A food with this name already exists.';
        } else {
            // Updated Query: Added 'image' column
            $query = "INSERT INTO foods (name, description, price, categoryId, active, image) VALUES (?, ?, ?, ?, ?, ?)";
            $addfoodstmt = $conn->prepare($query);
            $addfoodstmt->bind_param('ssdiss', $name, $description, $price, $categoryId, $active, $image);

            if ($addfoodstmt->execute()) {
                header("Location: menu.php?message=success");
                exit();
            } else {
                $nameError = "Database Error: " . $conn->error;
            }
        }
        $checkStmt->close();
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }

        /* Card Container */
        .form-card {
            background: #f1f1f1;
            width: 400px;
            margin: 20px auto;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .title {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            align-items: center;
        }

        .subtitle {
            margin: 8px 0 24px;
            font-size: 14px;
            color: #7d7d7d;
        }

        /* Form Elements */
        .styled-form .form-group {
            margin-bottom: 20px;
        }

        .styled-form label {
            display: flex;
            margin-bottom: 6px;
            font-size: 14px;
            gap: 2px;
        }

        .required {
            color: red;
        }

        .styled-form input,
        .styled-form select {
            width: 90%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .styled-form input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
        }

        /* Error States */
        .input-error {
            border-color: #e74c3c !important;
        }

        .error-text {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Submit Button */
        .btn-save {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #27ae60;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            color: red;
            transition: all 0.3s ease;
        }

        .add-menu {
            margin-top: 20px;
            font-size: 14px;
            color: #7d7d7d;
            text-align: center;
            align-items: center;
        }

        .add-menu a {
            text-decoration: underline;
            text-align: center;
            color: #eb4034;
            font-weight: bold;
        }

        .preview-container {
            margin-top: 10px;
            display: none;
            /* Hidden until image selected */
            text-align: center;
        }

        #imagePreview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }
    </style>
    <script>
        function previewFile() {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('previewContainer');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
                container.style.display = "block";
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>

<body>


    <div class="content">
        <div class="">
            <a href="dashboard.php" class="btn-back">
                <i class='bx bx-arrow-back'></i> Back to Dashboard
            </a>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_GET['message']; ?>
            </div>
        <?php endif; ?>
        <div class="form-card">
            <div class="card-header">
                <h1 class="title">Add New Food Item</h1>
                <p class="subtitle">Fill in the information below to add a new food item to the menu.</p>
            </div>
            <form id="productForm" action="add-food-item.php" method="POST" enctype="multipart/form-data"
                class="styled-form">
                <div class="form-group">
                    <label for="name">Food Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                        class="form-control <?php echo $nameError ? 'is-invalid' : ''; ?>" value="" required>
                    <div class="invalid-feedback"><?php echo $nameError; ?></div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="categoryId">Category *</label>
                    <select id="categoryId" name="categoryId" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $catQuery = "SELECT categoryId, title FROM food_categories";
                        $result = $conn->query($catQuery);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['categoryId']}'>{$row['title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price (RM) *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0.01" max="50.00" value=""
                        required>
                </div>

                <div class="form-group">
                    <label for="active">Select Status</label>
                    <select name="active" class="form-control" required>
                        <option value="active" <?= $active == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $active == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Food Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*"
                        onchange="previewFile()">
                    <div class="preview-container" id="previewContainer">
                        <img src="" id="imagePreview" alt="Image Preview">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" id="submitBtn" class="btn-save">
                        <i class="fas fa-plus"></i> Create Food Item
                    </button>
                </div>
            </form>
        </div>
        <p class="add-menu">
            If you want to create a food category, <a href="add-category.php">Tab Here!</a>
        </p>
    </div>
</body>

</html>
<?php
?>