<?php
include 'connection.php';
session_start();

$msg = '';
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $stmt = $conn->query("SELECT * FROM users WHERE username = '$username'");

    if ($stmt->num_rows > 0) {
        $msg = "<div class='border border-danger-subtle bg-danger-subtle text-danger small p-2 mb-3'>Username already exists</div>";
    } else {
        $s = $conn->prepare('INSERT INTO users(username, password, role) VALUES (?,?,?)');
        $s->bind_param('sss', $username, $password, $role);

        if ($s->execute()) {
            $_SESSION['msg'] = "<div class='border border-success-subtle bg-success-subtle text-success small p-2 mb-3'>Registration complete. Please sign in.</div>";
            header('Location: login.php');
            exit();
        } else {
            $msg = "<div class='border border-danger-subtle bg-danger-subtle text-danger small p-2 mb-3'>Registration failed: " . $conn->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
          <span class="text-uppercase text-secondary small" style="font-size: 0.7rem; letter-spacing: 0.08em;">Registration</span>
          <h4 class="fw-normal mt-1 mb-0">New Account</h4>
        </div>

        <?php echo $msg; ?>

        <form method="post">
          <div class="mb-3">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Username</label>
            <input class="form-control" type="text" required name="username" placeholder="Username">
          </div>

          <div class="mb-4">
            <label class="form-label text-secondary small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Password</label>
            <input class="form-control" type="password" required name="password" placeholder="••••••••">
          </div>

          <button class="btn btn-dark w-100 rounded-0 py-2 text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.08em;" name="submit">Create Account</button>
        </form>
      </div>

      <div class="text-center mt-3 text-secondary small">
        Already registered? <a href="login.php" class="text-dark text-decoration-underline">Sign In</a>
      </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>