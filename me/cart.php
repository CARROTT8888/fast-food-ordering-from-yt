<?php
require_once "../foodDB.php";

if (!isset($conn)) {
  die("Database connection variable \$conn not found. Please check foodDB.php");
}

$sql = "
  SELECT foodId, name, price, image
  FROM foods
  WHERE active = 'Yes'
  ORDER BY foodId ASC
";
$result = $conn->query($sql);

if (!$result) {
  die("SQL error: " . $conn->error);
}

$menuItems = [];
while ($row = $result->fetch_assoc()) {
  $menuItems[] = $row;
}

function safeImg($path)
{
  $p = trim((string) $path);
  if ($p === "")
    return "img/placeholder.jpg";
  return $p;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu</title>

  <style>
    :root {
      --bg: #ffffff;
      --card: #ffffff;
      --muted: #6b7280;
      --text: #111827;
      --red: #e11d2e;
      --red2: #ff2b3d;
      --line: rgba(0, 0, 0, .08);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color: var(--text);
      background: var(--bg);
    }

    header {
      max-width: 1100px;
      margin: 22px auto 10px;
      padding: 0 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    header h1 {
      margin: 0;
      font-size: 22px;
      letter-spacing: .3px;
    }

    .cart-pill {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: rgba(255, 255, 255, .04);
      cursor: pointer;
      user-select: none;
    }

    .badge {
      min-width: 26px;
      height: 26px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(180deg, var(--red2), var(--red));
      font-weight: 800;
      font-size: 13px;
      padding: 0 8px;
    }

    .wrap {
      max-width: 1100px;
      margin: 0 auto 28px;
      padding: 0 16px;
      display: grid;
      grid-template-columns: 1fr 330px;
      gap: 16px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
    }

    .card {
      position: relative;
      border-radius: 18px;
      overflow: hidden;
      background: var(--card);
      border: 1px solid var(--line);
      min-height: 170px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
    }

    .card img {
      width: 100%;
      height: 170px;
      object-fit: cover;
      display: block;
      filter: saturate(1.05) contrast(1.05);
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg,
          rgba(0, 0, 0, .55) 0%,
          rgba(0, 0, 0, .35) 55%,
          rgba(0, 0, 0, .10) 100%);
    }

    .info {
      position: absolute;
      left: 14px;
      right: 14px;
      bottom: 12px;
      z-index: 2;
    }

    .name {
      margin: 0 0 6px;
      font-size: 18px;
      font-weight: 900;
    }

    .price {
      margin: 0 0 10px;
      color: #ffd1d6;
      font-weight: 900;
    }

    .qty {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 10px;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: rgba(255, 255, 255, .06);
    }

    .qty button {
      width: 30px;
      height: 30px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, .18);
      background: rgba(255, 255, 255, .06);
      color: var(--text);
      cursor: pointer;
      font-size: 18px;
      line-height: 1;
    }

    .qty input {
      width: 44px;
      text-align: center;
      border: none;
      outline: none;
      background: transparent;
      color: var(--text);
      font-weight: 900;
      font-size: 14px;
    }

    .add-btn {
      margin-top: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      border: none;
      color: white;
      font-weight: 900;
      letter-spacing: .2px;
      padding: 10px 14px;
      border-radius: 999px;
      cursor: pointer;
      background: linear-gradient(180deg, var(--red2), var(--red));
      box-shadow: 0 12px 24px rgba(225, 29, 46, .22);
    }

    .cart {
      border: 1px solid var(--line);
      background: #ffffff;
      border-radius: 18px;
      padding: 14px;
      position: sticky;
      top: 14px;
      height: fit-content;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
    }

    .cart h2 {
      margin: 0 0 10px;
      font-size: 18px;
    }

    .hint {
      margin: 0 0 12px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.4;
    }

    .cart-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin: 12px 0;
    }

    .cart-item {
      border: 1px solid rgba(255, 255, 255, .10);
      background: rgba(0, 0, 0, .25);
      border-radius: 14px;
      padding: 10px;
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 8px;
    }

    .cart-item .title {
      font-weight: 900;
      margin: 0;
      font-size: 14px;
    }

    .cart-item .sub {
      margin: 2px 0 0;
      color: var(--muted);
      font-size: 12px;
    }

    .cart-item .right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: space-between;
      gap: 8px;
    }

    .mini-btn {
      border: 1px solid rgba(255, 255, 255, .18);
      background: rgba(255, 255, 255, .06);
      color: var(--text);
      border-radius: 10px;
      padding: 6px 8px;
      cursor: pointer;
      font-weight: 900;
      font-size: 12px;
    }

    .mini-btn.danger {
      border-color: rgba(255, 43, 61, .35);
      color: #ffd1d6;
    }

    .total {
      border-top: 1px solid rgba(255, 255, 255, .12);
      margin-top: 12px;
      padding-top: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
    }

    .checkout {
      width: 100%;
      margin-top: 12px;
      padding: 12px 14px;
      border: none;
      border-radius: 14px;
      cursor: pointer;
      color: white;
      font-weight: 900;
      background: linear-gradient(180deg, #22c55e, #16a34a);
      opacity: .95;
    }

    .checkout:disabled {
      opacity: .45;
      cursor: not-allowed;
    }

    @media (max-width: 980px) {
      .wrap {
        grid-template-columns: 1fr;
      }

      .cart {
        position: relative;
        top: auto;
      }
    }

    @media (max-width: 640px) {
      .grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <header>
    <h1>🍔 Menu</h1>

    <div class="cart-pill" id="cartPill" title="Go to cart">
      <span>Cart</span>
      <span class="badge" id="cartCount">0</span>
    </div>
  </header>

  <main class="wrap">
    <!-- MENU GRID -->
    <section class="grid" id="menuGrid">
      <?php if (count($menuItems) === 0): ?>
        <p style="color: var(--muted);">No active food items found.</p>
      <?php else: ?>
        <?php foreach ($menuItems as $it): ?>
          <article class="card" data-id="<?= (int) $it['foodId'] ?>" data-name="<?= htmlspecialchars($it['name']) ?>"
            data-price="<?= htmlspecialchars($it['price']) ?>">
            <img src="<?= htmlspecialchars(safeImg($it['image'])) ?>" alt="<?= htmlspecialchars($it['name']) ?>">
            <div class="overlay"></div>

            <div class="info">
              <p class="name"><?= htmlspecialchars($it['name']) ?></p>
              <p class="price">RM <?= number_format((float) $it['price'], 2) ?></p>

              <div class="qty" aria-label="quantity">
                <button type="button" class="dec">−</button>
                <input type="text" class="qtyInput" value="1" readonly>
                <button type="button" class="inc">+</button>
              </div>

              <button type="button" class="add-btn">ADD TO CART</button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <!-- CART -->
    <aside class="cart" aria-label="cart">
      <h2>🧾 Cart</h2>
      <p class="hint">Pick quantity → Add to cart → Checkout (POST to PHP).</p>

      <div class="cart-list" id="cartList"></div>

      <div class="total">
        <span>Total</span>
        <strong id="cartTotal">RM 0.00</strong>
      </div>

      <!-- ✅ checkout: 先把 cart JSON POST 去 checkout.php（你们组长那边如果已有 checkout 就改 action） -->
      <form method="post" action="checkout.php" id="checkoutForm">
        <input type="hidden" name="cart_json" id="cartJson">
        <button class="checkout" id="checkoutBtn" disabled>Checkout</button>
      </form>
    </aside>
  </main>

  <script>
    // ======= CART LOGIC (front-end) =======
    const cart = {}; // {id: {id, name, price, qty}}

    const cartList = document.getElementById("cartList");
    const cartTotal = document.getElementById("cartTotal");
    const cartCount = document.getElementById("cartCount");
    const cartJson = document.getElementById("cartJson");
    const checkoutBtn = document.getElementById("checkoutBtn");

    function formatRM(n) { return "RM " + n.toFixed(2); }

    function renderCart() {
      const items = Object.values(cart);
      cartList.innerHTML = "";

      if (items.length === 0) {
        cartList.innerHTML = `<p style="color: var(--muted); margin:0;">Cart is empty.</p>`;
        cartTotal.textContent = formatRM(0);
        cartCount.textContent = "0";
        cartJson.value = "";
        checkoutBtn.disabled = true;
        return;
      }

      let total = 0;
      let count = 0;

      items.forEach(it => {
        const line = it.price * it.qty;
        total += line;
        count += it.qty;

        const row = document.createElement("div");
        row.className = "cart-item";
        row.innerHTML = `
        <div>
          <p class="title">${it.name}</p>
          <p class="sub">${formatRM(it.price)} × ${it.qty} = <strong>${formatRM(line)}</strong></p>
        </div>
        <div class="right">
          <div style="display:flex;gap:6px;">
            <button type="button" class="mini-btn" data-act="minus">−</button>
            <button type="button" class="mini-btn" data-act="plus">+</button>
          </div>
          <button type="button" class="mini-btn danger" data-act="remove">Remove</button>
        </div>
      `;

        row.querySelector('[data-act="minus"]').onclick = () => changeQty(it.id, -1);
        row.querySelector('[data-act="plus"]').onclick = () => changeQty(it.id, +1);
        row.querySelector('[data-act="remove"]').onclick = () => removeItem(it.id);

        cartList.appendChild(row);
      });

      cartTotal.textContent = formatRM(total);
      cartCount.textContent = String(count);

      // ✅ POST 的内容（给 checkout.php）
      cartJson.value = JSON.stringify(items);
      checkoutBtn.disabled = false;
    }

    function addToCart(id, name, price, qty) {
      if (!cart[id]) cart[id] = { id: Number(id), name, price: Number(price), qty: 0 };
      cart[id].qty += qty;
      renderCart();
    }

    function changeQty(id, delta) {
      if (!cart[id]) return;
      cart[id].qty += delta;
      if (cart[id].qty <= 0) delete cart[id];
      renderCart();
    }

    function removeItem(id) {
      delete cart[id];
      renderCart();
    }

    // ======= Bind card events =======
    document.querySelectorAll(".card").forEach(card => {
      const id = card.dataset.id;
      const name = card.dataset.name;
      const price = card.dataset.price;

      const input = card.querySelector(".qtyInput");
      card.querySelector(".dec").addEventListener("click", () => {
        input.value = String(Math.max(1, Number(input.value) - 1));
      });
      card.querySelector(".inc").addEventListener("click", () => {
        input.value = String(Math.min(99, Number(input.value) + 1));
      });
      card.querySelector(".add-btn").addEventListener("click", () => {
        addToCart(id, name, price, Number(input.value));
        input.value = "1";
      });
    });

    // scroll to cart
    document.getElementById("cartPill").addEventListener("click", () => {
      document.querySelector(".cart").scrollIntoView({ behavior: "smooth", block: "start" });
    });

    renderCart();
  </script>
</body>

</html>