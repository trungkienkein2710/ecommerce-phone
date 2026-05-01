<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h3>🛒 Your Cart</h3>
     <a href="index.php" class="btn btn-secondary mb-3">
        ⬅ Back Home
    </a>

    <table class="table">

        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php 
        $total = 0;
        foreach($cart as $id => $item): 
            $sub = $item['price'] * $item['qty'];
            $total += $sub;
        ?>
        <tr>
            <td><img src="public/assets/images/<?= $item['image'] ?>" width="60"></td>
            <td><?= $item['name'] ?></td>
            <td><?= number_format($item['price']) ?></td>
            <td>
                <div class="d-flex align-items-center justify-content-center">

                    <!-- MINUS -->
                    <a href="index.php?action=update_cart&id=<?= $id ?>&type=decrease"
                    class="btn btn-sm btn-outline-danger">−</a>

                    <!-- QTY -->
                    <span class="mx-2"><?= $item['qty'] ?></span>

                    <!-- PLUS -->
                    <a href="index.php?action=update_cart&id=<?= $id ?>&type=increase"
                    class="btn btn-sm btn-outline-success">+</a>

                </div>
            </td>
                        <td><?= number_format($sub) ?></td>
            <td>
                <a href="index.php?action=remove_cart&id=<?= $id ?>" 
                   class="btn btn-danger btn-sm">Remove</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <h4>Total: <?= number_format($total) ?> $ </h4>

    <a href="index.php?action=checkout" class="btn btn-success">
        ✅ Checkout
    </a>

</div>

</body>
</html>