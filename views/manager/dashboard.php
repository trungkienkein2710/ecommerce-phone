<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
        }

        .card-box {
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            color: white;
            transition: 0.3s;
            cursor: pointer;
        }

        .card-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .bg-products { background: #0d6efd; }
        .bg-categories { background: #198754; }
        .bg-orders { background: #ffc107; color: black; }

        .icon {
            font-size: 40px;
        }

        .nav-btn {
            margin: 5px;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <!-- HEADER -->
    <div class="text-center mb-5">
        <h1 class="fw-bold">🛠 Manager Dashboard</h1>
        <p>Welcome, <?= $_SESSION['user']['name'] ?> 👋</p>
    </div>

    <!-- STATS -->
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card-box bg-products">
                <div class="icon">📱</div>
                <h4><?= $totalProducts ?? '-' ?></h4>
                <p>Products</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-categories">
                <div class="icon">🏷</div>
                <h4><?= $totalCategories ?? '-' ?></h4>
                <p>Categories</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-orders">
                <div class="icon">📦</div>
                <h4><?= $totalOrders ?? '-' ?></h4>
                <p>Orders</p>
            </div>
        </div>

    </div>

    <!-- ACTION BUTTONS -->
    <div class="text-center mt-5">

        <a href="index.php?action=manager_products" class="btn btn-light nav-btn">
            📱 Manage Products
        </a>

        <a href="index.php?action=manager_categories" class="btn btn-light nav-btn">
            🏷 Manage Categories
        </a>

        <a href="index.php?action=manager_orders" class="btn btn-light nav-btn">
            📦 Manage Orders
        </a>

        <a href="index.php" class="btn btn-outline-light nav-btn">
            ⬅ Back Home
        </a>

    </div>

</div>

</body>
</html>