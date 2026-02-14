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
        $subscriptionPlans = \App\Models\SubscriptionPlan::active()->get();
        return view('admin.users', compact('users', 'subscriptionPlans'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function assignSubscription(Request $request, User $user)
    {
        $request->validate([
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id'
        ]);

        // Cancel any existing active subscription
        if ($user->activeSubscription) {
            $user->activeSubscription->update(['status' => 'cancelled']);
        }

        if ($request->subscription_plan_id) {
            $plan = \App\Models\SubscriptionPlan::find($request->subscription_plan_id);

            // Create new subscription
            $user->subscriptions()->create([
                'subscription_plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'price' => $plan->price,
                'currency' => $plan->currency,
                'billing_cycle' => $plan->billing_cycle,
                'conversion_limit' => $plan->max_conversions_per_month,
                'enabled_addons' => $plan->included_addons,
                'starts_at' => now(),
                'ends_at' => null, // Admin assigned, no end date
                'status' => 'active',
            ]);
        }

        return redirect()->back()->with('success', 'User subscription updated successfully.');
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
        try {
            $addons = $this->getAvailableAddons();
            if (!isset($addons[$addonSlug])) {
                return redirect()->back()->with('error', 'Addon not found.');
            }

            $addon = $addons[$addonSlug];
            $newStatus = !$addon['enabled'];

            // Save to config
            $this->saveAddonStatus($addonSlug, $newStatus);

            $statusText = $newStatus ? 'enabled' : 'disabled';
            return redirect()->back()->with('success', "Addon '{$addon['name']}' has been {$statusText} successfully.");
        } catch (\Exception $e) {
            Log::error('Error toggling addon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update addon status. Please try again.');
        }
    }

    public function installAddon(Request $request)
    {
        $request->validate([
            'addon_zip' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        $file = $request->file('addon_zip');
        $tempPath = $file->store('temp', 'local');

        $zip = new \ZipArchive;
        if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {
            $extractPath = storage_path('app/temp/' . uniqid());
            $zip->extractTo($extractPath);
            $zip->close();

            // Check for config.php
            $configFile = $extractPath . '/config.php';
            if (!file_exists($configFile)) {
                // Cleanup
                $this->deleteDirectory($extractPath);
                unlink(storage_path('app/' . $tempPath));
                return redirect()->back()->with('error', 'Invalid addon: config.php not found.');
            }

            $config = require $configFile;
            if (empty($config['slug'])) {
                $this->deleteDirectory($extractPath);
                unlink(storage_path('app/' . $tempPath));
                return redirect()->back()->with('error', 'Invalid addon: slug not defined in config.');
            }

            $addonDir = base_path('addons/' . $config['slug']);
            if (is_dir($addonDir)) {
                $this->deleteDirectory($extractPath);
                unlink(storage_path('app/' . $tempPath));
                return redirect()->back()->with('error', 'Addon already exists.');
            }

            // Move to addons directory
            rename($extractPath, $addonDir);

            // Cleanup
            unlink(storage_path('app/' . $tempPath));

            return redirect()->back()->with('success', 'Addon installed successfully.');
        } else {
            unlink(storage_path('app/' . $tempPath));
            return redirect()->back()->with('error', 'Failed to open zip file.');
        }
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
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

    public function updatePaymentGateways(Request $request)
    {
        $request->validate([
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'cashfree_app_id' => 'nullable|string|max:255',
            'cashfree_secret' => 'nullable|string|max:255',
            'stripe_key' => 'nullable|string|max:255',
            'stripe_secret' => 'nullable|string|max:255',
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_client_secret' => 'nullable|string|max:255',
            'paypal_mode' => 'nullable|in:sandbox,live',
            'manual_qr_url' => 'nullable|url|max:500',
            'manual_qr_instructions' => 'nullable|string|max:1000',
            'manual_qr_enabled' => 'boolean',
        ]);

        // Update .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Razorpay
        $envContent = $this->updateEnvValue($envContent, 'RAZORPAY_KEY', $request->razorpay_key);
        $envContent = $this->updateEnvValue($envContent, 'RAZORPAY_SECRET', $request->razorpay_secret);

        // Cashfree
        $envContent = $this->updateEnvValue($envContent, 'CASHFREE_APP_ID', $request->cashfree_app_id);
        $envContent = $this->updateEnvValue($envContent, 'CASHFREE_SECRET', $request->cashfree_secret);

        // Stripe
        $envContent = $this->updateEnvValue($envContent, 'STRIPE_KEY', $request->stripe_key);
        $envContent = $this->updateEnvValue($envContent, 'STRIPE_SECRET', $request->stripe_secret);

        // PayPal
        $envContent = $this->updateEnvValue($envContent, 'PAYPAL_CLIENT_ID', $request->paypal_client_id);
        $envContent = $this->updateEnvValue($envContent, 'PAYPAL_CLIENT_SECRET', $request->paypal_client_secret);
        $envContent = $this->updateEnvValue($envContent, 'PAYPAL_MODE', $request->paypal_mode);

        // Manual QR Payment
        $envContent = $this->updateEnvValue($envContent, 'MANUAL_QR_URL', $request->manual_qr_url);
        $envContent = $this->updateEnvValue($envContent, 'MANUAL_QR_INSTRUCTIONS', $request->manual_qr_instructions);
        $envContent = $this->updateEnvValue($envContent, 'MANUAL_QR_ENABLED', $request->manual_qr_enabled ? 'true' : 'false');

        file_put_contents($envPath, $envContent);

        // Clear config cache
        Cache::flush();

        return redirect()->back()->with('success', 'Payment gateway settings updated successfully.');
    }

    private function updateEnvValue($envContent, $key, $value)
    {
        $pattern = "/^{$key}=.*$/m";
        $replacement = $key . '=' . ($value ? '"' . $value . '"' : '');
        if (preg_match($pattern, $envContent)) {
            return preg_replace($pattern, $replacement, $envContent);
        } else {
            return $envContent . "\n" . $replacement;
        }
    }

    // Subscription Plan Management
    public function subscriptionPlans()
    {
        $plans = \App\Models\SubscriptionPlan::ordered()->get();
        return view('admin.subscription-plans', compact('plans'));
    }

    public function createSubscriptionPlan()
    {
        $addons = $this->getAvailableAddons();
        return view('admin.subscription-plan-form', compact('addons'));
    }

    public function storeSubscriptionPlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_files_per_conversion' => 'required|integer|min:1',
            'max_conversions_per_month' => 'required|integer|min:0',
            'included_addons' => 'nullable|array',
            'features' => 'nullable|array',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ]);

        \App\Models\SubscriptionPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'billing_cycle' => $request->billing_cycle,
            'max_files_per_conversion' => $request->max_files_per_conversion,
            'max_conversions_per_month' => $request->max_conversions_per_month,
            'included_addons' => $request->included_addons ?? [],
            'features' => $request->features ?? [],
            'is_popular' => $request->is_popular ?? false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.subscription-plans')->with('success', 'Subscription plan created successfully.');
    }

    public function editSubscriptionPlan(\App\Models\SubscriptionPlan $plan)
    {
        $addons = $this->getAvailableAddons();
        return view('admin.subscription-plan-form', compact('plan', 'addons'));
    }

    public function updateSubscriptionPlan(Request $request, \App\Models\SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_files_per_conversion' => 'required|integer|min:1',
            'max_conversions_per_month' => 'required|integer|min:0',
            'included_addons' => 'nullable|array',
            'features' => 'nullable|array',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'billing_cycle' => $request->billing_cycle,
            'max_files_per_conversion' => $request->max_files_per_conversion,
            'max_conversions_per_month' => $request->max_conversions_per_month,
            'included_addons' => $request->included_addons ?? [],
            'features' => $request->features ?? [],
            'is_popular' => $request->is_popular ?? false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.subscription-plans')->with('success', 'Subscription plan updated successfully.');
    }

    public function toggleSubscriptionPlan(\App\Models\SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'Plan status updated successfully.');
    }

    public function deleteSubscriptionPlan(\App\Models\SubscriptionPlan $plan)
    {
        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('admin.subscription-plans')->with('success', 'Subscription plan deleted successfully.');
    }

    // User subscription handling
    public function cancelSubscription(Request $request)
    {
        $user = auth()->user();

        if ($user->activeSubscription) {
            $user->activeSubscription->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Your subscription has been cancelled successfully.');
        }

        return redirect()->back()->with('error', 'No active subscription found.');
    }

    public function subscribe(\App\Models\SubscriptionPlan $plan, Request $request)
    {
        $user = auth()->user();

        // Check if user already has an active subscription to this plan
        $existingSubscription = $user->activeSubscription;
        if ($existingSubscription && $existingSubscription->subscription_plan_id === $plan->id) {
            return redirect()->back()->with('info', 'You are already subscribed to this plan.');
        }

        // For free plans, create subscription immediately
        if ($plan->price == 0) {
            // Cancel any existing active subscription
            if ($existingSubscription) {
                $existingSubscription->update(['status' => 'cancelled']);
            }

            // Create new subscription
            $user->subscriptions()->create([
                'subscription_plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'price' => $plan->price,
                'currency' => $plan->currency,
                'billing_cycle' => $plan->billing_cycle,
                'conversion_limit' => $plan->max_conversions_per_month,
                'enabled_addons' => $plan->included_addons,
                'starts_at' => now(),
                'ends_at' => null, // Lifetime for free plans
                'status' => 'active',
            ]);

            return redirect()->route('dashboard')->with('success', 'Successfully subscribed to ' . $plan->name . ' plan!');
        }

        // For paid plans, redirect to payment processing
        // This would integrate with your payment gateway
        return redirect()->route('pricing')->with('info', 'Payment integration for ' . $plan->name . ' plan coming soon!');
    }

    // Content Management
    public function contentEditor()
    {
        $groups = \App\Models\Content::getGroups();
        $contents = \App\Models\Content::active()->orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.content-editor', compact('groups', 'contents'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'contents' => 'required|array',
            'contents.*.key' => 'required|string',
            'contents.*.value' => 'required|string',
        ]);

        foreach ($request->contents as $contentData) {
            \App\Models\Content::where('key', $contentData['key'])
                ->update(['value' => $contentData['value']]);
        }

        // Clear content cache
        \App\Models\Content::clearCache();

        return redirect()->back()->with('success', 'Content updated successfully.');
    }

    public function createContent(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:contents,key',
            'group' => 'required|string',
            'type' => 'required|in:text,html,json',
            'value' => 'required|string',
            'label' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        \App\Models\Content::create([
            'key' => $request->key,
            'group' => $request->group,
            'type' => $request->type,
            'value' => $request->value,
            'label' => $request->label,
            'description' => $request->description,
        ]);

        \App\Models\Content::clearCache();

        return redirect()->back()->with('success', 'Content item created successfully.');
    }

    public function deleteContent(\App\Models\Content $content)
    {
        $content->delete();
        \App\Models\Content::clearCache();

        return redirect()->back()->with('success', 'Content item deleted successfully.');
    }

    public function toggleContent(\App\Models\Content $content)
    {
        $content->update(['is_active' => !$content->is_active]);
        \App\Models\Content::clearCache();

        return redirect()->back()->with('success', 'Content status updated successfully.');
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
                        // Override the enabled status with system config value
                        $config['enabled'] = $this->isAddonEnabled($dir);
                        $addons[$dir] = $config;
                    }
                }
            }
        }

        return $addons;
    }

    private function isAddonEnabled($addonSlug)
    {
        // Force fresh config load by reading directly from file
        $configPath = config_path('system.php');
        if (file_exists($configPath)) {
            $config = include $configPath;
            $statuses = $config['addons']['statuses'] ?? [];
            return $statuses[$addonSlug] ?? false;
        }
        return false;
    }

    private function saveAddonStatus($addonSlug, $enabled)
    {
        // Read current config to preserve other settings
        $currentConfig = include config_path('system.php');
        $statuses = $currentConfig['addons']['statuses'] ?? [];
        $statuses[$addonSlug] = $enabled;

        // Update the config file with complete structure (no env() calls)
        $configPath = config_path('system.php');

        // Build the statuses array manually to ensure correct format
        $statusesStr = "[\n";
        foreach ($statuses as $key => $value) {
            $statusesStr .= "            '{$key}' => " . ($value ? 'true' : 'false') . ",\n";
        }
        $statusesStr .= "        ]";

        $configContent = "<?php\n\nreturn [\n    'app' => [\n        'name' => 'Royal SaaS Starter',\n        'env' => 'local',\n        'debug' => true,\n    ],\n    'addons' => [\n        'enabled' => true,\n        'statuses' => {$statusesStr},\n    ],\n    'license' => [\n        'server_url' => 'https://server.4amtech.in/api/license',\n        'require_online' => true,\n    ],\n];\n";

        file_put_contents($configPath, $configContent);

        // Clear all caches including config cache
        \Illuminate\Support\Facades\Cache::flush();
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
    }
}
