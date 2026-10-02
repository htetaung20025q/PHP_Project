<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // order: Approve
    if ($action == 'confirm' && isset($_GET['id'])) {
        $order_id = $_GET['id'];
        $conn->query("UPDATE orders SET status = 'Completed' WHERE id = '$order_id'");
        header('Location: admin_orders.php');
        exit();
    }

    // order: Cancel
    if ($action == 'cancel' && isset($_GET['id'])) {
        $order_id = $_GET['id'];
        $order_query = $conn->query("SELECT * FROM orders WHERE id = '$order_id'");
        $order_info = $order_query->fetch_assoc();
        $pname = $order_info['product_name'];

        $conn->query("UPDATE orders SET status = 'Cancelled' WHERE id = '$order_id'");
        $conn->query("UPDATE products SET quantity = quantity + 1 WHERE title = '$pname'");

        header('Location: admin_orders.php');
        exit();
    }

    // Approve all orders
    if ($action == 'confirm_all') {
        $conn->query("UPDATE orders SET status = 'Completed'");
        header('Location: admin_orders.php');
        exit();
    }

    // Cancel all orders
    if ($action == 'cancel_all') {
        $active_orders = $conn->query("SELECT product_name FROM orders WHERE status != 'Cancelled'");
        if ($active_orders && $active_orders->num_rows > 0) {
            while ($p_row = $active_orders->fetch_assoc()) {
                $pname = $p_row['product_name'];
                $conn->query("UPDATE products SET quantity = quantity + 1 WHERE title = '$pname'");
            }
        }
        $conn->query("UPDATE orders SET status = 'Cancelled'");
        header('Location: admin_orders.php');
        exit();
    }
}

$orders = $conn->query('SELECT * FROM orders ORDER BY id DESC');

$total_sales = 0;
$total_orders = 0;
$completed_orders = 0;

