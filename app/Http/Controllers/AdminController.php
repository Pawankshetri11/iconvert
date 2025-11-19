<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Middleware applied in routes/web.php

    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'total_conversions' => UsageLog::count(),
            'active_addons' => count(array_filter($this->getAvailableAddons(), fn($addon) => $addon['enabled'] ?? false)),
        ];

        // Get recent users
        $recentUsers = User::latest()->take(5)->get();

        $addons = $this->getAvailableAddons();

        return view('admin.dashboard', compact('addons', 'stats', 'recentUsers'));
    }

    public function users()
    {
        $users = User::withCount('usageLogs')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function addons()
    {
        $addons = $this->getAvailableAddons();
        return view('admin.addons', compact('addons'));
    }

    public function toggleAddon(Request $request, $addonSlug)
    {
        $addons = $this->getAvailableAddons();
        if (!isset($addons[$addonSlug])) {
            return redirect()->back()->with('error', 'Addon not found.');
        }

        $addon = $addons[$addonSlug];
        $addon['enabled'] = !$addon['enabled'];

        // Save to config or database
        $this->saveAddonStatus($addonSlug, $addon['enabled']);

        return redirect()->back()->with('success', 'Addon status updated.');
    }

    public function analytics()
    {
        // Get analytics data
        $analytics = [
            'user_registrations' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'conversions_by_type' => UsageLog::selectRaw('action as type, COUNT(*) as count')
                ->groupBy('action')
                ->get(),
            'top_users' => User::withCount('usageLogs')
                ->orderBy('usage_logs_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.analytics', compact('analytics'));
    }

    public function logs()
    {
        // Get recent logs from Laravel log files
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $logContent = file_get_contents($logPath);
            $logLines = array_slice(explode("\n", $logContent), -50); // Last 50 lines
            $logs = array_filter($logLines);
        }

        return view('admin.logs', compact('logs'));
    }

    public function settings()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_debug' => config('app.debug'),
            'mail_driver' => config('mail.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_debug' => 'boolean',
        ]);

        // Update .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Update APP_NAME
        $envContent = preg_replace('/APP_NAME=.*/', 'APP_NAME="' . $request->app_name . '"', $envContent);

        // Update APP_DEBUG
        $envContent = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=' . ($request->app_debug ? 'true' : 'false'), $envContent);

        file_put_contents($envPath, $envContent);

        // Clear config cache
        Cache::flush();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function clearCache()
    {
        Cache::flush();
        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }

    private function getAvailableAddons()
    {
        $addonsDir = base_path('addons');
        $addons = [];

        if (is_dir($addonsDir)) {
            $dirs = scandir($addonsDir);
            foreach ($dirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($addonsDir . '/' . $dir)) {
                    $configFile = $addonsDir . '/' . $dir . '/config.php';
                    if (file_exists($configFile)) {
                        $config = include $configFile;
                        $addons[$dir] = $config + ['enabled' => $this->isAddonEnabled($dir)];
                    }
                }
            }
        }

        return $addons;
    }

    private function isAddonEnabled($addonSlug)
    {
        // For simplicity, check if directory exists and has config
        // In production, store in database
        return true; // All addons are enabled by default
    }

    private function saveAddonStatus($addonSlug, $enabled)
    {
        // In production, save to database
        // For now, just return
    }
}
