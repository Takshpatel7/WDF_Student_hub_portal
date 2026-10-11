<?php
/**
 * Practical 8: MySQL Database Connectivity using PHP Data Objects (PDO)
 * --------------------------------------------------------------------
 * - Secure database connection with PDO
 * - Error handling using try-catch blocks and PDOException
 * - UTF-8 character encoding (utf8mb4)
 * - Prepared statement support with emulated prepares disabled
 * - Reusable getDBConnection() function
 */

// Database configuration parameters (Default for XAMPP environment)
$dbHost     = '127.0.0.1';
$dbPort     = '3306';
$dbName     = 'studenthub_db';
$dbUser     = 'root';
$dbPassword = ''; // Default XAMPP root password is empty
$dbCharset  = 'utf8mb4';

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$dbCharset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,        // Throw PDOExceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,               // Return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                          // Use native prepared statements for security
    PDO::ATTR_PERSISTENT         => false,                          // Disable persistent connections
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

$pdo = null;
$dbConnected = false;
$dbError = null;

try {
    $pdo = new PDO($dsn, $dbUser, $dbPassword, $options);
    $dbConnected = true;
} catch (PDOException $e) {
    $dbConnected = false;
    $dbError = $e->getMessage();
}

/**
 * Returns active PDO connection instance or throws an exception
 * @return PDO
 * @throws Exception
 */
function getDBConnection() {
    global $pdo, $dbConnected, $dbError;
    if ($dbConnected && $pdo instanceof PDO) {
        return $pdo;
    }
    throw new Exception("Database Connection Failed: " . ($dbError ?? "Unknown error"));
}