$calc_query = $conn->query('SELECT * FROM orders');
while ($row = $calc_query->fetch_assoc()) {
    $total_orders = $total_orders + 1;
    if ($row['status'] == 'Completed') {
        $total_sales = $total_sales + $row['price'];
        $completed_orders = $completed_orders + 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Order Ledger</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light text-dark">

    <?php include 'header.php'; ?>

    <main class="container py-5" style="max-width: 1080px;">

      <div class="d-flex justify-content-between align-items-end pb-3 mb-4 border-bottom">
        <div>
          <span class="text-uppercase text-secondary small fw-medium">Dashboard</span>
          <h3 class="fw-normal m-0 mt-1">Order Ledger</h3>
        </div>
        <div class="d-flex align-items-center gap-2">
 
        <button type="button" class="btn btn-dark btn-sm rounded-0 px-3 py-1 text-uppercase" data-bs-toggle="modal" data-bs-target="#modalApproveAll">
            Approve All
          </button>
          
          <!-- Cancel All Trigger -->
          <button type="button" class="btn btn-outline-secondary btn-sm rounded-0 px-3 py-1 text-uppercase" data-bs-toggle="modal" data-bs-target="#modalCancelAll">
            Cancel All
          </button>

          <a href="product_list.php" class="btn btn-outline-dark btn-sm rounded-0 px-3 py-1 text-uppercase">
            Products List
          </a>
        </div>
      </div>

      <div class="row g-0 bg-white border mb-5">
        <div class="col-md-4 p-4 border-end">
          <span class="text-uppercase text-secondary small">Total Orders</span>
          <div class="fs-3 fw-light mt-1"><?php echo $total_orders; ?> <span class="fs-6 text-secondary">units</span></div>
        </div>
        <div class="col-md-4 p-4 border-end">
          <span class="text-uppercase text-secondary small">Fulfilled</span>
          <div class="fs-3 fw-light mt-1 text-dark"><?php echo $completed_orders; ?></div>
        </div>
        <div class="col-md-4 p-4">
          <span class="text-uppercase text-secondary small">Total Revenue</span>
          <div class="fs-3 fw-light mt-1"><?php echo number_format($total_sales); ?> <span class="fs-6 text-secondary">MMK</span></div>
        </div>
      </div>

      <div class="bg-white border">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="border-bottom bg-light">
              <tr class="text-uppercase text-secondary small">
                <th class="ps-4 py-3 fw-semibold">Reference</th>
                <th class="py-3 fw-semibold">Client</th>
                <th class="py-3 fw-semibold">Item</th>
                <th class="py-3 fw-semibold">Amount</th>
                <th class="py-3 fw-semibold">Status</th>
                <th class="pe-4 py-3 text-end fw-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders->num_rows > 0): ?>
                <?php while ($r = $orders->fetch_assoc()): ?>
                <tr>
                  <td class="ps-4 font-monospace text-secondary small">
                    #ORD-<?php echo str_pad($r['id'], 4, '0', STR_PAD_LEFT); ?>
                  </td>
                  <td class="text-secondary small">
                    User · <?php echo $r['user_id']; ?>
                  </td>
                  <td class="fw-medium">
                    <?php echo htmlspecialchars($r['product_name']); ?>
                  </td>
                  <td class="font-monospace">
                    <?php echo number_format($r['price']); ?> <span class="text-secondary small">MMK</span>
                  </td>
                  <td>
                    <?php if ($r['status'] == 'Processing'): ?>
                      <span class="d-inline-flex align-items-center small text-secondary">
                        <span class="rounded-circle bg-warning me-2" style="width: 6px; height: 6px;"></span>
                        Processing
                      </span>
                    <?php elseif ($r['status'] == 'Completed'): ?>
                      <span class="d-inline-flex align-items-center small text-dark">
                        <span class="rounded-circle bg-success me-2" style="width: 6px; height: 6px;"></span>
                        Pending...
                      </span>
                    <?php else: ?>
                      <span class="d-inline-flex align-items-center small text-muted">
                        <span class="rounded-circle bg-secondary me-2" style="width: 6px; height: 6px;"></span>
                        Cancelled
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="pe-4 text-end">
                    <?php if ($r['status'] == 'Processing'): ?>
 
                      <div class="d-inline-flex gap-2">
                        <a href="admin_orders.php?action=confirm&id=<?php echo $r['id']; ?>" class="btn btn-dark btn-sm rounded-0 px-3 py-1 text-uppercase">
                          Approve
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-0 px-3 py-1 text-uppercase" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $r['id']; ?>">
                          Cancel
                        </button>
                      </div>


                      <div class="modal fade" id="cancelModal<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered text-start">
                          <div class="modal-content rounded-0">
                            <div class="modal-header">
                              <h6 class="modal-title text-uppercase small">Confirm Cancellation</h6>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body small text-secondary">
                              Order #ORD-<?php echo str_pad($r['id'], 4, '0', STR_PAD_LEFT); ?> ကို Cancel လုပ်မှာ သေချာပါသလား?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary btn-sm rounded-0 text-uppercase" data-bs-dismiss="modal">Close</button>
                              <a href="admin_orders.php?action=cancel&id=<?php echo $r['id']; ?>" class="btn btn-dark btn-sm rounded-0 text-uppercase">Confirm</a>
                            </div>
                          </div>
                        </div>
                      </div>

                    <?php else: ?>
                      <span class="text-secondary small">-</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-5 text-secondary small">
                    No orders recorded yet.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>

    <!-- Bootstrap Approve All Modal -->
    <div class="modal fade" id="modalApproveAll" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
          <div class="modal-header">
            <h6 class="modal-title text-uppercase small">Approve All Orders</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body small text-secondary">
            Are you sure you want to approve all orders?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-0 text-uppercase" data-bs-dismiss="modal">Close</button>
            <a href="admin_orders.php?action=confirm_all" class="btn btn-dark btn-sm rounded-0 text-uppercase">Confirm</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap Cancel All Modal -->
    <div class="modal fade" id="modalCancelAll" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
          <div class="modal-header">
            <h6 class="modal-title text-uppercase small">Cancel All Orders</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body small text-secondary">
            Are you sure you want to cancel all orders?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-0 text-uppercase" data-bs-dismiss="modal">Close</button>
            <a href="admin_orders.php?action=cancel_all" class="btn btn-dark btn-sm rounded-0 text-uppercase">Confirm</a>
          </div>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>