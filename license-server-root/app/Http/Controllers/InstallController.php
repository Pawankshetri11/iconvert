<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function index()
    {
        // Check if already installed
        if (File::exists(storage_path('installed'))) {
            return redirect('/')->with('error', 'License Server is already installed.');
        }

        return view('install');
    }

    public function install(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'app_key' => 'required|string|size:32',
        ]);

        try {
            // Test database connection
            $dsn = "mysql:host={$request->db_host};dbname={$request->db_database};charset=utf8mb4";
            $pdo = new \PDO($dsn, $request->db_username, $request->db_password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Create .env file
            $envContent = File::get(base_path('.env.example'));
            $envContent = str_replace('DB_HOST=127.0.0.1', "DB_HOST={$request->db_host}", $envContent);
            $envContent = str_replace('DB_DATABASE=laravel', "DB_DATABASE={$request->db_database}", $envContent);
            $envContent = str_replace('DB_USERNAME=root', "DB_USERNAME={$request->db_username}", $envContent);
            $envContent = str_replace('DB_PASSWORD=', "DB_PASSWORD={$request->db_password}", $envContent);
            $envContent = str_replace('APP_KEY=', "APP_KEY=base64:" . base64_encode($request->app_key), $envContent);

            File::put(base_path('.env'), $envContent);

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

            // Insert test license
            $pdo->exec("
                INSERT INTO `licenses` (`license_key`, `type`, `status`, `created_at`, `updated_at`)
                VALUES ('ROYAL-TEST12345678', 'extended', 'inactive', NOW(), NOW())
                ON DUPLICATE KEY UPDATE `updated_at` = NOW();
            ");

            // Mark as installed
            File::put(storage_path('installed'), 'installed');

            return redirect('/')->with('success', 'License Server installed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Installation failed: ' . $e->getMessage());
        }
    }
}