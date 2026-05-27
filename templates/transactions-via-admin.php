<?php

session_start();

require '../src/db.php';
require '../src/TransactionRepository.php';
require '../src/AccountRepository.php';
require '../src/auth.php';
$transactionRepo = new TransactionRepository($pdo);
$accountRepo = new AccountRepository($pdo);

require_role('admin');

if (isset($_GET['account'])) {
    $_SESSION['active_account_id'] = $_GET['account'];
}

$selectedType = $_GET['type'] ?? '';
$fromDate = $_GET['from-date'] ?? '';
$toDate = $_GET['to-date'] ?? '';

try {
    $privateAccounts = $accountRepo->getAllBankAccounts();
    $transactions = $transactionRepo->getAllTransactions($selectedType, $fromDate, $toDate);
} catch (PDOException $e) {
    die("Failed While running: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Of Transactions</title>
    <link rel="stylesheet" href="../templates/css/transactions-via-admin.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/admin.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>
    <h1 class="transactions-header-title">Transactions</h1>

    <section class="wrapper chosen-date">
        <form action="" method="GET" class="filtering-date-forms">

            <label for="type" class="title-above-select">Transaction Type</label>
            <select name="type" id="type" class="select-transfer-way">
                <option value="">-- All types --</option>
                <option value="Deposit">Deposit</option>
                <option value="Withdraw">Withdraw</option>
                <option value="Transfer">Transfer</option>
            </select>

            <div class="label-from-to">
                <div class="date-group">
                    <label for="from-date" class="two-labels">From</label>
                    <input type="date" name="from-date" id="from-date" class="two-inputs">
                </div>
                <div class="date-group">
                    <label for="to-date" class="two-labels">To</label>
                    <input type="date" name="to-date" id="to-date" class="two-inputs">
                </div>
            </div>

            <button class="filtering-btn" type="submit">Filter</button>
        </form>
    </section>

    <table class="wrapper table-of-transactions">
        <thead>
            <tr>
                <th class="transaction-header-border">ID</th>
                <th class="transaction-header-border">Type</th>
                <th class="transaction-header-border">Amount</th>
                <th class="transaction-header-border">From</th>
                <th class="transaction-header-border">To</th>
                <th class="transaction-header-border">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td class="transaction-header-border"><?= htmlspecialchars($transaction['id']); ?></td>
                    <td class="transaction-header-border"><strong><?php echo htmlspecialchars($transaction['type']); ?></strong></td>
                    <td class="transaction-header-border"><?php echo htmlspecialchars($transaction['amount']); ?></td>
                    <td class="transaction-header-border"><?php echo htmlspecialchars($transaction['from_account'] ?? ''); ?> <?= !empty($transaction['from_type']) ? '(' . htmlspecialchars($transaction['from_type']) . ')' : ''; ?></td>
                    <td class="transaction-header-border"><?php echo htmlspecialchars($transaction['to_account'] ?? ''); ?> <?= !empty($transaction['to_type']) ? '(' . htmlspecialchars($transaction['to_type']) . ')' : ''; ?></td>
                    <td class="transaction-header-border"><?php echo htmlspecialchars($transaction['date']); ?></td>
                </tr>
        </tbody>
    <?php endforeach; ?>
    </table>
</body>

</html>