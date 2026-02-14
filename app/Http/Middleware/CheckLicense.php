<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\License;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for license management routes to avoid redirect loop
        if ($request->routeIs('admin.license*')) {
            return $next($request);
        }

        $license = License::where('domain', $request->getHost())->where('status', 'active')->first();

        // If no license locally, block access
        if (!$license) {
            return redirect()->route('admin.license.index')->with('error', 'Please activate your license to continue using the application.');
        }

        // Check if license is expired locally
        if (!$license->isValid()) {
            return redirect()->route('admin.license.index')->with('error', 'Your license has expired. Please renew to continue.');
        }

        // Periodic server validation (every 24 hours)
        if ($license->updated_at->addHours(24)->isPast()) {
            try {
                $serverUrl = config('system.license.server_url');
                if (!str_contains($serverUrl, '/license')) {
                    $serverUrl = rtrim($serverUrl, '/') . '/license';
                }

                $response = \Illuminate\Support\Facades\Http::timeout(5)->post($serverUrl . '/validate', [
                    'license_key' => $license->license_key,
                    'domain' => $request->getHost(),
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!$data['valid']) {
                        $license->update(['status' => 'suspended']);
                        if ($request->is('admin/*')) {
                             return redirect()->route('admin.license.index')->with('error', 'License validation failed. Please contact support.');
                        }
                    } else {
                        // Update expiry if changed
                        if (isset($data['expires_at'])) {
                            $license->update([
                                'expires_at' => \Carbon\Carbon::parse($data['expires_at']),
                                'updated_at' => now()
                            ]);
                        } else {
                            $license->update(['updated_at' => now()]);
                        }
                    }
                }
                // If server check fails, allow local license to continue working
            } catch (\Exception $e) {
                // Server unreachable, continue with local license
            }
        }

        return $next($request);
    }
}
