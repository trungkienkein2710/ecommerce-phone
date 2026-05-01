<!DOCTYPE html>
<html>
<head>
    <title>Order Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h3>🧾 Order #<?= $order['order_id'] ?></h3>

    <!-- INFO -->
    <div class="card mb-3 p-3 shadow">
        <p><b>Customer:</b> <?= $order['name'] ?></p>
        <p><b>Phone:</b> <?= $order['phone'] ?></p>
        <p><b>Address:</b> <?= $order['address'] ?></p>
        <p><b>Payment:</b> <?= $order['payment_method'] ?></p>
        <p><b>Status:</b> <?= $order['status'] ?></p>
        <p><b>Date:</b> <?= $order['created_at'] ?></p>
    </div>

    <!-- ITEMS -->
    <table class="table table-bordered shadow">

        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <?php $total = 0; ?>
            <?php while($i = $items->fetch_assoc()): 
                $sub = $i['price'] * $i['quantity'];
                $total += $sub;
            ?>
            <tr>
                <td>
                    <img src="public/assets/images/<?= $i['image'] ?>" width="60">
                </td>
                <td><?= $i['name'] ?></td>
                <td><?= number_format($i['price']) ?> $</td>
                <td><?= $i['quantity'] ?></td>
                <td><?= number_format($sub) ?> $</td>
            </tr>
            <?php endwhile; ?>
        </tbody>

    </table>

    <h4 class="text-end text-danger">
        Total: <?= number_format($total) ?> $
    </h4>

    <a href="index.php?action=my_orders" class="btn btn-secondary mt-3">
        ⬅ Back
    </a>

</div>

</body>
</html>