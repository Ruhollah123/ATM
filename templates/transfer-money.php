<?php

session_start();

require '../src/db.php';
require '../src/TransactionRepository.php';
require '../src/AccountRepository.php';
require '../src/auth.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$transactionRepo = new TransactionRepository($pdo);
$accountRepo = new AccountRepository($pdo);

require_role('user');

$cardNumber = $_SESSION['card_number'] ?? '';

$myAccounts = [];
$allAccounts = [];
$error = "";
$success = "";


$customer = $accountRepo->getCustomerByCardNumber($cardNumber);

if ($customer) {
    $ownerName = $customer['name'];

    $myAccounts = $accountRepo->getOwnerWithBalance($ownerName);
}

$allAccounts = $accountRepo->getAllBankAccounts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed. Request denied.");
    }

    $fromAccount = $_POST['fromAccount'];
    $toAccount = $_POST['toAccount'];

    $amount = floatval($_POST['amount']);

    if (!empty($fromAccount) && !empty($toAccount) && $amount > 0) {
        if ($fromAccount === $toAccount) {
            $error = "You cannot transfer money to the same account.";
        } else {
            $acc = $accountRepo->getAccountById($fromAccount);

            if ($acc && $acc['balance'] >= $amount) {
                $pdo->beginTransaction();

                $accountRepo->subtractBalance($fromAccount, $amount);
                $accountRepo->addBalance($toAccount, $amount);

                $pdo->commit();

                $toAccData = $accountRepo->getAccountById($toAccount);
                $toOwnerName = $toAccData ? $toAccData['owner'] : $toAccount;

                $transactionRepo->logTransaction('Transfer', $amount, $ownerName, $toOwnerName);

                $success = "Transfer Successful!";
                header("Refresh:2");
            } else {
                $error = "Insufficient funds.";
            }
        }
    } else {
        $error = "Please fill in all fields correctly.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money</title>
    <link rel="stylesheet" href="../templates/css/transfer-money.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/dashboard.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>

    <h1 class="wrapper transfer-money-title">Transfer Money</h1>


    <form action="" method="POST" class="wrapper two-forms-transfer-money">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <section class="transfering-money-section">
            <label for="fromAccount" class="from-account-transfer-title">From Account</label> <br>
            <select name="fromAccount" id="fromAccount" class="selectedAccounttoWithdraw">
                <option value="" disabled selected>Choose Account</option>
                <?php foreach ($myAccounts as $account): ?>
                    <option value="<?= $account['id']; ?>">
                        <?= htmlspecialchars($account['account_type']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="amount" id="amount" placeholder="Amount" class="from-account-input" required>

        </section>

        <section class="second-transfering-money-section">

            <?php if (!empty($error)): ?>
                <p style="color: #f4f4f4; font-weight: bold;"><?php echo htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <p style="color: #f4f4f4; font-weight: bold;"><?php echo htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <label for="toAccount" class="to-account-transfer-title">To Account</label> <br>
            <select name="toAccount" id="toAccount" class="selectedAccounttoWithdraw">
                <option value="" disabled selected>Choose Account</option>
                <?php foreach ($allAccounts as $account): ?>
                    <option value="<?= $account['id']; ?>">
                        <?= htmlspecialchars($account['account_type']); ?> - <?= htmlspecialchars($account['owner']); ?>
                    </option>
                <?php endforeach; ?>
            </select> <br>
            <button class="transfer-send-btn" type="submit">Send</button>

        </section>
    </form>
</body>

</html>