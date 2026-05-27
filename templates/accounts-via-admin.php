<?php
session_start();

require '../src/AccountRepository.php';
require '../src/db.php';
require '../src/auth.php';

$accountRepo = new AccountRepository($pdo);
require_role('admin');

$limit = 20;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

try {

    $total_rows = $accountRepo->accountsAmount();
    $total_pages = ceil($total_rows / $limit);

    $accounts = $accountRepo->getAccountsWithPagination($limit, $offset);
} catch (PDOException $e) {
    die("Failed while running: " . $e->getMessage());
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Accounts</title>
    <link rel="stylesheet" href="../templates/css/accounts-via-admin.css">
</head>

<body>
    <div class="different-back-logout-btn">
        <a href="../templates/admin.php" class="going-back-btn">Back</a>
        <a href="../src/logout.php" class="logout-btn">Log out</a>
    </div>

    <h1 class="bankaccounts-header-title">Bank Accounts</h1>

    <table class="wrapper table-of-accounts">
        <thead>
            <tr>
                <th class="accounts-header-border">ID</th>
                <th class="accounts-header-border">Owner</th>
                <th class="accounts-header-border">Account Type</th>
                <th class="accounts-header-border">Balance</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td class="accounts-header-border"><?php echo htmlspecialchars($account['id']); ?></td>
                    <td class="accounts-header-border"><strong><?php echo htmlspecialchars($account['owner']); ?></strong></td>
                    <td class="accounts-header-border"><?php echo htmlspecialchars($account['account_type']); ?></td>
                    <td class="accounts-header-border"><?php echo htmlspecialchars($account['balance']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">«Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page= <?php echo $page + 1; ?>">Next »</a>
        <?php endif; ?>
    </div>
</body>

</html>