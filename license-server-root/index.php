<?php
// Simple license server - no Laravel dependencies required
// This is a standalone PHP server that doesn't need composer

$host = '127.0.0.1';
$port = getenv('PORT') ?: 8001;

// License storage file
$licenseFile = __DIR__ . '/licenses.json';

// Load existing licenses
$licenses = [];
if (file_exists($licenseFile)) {
    $licenses = json_decode(file_get_contents($licenseFile), true) ?: [];
}

// Save licenses function
function saveLicenses($licenses, $file) {
    file_put_contents($file, json_encode($licenses, JSON_PRETTY_PRINT));
}

// Simple routing
$routes = [
    'GET /' => function() {
        return [
            'content' => json_encode([
                'message' => 'License Server API',
                'status' => 'running',
                'version' => '1.0',
                'endpoints' => [
                    'POST /api/license/generate' => 'Generate license key',
                    'POST /api/license/activate' => 'Activate license',
                    'POST /api/license/validate' => 'Validate license',
                    'GET /install' => 'Web installer'
                ]
            ]),
            'content_type' => 'application/json'
        ];
    },
    'GET /admin' => function() {
        // Check if installed
        if (!file_exists(__DIR__ . '/storage/installed')) {
            header('Location: /install');
            exit;
        }

        // Load licenses from database
        $licenses = [];
        try {
            if (file_exists(__DIR__ . '/.env')) {
                // Read .env file manually since parse_ini_file has issues with quotes
                $envContent = file_get_contents(__DIR__ . '/.env');
                $env = [];
                foreach (explode("\n", $envContent) as $line) {
                    $line = trim($line);
                    if (empty($line) || strpos($line, '#') === 0) continue;
                    list($key, $value) = explode('=', $line, 2);
                    $env[trim($key)] = trim($value, '"');
                }

                if (!empty($env['DB_HOST'])) {
                    $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};charset=utf8mb4";
                    $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->query("SELECT * FROM licenses ORDER BY created_at DESC");
                    $licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (Exception $e) {
            $licenses = [];
        }

        $licensesTable = '';
        if (!empty($licenses)) {
            $licensesTable = '<table style="width:100%; border-collapse:collapse; margin-top:20px;">
                <thead>
                    <tr style="background:#f8f9fa;">
                        <th style="border:1px solid #ddd; padding:8px;">License Key</th>
                        <th style="border:1px solid #ddd; padding:8px;">Domain</th>
                        <th style="border:1px solid #ddd; padding:8px;">Client</th>
                        <th style="border:1px solid #ddd; padding:8px;">Status</th>
                        <th style="border:1px solid #ddd; padding:8px;">Activated</th>
                        <th style="border:1px solid #ddd; padding:8px;">Actions</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($licenses as $license) {
                $statusColor = $license['status'] === 'active' ? '#10b981' : ($license['status'] === 'suspended' ? '#ef4444' : '#6b7280');
                $licensesTable .= "<tr>
                    <td style='border:1px solid #ddd; padding:8px; font-family:monospace;'>{$license['license_key']}</td>
                    <td style='border:1px solid #ddd; padding:8px;'>{$license['domain']}</td>
                    <td style='border:1px solid #ddd; padding:8px;'>{$license['client_name']}</td>
                    <td style='border:1px solid #ddd; padding:8px;'><span style='color:{$statusColor}; font-weight:bold;'>{$license['status']}</span></td>
                    <td style='border:1px solid #ddd; padding:8px;'>{$license['activated_at']}</td>
                    <td style='border:1px solid #ddd; padding:8px;'>
                        <form method='POST' action='/admin/suspend/{$license['license_key']}' style='display:inline;'>
                            <button type='submit' style='background:#ef4444; color:white; border:none; padding:4px 8px; border-radius:4px; cursor:pointer;'>Suspend</button>
                        </form>
                    </td>
                </tr>";
            }
            $licensesTable .= '</tbody></table>';
        } else {
            $licensesTable = '<p style="color:#666; margin-top:20px;">No licenses found. Generate some licenses to get started.</p>';
        }

        return [
            'content' => '<!DOCTYPE html>
            <html>
            <head>
                <title>License Server Admin</title>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
                    .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .stats { display: flex; gap: 20px; margin-bottom: 20px; }
                    .stat-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .stat-number { font-size: 24px; font-weight: bold; color: #667eea; }
                    .generate-form { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .btn { background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; }
                    .btn:hover { background: #5a67d8; }
                    .btn-danger { background: #ef4444; }
                    .btn-danger:hover { background: #dc2626; }
                    table { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    th { background: #f8f9fa; font-weight: 600; }
                    td { border-bottom: 1px solid #e5e7eb; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>🚀 License Server Admin Dashboard</h1>
                    <p>Manage your license keys and monitor activations</p>
                </div>

                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number">' . count($licenses) . '</div>
                        <div>Total Licenses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">' . count(array_filter($licenses, fn($l) => $l['status'] === 'active')) . '</div>
                        <div>Active Licenses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">' . count(array_filter($licenses, fn($l) => $l['status'] === 'inactive')) . '</div>
                        <div>Available Licenses</div>
                    </div>
                </div>

                <div class="generate-form">
                    <h3>Generate New License Key</h3>
                    <form method="POST" action="/admin/generate">
                        <label style="display:block; margin-bottom:8px;">License Type:</label>
                        <select name="type" style="padding:8px; margin-right:10px; border:1px solid #ddd; border-radius:4px;">
                            <option value="standard">Standard</option>
                            <option value="extended">Extended</option>
                        </select>
                        <label style="margin-left:10px;">Expiry:</label>
                        <select name="expires_at" style="padding:8px; margin-left:5px; border:1px solid #ddd; border-radius:4px;">
                            <option value="lifetime">Lifetime (No Expiry)</option>
                            <option value="">Custom Date</option>
                        </select>
                        <input type="date" name="custom_expires_at" id="custom_expires" style="padding:8px; margin-left:5px; border:1px solid #ddd; border-radius:4px; display:none;">
                        <button type="submit" class="btn" style="margin-left:10px;">Generate License</button>
                    </form>
                    <script>
                        document.querySelector("select[name=\"expires_at\"]").addEventListener("change", function() {
                            var customDate = document.getElementById("custom_expires");
                            customDate.style.display = this.value === "" ? "inline-block" : "none";
                            customDate.required = this.value === "";
                        });
                    </script>
                </div>

                <div class="generate-form">
                    <h3>License Keys</h3>
                    ' . $licensesTable . '
                </div>
            </body>
            </html>',
            'content_type' => 'text/html'
        ];
    },
    'POST /admin/generate' => function($post) {
        if (!file_exists(__DIR__ . '/storage/installed')) {
            return ['content' => json_encode(['error' => 'Not installed']), 'content_type' => 'application/json'];
        }

        try {
            // Read .env file manually
            $envContent = file_get_contents(__DIR__ . '/.env');
            $env = [];
            foreach (explode("\n", $envContent) as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                $env[trim($key)] = trim($value, '"');
            }

            $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};charset=utf8mb4";
            $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $key = 'ROYAL-' . strtoupper(substr(md5(uniqid()), 0, 16));
            $type = $post['type'] ?? 'standard';

            // Handle expiry logic
            if (isset($post['expires_at']) && $post['expires_at'] === 'lifetime') {
                $expiresAt = null; // Lifetime license
            } elseif (!empty($post['custom_expires_at'])) {
                $expiresAt = $post['custom_expires_at'] . ' 23:59:59';
            } else {
                $expiresAt = null;
            }

            $stmt = $pdo->prepare("
                INSERT INTO licenses (license_key, type, expires_at, status, created_at)
                VALUES (?, ?, ?, 'inactive', NOW())
            ");
            $stmt->execute([$key, $type, $expiresAt]);

            header('Location: /admin?generated=' . urlencode($key));
            exit;

        } catch (Exception $e) {
            return ['content' => json_encode(['error' => $e->getMessage()]), 'content_type' => 'application/json'];
        }
    },
    'POST /admin/suspend/*' => function($post) {
        $path = $_SERVER['REQUEST_URI'];
        $key = basename($path);

        if (!file_exists(__DIR__ . '/storage/installed')) {
            return ['content' => json_encode(['error' => 'Not installed']), 'content_type' => 'application/json'];
        }

        try {
            // Read .env file manually
            $envContent = file_get_contents(__DIR__ . '/.env');
            $env = [];
            foreach (explode("\n", $envContent) as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                $env[trim($key)] = trim($value, '"');
            }

            $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};charset=utf8mb4";
            $pdo = new PDO($dsn, $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                UPDATE licenses SET status = 'suspended', suspended_at = NOW(), updated_at = NOW()
                WHERE license_key = ?
            ");
            $stmt->execute([$key]);

            header('Location: /admin?suspended=' . urlencode($key));
            exit;

        } catch (Exception $e) {
            return ['content' => json_encode(['error' => $e->getMessage()]), 'content_type' => 'application/json'];
        }
    },
    'GET /install' => function() {
        return [
            'content' => '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>License Server Installation</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    margin: 0;
                    padding: 0;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .container {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                    padding: 40px;
                    max-width: 500px;
                    width: 90%;
                }
                h1 {
                    color: #333;
                    text-align: center;
                    margin-bottom: 30px;
                    font-size: 28px;
                    font-weight: 600;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                label {
                    display: block;
                    margin-bottom: 8px;
                    color: #555;
                    font-weight: 500;
                    font-size: 14px;
                }
                input[type="text"], input[type="password"] {
                    width: 100%;
                    padding: 12px 16px;
                    border: 2px solid #e1e5e9;
                    border-radius: 8px;
                    font-size: 16px;
                    transition: border-color 0.3s ease;
                    box-sizing: border-box;
                }
                input[type="text"]:focus, input[type="password"]:focus {
                    outline: none;
                    border-color: #667eea;
                    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                }
                .help-text {
                    font-size: 12px;
                    color: #666;
                    margin-top: 4px;
                }
                button {
                    width: 100%;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                    padding: 14px 20px;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: transform 0.2s ease;
                }
                button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                }
                .success {
                    background: #10b981;
                    color: white;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    text-align: center;
                }
                .error {
                    background: #ef4444;
                    color: white;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🚀 License Server Setup</h1>
                <form method="POST" action="/install">
                    <div class="form-group">
                        <label for="db_host">Database Host</label>
                        <input type="text" id="db_host" name="db_host" value="localhost" required>
                    </div>

                    <div class="form-group">
                        <label for="db_database">Database Name</label>
                        <input type="text" id="db_database" name="db_database" value="license_db" required>
                    </div>

                    <div class="form-group">
                        <label for="db_username">Database Username</label>
                        <input type="text" id="db_username" name="db_username" value="root" required>
                    </div>

                    <div class="form-group">
                        <label for="db_password">Database Password</label>
                        <input type="password" id="db_password" name="db_password">
                        <div class="help-text">Leave empty if no password</div>
                    </div>

                    <div class="form-group">
                        <label for="app_key">Application Key (32 characters)</label>
                        <input type="text" id="app_key" name="app_key" value="c3VwZXJzZWNyZXRrZXl0aGF0aXMyY2hhcmFjdGVycw==" required maxlength="32">
                        <div class="help-text">Auto-generated secure key</div>
                    </div>

                    <button type="submit">Install License Server</button>
                </form>
            </div>
        </body>
        </html>',
            'content_type' => 'text/html'
        ];
    },
    'POST /install' => function($post) {
        try {
            // Validate required fields
            if (empty($post['db_host']) || empty($post['db_database']) || empty($post['db_username'])) {
                throw new Exception('Missing required database fields');
            }

            // Test database connection
            $dsn = "mysql:host={$post['db_host']};dbname={$post['db_database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $post['db_username'], $post['db_password'] ?? '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create licenses table
            $sql = "
                CREATE TABLE IF NOT EXISTS `licenses` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `license_key` varchar(255) NOT NULL,
                  `domain` varchar(255) DEFAULT NULL,
                  `client_name` varchar(255) DEFAULT NULL,
                  `email` varchar(255) DEFAULT NULL,
                  `type` varchar(50) NOT NULL DEFAULT 'standard',
                  `status` enum('inactive','active','suspended') NOT NULL DEFAULT 'inactive',
                  `activated_at` datetime DEFAULT NULL,
                  `expires_at` datetime DEFAULT NULL,
                  `suspended_at` datetime DEFAULT NULL,
                  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `license_key` (`license_key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";

            $pdo->exec($sql);

            // Insert test license
            $testKey = 'ROYAL-' . strtoupper(substr(md5(uniqid()), 0, 16));
            $stmt = $pdo->prepare("
                INSERT INTO licenses (license_key, type, status, created_at)
                VALUES (?, 'extended', 'inactive', NOW())
                ON DUPLICATE KEY UPDATE updated_at = NOW()
            ");
            $stmt->execute([$testKey]);

            // Save configuration to .env file
            $envContent = "APP_NAME=\"License Server\"\n";
            $envContent .= "APP_KEY=\"" . (isset($post['app_key']) ? $post['app_key'] : 'c3VwZXJzZWNyZXRrZXl0aGF0aXMyY2hhcmFjdGVycw==') . "\"\n";
            $envContent .= "DB_CONNECTION=mysql\n";
            $envContent .= "DB_HOST=\"{$post['db_host']}\"\n";
            $envContent .= "DB_DATABASE=\"{$post['db_database']}\"\n";
            $envContent .= "DB_USERNAME=\"{$post['db_username']}\"\n";
            $envContent .= "DB_PASSWORD=\"" . ($post['db_password'] ?? '') . "\"\n";

            file_put_contents(__DIR__ . '/.env', $envContent);

            // Mark as installed
            file_put_contents(__DIR__ . '/storage/installed', 'installed');

            // Return success with redirect to dashboard
            return [
                'content' => '<!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv="refresh" content="2;url=/admin">
                    <title>Installation Complete</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                        .success { color: #10b981; font-size: 24px; }
                        .redirect { color: #666; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="success">✅ Installation Complete!</div>
                    <p>Database tables created successfully.</p>
                    <p>Test license key generated: <strong>' . $testKey . '</strong></p>
                    <div class="redirect">Redirecting to admin dashboard...</div>
                </body>
                </html>',
                'content_type' => 'text/html'
            ];

        } catch (Exception $e) {
            return [
                'content' => json_encode([
                    'success' => false,
                    'message' => 'Installation failed: ' . $e->getMessage()
                ]),
                'content_type' => 'application/json'
            ];
        }
    },
    'POST /api/license/generate' => function($post) {
        $key = 'ROYAL-' . strtoupper(substr(md5(uniqid()), 0, 16));
        return [
            'content' => json_encode([
                'license_key' => $key,
                'type' => $post['type'] ?? 'extended',
                'message' => 'License key generated successfully'
            ]),
            'content_type' => 'application/json'
        ];
    },
    'POST /api/license/activate' => function($post) use (&$licenses, $licenseFile) {
        $key = $post['license_key'] ?? '';
        $domain = $post['domain'] ?? '';

        if (empty($key) || empty($domain)) {
            return [
                'content' => json_encode([
                    'valid' => false,
                    'message' => 'Missing license_key or domain'
                ]),
                'content_type' => 'application/json'
            ];
        }

        // Check if license exists and domain locking
        if (isset($licenses[$key])) {
            if ($licenses[$key]['domain'] !== $domain) {
                return [
                    'content' => json_encode([
                        'valid' => false,
                        'message' => 'License already activated on another domain'
                    ]),
                    'content_type' => 'application/json'
                ];
            }
            // Reactivate on same domain
            $licenses[$key]['activated_at'] = date('Y-m-d H:i:s');
        } else {
            // New activation
            $licenses[$key] = [
                'domain' => $domain,
                'client_name' => $post['client_name'] ?? '',
                'email' => $post['email'] ?? '',
                'type' => 'extended',
                'status' => 'active',
                'activated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        saveLicenses($licenses, $licenseFile);

        return [
            'content' => json_encode([
                'valid' => true,
                'type' => 'extended',
                'message' => 'License activated successfully for domain: ' . $domain
            ]),
            'content_type' => 'application/json'
        ];
    },
    'POST /api/license/validate' => function($post) use (&$licenses) {
        $key = $post['license_key'] ?? '';
        $domain = $post['domain'] ?? '';

        if (isset($licenses[$key]) && $licenses[$key]['domain'] === $domain && $licenses[$key]['status'] === 'active') {
            return [
                'content' => json_encode([
                    'valid' => true,
                    'type' => $licenses[$key]['type'],
                    'message' => 'License is valid'
                ]),
                'content_type' => 'application/json'
            ];
        }

        return [
            'content' => json_encode([
                'valid' => false,
                'message' => 'License not found or invalid'
            ]),
            'content_type' => 'application/json'
        ];
    }
];

// Handle the request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remove query string and normalize path
$path = parse_url($requestUri, PHP_URL_PATH);
$path = '/' . ltrim($path, '/'); // Ensure it starts with /

/*
// Debug: Uncomment to see what path is being matched
header('Content-Type: text/plain');
echo "Method: $method\n";
echo "Request URI: $requestUri\n";
echo "Parsed Path: $path\n";
echo "Route Key: $method $path\n";
exit;
*/

// Get POST data
$postData = [];
if ($method === 'POST') {
    $postData = $_POST;
    // Handle JSON POST data
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $jsonData = json_decode($input, true);
        if ($jsonData) {
            $postData = array_merge($postData, $jsonData);
        }
    }
}

// Handle dynamic routes
$routeResponse = null;
$routeKey = $method . ' ' . $path;

// Check exact matches first
if (isset($routes[$routeKey])) {
    $routeResponse = $routes[$routeKey]($postData);
} else {
    // Check wildcard routes
    foreach ($routes as $routePattern => $handler) {
        if (strpos($routePattern, '*') !== false) {
            $pattern = str_replace('*', '.*', $routePattern);
            if (preg_match('#^' . $pattern . '$#', $routeKey)) {
                $routeResponse = $handler($postData);
                break;
            }
        }
    }
}

if (!$routeResponse) {
    $routeResponse = [
        'content' => json_encode([
            'error' => 'Route not found',
            'method' => $method,
            'path' => $path,
            'route_key' => $routeKey,
            'available_routes' => array_keys($routes)
        ]),
        'content_type' => 'application/json'
    ];
}

// Handle different response formats
if (is_array($routeResponse)) {
    $response = $routeResponse['content'];
    $contentType = $routeResponse['content_type'] ?? 'application/json';
} else {
    $response = $routeResponse;
    $contentType = 'application/json';
}

// Send response
header('Content-Type: ' . $contentType);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
echo $response;
?>