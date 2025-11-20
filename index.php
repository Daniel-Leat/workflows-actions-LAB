<?php
// Define application constants
define('APP_NAME', 'LAB5 PHP MySQL App');
define('APP_VERSION', '1.0.0');

// Database connection
try {
    $host = getenv('DB_HOST') ?: '136.114.93.122';
    $db   = getenv('DB_NAME') ?: '89413';
    $user = getenv('DB_USER') ?: 'stud';
    $pass = getenv('DB_PASSWORD') ?: 'Uwb123!!';
    
    // Try different possible database name formats
    $possible_db_names = [
        $db,                    // 89413
        's' . $db,             // s89413
        'student_' . $db,      // student_89413
        'db_' . $db,           // db_89413
    ];
    
    $pdo = null;
    $last_error = '';
    
    foreach ($possible_db_names as $try_db) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$try_db;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $db = $try_db; // Save successful database name
            break;
        } catch (PDOException $e) {
            $last_error = $e->getMessage();
            continue;
        }
    }
    
    if (!$pdo) {
        throw new PDOException($last_error);
    }
    
    $db_status = '<span style="color: green;">✓ Connected to database: ' . htmlspecialchars($db) . '</span>';
    
    // Fetch users from database (create table if not exists)
    try {
        $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Table might not exist yet
        $users = [];
        $db_status .= '<br><span style="color: orange;">⚠ Table "users" not found. Run migrations.php to create tables.</span>';
    }
    
} catch (PDOException $e) {
    $db_status = '<span style="color: red;">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    $db_status .= '<br><br><strong>Troubleshooting:</strong><ul>';
    $db_status .= '<li>Tried databases: ' . implode(', ', $possible_db_names) . '</li>';
    $db_status .= '<li>Contact your database administrator to:</li>';
    $db_status .= '<li>1. Verify database name (your student number: 89413)</li>';
    $db_status .= '<li>2. Grant access from IP: ' . ($_SERVER['SERVER_ADDR'] ?? 'N/A') . '</li>';
    $db_status .= '<li>3. Run: <code>GRANT ALL PRIVILEGES ON [db_name].* TO \'stud\'@\'136.116.111.59\';</code></li>';
    $db_status .= '</ul>';
    $users = [];
}

// Get current server information
$server_info = [
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
    'PHP Version' => phpversion() ?? 'N/A',
    'Server Protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'N/A',
    'Server Address' => $_SERVER['SERVER_ADDR'] ?? 'N/A',
    'Remote Address' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
];

// Get current date and time
$current_time = date('Y-m-d H:i:s T');

// Get a selection of environment variables (for deployment context)
$env_vars = [
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'N/A',
    'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? 'N/A',
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'N/A',
];

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        h2 {
            color: #764ba2;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .version {
            color: #888;
            margin-bottom: 20px;
        }
        .status-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .db-status {
            font-size: 1.2em;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo APP_NAME; ?></h1>
        <p class="version">Version: <strong><?php echo APP_VERSION; ?></strong></p>
        <p>Prosta aplikacja PHP z połączeniem do bazy danych MySQL, wdrożona automatycznie przez GitHub Actions.</p>

        <div class="status-box">
            <h3>Status bazy danych</h3>
            <p class="db-status"><?php echo $db_status; ?></p>
        </div>

        <h2>Użytkownicy w bazie danych</h2>
        <?php if (!empty($users)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Email</th>
                    <th>Data dodania</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Brak użytkowników w bazie danych lub błąd połączenia.</p>
        <?php endif; ?>

        <h2>Status</h2>
        <table>
            <tr>
                <th>Parametr</th>
                <th>Wartość</th>
            </tr>
            <tr>
                <td>Aktualny czas</td>
                <td><?php echo htmlspecialchars($current_time); ?></td>
            </tr>
        </table>

        <h2>Informacje o serwerze</h2>
        <table>
            <?php foreach ($server_info as $key => $value): ?>
                <tr>
                    <th><?php echo htmlspecialchars($key); ?></th>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Zmienne środowiskowe i żądanie</h2>
        <table>
            <?php foreach ($env_vars as $key => $value): ?>
                <tr>
                    <th><?php echo htmlspecialchars($key); ?></th>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="footer">
            <p>LAB5 - Usługi w Chmurze | Automatyczne wdrożenie przez GitHub Actions</p>
        </div>
    </div>
</body>
</html>
