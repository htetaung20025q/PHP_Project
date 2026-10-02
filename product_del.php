<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $item = $conn->query("SELECT title FROM products WHERE id = $id")->fetch_assoc();
} else {
    header('Location: product_list.php');
    exit();
}

if (isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: product_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<main class="border bg-white p-4" style="max-width: 420px; width: 100%;">
  <div class="mb-3">
    <span class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">Destructive Action</span>
    <h5 class="fw-normal mt-1 mb-0">Confirm Deletion</h5>
  </div>

  <p class="text-secondary small mb-4">
    Are you sure you want to permanently remove <strong>"<?php echo htmlspecialchars($item['title']); ?>"</strong>? This transaction cannot be reverted.
  </p>

  <form method="POST">
    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
      <a href="product_list.php" class="btn btn-outline-secondary btn-sm rounded-0 px-3 py-1 text-uppercase" style="font-size: 0.75rem;">Cancel</a>
      <button type="submit" name="confirm_delete" class="btn btn-dark btn-sm rounded-0 px-3 py-1 text-uppercase" style="font-size: 0.75rem;">Confirm</button>
    </div>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>