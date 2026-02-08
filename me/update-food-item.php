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

    // 1. Start with the current image from the hidden field
    $image = $_POST['image'];

    // 2. CHECK FOR REMOVAL FIRST
    // If the checkbox is ticked, we set $image to an empty string or NULL
    if (isset($_POST['remove_image'])) {
        $image = "";
    }

    if (empty($name) || $price < 0 || empty($categoryId)) {
        $error_msg = "Validation Error: Please check your inputs.";
        $food['name'] = $name;
        $food['price'] = $price;
        $food['active'] = $active;
        $food['categoryId'] = $categoryId;
        $food['description'] = $description;
    } else {
        $image = $_POST['image'];

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
            $stmt->bind_param("sdsissi", $name, $price, $active, $categoryId, $description, $image, $foodId);

            if ($stmt->execute()) {
                $success_msg = "The food updated successfully!";
                $food['name'] = $name;
                $food['price'] = $price;
                $food['active'] = $active;
                $food['categoryId'] = $categoryId;
                $food['description'] = $description;
                $food['image'] = $image;
                header("Location: menu.php?message=success");
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
    <title>Update The Food's Details</title>
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

        .arrow-back {
            color: black;
        }

        .arrow-back:hover {
            color: #e74c3c;
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

</head>

<body>

    <div class="content">
        <div class="">
            <a href="dashboard.php" class="btn-back">
                <i class='bx bx-arrow-back arrow-back'></i> Back to Dashboard
            </a>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <?php if ($food): ?>
            <div class="form-card">
                <div class="card-header">
                    <h1 class="title">Add New Food Item</h1>
                    <p class="subtitle">Fill in the information below to add a new food item to the menu.</p>
                </div>
                <form action="update-food-item.php?id=<?php echo $food['foodId']; ?>" method="POST"
                    enctype="multipart/form-data" class="styled-form">
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


                    <div class="form-group" style="flex: 1;">
                        <label for="price">Price (RM) *</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                            value="<?php echo htmlspecialchars($food['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="active">Select Status</label>
                        <select name="active" class="form-control" required>
                            <option value="active" <?= $food['active'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $food['active'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
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
                                    <img src="../uploads/menu/<?php echo htmlspecialchars($food['image']); ?>"
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

                    <div class="form-actions">
                        <button type="submit" id="submitBtn" class="btn-save">
                            <i class="fas fa-plus"></i> Create Food Item
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
</body>

</html>