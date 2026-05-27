<?php
class TransactionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTransactions(string $type, string $fromDate, string $toDate)
    {
        $sql = "SELECT t.id, t.type, t.amount, t.from_account, t.to_account, t.date FROM Transactions t WHERE 1=1";
        $params = [];

        if (!empty($type)) {
            $sql .= " AND t.type = :type";
            $params['type'] = $type;
        }

        if (!empty($fromDate)) {
            $sql .= " AND t.date >= :fromDate";
            $params['fromDate'] = $fromDate . ' 00:00:00';
        }

        if (!empty($toDate)) {
            $sql .= " AND t.date <= :toDate";
            $params['toDate'] = $toDate . ' 23:59:59';
        }


        $sql .= " ORDER BY t.date ASC";


        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logTransaction(string $type, float $depositedAmount, string $from_account, string $to_account)
    {
        $stmtTransaction = $this->pdo->prepare("INSERT INTO Transactions (type, amount, from_account, to_account, date) VALUES (?, ?, ?, ?, NOW())");
        $stmtTransaction->execute([$type, $depositedAmount, $from_account, $to_account]);
    }
}
?>