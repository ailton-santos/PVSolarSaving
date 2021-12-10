<?php

require_once __DIR__.'/configuration.php';

/*
 * CREATE THE DATABASE
 */
$pdoDatabase = new PDO('mysql:host=localhost', $databaseUser, $databasePassword);
$pdoDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$createDB = 'CREATE DATABASE IF NOT EXISTS ' . $databaseName . ';';
$pdoDatabase->exec($createDB);

/*
 * CREATE THE TABLE
 */
$pdo = new PDO('mysql:host=localhost;dbname='.$databaseName, $databaseUser, $databasePassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// initialize the table
$pdo->exec('DROP TABLE IF EXISTS calculator;');

$pdo->exec('CREATE TABLE `calculator` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `e_bill` numeric(15,2) NOT NULL,
 `est_cost` numeric(15,2) NOT NULL,
 `zip_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
 `percent_saved` numeric(15,2) NULL,
 `payback_years` int(11) NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

/*
 * INSERT SOME DATA!
 */
$pdo->exec('INSERT INTO calculator
    (e_bill, est_cost, zip_code, percent_saved, payback_years) VALUES
    (240.35, 35000.25, "62901", 15.34, 21)'
);
$pdo->exec('INSERT INTO calculator
    (e_bill, est_cost, zip_code, percent_saved, payback_years) VALUES
    (32.35, 12450.50, "60608", 54.5, 23)'
);

echo "Complete!\n";
