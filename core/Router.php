<?php
namespace Core;

class Router
{
    public function dispatch()
    {
        $route = $_GET['route'] ?? 'home';

        if ($route === 'home') {
            require __DIR__ . '/../views/frontend/home.php';
            return;
        }

        // Very basic addon route handler: ?route=addon_slug/action
        $parts = explode('/', $route);
        $addonSlug = $parts[0] ?? null;
        $action = $parts[1] ?? 'index';

        $addons = $GLOBALS['addons'] ?? [];

        if ($addonSlug && isset($addons[$addonSlug])) {
            // In real app you would map to controller
            echo "<h1>{$addons[$addonSlug]['name']} - {$action}</h1>";
            echo "<p>This is a placeholder route handled by the starter router.</p>";
            return;
        }

        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
    }
}
