<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

<div class="container mt-4">
    
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

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>🏷 Categories</h3>

        <div>
            <a href="index.php?action=manager" class="btn btn-secondary me-2">
                ⬅ Back Dashboard
            </a>

            <a href="index.php?action=add_category" class="btn btn-success">
                ➕ Add Category
            </a>
        </div>

    </div>

    <!-- TABLE -->
    <table class="table table-hover table-bordered shadow">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Products</th>
                <th style="width:180px;">Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while($c = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $c['category_id'] ?></td>

                <td class="fw-bold">
                    <?= $c['name'] ?>
                </td>

                <td>
                    <?= $c['description'] ?>
                </td>

       
                <td class="text-center">
                    <?= $c['product_count'] ?? 0 ?>
                </td>

                <td>

                    <a href="index.php?action=edit_category&id=<?= $c['category_id'] ?>" 
                        class="btn btn-warning btn-sm">✏ Edit</a>

                    <?php if(($c['product_count'] ?? 0) > 0): ?>

                        <!-- ❌ disable if using -->
                        <button class="btn btn-secondary btn-sm" disabled>
                            ❌ In Use
                        </button>

                    <?php else: ?>

                        <!--  accept delete -->
                        <a href="index.php?action=delete_category&id=<?= $c['category_id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this category?')">
                           Delete
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