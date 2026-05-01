<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

<div class="container mt-5">

    <div class="form-card mx-auto">

        <h3 class="text-center mb-4">✏ Edit Category</h3>
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach($_SESSION['errors'] as $e): ?>
                    <div>⚠️ <?= $e ?></div>
                <?php endforeach; ?>
            </div>
        <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        <form method="POST" action="index.php?action=update_category">

            <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">

            <!-- NAME -->
            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" 
                       value="<?= $category['name'] ?>" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"><?= $category['description'] ?></textarea>
            </div>

            <!-- BUTTON -->
            <button class="btn btn-success w-100">💾 Update Category</button>

            <a href="index.php?action=manager_categories" 
               class="btn btn-secondary w-100 mt-2">
               ⬅ Back
            </a>

        </form>

    </div>

</div>

</body>
</html>