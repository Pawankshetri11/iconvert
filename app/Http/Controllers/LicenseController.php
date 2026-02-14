<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LicenseController extends Controller
{
    public function index()
    {
        $domain = request()->getHost();
        \Log::info('License check for domain', ['domain' => $domain]);

        $license = License::where('domain', $domain)->first();

        if ($license) {
            \Log::info('License found', [
                'id' => $license->id,
                'key' => $license->license_key,
                'status' => $license->status,
                'domain' => $license->domain,
                'is_valid' => $license->isValid()
            ]);
        } else {
            \Log::info('No license found for domain', ['domain' => $domain]);
        }

        return view('admin.license', compact('license'));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'client_name' => 'required|string',
            'email' => 'required|email',
        ]);

        $key = $request->license_key;
        $currentDomain = $request->getHost();
        $email = $request->email;
        $licenseServer = config('system.license.server_url');
        
        // Ensure we are targeting the correct API endpoint structure
        if (!str_contains($licenseServer, '/license')) {
             $licenseServer = rtrim($licenseServer, '/') . '/license';
        }
        
        $endpoint = $licenseServer . '/activate';
        \Log::info('Attempting license activation', ['endpoint' => $endpoint, 'key' => $key]);

        // Always try server first for proper license validation

        try {
            // Call license server for validation
            $response = Http::timeout(30)->withoutVerifying()->post($endpoint, [
                'license_key' => $key,
                'domain' => $currentDomain,
                'client_name' => $request->client_name,
                'email' => $email,
            ]);

            \Log::info('License activation attempt', [
                'endpoint' => $endpoint,
                'key' => $key,
                'domain' => $currentDomain,
                'request_data' => [
                    'license_key' => $key,
                    'domain' => $currentDomain,
                    'client_name' => $request->client_name,
                    'email' => $email,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info('License Server Response: ', $data);

                if (isset($data['valid']) && $data['valid']) {
                    // Valid license logic
                    $existing = License::where('license_key', $key)->first();

                    $expiresAt = isset($data['expires_at']) && $data['expires_at'] ? \Carbon\Carbon::parse($data['expires_at']) : null;
                    $type = $data['type'] ?? 'extended';

                    if ($existing) {
                        \Log::info('Updating existing license', ['id' => $existing->id, 'domain' => $currentDomain]);
                        $existing->update([
                            'client_name' => $request->client_name,
                            'email' => $email,
                            'domain' => $currentDomain,
                            'type' => $type,
                            'status' => 'active',
                            'activated_at' => now(),
                            'expires_at' => $expiresAt,
                        ]);
                    } else {
                        \Log::info('Creating new license', ['key' => $key, 'domain' => $currentDomain]);
                        License::create([
                            'license_key' => $key,
                            'client_name' => $request->client_name,
                            'email' => $email,
                            'domain' => $currentDomain,
                            'type' => $type,
                            'status' => 'active',
                            'activated_at' => now(),
                            'expires_at' => $expiresAt,
                        ]);
                    }

                    return redirect()->back()->with('success', 'License activated successfully!');
                } else {
                    // Server replied but said invalid
                    $errorMsg = $data['message'] ?? 'Invalid license key.';
                    \Log::warning('License server rejected activation', ['message' => $errorMsg]);
                    return redirect()->back()->with('error', $errorMsg);
                }
            } else {
                // Server returned 4xx or 5xx
                \Log::error('License server returned error status', ['status' => $response->status(), 'body' => $response->body()]);
                return redirect()->back()->with('error', 'License server validation failed (Status: ' . $response->status() . '). Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('License activation exception: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'exception' => $e->getTraceAsString()
            ]);

            // Server is required for proper license validation
            return redirect()->back()->with('error', 'License server is unreachable. Please check your internet connection and try again.');
        }
    }

    private function activateOffline(Request $request)
    {
        $key = $request->license_key;
        $currentDomain = $request->getHost();
        $email = $request->email;

        // Basic offline validation - accept any ROYAL- key for testing
        $isValid = str_starts_with($key, 'ROYAL-');

        if ($isValid) {
            $existing = License::where('license_key', $key)->first();

            if ($existing) {
                if ($existing->domain !== $currentDomain) {
                    return redirect()->back()->with('error', 'This license key is already activated on another domain.');
                } else {
                    $existing->update([
                        'client_name' => $request->client_name,
                        'email' => $email,
                        'status' => 'active',
                        'activated_at' => now(),
                    ]);
                    return redirect()->back()->with('success', 'License reactivated successfully!');
                }
            } else {
                License::create([
                    'license_key' => $key,
                    'client_name' => $request->client_name,
                    'email' => $email,
                    'domain' => $currentDomain,
                    'type' => 'extended',
                    'status' => 'active',
                    'activated_at' => now(),
                    'expires_at' => null,
                ]);

                return redirect()->back()->with('success', 'License activated successfully!');
            }
        }

        return redirect()->back()->with('error', 'Invalid license key. Use a key starting with ROYAL-');
    }

    public function checkConnection()
    {
        try {
            $serverUrl = config('system.license.server_url');
            \Log::info('Checking license server connection', ['url' => $serverUrl]);

            if (!str_contains($serverUrl, '/license')) {
                $serverUrl = rtrim($serverUrl, '/') . '/license';
            }

            // Attempt to connect to the license server
            $response = Http::timeout(5)->post($serverUrl . '/validate', [
                'license_key' => 'test',
                'domain' => 'test.com'
            ]);

            \Log::info('License server connection check response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // If we get any response, the server is reachable
            return response()->json(['status' => 'connected', 'message' => 'Server is reachable']);
        } catch (\Exception $e) {
            \Log::error('License server connection check failed', [
                'error' => $e->getMessage(),
                'url' => $serverUrl ?? 'unknown'
            ]);
            return response()->json(['status' => 'disconnected', 'message' => $e->getMessage()]);
        }
    }

    public function deactivate()
    {
        $license = License::where('domain', request()->getHost())->first();
        if ($license) {
            $license->update(['status' => 'inactive']);
            return redirect()->back()->with('success', 'License deactivated.');
        }
        return redirect()->back()->with('error', 'No license found for this domain.');
    }

}
