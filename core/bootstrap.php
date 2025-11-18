<?php
/**
 * Royal SaaS Starter - Bootstrap
 */

spl_autoload_register(function ($class) {
    $prefix = 'Core\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

require __DIR__ . '/helpers/functions.php';

// Load config
$config = require __DIR__ . '/../config/system.php';

// Initialize addon manager
$addonManager = new Core\AddonManager(__DIR__ . '/../addons');
$GLOBALS['addons'] = $addonManager->getRegisteredAddons();
