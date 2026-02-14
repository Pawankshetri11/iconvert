<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'client_name' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $key = $request->license_key;
        $domain = $request->domain;

        // Check if license key exists
        $license = License::where('license_key', $key)->first();

        if (!$license) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid license key.'
            ], 400);
        }

        // Check if already activated on different domain
        if ($license->domain && $license->domain !== $domain) {
            return response()->json([
                'valid' => false,
                'message' => 'This license key is already activated on another domain.'
            ], 400);
        }

        // Check if suspended
        if ($license->isSuspended()) {
            return response()->json([
                'valid' => false,
                'message' => 'This license has been suspended.'
            ], 400);
        }

        // Activate or update
        try {
            \Log::info('Updating license', [
                'key' => $key,
                'data' => [
                    'domain' => $domain,
                    'client_name' => $request->client_name,
                    'email' => $request->email,
                ]
            ]);

            $updated = $license->update([
                'domain' => $domain,
                'client_name' => $request->client_name,
                'email' => $request->email,
                'status' => 'active',
                'activated_at' => now(),
            ]);

            \Log::info('License update result', ['updated' => $updated, 'fresh_data' => $license->fresh()->toArray()]);

        } catch (\Exception $e) {
            \Log::error('License update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'valid' => false,
                'message' => 'Database update failed: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'valid' => true,
            'type' => $license->type,
            'expires_at' => $license->expires_at?->toISOString(),
            'message' => 'License activated successfully.'
        ]);
    }

    public function validate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
        ]);

        $license = License::where('license_key', $request->license_key)
                         ->where('domain', $request->domain)
                         ->first();

        if (!$license || !$license->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'License is invalid or expired.'
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'type' => $license->type,
            'expires_at' => $license->expires_at?->toISOString(),
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:standard,extended',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $key = 'ROYAL-' . strtoupper(Str::random(16));

        License::create([
            'license_key' => $key,
            'type' => $request->type,
            'status' => 'inactive',
            'expires_at' => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at) : null,
        ]);

        return response()->json([
            'license_key' => $key,
            'type' => $request->type,
            'expires_at' => $request->expires_at,
        ]);
    }

    public function list(Request $request)
    {
        $licenses = License::orderBy('created_at', 'desc')
                          ->paginate(50);

        return response()->json($licenses);
    }

    public function suspend(Request $request, $licenseKey)
    {
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            return response()->json([
                'message' => 'License not found.'
            ], 404);
        }

        $license->update([
            'status' => 'suspended',
            'suspended_at' => now(),
        ]);

        return response()->json([
            'message' => 'License suspended successfully.'
        ]);
    }
}