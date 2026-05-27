CREATE TABLE IF NOT EXISTS Customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    card_number varchar(16) NOT NULL UNIQUE,
    pin_hash varchar(256) NOT NULL,
    balance DECIMAL(10, 2) DEFAULT 0.00,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name varchar(256) NOT NULL,
    card_number varchar(20) NOT NULL,
    role varchar(30) NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type varchar(30) NOT NULL,
    amount decimal(10, 2) NOT NULL,
    from_account varchar(30) NOT NULL,
    to_account varchar(30) NOT NULL,
    date TIMESTAMP default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS BankAccounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner VARCHAR(50) NOT NULL,
    account_type varchar(50) NOT NULL,
    balance DECIMAL(10, 2) NOT NULL
)