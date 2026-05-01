<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="public/css/style.css">

    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
        }

        .form-container {
            display: flex;
            justify-content: center;
            padding: 50px 0;
        }

        label {
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="form-container">

    <div class="form-card">

        <h3 class="text-center mb-4">➕ Add New Product</h3>
        <?php if(!empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach($_SESSION['errors'] as $e): ?>
                    <div><?= $e ?></div>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">

            <!-- NAME -->
            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <!-- PRICE -->
            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <!-- STOCK -->
            <div class="mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control">
            </div>

            <!-- CATEGORY -->
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <?php while($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['category_id'] ?>">
                            <?= $c['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            

            <!-- IMAGE -->
            <div class="mb-3">
                <label>Product Image</label>
                <input type="file" name="image" class="form-control" onchange="previewImage(event)">
            </div>

            <!-- PREVIEW -->
            <img id="preview" style="width:100%; border-radius:10px; margin-bottom:15px;"/>

            <!-- ===== SPECIFICATIONS ===== -->
            <hr>
            <h5 class="mt-3">⚙️ Specifications</h5>

            <div class="mb-3">
                <label>Display</label>
                <input type="text" name="display" class="form-control">
            </div>

            <div class="mb-3">
                <label>Chipset</label>
                <input type="text" name="chipset" class="form-control">
            </div>

            <div class="mb-3">
                <label>CPU</label>
                <input type="text" name="cpu" class="form-control">
            </div>

            <div class="mb-3">
                <label>RAM</label>
                <input type="text" name="ram" class="form-control">
            </div>

            <div class="mb-3">
                <label>Storage</label>
                <input type="text" name="storage" class="form-control">
            </div>

            <div class="mb-3">
                <label>Battery</label>
                <input type="text" name="battery" class="form-control">
            </div>

            <div class="mb-3">
                <label>OS</label>
                <input type="text" name="os" class="form-control">
            </div>

            <div class="mb-3">
                <label>NFC</label>
                <select name="nfc" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- BUTTON -->
            <button class="btn btn-success w-100">
                💾 Save Product
            </button>

            <!-- BACK -->
            <a href="index.php?action=manager_products" 
               class="btn btn-outline-light w-100 mt-2">
               ⬅ Back
            </a>

        </form>

    </div>

</div>

<!-- PREVIEW SCRIPT -->
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>