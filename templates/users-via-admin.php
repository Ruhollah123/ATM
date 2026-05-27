<?php
session_start();

require '../src/db.php';
require '../src/AccountRepository.php';
require '../src/auth.php';

$accountRepo = new AccountRepository($pdo);

require_role('admin');


try {
    $users = $accountRepo->getAllUsers();
} catch (PDOException $e) {
    die("Could not bring data: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../templates/css/users-via-admin.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/admin.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>
    <h1 class="users-header-title">Users</h1>

    <table class="wrapper table-of-users">
        <thead>
            <tr>
                <th class="t-header-border">ID</th>
                <th class="t-header-border">Name</th>
                <th class="t-header-border">Card-Number</th>
                <th class="t-header-border">Role</th>
                <th class="t-header-border">Created At</th>
                <th class="t-header-border">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rowNumber = 1;
            foreach ($users as $user): ?>
                <tr>
                    <td class="t-header-border"><?php echo $rowNumber++; ?></td>
                    <td class="t-header-border"><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                    <td class="t-header-border"><?php echo htmlspecialchars($user['card_number']); ?></td>
                    <td class="t-header-border"><?php echo htmlspecialchars($user['role']); ?></td>
                    <td class="t-header-border"><?php echo htmlspecialchars($user['created_date']); ?></td>

                    <td class="t-header-border">
                        <a href="edit-user.php?id=<?= $user['id']; ?>" class="edit-user">Edit</a>
                        <a href="delete-user.php?id=<?= $user['id']; ?>" class="delete-user" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="changing-names">
        <a href="add-user.php" class="add-user">Add User</a>
    </div>
</body>

</html>