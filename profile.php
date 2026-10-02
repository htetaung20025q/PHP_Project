<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $conn->query("UPDATE users SET username = '$username' WHERE id = $id");
    $_SESSION['username'] = $username;
    $_SESSION['msg'] = "<div class='border border-success-subtle bg-success-subtle text-success small p-2 mb-3'>Profile updated.</div>";
    header('Location: profile.php');
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .side-nav a { display: block; padding: 0.6rem 0.8rem; text-decoration: none; color: #4b5563; font-size: 0.85rem; border-left: 2px solid transparent; }
      .side-nav a.active { border-color: #111; color: #111; font-weight: 500; }
      .form-control { border-radius: 0; border-color: #d1d5db; padding: 0.65rem 0.8rem; font-size: 0.9rem; }
      .form-control:focus { border-color: #111; box-shadow: none; }
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
            <a href="profile.php" class="active">Account Settings</a>
            <a href="order.php">Purchase Records</a>
          </div>
        </aside>

        <section class="col-lg-9">
          <div class="pb-3 mb-4 border-bottom">
            <h4 class="fw-normal m-0">Settings</h4>
          </div>

          <div class="border bg-white p-4" style="max-width: 520px;">
            <?php
              if (isset($_SESSION['msg'])) {
                  echo $_SESSION['msg'];
                  unset($_SESSION['msg']);
              }
            ?>

            <form method="POST">
              <div class="mb-3">
                <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Username</label>
                <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
              </div>

              <div class="mb-4">
                <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Access Role</label>
                <input class="form-control bg-light" type="text" value="<?php echo $user['role']; ?>" disabled>
              </div>

              <button class="btn btn-dark rounded-0 px-4 py-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.06em;" name="update_profile">Save Changes</button>
            </form>
          </div>
        </section>

      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>