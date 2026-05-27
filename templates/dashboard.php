<?php
session_start();
require '../src/db.php';
require '../src/AccountRepository.php';
require '../src/auth.php';

$accountRepo = new AccountRepository($pdo);

require_role('user');

$cardNumber = $_SESSION['card_number'] ?? '';
$balance = 0;

$user = $accountRepo->getCustomerByCardNumber($cardNumber);

if ($user) {
    $ownerName = $user['name'];

    $balance = $accountRepo->getBalanceByOwner($ownerName);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../templates/css/dashboard.css">
</head>

<body>
    <a href="../src/logout.php" class="wrapper logout-btn">Log out</a>
    <h1 class="wrapper header-menu-title">Menu</h1>

    <section class="wrapper Different-options">
        <h2>Welcome!</h2>
        <h2>Your Balance is: <?php echo $balance; ?></h2>

        <a href="withdraw-money.php" class="withdraw-deposit-btn">Withdraw</a>
        <a href="deposit-money.php" class="withdraw-deposit-btn">Deposit</a>

        <a href="transfer-money.php" class="transfer-button-btn">Transfer</a>
    </section>
</body>

</html>