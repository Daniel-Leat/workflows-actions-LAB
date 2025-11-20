<?php
// Database connection test file
$host = getenv('DB_HOST') ?: '136.114.93.122';
$db   = getenv('DB_NAME') ?: 'your_db_name';
$user = getenv('DB_USER') ?: 'stud';
$pass = getenv('DB_PASSWORD') ?: 'Uwb123!!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "Connected successfully to database: $db on host: $host\n";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Number of users in database: " . $result['count'] . "\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
