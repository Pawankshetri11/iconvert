<?php
namespace Core;

class AddonManager
{
    protected string $addonsPath;

    public function __construct(string $addonsPath)
    {
        $this->addonsPath = rtrim($addonsPath, '/');
    }

    public function getRegisteredAddons(): array
    {
        $registered = [];

        foreach (glob($this->addonsPath . '/*', GLOB_ONLYDIR) as $dir) {
            $configFile = $dir . '/config.php';
            if (file_exists($configFile)) {
                $config = require $configFile;
                if (!empty($config['slug'])) {
                    $registered[$config['slug']] = $config;
                }
            }
        }

        return $registered;
    }
}
