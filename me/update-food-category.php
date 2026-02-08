<?php
require_once '../foodDB.php';

$error_msg = "";
$success_msg = "";
$category = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM food_categories WHERE categoryId = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        die("<div class=''>Error: Category ID not found. <a href='dashboard.php'>Go Back</a></div>");
    }
    $stmt->close();
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("<div class=''>Error: Invalid Request. <a href='dashboard.php'>Go Back</a></div>");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryId = intval($_POST['categoryId']);
    $title = trim($_POST['title']);
    $active = $_POST['active'];

    if (empty($title) || empty($active)) {
        $error_msg = "Validation Error: Please check your inputs.";
        $category['title'] = $title;
        $category['active'] = $active;
    }

    if (empty($error_msg)) {
        $sql = "UPDATE food_categories SET title=?, active=? WHERE categoryId=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $active, $categoryId);

        if ($stmt->execute()) {
            $success_msg = "The category updated successfully!";
            $category['title'] = $title;
            $category['active'] = $active;
            header("Location: menu.php?message=success");
        } else {
            $error_msg = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update The Category's Details</title>
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

        <?php if ($category): ?>
            <div class="form-card">
                <div class="card-header">
                    <h1 class="title">Update the Category's Details</h1>
                    <p class="subtitle">Fill in the information below to add a new food item to the menu.</p>
                </div>
                <form action="update-food-category.php?id=<?php echo $category['categoryId']; ?>" method="POST"
                    enctype="multipart/form-data" class="styled-form">
                    <input type="hidden" name="categoryId" value="<?php echo $category['categoryId']; ?>">

                    <div class="form-group">
                        <label for="title">Category's Title</label>
                        <input type="text" id="title" name="title" class="form-control"
                            value="<?php echo htmlspecialchars($category['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="active">Select Status</label>
                        <select name="active" class="form-control" required>
                            <option value="visible" <?= $category['active'] == 'visible' ? 'selected' : '' ?>>Visible (Shown on site)</option>
                            <option value="invisible" <?= $category['active'] == 'invisible' ? 'selected' : '' ?>>Inactive (Hidden)</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" id="submitBtn" class="btn-save">
                            <i class="fas fa-plus"></i> Update Food Category
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
</body>

</html>