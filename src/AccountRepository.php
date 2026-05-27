<?php
class AccountRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCustomerByCardNumber(int $cardNumber)
    {
        $stmtUser = $this->pdo->prepare("SELECT * FROM Customers WHERE card_number = ?");
        $stmtUser->execute([$cardNumber]);
        return $stmtUser->fetch();
    }

    public function getAccountsByOwner(string $ownerName)
    {
        $stmtAcc = $this->pdo->prepare("SELECT id, account_type FROM BankAccounts WHERE owner = ?");
        $stmtAcc->execute([$ownerName]);
        return $stmtAcc->fetchAll();
    }

    public function addBalance(int $accountId, float $depositedAmount)
    {
        $stmt = $this->pdo->prepare("UPDATE BankAccounts SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$depositedAmount, $accountId]);
    }

    public function subtractBalance(int $accountId, float $withdrawnAmount)
    {
        $stmtMinus = $this->pdo->prepare("UPDATE BankAccounts SET balance = balance - ? WHERE id = ?");
        $stmtMinus->execute([$withdrawnAmount, $accountId]);
    }

    public function addUser(string $name, int $card_number, string $role)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT into Users (name, card_number, role) VALUES (?, ?, ?)");
            return $stmt->execute([$name, $card_number, $role]);
        } catch (Exception $e) {
            throw new Exception("Failed to create a user: " . $e->getMessage());
        }
    }

    public function getBalanceByOwner(string $ownerName): float
    {
        $stmtBalance = $this->pdo->prepare("Select balance AS balance FROM BankAccounts WHERE owner = ?");
        $stmtBalance->execute([$ownerName]);
        $accountData = $stmtBalance->fetch();

        return $accountData ? (float)$accountData['balance'] : 0.0;
    }


    public function deleteUser(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delet user: " . $e->getMessage());
        }
    }

    public function updateUsers(string $name, int $card_number, string $role, int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE Users SET name = ?, card_number = ?, role = ? WHERE id = ?");
            return $stmt->execute([$name, $card_number, $role, $id]);
        } catch (Exception $e) {
            throw new Exception("Failed updating user: " . $e->getMessage());
        }
    }

    public function getSpecificUser(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT name, card_number, role FROM Users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error fetching user data: " . $e->getMessage());
        }
    }

    public function getAllBankAccounts()
    {
        $stmt = $this->pdo->query("SELECT * FROM BankAccounts");
        return $stmt->fetchAll();
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->pdo->query("Select id, name, card_number, role, created_date FROM Users");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Could not bring data: " . $e->getMessage());
        }
    }

    public function getOwnerWithBalance(string $ownerName)
    {
        $stmtMy = $this->pdo->prepare("SELECT id, owner, account_type, balance FROM BankAccounts WHERE owner = ?");
        $stmtMy->execute([$ownerName]);
        return $stmtMy->fetchAll();
    }

    public function getAccountById(int $fromAccount)
    {
        $stmtCheck = $this->pdo->prepare("SELECT id, owner, account_type, balance FROM BankAccounts WHERE id = ?");
        $stmtCheck->execute([$fromAccount]);
        return $stmtCheck->fetch();
    }

    public function accountsAmount(): int
    {
        $total_stmt = $this->pdo->query("SELECT COUNT(*) FROM BankAccounts");
        return (int)$total_stmt->fetchColumn();
    }

    public function getAccountsWithPagination(float $limit, float $offset): array
    {
        $stmt = $this->pdo->prepare("SELECT id, owner, account_type, balance FROM BankAccounts LIMIT :limit OFFSET :offset ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}