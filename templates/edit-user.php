<?php
session_start();

require '../src/TransactionRepository.php';
require '../src/AccountRepository.php';
require '../src/db.php';
require '../src/auth.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$accountRepo = new AccountRepository($pdo);

require_role('admin');


if (!isset($_GET['id'])) {
    die("User id is missing.");
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed. Request denied.");
    }

    $name = $_POST['name'];
    $card_number = $_POST['card_number'];
    $role = $_POST['role'];

    try {
        $accountRepo->updateUsers($name, $card_number, $role, $id);
        header("Location: users-via-admin.php");
        exit();
    } catch (PDOException $e) {
        $errorMessage = "Could not update user: " . $e->getMessage();
    }
}

try {
    $users = $accountRepo->getSpecificUser($id);
} catch (PDOException $e) {
    die("Could not get user: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../templates/css/edit-user.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/users-via-admin.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>
    <h1 class="header-edituser-title">Edit User</h1>
    <section class="edited-form-sect">
        <form action="edit-user.php?id=<?php echo $id; ?>" method="POST" class="edit-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="name" class="name-label">Name</label>
            <input type="text" name="name" id="name" class="name-input" value="<?php echo htmlspecialchars($users['name']); ?>" required>

            <label for="card_number" class="cardnumber-label">Card Number</label>
            <input type="text" name="card_number" id="card_number" class="cardnumber-input" value="<?php echo htmlspecialchars($users['card_number']); ?>" required>

            <label for="role" class="role-label">Role</label>
            <input type="text" name="role" id="role" class="role-input" value="<?php echo htmlspecialchars($users['role']); ?>" required>

            <button class="save-changes-btn">Save Changes</button>
        </form>
    </section>
</body>

</html>