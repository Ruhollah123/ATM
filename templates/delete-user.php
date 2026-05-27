<?php
require '../src/db.php';
require '../src/AccountRepository.php';

$accountRepo = new AccountRepository($pdo);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}


if ($id) {
    try {
        $accountRepo->deleteUser($id);
        header("Location: users-via-admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Failed to delete: " . $e->getMessage();
        echo "<br><a href='users-via-admin.php'>Go Back</a>";
        exit();
    }
} else {
    header("Location: users-via-admin.php");
    exit();
}
