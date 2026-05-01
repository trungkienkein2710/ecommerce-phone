
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

<div class="container mt-5">

    <div class="form-card mx-auto">

        <h3 class="text-center mb-4">✏ Edit Product</h3>
        <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <?php foreach($_SESSION['errors'] as $e): ?>
                        <div>⚠️ <?= $e ?></div>
                    <?php endforeach; ?>
                </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        <form method="POST" action="index.php?action=update_product" enctype="multipart/form-data">

            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

            <!-- NAME -->
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>">
            </div>

            <!-- DESC -->
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"><?= $product['description'] ?></textarea>
            </div>

            <!-- PRICE -->
            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>">
            </div>

            <!-- STOCK -->
            <div class="mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>">
            </div>

            <!-- CATEGORY -->
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <?php while($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['category_id'] ?>"
                            <?= $c['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= $c['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- IMAGE -->
            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                <img src="public/assets/images/<?= $product['image'] ?>" width="100" class="mt-2">
            </div>

            <hr>

            <h5>⚙ Specifications</h5>

            <input type="text" name="display" class="form-control mb-2" placeholder="Display" value="<?= $spec['display'] ?? '' ?>">
            <input type="text" name="chipset" class="form-control mb-2" placeholder="Chipset" value="<?= $spec['chipset'] ?? '' ?>">
            <input type="text" name="cpu" class="form-control mb-2" placeholder="CPU" value="<?= $spec['cpu'] ?? '' ?>">
            <input type="text" name="ram" class="form-control mb-2" placeholder="RAM" value="<?= $spec['ram'] ?? '' ?>">
            <input type="text" name="storage" class="form-control mb-2" placeholder="Storage" value="<?= $spec['storage'] ?? '' ?>">
            <input type="text" name="battery" class="form-control mb-2" placeholder="Battery" value="<?= $spec['battery'] ?? '' ?>">
            <input type="text" name="os" class="form-control mb-2" placeholder="OS" value="<?= $spec['os'] ?? '' ?>">

            <select name="nfc" class="form-control mb-3">
                <option value="1" <?= ($spec['nfc'] ?? 0) == 1 ? 'selected' : '' ?>>NFC Yes</option>
                <option value="0" <?= ($spec['nfc'] ?? 0) == 0 ? 'selected' : '' ?>>NFC No</option>
            </select>

            <button class="btn btn-success w-100">💾 Update</button>

            <a href="index.php?action=manager_products" class="btn btn-secondary w-100 mt-2">
                ⬅ Back
            </a>

        </form>

    </div>

</div>

</body>
</html>