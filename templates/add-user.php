<?php
session_start();

require '../src/AccountRepository.php';
require '../src/db.php';
require '../src/auth.php';

require_role('admin');

$accountRepo = new AccountRepository($pdo);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $card_number = $_POST['card_number'];
    $role = $_POST['role'];

    $success = $accountRepo->addUser($name, $card_number, $role);

    if ($success) {
        header("Location: users-via-admin.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../templates/css/add-user.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/users-via-admin.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>
    <h1 class="header-adduser-title">Add New User</h1>

    <section class="add-user-section">
        <form action="add-user.php" method="POST" class="add-user-form">

            <label for="name" class="name-label">Name</label>
            <input type="text" name="name" class="name-input" id="name" required>

            <label for="card_number" class="cardnumber-label">Card Number</label>
            <input type="text" name="card_number" class="cardnumber-input" id="name" required>

            <label for="role" class="role-label">Role</label>
            <input type="text" name="role" class="role-input" id="name" required>

            <button type="submit" class="save-user-btn">Save User</button>
        </form>
    </section>
</body>

</html>