<!DOCTYPE html>
<html>
<head>
    <title><?= $product['name'] ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .product-img {
            height: 350px;
            object-fit: cover;
        }

        .card {
            border-radius: 15px;
        }

        .rating {
            color: #ffc107;
            font-size: 18px;
        }

        .review-box {
            background: #f8f9fa;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <a href="index.php" class="btn btn-secondary mb-3">
        ⬅ Back Home
    </a>

    <div class="row">

        <!-- IMAGE -->
        <div class="col-md-5">
            <img src="public/assets/images/<?= $product['image'] ?>" 
                 class="img-fluid rounded shadow product-img">
        </div>

        <!-- INFO -->
        <div class="col-md-7">

            <h2><?= $product['name'] ?></h2>

            <p class="text-muted">
                <?= $product['category_name'] ?>
            </p>

            <!-- AVG RATING -->
            <div class="mb-2">
                <span class="rating">
                    <?= str_repeat("⭐", round($avg['avg_rating'] ?? 0)) ?>
                </span>
                <small class="text-muted">
                    (<?= round($avg['avg_rating'],1) ?> / 5 - <?= $avg['total'] ?> reviews)
                </small>
            </div>

            <h3 class="text-danger">
                <?= number_format($product['price']) ?> $
            </h3>

            <p><?= $product['description'] ?></p>

            <a href="index.php?action=add_to_cart&id=<?= $product['product_id'] ?>" 
               class="btn btn-success mb-3">
               🛒 Add to Cart
            </a>

        </div>

    </div>

    <!-- SPECIFICATIONS -->
    <div class="mt-5">
        <h4>⚙️ Specifications</h4>

        <table class="table table-bordered mt-3">
            <tr><th>Display</th><td><?= $specs['display'] ?? '-' ?></td></tr>
            <tr><th>Chipset</th><td><?= $specs['chipset'] ?? '-' ?></td></tr>
            <tr><th>CPU</th><td><?= $specs['cpu'] ?? '-' ?></td></tr>
            <tr><th>RAM</th><td><?= $specs['ram'] ?? '-' ?></td></tr>
            <tr><th>Storage</th><td><?= $specs['storage'] ?? '-' ?></td></tr>
            <tr><th>Battery</th><td><?= $specs['battery'] ?? '-' ?></td></tr>
            <tr><th>OS</th><td><?= $specs['os'] ?? '-' ?></td></tr>
            <tr><th>NFC</th><td><?= ($specs['nfc'] ?? 0) ? 'Yes' : 'No' ?></td></tr>
        </table>
    </div>

    <!-- FEEDBACK SECTION -->
    <div class="mt-5">

        <h4>⭐ Customer Reviews</h4>

        <!-- WRITE FEEDBACK -->
        <?php if(isset($_SESSION['user'])): ?>
        <div class="card p-3 mb-4 shadow-sm">

            <h5>📝 Write your review</h5>

            <form method="POST" action="index.php?action=add_feedback">

                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                <select name="rating" class="form-select mb-2">
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>

                <textarea name="comment" class="form-control mb-2"
                          placeholder="Write your review..." required></textarea>

                <button class="btn btn-primary">Submit</button>

            </form>
        </div>
        <?php else: ?>
            <p class="text-muted">🔒 Login to write review</p>
        <?php endif; ?>

        <!-- LIST FEEDBACK -->
        <?php if($feedbacks && $feedbacks->num_rows > 0): ?>

            <?php while($f = $feedbacks->fetch_assoc()): ?>
            <div class="card review-box p-3 mb-3 shadow-sm">

                <div class="d-flex justify-content-between">
                    <strong><?= $f['name'] ?></strong>
                    <small class="text-muted"><?= $f['created_at'] ?></small>
                </div>

                <div class="rating mb-1">
                    <?= str_repeat("⭐", $f['rating']) ?>
                </div>

                <p class="mb-0"><?= $f['comment'] ?></p>

            </div>
            <?php endwhile; ?>

        <?php else: ?>
            <p>No reviews yet</p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>