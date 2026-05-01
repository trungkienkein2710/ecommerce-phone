<!DOCTYPE html>
<html>
<head>
    <title>Phone Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }
        .product-desc {
            font-size: 14px;
            color: #666;
            height: 50px;
            overflow: hidden;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex justify-content-between">

        <span class="navbar-brand">📱 Phone Store</span>

        <div>
            <?php if(isset($_SESSION['user'])): ?>

                <span class="text-white me-3">
                    👋 <?= $_SESSION['user']['name'] ?> 
                    (<?= $_SESSION['user']['role'] ?>)
                </span>

                <a href="index.php?action=profile" class="btn btn-info btn-sm me-2">👤 Profile</a>

                <?php if($_SESSION['user']['role'] == 'admin'): ?>
                    <a href="index.php?action=admin" class="btn btn-danger btn-sm me-2">👑 Admin</a>
                <?php endif; ?>

                <?php if($_SESSION['user']['role'] == 'manager'): ?>
                    <a href="index.php?action=manager" class="btn btn-warning btn-sm me-2">🛠 Manager</a>
                <?php endif; ?>

                <a href="index.php?action=my_orders" class="btn btn-info btn-sm me-2">📦 My Orders</a>
                <a href="index.php?action=cart" class="btn btn-warning btn-sm me-2">🛒 Cart</a>
                <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">Logout</a>

            <?php else: ?>

                <a href="index.php?action=login" class="btn btn-outline-light btn-sm me-2">Login</a>
                <a href="index.php?action=register" class="btn btn-success btn-sm">Register</a>

            <?php endif; ?>
        </div>

    </div>
</nav>

<div class="container mt-4">

    <!-- SUCCESS MESSAGE -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <h2 class="mb-4 text-center">🔥 Latest Phones</h2>

    <!-- 🔍 SEARCH + FILTER -->
    <form method="GET" class="row mb-4">

        <input type="hidden" name="action" value="home">

        <!-- SEARCH -->
        <div class="col-md-4">
            <input type="text" name="keyword" 
                   class="form-control"
                   placeholder="🔍 Search phone..."
                   value="<?= $_GET['keyword'] ?? '' ?>">
        </div>

        <!-- PRICE -->
        <div class="col-md-3">
            <select name="price" class="form-control">
                <option value="">💰 All Price</option>
                <option value="1" <?= (($_GET['price'] ?? '') == 1) ? 'selected' : '' ?>>Under 5M</option>
                <option value="2" <?= (($_GET['price'] ?? '') == 2) ? 'selected' : '' ?>>5M - 10M</option>
                <option value="3" <?= (($_GET['price'] ?? '') == 3) ? 'selected' : '' ?>>Above 10M</option>
            </select>
        </div>

        <!-- CATEGORY -->
        <div class="col-md-3">
            <select name="category_id" class="form-control">
                <option value="">📂 All Category</option>
                <?php foreach($categoryList as $c): ?>
                    <option value="<?= $c['category_id'] ?>"
                        <?= (($_GET['category_id'] ?? '') == $c['category_id']) ? 'selected' : '' ?>>
                        <?= $c['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>

    </form>

    <!-- PRODUCTS -->
    <div class="row">
        <?php while($row = $products->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card shadow mb-4">

                    <img src="public/assets/images/<?= $row['image'] ?>" 
                         onerror="this.src='public/assets/images/default.jpg'"
                         class="card-img-top"
                         style="height:250px; object-fit:cover;">

                    <div class="card-body">

                        <h5 class="text-center"><?= $row['name'] ?></h5>

                        <p class="text-muted text-center mb-1">
                            <?= $row['category_name'] ?? 'No Category' ?>
                        </p>

                        <p class="product-desc text-center">
                            <?= substr($row['description'], 0, 80) ?>...
                        </p>

                        <p class="text-danger fw-bold fs-5 text-center">
                            <?= number_format($row['price']) ?> $
                        </p>

                        <!-- STOCK -->
                        <?php if($row['stock'] > 0): ?>
                            <p class="text-success text-center">✔ In stock: <?= $row['stock'] ?></p>
                        <?php else: ?>
                            <p class="text-danger text-center fw-bold">❌ Out of stock</p>
                        <?php endif; ?>

                        <div class="d-grid gap-2">

                            <a href="index.php?action=product_detail&id=<?= $row['product_id'] ?>" 
                               class="btn btn-outline-dark">
                               🔍 View Detail
                            </a>

                            <?php if($row['stock'] > 0): ?>
                                <a href="index.php?action=add_to_cart&id=<?= $row['product_id'] ?>" 
                                   class="btn btn-success">
                                    🛒 Add to Cart
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    ❌ Out of Stock
                                </button>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>

<footer class="bg-dark text-white text-center p-3 mt-5">
    © 2026 Phone Store |
    <a href="index.php?action=about" class="text-white">About</a> |
    <a href="index.php?action=privacy" class="text-white">Privacy</a>
</footer>
</html>