<?php
require 'src/db.php';

$customersToCreate = [
    ['Anna Andersson', '9876', '9800', 1200.00, 'user'],
    ['Johan Carlström', '3900', '5400', 4500.00, 'user'],
    ['Sara Eriksson', '8400', '3400', 7500.00, 'admin'],
];

$usersToCreate = [
    ['Anna Carlsson', '9876', 'user'],
    ['Johan Carlström', '3900', 'user'],
    ['Sara Eriksson', '8400', 'admin'],
];

$transactionsToCreate = [
    ['Deposit',  2000.00,  'ATM', '9876'],
    ['Withdraw', 500.00,   '5432', 'ATM'],
    ['Transfer', 350.00,   '1642', '9876'],
    ['Deposit',  10000.00, 'Employer', '9876'],
];

try {
    $sqlCustomers = "INSERT INTO Customers (name, card_number, pin_hash, balance, role) VALUES (?, ?, ?, ?, ?)";
    $stmtCustomers = $pdo->prepare($sqlCustomers);

    foreach ($customersToCreate as $c) {

        $hashedPin = password_hash($c[2], PASSWORD_BCRYPT);
        $stmtCustomers->execute([$c[0], $c[1], $hashedPin, $c[3], $c[4]]);
    }
    echo "Customers seeded successfully!<br>";
} catch (PDOException $e) {
    echo "Failed seeding Customers: " . $e->getMessage() . "<br>";
}

try {
    $sqlUsers = "INSERT INTO Users (name, card_number, role) VALUES (?, ?, ?)";
    $stmtUsers = $pdo->prepare($sqlUsers);

    foreach ($usersToCreate as $u) {
        $stmtUsers->execute([$u[0], $u[1], $u[2]]);
    }
    echo "Users seeded successfully!<br>";
} catch (PDOException $e) {
    echo "Failed seeding Users: " . $e->getMessage() . "<br>";
}

try {
    $sqlTransactions = "INSERT INTO Transactions (type, amount, from_account, to_account) VALUES (?, ?, ?, ?)";
    $stmtTransactions = $pdo->prepare($sqlTransactions);

    foreach ($transactionsToCreate as $t) {
        $stmtTransactions->execute([$t[0], $t[1], $t[2], $t[3]]);
    }
    echo "Transactions seeded successfully!<br>";
} catch (PDOException $e) {
    echo "Failed seeding Transactions: " . $e->getMessage() . "<br>";
}

$bankAccountsToCreate = [
    ['Andreas Ek', 'Personal Account', 8300.00],
    ['Andreas Ek', 'Savings Account', 41000.00],
    ['Ida Mattsson', 'Checking Account', 3100.50],
    ['Ida Mattsson', 'Travel Fund', 12000.00],
    ['Thomas Hermansson', 'Business Account', 89000.00],
    ['Thomas Hermansson', 'Tax Account', 14500.00],
    ['Hanna Ström', 'Personal Account', 6200.00],
    ['Hanna Ström', 'Investments', 55000.00],
    ['Viktor Ekdahl', 'Crypto Wallet', 7400.00],
    ['Viktor Ekdahl', 'Personal Account', 900.00],
    ['Amanda Sundberg', 'Savings Account', 28500.00],
    ['Amanda Sundberg', 'Buffer Account', 9500.00],
    ['Peter Fransson', 'Pension Account', 140000.00],
    ['Peter Fransson', 'Personal Account', 11200.00],
    ['Cecilia Lindqvist', 'Shared Account', 6800.00],
    ['Cecilia Lindqvist', 'Vacation Account', 18500.00],
    ['Marcus Jonsson', 'Personal Account', 4300.00],
    ['Marcus Jonsson', 'Car Fund', 22000.00],
    ['Lovisa Åkesson', 'Student Account', 650.00],
    ['Lovisa Åkesson', 'Savings Account', 8000.00],
    ['Simon Norberg', 'Business Account', 125000.00],
    ['Simon Norberg', 'Personal Account', 16700.00],
    ['Matilda Sjöberg', 'Investments', 33000.00],
    ['Matilda Sjöberg', 'Checking Account', 2900.00],
    ['Robert Henriksson', 'Savings Account', 52000.00],
    ['Robert Henriksson', 'Renovation Fund', 35000.00],
    ['Jenny Ahlin', 'Personal Account', 9100.00],
    ['Jenny Ahlin', 'Children Savings', 15000.00],
    ['Filip Sandström', 'Crypto Account', 11500.00],
    ['Filip Sandström', 'Personal Account', 1400.50],
    ['Julia Boström', 'Checking Account', 5300.00],
    ['Julia Boström', 'Savings Account', 64000.00],
    ['Anton Hellström', 'Business Account', 42000.00],
    ['Anton Hellström', 'Tax Account', 7800.00],
    ['Moa Lundgren', 'Personal Account', 12400.00],
    ['Moa Lundgren', 'Investments', 21000.00],
    ['Jakob Berglund', 'Car Fund', 19500.00],
    ['Jakob Berglund', 'Personal Account', 3200.00],
    ['Linnéa Arvidsson', 'Student Account', 1100.00],
    ['Linnéa Arvidsson', 'Savings Account', 14000.00],
    ['Sebastian Hedlund', 'Pension Account', 98000.00],
    ['Sebastian Hedlund', 'Checking Account', 6700.00],
    ['Frida Axelsson', 'Shared Account', 8900.00],
    ['Frida Axelsson', 'Travel Fund', 25000.00],
    ['Daniel Nyström', 'Personal Account', 7100.00],
    ['Daniel Nyström', 'Investments', 43000.00],
    ['Ebba Mårtensson', 'Savings Account', 37000.00],
    ['Ebba Mårtensson', 'Buffer Account', 11000.00],
    ['Christoffer Dahl', 'Business Account', 68000.00],
    ['Christoffer Dahl', 'Personal Account', 5400.00],
    ['Sanna Björk', 'Checking Account', 2200.00],
    ['Sanna Björk', 'Holiday Account', 13500.00],
    ['Martin Falk', 'Crypto Wallet', 2800.00],
    ['Martin Falk', 'Personal Account', 850.00],
    ['Sara Haglund', 'Savings Account', 79000.00],
    ['Sara Haglund', 'Investments', 115000.00],
    ['Alexander Skog', 'Pension Account', 160000.00],
    ['Alexander Skog', 'Personal Account', 14200.00],
    ['Evelina bergqvist', 'Shared Account', 5100.00],
    ['Evelina bergqvist', 'Car Fund', 8000.00],
    ['Patrik malm', 'Business Account', 31000.00],
    ['Patrik malm', 'Tax Account', 5200.00],
    ['Caroline ekund', 'Personal Account', 10500.00],
    ['Caroline ekund', 'Savings Account', 48000.00],
    ['Henrik jansson', 'Checking Account', 3900.00],
    ['Henrik jansson', 'Travel Fund', 16000.00],
    ['Louise palm', 'Investments', 27000.00],
    ['Louise palm', 'Personal Account', 6100.00],
    ['Erik wall', 'Savings Account', 53000.00],
    ['Erik wall', 'Buffer Account', 12000.00]
];

try {
    $sql = "INSERT INTO BankAccounts (owner, account_type, balance) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($bankAccountsToCreate as $b) {
        $stmt->execute([$b[0], $b[1], $b[2]]);
    }

    echo "Successfully seeded data via seed.php";
} catch (PDOException $e) {
    echo "Failed while adding: " . $e->getMessage();
}