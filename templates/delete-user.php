<?php
session_start();
require '../src/db.php';
require '../src/AccountRepository.php';
require '../src/auth.php';

require_role('admin');

$accountRepo = new AccountRepository($pdo);

$id = isset($_GET['id']) ? $_GET['id'] : null;

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
