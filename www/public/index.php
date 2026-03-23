<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker LEMP Stack</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.2em; opacity: 0.9; }
        .content { padding: 40px; }
        .section {
            margin-bottom: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .info-item strong {
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover { background: #f8f9fa; }
        code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐳 Docker LEMP Stack</h1>
            <p>Nginx + PHP <?php echo PHP_VERSION; ?> + MySQL</p>
        </div>
        
        <div class="content">
            <!-- PHP Info -->
            <div class="section">
                <h2>📋 PHP Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>PHP Version</strong>
                        <?php echo PHP_VERSION; ?>
                    </div>
                    <div class="info-item">
                        <strong>Server Software</strong>
                        <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?>
                    </div>
                    <div class="info-item">
                        <strong>Document Root</strong>
                        <?php echo $_SERVER['DOCUMENT_ROOT']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Server IP</strong>
                        <?php echo $_SERVER['SERVER_ADDR'] ?? 'N/A'; ?>
                    </div>
                </div>
            </div>

            <!-- MySQL Connection Test -->
            <div class="section">
                <h2>🗄️ MySQL Connection Test</h2>
                <?php
                $host = 'mysql';
                $db = getenv('MYSQL_DATABASE') ?: 'myapp_db';
                $user = getenv('MYSQL_USER') ?: 'myapp_user';
                $pass = getenv('MYSQL_PASSWORD') ?: 'myapp_password_123';
                
                try {
                    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
                    $pdo = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                    
                    echo '<span class="status success">✓ Connected Successfully</span>';
                    
                    // Get MySQL version
                    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
                    echo '<div class="info-grid" style="margin-top: 15px;">';
                    echo '<div class="info-item"><strong>MySQL Version</strong>' . $version . '</div>';
                    echo '<div class="info-item"><strong>Database</strong>' . $db . '</div>';
                    echo '<div class="info-item"><strong>Host</strong>' . $host . '</div>';
                    echo '<div class="info-item"><strong>User</strong>' . $user . '</div>';
                    echo '</div>';
                    
                    // Query sample data
                    $stmt = $pdo->query('SELECT * FROM users LIMIT 10');
                    $users = $stmt->fetchAll();
                    
                    if ($users) {
                        echo '<h3 style="margin-top: 20px; color: #667eea;">Sample Data from "users" table:</h3>';
                        echo '<table>';
                        echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th></tr>';
                        foreach ($users as $user) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($user['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['created_at']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    }
                    
                } catch (PDOException $e) {
                    echo '<span class="status error">✗ Connection Failed</span>';
                    echo '<p style="margin-top: 15px; color: #721c24;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
                ?>
            </div>

            <!-- PHP Extensions -->
            <div class="section">
                <h2>🔌 Loaded PHP Extensions</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 15px;">
                    <?php
                    $extensions = get_loaded_extensions();
                    sort($extensions);
                    foreach ($extensions as $ext) {
                        echo '<div style="background: white; padding: 10px; border-radius: 6px; border: 1px solid #e0e0e0; text-align: center;">' . $ext . '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Quick Commands -->
            <div class="section">
                <h2>⚡ Quick Commands</h2>
                <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 15px;">
                    <p style="margin-bottom: 10px;"><strong>Start containers:</strong> <code>docker-compose up -d</code></p>
                    <p style="margin-bottom: 10px;"><strong>Stop containers:</strong> <code>docker-compose down</code></p>
                    <p style="margin-bottom: 10px;"><strong>View logs:</strong> <code>docker-compose logs -f</code></p>
                    <p style="margin-bottom: 10px;"><strong>Rebuild PHP:</strong> <code>docker-compose up -d --build php</code></p>
                    <p style="margin-bottom: 10px;"><strong>MySQL CLI:</strong> <code>docker exec -it mysql mysql -u root -p</code></p>
                    <p><strong>PHP container bash:</strong> <code>docker exec -it php bash</code></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
