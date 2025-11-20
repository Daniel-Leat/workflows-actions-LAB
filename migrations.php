<?php
// Database migrations script
$host = getenv('DB_HOST') ?: '136.114.93.122';
$db   = getenv('DB_NAME') ?: 'your_db_name';
$user = getenv('DB_USER') ?: 'stud';
$pass = getenv('DB_PASSWORD') ?: 'Uwb123!!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "Connected to database successfully.\n";
    
    // Create migrations table if not exists
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_filename (filename)
    )
    ");
    
    echo "Migrations table ready.\n";
    
    $dir = __DIR__ . '/sql';
    if (!is_dir($dir)) {
        echo "No sql directory found. Creating one...\n";
        mkdir($dir, 0755, true);
        exit(0);
    }
    
    $files = glob("$dir/*.sql");
    if (empty($files)) {
        echo "No migration files found in sql directory.\n";
        exit(0);
    }
    
    sort($files);
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE filename = ?");
        $stmt->execute([$filename]);
        if ($stmt->fetchColumn() > 0) {
            echo "✓ $filename already applied.\n";
            continue;
        }
        
        $sql = file_get_contents($file);
        $pdo->beginTransaction();
        try {
            $pdo->exec($sql);
            $pdo->commit();
            
            $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
            $stmt->execute([$filename]);
            
            echo "✓ Applied $filename\n";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "✗ Error applying $filename: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    echo "\nAll migrations completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
