<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
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
    <title>Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .table > :not(caption) > * > * { padding: 0.9rem 0.85rem; background-color: transparent; border-bottom-color: #e5e7eb; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container py-5" style="max-width: 1080px;">
      <div class="d-flex justify-content-between align-items-baseline pb-3 mb-4 border-bottom">
        <div>
          <span class="text-uppercase text-secondary small" style="font-size: 0.72rem; letter-spacing: 0.08em;">Inventory</span>
          <h3 class="fw-normal mt-1 mb-0">Products</h3>
        </div>
        <a href="product_create.php" class="btn btn-dark btn-sm rounded-0 px-3 py-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.06em;">New Entry</a>
      </div>

      <div class="bg-white border">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="border-bottom bg-light">
              <tr class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                <th class="ps-4 py-3 fw-semibold">ID</th>
                <th class="py-3 fw-semibold">Asset</th>
                <th class="py-3 fw-semibold">Title</th>
                <th class="py-3 fw-semibold">Price</th>
                <th class="py-3 fw-semibold">Inventory</th>
                <th class="pe-4 py-3 text-end fw-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($products->num_rows > 0): ?>
                <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                  <td class="ps-4 font-monospace text-secondary small">#<?php echo $row['id']; ?></td>
                  <td>
                    <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                      <img src="uploads/<?php echo $row['image']; ?>" width="44" height="44" class="object-fit-cover border">
                    <?php else: ?>
                      <div class="border bg-light text-secondary small d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 0.65rem;">N/A</div>
                    <?php endif; ?>
                  </td>
                  <td class="fw-medium"><?php echo htmlspecialchars($row['title']); ?></td>
                  <td class="font-monospace"><?php echo number_format($row['price']); ?> MMK</td>
                  <td class="text-secondary small font-monospace"><?php echo $row['quantity']; ?></td>
                  <td class="pe-4 text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="product_update.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary btn-sm rounded-0 px-2 py-1 text-uppercase" style="font-size: 0.68rem;">Edit</a>
                      <a href="product_del.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark btn-sm rounded-0 px-2 py-1 text-uppercase" style="font-size: 0.68rem;">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center py-5 text-secondary small">No records in directory.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>