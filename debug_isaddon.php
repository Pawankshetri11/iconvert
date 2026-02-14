<?php

/**
 * Debug isAddonEnabled Function
 */

echo "=== DEBUGGING isAddonEnabled ===\n\n";

// Test 1: Direct config reading
echo "1. Direct config reading...\n";
$configPath = __DIR__ . '/config/system.php';
if (file_exists($configPath)) {
    $config = include $configPath;
    $statuses = $config['addons']['statuses'] ?? [];
    echo "Direct read - pdf-converter: " . ($statuses['pdf-converter'] ? 'true' : 'false') . "\n";
    echo "Direct read - image-converter: " . ($statuses['image-converter'] ? 'true' : 'false') . "\n";
} else {
    echo "Config file not found\n";
}

echo "\n";

// Test 2: isAddonEnabled function
echo "2. Testing isAddonEnabled function...\n";
require_once __DIR__ . '/core/helpers/functions.php';

$result1 = isAddonEnabled('pdf-converter');
$result2 = isAddonEnabled('image-converter');

echo "isAddonEnabled('pdf-converter'): " . ($result1 ? 'true' : 'false') . "\n";
echo "isAddonEnabled('image-converter'): " . ($result2 ? 'true' : 'false') . "\n";

echo "\n";

// Test 3: Check if Laravel config is available
echo "3. Checking Laravel config availability...\n";
if (function_exists('app') && app()->bound('config')) {
    echo "Laravel config available\n";
    $laravelStatuses = app('config')->get('system.addons.statuses', []);
    echo "Laravel config - pdf-converter: " . ($laravelStatuses['pdf-converter'] ?? 'not found') . "\n";
    echo "Laravel config - image-converter: " . ($laravelStatuses['image-converter'] ?? 'not found') . "\n";
} else {
    echo "Laravel config not available\n";
}

echo "\n";

// Test 4: Force re-read
echo "4. Force re-read test...\n";
function isAddonEnabledDirect($addonSlug) {
    $configPath = __DIR__ . '/config/system.php';
    if (file_exists($configPath)) {
        $config = include $configPath;
        $statuses = $config['addons']['statuses'] ?? [];
        return $statuses[$addonSlug] ?? false;
    }
    return false;
}

$result3 = isAddonEnabledDirect('pdf-converter');
$result4 = isAddonEnabledDirect('image-converter');

echo "Direct function - pdf-converter: " . ($result3 ? 'true' : 'false') . "\n";
echo "Direct function - image-converter: " . ($result4 ? 'true' : 'false') . "\n";

echo "\n=== DEBUG COMPLETE ===\n";
