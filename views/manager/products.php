<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

    <h3>📱 Products</h3>

    <div>
         <a href="index.php?action=manager" class="btn btn-secondary me-2">
            ⬅ Back Dashboard
        </a>

        <a href="index.php?action=add_product" class="btn btn-success">
            ➕ Add Product
        </a>
    </div>

</div>

    <table class="table table-hover table-bordered shadow">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if($products && $products->num_rows > 0): ?>
                 <?php while($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $p['product_id'] ?></td>

                <td>
                    <img src="public/assets/images/<?= $p['image'] ?>" 
                         width="60">
                </td>

                <td><?= $p['name'] ?></td>
                <td><?= number_format($p['price']) ?> $</td>
                <td><?= $p['stock'] ?></td>

                <td>
                    <a href="index.php?action=edit_product&id=<?= $p['product_id'] ?>" 
                    class="btn btn-warning btn-sm">✏ Edit</a>
                    <a href="index.php?action=delete_product&id=<?= $p['product_id'] ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('⚠️ Are you sure to delete this product?')">
                        Delete
                    </a>
                </td>
            </tr>
            
                <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center">No products found</td>
    </tr>
<?php endif; ?>
        </tbody>

    </table>

</div>

</body>
</html>