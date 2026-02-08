<?php
require_once '../foodDB.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Base Query
$sql = "SELECT * FROM foods WHERE active = 'active'";

// Add Search Filter
if (!empty($search)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

// Add Category Filter (so search works within the current category)
if ($cat > 0) {
    $sql .= " AND categoryId = $cat";
}

$sql .= " ORDER BY foodId DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $imgPath = !empty($data['image']) ? "../uploads/menu/" . $data['image'] : "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=1080";
        ?>
        <div class="card">
            <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($data['name']) ?>">
            <div class="card-body">
                <h3><?= htmlspecialchars($data['name']) ?></h3>
                <div class="card-footer">
                    <div class="price">RM <?= number_format($data['price'], 2) ?></div>
                    <button class="add-btn" onclick="addToCart(<?= $data['foodId'] ?>, '<?= addslashes($data['name']) ?>', <?= $data['price'] ?>)">
                        + Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p style='padding: 20px;'>No food items match your search.</p>";
}