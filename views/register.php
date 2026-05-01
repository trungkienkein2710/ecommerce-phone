<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #198754, #20c997);
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
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<div class="card-box">

    <h3 class="text-center mb-4">📝 Register</h3>

    <!-- ERROR -->
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach($_SESSION['errors'] as $e): ?>
                <div><?= $e ?></div>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="name" class="form-control mb-3"
               placeholder="👤 Name"
               value="<?= $_SESSION['old']['name'] ?? '' ?>">

        <input type="email" name="email" class="form-control mb-3"
               placeholder="📧 Email"
               value="<?= $_SESSION['old']['email'] ?? '' ?>">

        <input type="text" name="phone" class="form-control mb-3"
               placeholder="📱 Phone"
               value="<?= $_SESSION['old']['phone'] ?? '' ?>">

        <input type="password" name="password" class="form-control mb-3"
               placeholder="🔑 Password">

        <button class="btn btn-success w-100">Register</button>

    </form>

    <!-- ACTION -->
    <div class="text-center mt-3">
        <a href="index.php?action=login">Already have account?</a>
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