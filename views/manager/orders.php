<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Manage Orders</h3>
        <a href="index.php?action=manager" class="btn btn-secondary">
            ⬅ Back Dashboard
        </a>
    </div>

    <!-- ALERT -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered shadow">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th style="width:250px;">Change</th>
            </tr>
        </thead>

        <tbody>
            <?php while($o = $orders->fetch_assoc()): ?>

            <tr>
                <td>
                    <a href="index.php?action=order_detail&id=<?= $o['order_id'] ?>">
                        #<?= $o['order_id'] ?>
                    </a>
                </td>

                <td><?= $o['name'] ?></td>

                <td class="text-danger fw-bold">
                    <?= number_format($o['total_price']) ?> $
                </td>

                <td><?= $o['payment_method'] ?></td>

                <!-- STATUS BADGE -->
                <td>
                    <?php if($o['status'] == 'pending'): ?>
                        <span class="badge bg-secondary">Pending</span>
                    <?php elseif($o['status'] == 'shipping'): ?>
                        <span class="badge bg-warning text-dark">Shipping</span>
                    <?php elseif($o['status'] == 'completed'): ?>
                        <span class="badge bg-success">Completed</span>
                    <?php elseif($o['status'] == 'cancelled'): ?>
                        <span class="badge bg-danger">Cancelled</span>
                    <?php endif; ?>
                </td>

                <!-- ACTION -->
                <td>

                    <?php if(in_array($o['status'], ['completed','cancelled'])): ?>

                        <span class="badge bg-dark">🔒 Locked</span>

                    <?php elseif($o['status'] == 'pending'): ?>

                        <a href="index.php?action=update_order_status&id=<?= $o['order_id'] ?>&status=shipping" 
                           class="btn btn-warning btn-sm">
                           🚚 Shipping
                        </a>

                        <a href="index.php?action=update_order_status&id=<?= $o['order_id'] ?>&status=cancelled" 
                           class="btn btn-danger btn-sm">
                           ❌ Cancel
                        </a>

                    <?php elseif($o['status'] == 'shipping'): ?>

                        <a href="index.php?action=update_order_status&id=<?= $o['order_id'] ?>&status=completed" 
                           class="btn btn-success btn-sm">
                           ✅ Done
                        </a>

                    <?php endif; ?>

                </td>
            </tr>

            <?php endwhile; ?>
        </tbody>

    </table>

</div>

</body>
</html>