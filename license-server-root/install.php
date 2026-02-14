<?php
// Simple installation script that works without Laravel fully loaded

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle installation
    $db_host = $_POST['db_host'] ?? '';
    $db_database = $_POST['db_database'] ?? '';
    $db_username = $_POST['db_username'] ?? '';
    $db_password = $_POST['db_password'] ?? '';
    $app_key = $_POST['app_key'] ?? '';

    try {
        // Test database connection
        $dsn = "mysql:host={$db_host};dbname={$db_database};charset=utf8mb4";
        $pdo = new PDO($dsn, $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create .env file
        $envContent = file_get_contents(__DIR__ . '/.env.example');
        $envContent = str_replace('DB_HOST=127.0.0.1', "DB_HOST={$db_host}", $envContent);
        $envContent = str_replace('DB_DATABASE=laravel', "DB_DATABASE={$db_database}", $envContent);
        $envContent = str_replace('DB_USERNAME=root', "DB_USERNAME={$db_username}", $envContent);
        $envContent = str_replace('DB_PASSWORD=', "DB_PASSWORD={$db_password}", $envContent);
        $envContent = str_replace('APP_KEY=', "APP_KEY=base64:" . base64_encode($app_key), $envContent);

        file_put_contents(__DIR__ . '/.env', $envContent);

        // Create users table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
              `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `email_verified_at` timestamp NULL DEFAULT NULL,
              `password` varchar(255) NOT NULL,
              `remember_token` varchar(100) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `users_email_unique` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create licenses table
        $sql = "
            CREATE TABLE IF NOT EXISTS `licenses` (
              `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
              `license_key` varchar(255) NOT NULL,
              `domain` varchar(255) DEFAULT NULL,
              `client_name` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `type` varchar(255) NOT NULL DEFAULT 'standard',
              `status` enum('inactive','active','suspended') NOT NULL DEFAULT 'inactive',
              `activated_at` timestamp NULL DEFAULT NULL,
              `expires_at` timestamp NULL DEFAULT NULL,
              `suspended_at` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `licenses_license_key_unique` (`license_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        $pdo->exec($sql);

        // Insert admin user
        $passwordHash = password_hash('password', PASSWORD_DEFAULT);
        $pdo->exec("
            INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`)
            VALUES ('Admin', 'admin@license-server.com', '{$passwordHash}', NOW(), NOW())
            ON DUPLICATE KEY UPDATE `updated_at` = NOW();
        ");

        // Generate a unique license key for this installation
        $uniqueKey = 'ROYAL-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 16));

        // Insert generated license
        $pdo->exec("
            INSERT INTO `licenses` (`license_key`, `type`, `status`, `created_at`, `updated_at`)
            VALUES ('{$uniqueKey}', 'extended', 'inactive', NOW(), NOW())
            ON DUPLICATE KEY UPDATE `updated_at` = NOW();
        ");

        // Mark as installed
        file_put_contents(__DIR__ . '/storage/installed', 'installed');

        // Redirect to success with the generated key
        header('Location: /?installed=1&key=' . urlencode($uniqueKey));
        exit;

    } catch (Exception $e) {
        $error = 'Installation failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Server Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">License Server Setup</h1>
            <p class="text-gray-600 mt-2">Configure your database connection</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                <input type="text" name="db_host" value="localhost" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                <input type="text" name="db_database" placeholder="license_db" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Username</label>
                <input type="text" name="db_username" placeholder="db_user" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Password</label>
                <input type="password" name="db_password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Application Key (32 characters)</label>
                <input type="text" name="app_key" placeholder="Generate a random 32-char key" required maxlength="32"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">
                <p class="text-xs text-gray-500 mt-1">Generate with: <code>openssl rand -base64 32</code></p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Install License Server
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Need help? Check the README.md file for detailed instructions.</p>
        </div>
    </div>
</body>
</html>