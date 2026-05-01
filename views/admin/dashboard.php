<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">👑 Admin Dashboard</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Change Role</th>
            </tr>
        </thead>

        <tbody>
            <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>

                    <td>
                        <a href="index.php?action=change_role&id=<?= $user['user_id'] ?>&role=customer"
                           class="btn btn-success btn-sm">Customer</a>

                        <a href="index.php?action=change_role&id=<?= $user['user_id'] ?>&role=manager"
                           class="btn btn-warning btn-sm">Manager</a>

                        <a href="index.php?action=change_role&id=<?= $user['user_id'] ?>&role=admin"
                           class="btn btn-danger btn-sm">Admin</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-dark mt-3">⬅ Back</a>

</div>

</body>
</html>