<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$products = $conn->query('SELECT * FROM products');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .product-item { background: #fff; border: 1px solid #e5e7eb; }
      .product-frame { height: 260px; background-color: #f3f4f6; position: relative; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container py-5" style="max-width: 1140px;">
      <div class="d-flex justify-content-between align-items-baseline pb-3 mb-5 border-bottom">
        <div>
          <span class="text-uppercase text-secondary small" style="font-size: 0.72rem; letter-spacing: 0.08em;">Collection</span>
          <h3 class="fw-normal mt-1 mb-0">Catalogue</h3>
        </div>
        <span class="text-secondary font-monospace small"><?php echo $products->num_rows; ?> Items</span>
      </div>

      <div class="row g-4">
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
            <div class="col-md-4 col-lg-3">
                <div class="product-item h-100 d-flex flex-column">

                    <div class="product-frame overflow-hidden">
                      <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                          <img src="uploads/<?php echo $row['image']; ?>" class="w-100 h-100 object-fit-cover">
                      <?php else: ?>
                          <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary small font-monospace">
                              No Asset
                          </div>
                      <?php endif; ?>
                    </div>

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                          <h6 class="fw-normal text-dark text-truncate mb-0" style="font-size: 0.95rem;"><?php echo htmlspecialchars($row['title']); ?></h6>
                          <span class="font-monospace small ms-2"><?php echo number_format($row['price']); ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center text-secondary small mb-3" style="font-size: 0.75rem;">
                            <span>Stock</span>
                            <span><?php echo $row['quantity'] > 0 ? $row['quantity'] . ' units' : 'Out of Stock'; ?></span>
                        </div>

                        <div class="mt-auto">
                            <?php if ($row['quantity'] > 0): ?>
                                <button type="button" 
                                        class="btn btn-outline-dark btn-sm w-100 rounded-0 text-uppercase py-2" 
                                        style="font-size: 0.72rem; letter-spacing: 0.06em;"
                                        data-bs-toggle="offcanvas" 
                                        data-bs-target="#orderDrawer<?php echo $row['id']; ?>">
                                    Purchase
                                </button>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm w-100 rounded-0 text-uppercase py-2 text-muted border" disabled style="font-size: 0.72rem;">
                                    Sold Out
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-end rounded-0 border-start" tabindex="-1" id="orderDrawer<?php echo $row['id']; ?>">
              <div class="offcanvas-header border-bottom p-4">
                <div>
                  <span class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">Checkout</span>
                  <h5 class="offcanvas-title fw-normal mt-1 mb-0">Delivery Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
              </div>

              <div class="offcanvas-body p-4">
                <div class="border p-3 bg-light mb-4">
                  <div class="fw-medium text-dark"><?php echo htmlspecialchars($row['title']); ?></div>
                  <div class="font-monospace small text-secondary mt-1"><?php echo number_format($row['price']); ?> MMK</div>
                </div>

                <form action="order.php" method="POST">
                  <input type="hidden" name="buy_id" value="<?php echo $row['id']; ?>">

                  <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Customer Name</label>
                    <input class="form-control rounded-0" type="text" name="customer_name" placeholder="Enter your name" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Phone Number</label>
                    <input class="form-control rounded-0" type="text" name="phone" placeholder="09xxxxxxxxx" required>
                  </div>

                  <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Location / Address</label>
                    <textarea class="form-control rounded-0" rows="3" name="address" placeholder="City, Township, Street, Building..." required></textarea>
                  </div>

                  <button type="submit" name="confirm_order" class="btn btn-dark w-100 rounded-0 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.06em;">
                    Place Order
                  </button>
                </form>
              </div>
            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 py-5 text-center text-secondary small">Catalogue is currently empty.</div>
        <?php endif; ?>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>