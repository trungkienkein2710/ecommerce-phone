<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

<div class="form-container">

    <div class="form-card">

        <h3 class="text-center mb-4">🏷 Add New Category</h3>
         <?php if(!empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach($_SESSION['errors'] as $e): ?>
                    <div><?= $e ?></div>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        <form method="POST">

            <!-- NAME -->
            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <!-- BUTTON -->
            <button class="btn btn-success w-100">
                💾 Save Category
            </button>

            <!-- BACK -->
            <a href="index.php?action=manager_categories" 
               class="btn btn-outline-secondary w-100 mt-2">
               ⬅ Back
            </a>

        </form>

    </div>

</div>

</body>
</html>