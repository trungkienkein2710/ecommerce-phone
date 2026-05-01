<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5" style="max-width:600px;">

    <h3 class="text-center mb-4">👤 My Profile</h3>

    <!-- ALERT -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach($_SESSION['errors'] as $e): ?>
                <div><?= $e ?></div>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=update_profile">

        <label>Name</label>
        <input type="text" name="name" class="form-control mb-3"
               value="<?= $_SESSION['user']['name'] ?>">

        <label>Email</label>
        <input type="email" name="email" class="form-control mb-3"
               value="<?= $_SESSION['user']['email'] ?>">

        <label>Phone</label>
        <input type="text" name="phone" class="form-control mb-3"
               value="<?= $_SESSION['user']['phone'] ?? '' ?>">

        <label>New Password (optional)</label>
        <input type="password" name="password" class="form-control mb-3">

        <button class="btn btn-primary w-100">💾 Update Profile</button>

    </form>

    <a href="index.php" class="btn btn-secondary w-100 mt-2">
        ⬅ Back Home
    </a>

</div>

</body>
</html>