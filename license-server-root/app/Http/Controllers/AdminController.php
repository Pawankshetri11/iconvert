<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $licenses = License::latest()->paginate(20);
        return view('admin.dashboard', compact('licenses'));
    }

    public function generateLicense(Request $request)
    {
        $request->validate([
            'type' => 'required|in:standard,extended',
            'expires_at' => 'nullable|date|after:today',
            'client_name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $key = 'ROYAL-' . strtoupper(Str::random(16));

        License::create([
            'license_key' => $key,
            'type' => $request->type,
            'status' => 'inactive',
            'expires_at' => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at) : null,
            'client_name' => $request->client_name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'License generated: ' . $key);
    }

    public function suspendLicense($licenseKey)
    {
        $license = License::where('license_key', $licenseKey)->firstOrFail();
        $license->update(['status' => 'suspended', 'suspended_at' => now()]);

        return redirect()->back()->with('success', 'License suspended successfully.');
    }

    public function activateLicense($licenseKey)
    {
        $license = License::where('license_key', $licenseKey)->firstOrFail();
        $license->update(['status' => 'active', 'activated_at' => now()]);

        return redirect()->back()->with('success', 'License activated successfully.');
    }

    public function deleteLicense($licenseKey)
    {
        $license = License::where('license_key', $licenseKey)->firstOrFail();
        $license->delete();

        return redirect()->back()->with('success', 'License deleted successfully.');
    }
}