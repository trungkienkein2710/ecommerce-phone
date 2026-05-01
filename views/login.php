<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .title {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="card-box">

    <h3 class="text-center title mb-4">🔐 Login</h3>

    <!-- ERROR -->
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach($_SESSION['errors'] as $e): ?>
                <div>⚠ <?= $e ?></div>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="email" name="email" class="form-control mb-3"
               placeholder="📧 Email"
               value="<?= $_SESSION['old']['email'] ?? '' ?>">

        <input type="password" name="password" class="form-control mb-3"
               placeholder="🔑 Password">

        <button class="btn btn-primary w-100">Login</button>

    </form>

    <!-- ACTION -->
    <div class="text-center mt-3">
        <a href="index.php?action=register">Create account</a>
    </div>

    <!-- BACK -->
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-outline-dark w-100">
            ⬅ Back Home
        </a>
    </div>

</div>

</body>
</html>