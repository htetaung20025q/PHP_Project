<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$media = 'uploads/';
if (!is_dir($media)) {
    mkdir($media);
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $media . $image);
    }

    $conn->query("INSERT INTO products (title, price, image, quantity) VALUES ('$title', '$price', '$image', '$qty')");
    header('Location: product_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
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
          <h4 class="fw-normal mt-1 mb-0">Create Item</h4>
        </div>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Title</label>
            <input class="form-control" type="text" required name="title" placeholder="Product name">
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Price (MMK)</label>
              <input class="form-control" type="number" required name="price" placeholder="0">
            </div>
            <div class="col-md-6">
              <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Stock</label>
              <input class="form-control" type="number" name="qty" placeholder="0">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Image</label>
            <input class="form-control" type="file" name="image">
          </div>

          <div class="d-flex justify-content-between align-items-center pt-3 border-top">
            <a href="product_list.php" class="text-secondary text-decoration-none small text-uppercase" style="font-size: 0.75rem;">Cancel</a>
            <button class="btn btn-dark rounded-0 px-4 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.06em;" name="submit">Commit Record</button>
          </div>
        </form>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>