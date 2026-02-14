<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (!file_exists(storage_path('installed'))) {
        return redirect('/install');
    }
    if (auth()->check()) {
        return redirect('/admin');
    }
    if (isset($_GET['installed']) && isset($_GET['key'])) {
        return response()->json([
            'message' => 'License Server installed successfully!',
            'generated_license_key' => $_GET['key'],
            'login_url' => url('/login'),
            'admin_credentials' => [
                'email' => 'admin@license-server.com',
                'password' => 'password'
            ]
        ]);
    }
    return redirect('/login');
});

// Test route to verify routes are working
Route::get('/test', function () {
    return response()->json(['message' => 'Routes are working', 'time' => now()]);
});

Route::get('/install', [InstallController::class, 'index']);
Route::post('/install', [InstallController::class, 'install']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Test login route
Route::get('/test-login', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/generate-license', [AdminController::class, 'generateLicense'])->name('admin.generate-license');
    Route::post('/admin/suspend-license/{licenseKey}', [AdminController::class, 'suspendLicense'])->name('admin.suspend-license');
    Route::post('/admin/activate-license/{licenseKey}', [AdminController::class, 'activateLicense'])->name('admin.activate-license');
    Route::delete('/admin/delete-license/{licenseKey}', [AdminController::class, 'deleteLicense'])->name('admin.delete-license');
});