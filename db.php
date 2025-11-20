<?php
// Database connection test file
$host = getenv('DB_HOST') ?: '136.114.93.122';
$db   = getenv('DB_NAME') ?: '89413';
$user = getenv('DB_USER') ?: 'stud';
$pass = getenv('DB_PASSWORD') ?: 'Uwb123!!';

echo "Testing database connection...\n";
echo "Host: $host\n";
echo "User: $user\n";
echo "Attempting to connect to database: $db\n\n";

// Try different possible database name formats
$possible_db_names = [
    $db,                    // 89413
    's' . $db,             // s89413
    'student_' . $db,      // student_89413
    'db_' . $db,           // db_89413
];

$connected = false;
foreach ($possible_db_names as $try_db) {
    try {
        echo "Trying database: $try_db ... ";
        $pdo = new PDO("mysql:host=$host;dbname=$try_db;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        echo "✓ SUCCESS!\n";
        echo "Connected successfully to database: $try_db on host: $host\n\n";
        
        // Test query
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Number of users in database: " . $result['count'] . "\n";
        } catch (PDOException $e) {
            echo "Note: Table 'users' does not exist yet. Run migrations.php to create it.\n";
        }
        
        $connected = true;
        break;
    } catch (PDOException $e) {
        echo "✗ Failed\n";
        continue;
    }
}

if (!$connected) {
    echo "\n❌ Could not connect to any database!\n";
    echo "Tried: " . implode(', ', $possible_db_names) . "\n";
    echo "Please check database name and credentials.\n";
    exit(1);
}
