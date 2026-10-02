<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$uid = $_SESSION['user_id'];

if (isset($_POST['confirm_order'])) {
    $pid = $_POST['buy_id'];
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $item_query = $conn->query("SELECT * FROM products WHERE id = $pid");
    $item = $item_query->fetch_assoc();

    if ($item) {
        $pname = $item['title'];
        $price = $item['price'];
        $stock = $item['quantity'];

        if ($stock > 0) {
            $conn->query("INSERT INTO orders (user_id, product_name, price, status, customer_name, phone, address) VALUES ('$uid', '$pname', '$price', 'Processing', '$customer_name', '$phone', '$address')");

            $new_stock = $stock - 1;
            $conn->query("UPDATE products SET quantity = '$new_stock' WHERE id = '$pid'");

            header('Location: order.php');
            exit();
        } else {
            header('Location: index.php');
            exit();
        }
    }
}

if (isset($_GET['buy_id'])) {
    $pid = $_GET['buy_id'];

    $item_query = $conn->query("SELECT * FROM products WHERE id = $pid");
    $item = $item_query->fetch_assoc();

    if ($item) {
        $pname = $item['title'];
        $price = $item['price'];
        $stock = $item['quantity'];

        if ($stock > 0) {
            $conn->query("INSERT INTO orders (user_id, product_name, price, status) VALUES ('$uid', '$pname', '$price', 'Processing')");

            $new_stock = $stock - 1;
            $conn->query("UPDATE products SET quantity = '$new_stock' WHERE id = '$pid'");

            header('Location: order.php');
            exit();
        } else {
            header('Location: index.php');
            exit();
        }
    }
}

$orders = $conn->query("SELECT * FROM orders WHERE user_id = $uid ORDER BY id DESC");
$user_query = $conn->query("SELECT * FROM users WHERE id = $uid");
$user = $user_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .side-nav a { display: block; padding: 0.6rem 0.8rem; text-decoration: none; color: #4b5563; font-size: 0.85rem; border-left: 2px solid transparent; }
      .side-nav a.active { border-color: #111; color: #111; font-weight: 500; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container py-5" style="max-width: 1080px;">
      <div class="row g-5">

        <aside class="col-lg-3">
          <div class="border p-3 bg-white mb-4">
            <span class="text-uppercase text-secondary small" style="font-size: 0.68rem; letter-spacing: 0.08em;">Account</span>
            <div class="fw-medium mt-1"><?php echo htmlspecialchars($user['username']); ?></div>
            <div class="text-secondary small font-monospace mt-1">Role: <?php echo $user['role']; ?></div>
          </div>

          <div class="side-nav">
            <a href="profile.php">Account Settings</a>
            <a href="order.php" class="active">Purchase Records</a>
          </div>
        </aside>

        <section class="col-lg-9">
          <div class="pb-3 mb-4 border-bottom d-flex justify-content-between align-items-baseline">
            <h4 class="fw-normal m-0">Purchase Records</h4>
            <span class="text-secondary font-monospace small"><?php echo $orders->num_rows; ?> Entries</span>
          </div>

          <?php if ($orders->num_rows > 0): ?>
            <div class="border bg-white">
              <?php while ($r = $orders->fetch_assoc()): ?>
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                  <div>
                    <span class="font-monospace text-secondary small">#ORD-<?php echo str_pad($r['id'], 4, '0', STR_PAD_LEFT); ?></span>
                    <div class="fw-medium mt-1"><?php echo htmlspecialchars($r['product_name']); ?></div>
                    <span class="text-secondary small">Quantity: 1</span>

                    <?php if (!empty($r['customer_name']) || !empty($r['phone']) || !empty($r['address'])): ?>
                      <div class="text-secondary small mt-2">
                        <?php if (!empty($r['customer_name'])): ?>
                          <div><strong>Name:</strong> <?php echo htmlspecialchars($r['customer_name']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($r['phone'])): ?>
                          <div><strong>Phone:</strong> <?php echo htmlspecialchars($r['phone']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($r['address'])): ?>
                          <div><strong>Location:</strong> <?php echo htmlspecialchars($r['address']); ?></div>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="text-end">
                    <div class="font-monospace"><?php echo number_format($r['price']); ?> MMK</div>
                    <div class="mt-1">
                      <?php if ($r['status'] == 'Completed'): ?>
                        <span class="text-dark small">Fulfilled</span>
                      <?php elseif ($r['status'] == 'Cancelled'): ?>
                        <span class="text-muted small">Cancelled</span>
                      <?php else: ?>
                        <span class="text-secondary small">Processing</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <div class="border bg-white p-5 text-center text-secondary small">
              No historical orders recorded.
            </div>
          <?php endif; ?>
        </section>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>