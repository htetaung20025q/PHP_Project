<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="border-bottom bg-white sticky-top">
  <div class="container d-flex flex-wrap align-items-center justify-content-between py-3" style="max-width: 1140px;">
<a href="index.php" class="text-decoration-none text-dark d-inline-block">
  <span class="fs-4 fw-bold text-uppercase" style="letter-spacing: 0.14em; font-family: system-ui, -apple-system, sans-serif;">
    Next Aura
  </span>
  <span class="fs-4 fw-light text-secondary text-uppercase ms-1" style="letter-spacing: 0.14em;">
    Innovation
  </span>
</a>

    <nav class="d-flex align-items-center gap-4">
      <a href="index.php" class="text-secondary text-decoration-none small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Products</a>

      <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')): ?>
        <a href="order.php" class="text-secondary text-decoration-none small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">My Orders</a>
      <?php endif; ?>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <a href="product_list.php" class="text-dark text-decoration-none small text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.05em;">Inventory</a>
        <a href="admin_orders.php" class="text-dark text-decoration-none small text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.05em;">Orders</a>
      <?php endif; ?>
    </nav>

    <div class="d-flex align-items-center gap-3">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php" class="text-dark text-decoration-none small">
          <span class="font-monospace text-secondary">@<?php echo $_SESSION['username']; ?></span>
          <span class="border px-2 py-0 text-uppercase ms-1 text-secondary" style="font-size: 0.65rem;"><?php echo $_SESSION['role']; ?></span>
        </a>
        <a href="logout.php" class="btn btn-outline-dark btn-sm rounded-0 px-3 py-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Logout</a>
      <?php else: ?>
        <a href="login.php" class="text-secondary text-decoration-none small text-uppercase" style="font-size: 0.75rem;">Login</a>
        <a href="register.php" class="btn btn-dark btn-sm rounded-0 px-3 py-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>