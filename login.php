<?php
include 'connection.php';
session_start();

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($_SESSION['role'] == 'admin') {
                header('Location: product_list.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Incorrect password.';
        }
    } else {  
        $error = 'Username not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
      body { background-color: #fafafa; color: #111; }
      .form-control { border-radius: 0; border-color: #d1d5db; padding: 0.65rem 0.8rem; font-size: 0.9rem; }
      .form-control:focus { border-color: #111; box-shadow: none; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container px-3" style="max-width: 380px; margin: 80px auto;">
      <div class="border bg-white p-4">
        <div class="mb-4">
          <span class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">Authentication</span>
          <h4 class="fw-normal mt-1 mb-0">Sign In</h4>
        </div>

        <?php if (!empty($error)): ?>
          <div class="border border-danger-subtle bg-danger-subtle text-danger small p-2 mb-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Username</label>
            <input class="form-control" type="text" required name="username" placeholder="Username">
          </div>

          <div class="mb-4">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Password</label>
            <input class="form-control" type="password" required name="password" placeholder="••••••••">
          </div>

          <button class="btn btn-dark w-100 rounded-0 py-2 text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.08em;" name="login">Continue</button>
        </form>
      </div>

      <div class="text-center mt-3 text-secondary small">
        Do not have an account? <a href="register.php" class="text-dark text-decoration-underline">Register</a>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>