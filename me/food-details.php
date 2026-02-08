<?php
require_once '../foodDB.php';
session_start();

if(!isset($_SESSION['userId'])){
    header("Location: sign-in.php");
    exit();
}

$foodId = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM foods WHERE foodId=? AND active='active'");
$stmt->bind_param("i",$foodId);
$stmt->execute();
$food = $stmt->get_result()->fetch_assoc();

if(!$food){
    echo "Food not found";
    exit();
}

/* cart count */
$userId = $_SESSION['userId'];
$countQuery = "SELECT SUM(quantity) as totalItems FROM carts WHERE userId=?";
$stmtCount = $conn->prepare($countQuery);
$stmtCount->bind_param("i",$userId);
$stmtCount->execute();
$count = $stmtCount->get_result()->fetch_assoc()['totalItems'] ?? 0;

$image = !empty($food['image'])
    ? "../uploads/menu/".$food['image']
    : "https://via.placeholder.com/600x400?text=Food";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($food['name']) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
*{
  box-sizing:border-box;
  font-family:Poppins,sans-serif;
}

body{
  background:#f6f6f6;
  margin:0;
}

/* ===== header ===== */
header{
  background:white;
  padding:15px 40px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.logo{
  color:red;
  font-weight:700;
  font-size:20px;
  text-decoration:none;
}

.cart-btn{
  background:red;
  color:white;
  padding:8px 16px;
  border:none;
  border-radius:25px;
  cursor:pointer;
}

/* ===== layout ===== */
.container{
  max-width:1100px;
  margin:40px auto;
  padding:20px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
  background:white;
  border-radius:18px;
  box-shadow:0 6px 20px rgba(0,0,0,.05);
}

/* image */
.food-img{
  width:100%;
  border-radius:16px;
  object-fit:cover;
}

/* details */
.title{
  font-size:28px;
  font-weight:700;
}

.price{
  color:red;
  font-size:22px;
  margin:15px 0;
}

.desc{
  color:#666;
  margin-bottom:25px;
}

/* qty */
.qty-box{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:25px;
}

.qty-btn{
  width:36px;
  height:36px;
  border:none;
  background:#eee;
  font-size:18px;
  cursor:pointer;
  border-radius:8px;
}

.qty-input{
  width:50px;
  text-align:center;
}

/* add btn */
.add-btn{
  background:red;
  color:white;
  padding:12px 22px;
  border:none;
  border-radius:12px;
  font-size:16px;
  cursor:pointer;
}

/* mobile */
@media(max-width:768px){
  .container{
    grid-template-columns:1fr;
  }
}
</style>
</head>

<body>

<header>
  <a class="logo" href="home.php">YouTube Food</a>
  <a href="cart.php">
    <button class="cart-btn">Cart (<span id="cartCount"><?= $count ?></span>)</button>
  </a>
</header>

<div class="container">

  <img src="<?= $image ?>" class="food-img">

  <div>
      <div class="title"><?= htmlspecialchars($food['name']) ?></div>

      <div class="price">RM <?= number_format($food['price'],2) ?></div>

      <div class="desc">
          <?= nl2br(htmlspecialchars($food['description'] ?? "Delicious fast food prepared fresh daily!")) ?>
      </div>

      <div class="qty-box">
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
          <input id="qty" value="1" class="qty-input">
          <button class="qty-btn" onclick="changeQty(1)">+</button>
      </div>

      <button class="add-btn" onclick="addToCart()">Add To Cart</button>
  </div>

</div>


<script>
function changeQty(delta){
    let input = document.getElementById('qty');
    let v = parseInt(input.value) + delta;
    if(v < 1) v = 1;
    input.value = v;
}

function addToCart(){
    const qty = document.getElementById('qty').value;

    fetch('add-to-db-cart.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`foodId=<?= $foodId ?>&quantity=${qty}`
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            document.getElementById('cartCount').innerText = data.newCount;
            alert("Added to cart!");
        }
    });
}
</script>

</body>
</html>
