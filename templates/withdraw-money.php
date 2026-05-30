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

$error = "";
$success = "";

$user = $accountRepo->getCustomerByCardNumber($cardNumber);

$accounts = [];

if ($user) {
    $ownerName = $user['name'];
    $accounts = $accountRepo->getOwnerWithBalance($ownerName);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed. Request denied.");
    }

    $withdrawAmount = floatval($_POST['withdrawnAmount']);
    $accountId = $_POST['selectedAccounttoWithdraw'];

    if ($withdrawAmount > 0 && !empty($accountId)) {

        $currentAccount = $accountRepo->getAccountById($accountId);

        if ($currentAccount && $currentAccount['balance'] >= $withdrawAmount) {

            $accountRepo->subtractBalance($accountId, $withdrawAmount);
            $transactionRepo->logTransaction('Withdraw', $withdrawAmount, 'ATM', $ownerName);

            $success = "Withdrawal Succesful!";
            header("Refresh:1");
        } else {
            $error = "Insufficient funds on this account.";
        }
    } else {
        $error = "Please enter a valid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Money</title>
    <link rel="stylesheet" href="../templates/css/withdraw-money.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/dashboard.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Logout</a>
    </div>

    <h1 class="wrapper header-withdraw-title">Withdraw Money</h1>

    <section class="wrapper form-withdraw-section">
        <?php if (!empty($error)): ?>
            <p style="color: #f4f4f4; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color: #f4f4f4; font-weight: bold;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>


        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="withdraw-amount" class="widthdraw-amount-title">Withdraw Amount</label> <br>
            <input type="text" class="withdraw-amount-input" name="withdrawnAmount" id="widthdrawnAmount" placeholder="Withdraw Amount"> <br>
            <select name="selectedAccounttoWithdraw" id="selectedAccounttoWithdraw" class="selectedAccounttoWithdraw">
                <option value="" disabled selected>Choose an account</option>
                <?php foreach ($accounts as $account): ?>

                    <option value="<?= $account['id']; ?>">
                        <?php echo htmlspecialchars($account['account_type']); ?>
                    </option>
                <?php endforeach; ?>
            </select> <br>
            <button class="withdraw-money-btn">Withdraw</button>
        </form>
    </section>
</body>

</html>