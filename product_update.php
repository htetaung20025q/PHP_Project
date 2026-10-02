<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$item = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $image = $item['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
    }

    $conn->query("UPDATE products SET title = '$title', price = '$price', quantity = '$qty', image = '$image' WHERE id = $id");
    header('Location: product_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .form-control { border-radius: 0; border-color: #d1d5db; padding: 0.65rem 0.8rem; font-size: 0.9rem; }
      .form-control:focus { border-color: #111; box-shadow: none; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container py-5" style="max-width: 580px;">
      <div class="border bg-white p-4">
        <div class="mb-4 pb-3 border-bottom">
          <span class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">Inventory</span>
          <h4 class="fw-normal mt-1 mb-0">Modify Item #<?php echo $id; ?></h4>
        </div>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Title</label>
            <input class="form-control" type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Price (MMK)</label>
              <input class="form-control" type="number" name="price" value="<?php echo $item['price']; ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Quantity</label>
              <input class="form-control" type="number" name="qty" value="<?php echo $item['quantity']; ?>">
            </div>
          </div>
          
          <div class="mb-4">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Image</label>
            <input class="form-control mb-2" type="file" name="image">
            <span class="text-secondary small font-monospace d-block" style="font-size: 0.75rem;">Active: <?php echo $item['image'] ? $item['image'] : 'None'; ?></span>
          </div>

          <div class="d-flex justify-content-between align-items-center pt-3 border-top">
            <a href="product_list.php" class="text-secondary text-decoration-none small text-uppercase" style="font-size: 0.75rem;">Cancel</a>
            <button class="btn btn-dark rounded-0 px-4 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.06em;" name="update">Save</button>
          </div>
        </form>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>