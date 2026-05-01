<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h3 class="mb-4">🧾 Checkout</h3>

    <form method="POST" action="index.php?action=place_order">

        <!-- ADDRESS -->
        <div class="mb-3">
            <label>Shipping Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <!-- PHONE -->
        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <!-- PAYMENT -->
        <div class="mb-3">
            <label>Payment Method</label>

            <select name="payment_method" class="form-control">
                <option value="cod">💵 Cash on Delivery</option>
                <option value="card">💳 Credit Card</option>
            </select>
        </div>

        <!-- CART SUMMARY -->
        <h5 class="mt-4">🛒 Order Summary</h5>

        <ul class="list-group mb-3">
            <?php 
            $total = 0;
            foreach($cart as $item): 
                $sub = $item['price'] * $item['qty'];
                $total += $sub;
            ?>
            <li class="list-group-item d-flex justify-content-between">
                <?= $item['name'] ?> x <?= $item['qty'] ?>
                <span><?= number_format($sub) ?> $</span>
            </li>
            <?php endforeach; ?>

            <li class="list-group-item d-flex justify-content-between fw-bold">
                Total
                <span><?= number_format($total) ?> $</span>
            </li>
        </ul>

        <!-- BUTTON -->
        <button class="btn btn-success w-100">
            ✅ Place Order
        </button>

    </form>

</div>

</body>
</html>