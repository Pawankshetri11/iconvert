<?php
// Simple local test script for license server
echo "License Server Local Test\n";
echo "========================\n\n";

// Test 1: Check if files exist
$files = [
    'index.php',
    'install.php',
    'composer.json',
    'app/Http/Controllers/Api/LicenseController.php',
    'app/Models/License.php',
    'bootstrap/app.php',
    'config/app.php',
    'vendor/autoload.php'
];

echo "File Check:\n";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}\n";
    } else {
        echo "❌ {$file} - MISSING\n";
    }
}

echo "\nDirectory Check:\n";
$dirs = [
    'app',
    'bootstrap',
    'config',
    'database',
    'resources',
    'routes',
    'storage',
    'vendor'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ {$dir}/\n";
    } else {
        echo "❌ {$dir}/ - MISSING\n";
    }
}

echo "\nAPI Endpoints to test:\n";
echo "POST /api/license/generate - Generate new license key\n";
echo "POST /api/license/activate - Activate license for domain\n";
echo "POST /api/license/validate - Validate existing license\n";

echo "\nTo test locally:\n";
echo "1. Set up a local MySQL database\n";
echo "2. Copy .env.example to .env\n";
echo "3. Configure database settings in .env\n";
echo "4. Run: php artisan migrate\n";
echo "5. Run: php artisan serve --port=8001\n";
echo "6. Visit: http://127.0.0.1:8001/install\n";

echo "\nLicense Server is ready for local testing! 🚀\n";
?>