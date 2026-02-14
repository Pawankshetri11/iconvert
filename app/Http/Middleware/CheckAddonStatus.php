<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAddonStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $addonSlug)
    {
        // Force fresh config load by reading directly from file
        $configPath = config_path('system.php');
        if (file_exists($configPath)) {
            $config = include $configPath;
            $addonStatuses = $config['addons']['statuses'] ?? [];
        } else {
            $addonStatuses = [];
        }

        if (!isset($addonStatuses[$addonSlug]) || !$addonStatuses[$addonSlug]) {
            abort(404, 'Addon is not enabled or not found.');
        }

        return $next($request);
    }
}