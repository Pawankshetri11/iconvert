<?php

// Avoid conflicts with Laravel's built-in helpers
if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        global $config;

        $segments = explode('.', $key);
        $value = $config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('isAddonEnabled')) {
    function isAddonEnabled($addonSlug)
    {
        // Use Laravel's config helper if available, otherwise read directly from file
        if (function_exists('app') && app()->bound('config')) {
            $statuses = app('config')->get('system.addons.statuses', []);
        } else {
            // Read directly from config file
            $configPath = __DIR__ . '/../../config/system.php';
            if (file_exists($configPath)) {
                $config = include $configPath;
                $statuses = $config['addons']['statuses'] ?? [];
            } else {
                $statuses = [];
            }
        }
        return $statuses[$addonSlug] ?? false;
    }
}
