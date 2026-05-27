<?php
session_start();
require '../src/db.php';
require '../src/auth.php';
require_role('admin');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Site</title>
    <link rel="stylesheet" href="../templates/css/admin.css">
</head>

<body>
    <a href="../src/logout.php" class="logout-btn">Log out</a>
    <h1 class="wrapper admin-header-title">Admin</h1>

    <section class="wrapper admin-menu">
        <a href="users-via-admin.php" class="list-of-users">Users</a>
        <a href="transactions-via-admin.php" class="list-of-transactions">Transactions</a> <br>
        <a href="accounts-via-admin.php" class="list-of-accounts">Bank Accounts</a>
    </section>

</body>

</html>