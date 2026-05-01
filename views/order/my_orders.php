<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h3>📦 My Orders</h3>
     <a href="index.php" class="btn btn-secondary mb-3">
        ⬅ Back Home
    </a>
    <table class="table table-bordered shadow">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
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
                <td><?= number_format($o['total_price']) ?> $</td>
                <td><?= $o['payment_method'] ?></td>
                <td>
                    <span class="badge bg-info">
                        <?= $o['status'] ?>
                    </span>
                </td>
                <td><?= $o['created_at'] ?></td>
                <td>

                    <?php if($o['status'] == 'pending'): ?>
                        <a href="index.php?action=cancel_order&id=<?= $o['order_id'] ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Cancel this order?')">
                        ❌ Cancel
                        </a>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>

    </table>

</div>

</body>
</html>