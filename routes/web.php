<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PDF Converter routes (protected)
    Route::get('/pdf-converter', function () {
        return view('pdf-converter');
    })->name('pdf-converter');

    Route::post('/pdf-converter/convert', function (Illuminate\Http\Request $request) {
        // Include our custom handler
        require __DIR__ . '/../addons/pdf-converter/handlers/convert.php';
        exit; // Prevent Laravel from continuing
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User Management
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.update-user-role');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.delete-user');

    // Addon Management
    Route::get('/admin/addons', [App\Http\Controllers\AdminController::class, 'addons'])->name('admin.addons');
    Route::post('/admin/addon/{addonSlug}/toggle', [App\Http\Controllers\AdminController::class, 'toggleAddon'])->name('admin.toggle-addon');

    // Analytics
    Route::get('/admin/analytics', [App\Http\Controllers\AdminController::class, 'analytics'])->name('admin.analytics');

    // System Logs
    Route::get('/admin/logs', [App\Http\Controllers\AdminController::class, 'logs'])->name('admin.logs');

    // Settings
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.update-settings');
    Route::post('/admin/clear-cache', [App\Http\Controllers\AdminController::class, 'clearCache'])->name('admin.clear-cache');
});

Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
