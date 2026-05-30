<?php
session_start();

require '../src/db.php';
require '../src/TransactionRepository.php';
require '../src/AccountRepository.php';
require '../src/auth.php';
require_role('user');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$cardNumber = $_SESSION['card_number'] ?? '';

$accountRepo = new AccountRepository($pdo);
$transactionRepo = new TransactionRepository($pdo);

$user = $accountRepo->getCustomerByCardNumber($cardNumber);
$accounts = [];


if ($user) {
    $ownerName = $user['name'];
    $accounts = $accountRepo->getAccountsByOwner($ownerName);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed. Request denied.");
    }

    $depositedAmount = $_POST['deposited-amount'];
    $accountId = $_POST['Accounts'];

    if ($depositedAmount > 0) {

        $accountRepo->addBalance($accountId, $depositedAmount);
        $transactionRepo->logTransaction('Deposit', $depositedAmount, 'ATM', $ownerName);

        echo "Deposit Successful!";
        header("Refresh:1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Money</title>
    <link rel="stylesheet" href="../templates/css/deposit-money.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/dashboard.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>
    <h1 class="wrapper header-deposit-title">Deposit Money</h1>

    <section class="wrapper deposit-section">

        <form action="" method="POST" class="form-deposit-money">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="deposit" class="deposit-label-title">Deposit Amount</label> <br>
            <input type="text" name="deposited-amount" id="deposit" placeholder="Deposit Money" class="despoit-money-input">

            <select name="Accounts" id="Accounts" required class="selected-options">
                <option value="" disabled selected>Choose an account</option>

                <?php foreach ($accounts as $account): ?>
                    <option value="<?= $account['id']; ?>">
                        <?= htmlspecialchars($account['account_type']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn-deposit-money" type="submit">Send</button>
        </form>
    </section>
</body>

</html>