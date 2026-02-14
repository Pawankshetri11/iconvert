<?php

if (!function_exists('isAddonEnabled')) {
    /**
     * Check if an addon is enabled
     */
    function isAddonEnabled($addonSlug)
    {
        if (function_exists('app') && app()->bound('config')) {
            $statuses = app('config')->get('system.addons.statuses', []);
        } else {
            // Fallback for non-Laravel contexts
            $configPath = base_path('config/system.php');
            if (file_exists($configPath)) {
                $config = require $configPath;
                $statuses = $config['addons']['statuses'] ?? [];
            } else {
                $statuses = [];
            }
        }
        return $statuses[$addonSlug] ?? false;
    }
}