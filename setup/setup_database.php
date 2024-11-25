<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Database;

// Połączenie z bazą danych
$pdo = Database::getConnection();

// Utworzenie bazy danych, jeśli nie istnieje
$pdo->exec("CREATE DATABASE IF NOT EXISTS contracts_management");
$pdo->exec("USE contracts_management");

// Utworzenie tabeli `contracts`
$pdo->exec("
    CREATE TABLE IF NOT EXISTS contracts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        entrepreneur_name VARCHAR(255) NOT NULL,
        nip VARCHAR(10) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL
    )
");

// Wyczyszczenie istniejących danych
$pdo->exec("TRUNCATE TABLE contracts");

// Wypełnienie tabeli przykładowymi danymi
$pdo->exec("
    INSERT INTO contracts (entrepreneur_name, nip, amount) VALUES
    ('Firma Alfa', '1234567890', 15.00),
    ('Firma Beta', '9876543210', 25.50),
    ('Firma Gamma', '1122334455', 8.00),
    ('Firma Delta', '5566778899', 12.30),
    ('Firma Epsilon', '6677889900', 30.00)
");

echo "Baza danych została skonfigurowana i wypełniona przykładowymi danymi!";