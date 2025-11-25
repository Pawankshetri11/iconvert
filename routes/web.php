<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/pricing', function () {
    $plans = \App\Models\SubscriptionPlan::active()->ordered()->get();
    return view('pricing', compact('plans'));
})->name('pricing');

Route::get('/enterprise', function () {
    $enterprisePlan = \App\Models\SubscriptionPlan::where('name', 'Enterprise')->first();
    return view('enterprise', compact('enterprisePlan'));
})->name('enterprise');

// Core Products
Route::get('/image-converter', function () {
    return view('image-converter');
})->name('image-converter');

Route::get('/image-editor/{tool?}', function ($tool = 'crop') {
    $allowedTools = ['convert', 'compress', 'resize', 'crop', 'edit'];

    if (!in_array($tool, $allowedTools)) {
        abort(404);
    }

    return view('image-editor', compact('tool'));
})->name('image-editor');

// Image Tools Suite
Route::post('/image-tools/process', function (Illuminate\Http\Request $request) {
    // Include the image tools handler
    require __DIR__ . '/../addons/image-converter/handlers/image-tools.php';
    exit; // Prevent Laravel from continuing
});

Route::get('/mp3-converter', function () {
    return view('mp3-converter');
})->name('mp3-converter');

Route::get('/file-compressor', function () {
    return view('file-compressor');
})->name('file-compressor');

// Business Add-ons
Route::get('/invoice-generator', function () {
    return view('invoice-generator');
})->name('invoice-generator');

Route::get('/invoice-editor/{tool?}', function ($tool = 'standard') {
    $allowedTools = ['standard', 'proforma', 'recurring', 'commercial', 'freelancer', 'small-business', 'consulting', 'retail', 'payment', 'multilingual', 'tax', 'bulk'];

    if (!in_array($tool, $allowedTools)) {
        abort(404);
    }

    return view('invoice-editor', compact('tool'));
})->name('invoice-editor');

Route::get('/id-card-creator', function () {
    return view('id-card-creator');
})->name('id-card-creator');

Route::get('/id-card-editor/{tool?}', function ($tool = 'corporate') {
    $allowedTools = ['corporate', 'student', 'employee', 'visitor', 'member', 'security', 'modern', 'custom', 'qr-code', 'photo', 'bulk', 'access'];

    if (!in_array($tool, $allowedTools)) {
        abort(404);
    }

    return view('id-card-editor', compact('tool'));
})->name('id-card-editor');

Route::get('/letterhead-maker', function () {
    return view('letterhead-maker');
})->name('letterhead-maker');

Route::get('/letterhead-editor/{tool?}', function ($tool = 'classic') {
    $allowedTools = ['classic', 'modern', 'elegant', 'corporate', 'law', 'medical', 'real-estate', 'consulting', 'logo', 'colors', 'typography', 'bulk'];

    if (!in_array($tool, $allowedTools)) {
        abort(404);
    }

    return view('letterhead-editor', compact('tool'));
})->name('letterhead-editor');

Route::get('/qr-generator', function () {
    return view('qr-generator');
})->name('qr-generator');

Route::get('/qr-editor/{tool?}', function ($tool = 'url') {
    $allowedTools = ['url', 'text', 'wifi', 'contact', 'payment', 'phone', 'email', 'sms', 'logo', 'colors', 'correction', 'bulk'];

    if (!in_array($tool, $allowedTools)) {
        abort(404);
    }

    return view('qr-editor', compact('tool'));
})->name('qr-editor');

// Support
Route::get('/help-center', function () {
    return view('help-center');
})->name('help-center');

Route::get('/api-access', function () {
    return view('api-access');
})->name('api-access');

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');

// Legal
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms-of-service');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    // PDF Converter routes (freemium - allow guests with limits)
    Route::get('/pdf-converter', function () {
        return view('pdf-converter');
    })->name('pdf-converter');

    Route::get('/pdf-editor/{tool}', function ($tool) {
        $allowedTools = [
            'pdf-to-word', 'pdf-to-excel', 'pdf-to-ppt', 'pdf-to-text', 'pdf-to-html', 'pdf-to-images',
            'word-to-pdf', 'excel-to-pdf', 'ppt-to-pdf', 'html-to-pdf', 'images-to-pdf', 'text-to-pdf',
            'pdf-editor', 'pdf-rotate', 'pdf-watermark', 'pdf-protect', 'pdf-unlock',
            'pdf-merge', 'pdf-split', 'pdf-compress', 'pdf-repair'
        ];

        if (!in_array($tool, $allowedTools)) {
            abort(404);
        }

        return view('pdf-editor', compact('tool'));
    })->name('pdf-editor');

    // PDF Tools Suite - Allow guests for basic tools
    Route::post('/pdf-tools/process', function (Illuminate\Http\Request $request) {
        // Include the comprehensive PDF tools handler
        require __DIR__ . '/../addons/pdf-converter/handlers/pdf-tools.php';
        exit; // Prevent Laravel from continuing
    });

    Route::post('/pdf-converter/convert', function (Illuminate\Http\Request $request) {
        // Include our custom handler
        require __DIR__ . '/../addons/pdf-converter/handlers/convert.php';
        exit; // Prevent Laravel from continuing
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Subscription routes
    Route::post('/subscribe/{plan}', [App\Http\Controllers\AdminController::class, 'subscribe'])->name('subscribe');
    Route::delete('/cancel-subscription', [App\Http\Controllers\AdminController::class, 'cancelSubscription'])->name('cancel-subscription');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User Management
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.update-user-role');
    Route::patch('/admin/users/{user}/subscription', [App\Http\Controllers\AdminController::class, 'assignSubscription'])->name('admin.assign-subscription');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.delete-user');

    // Addon Management
    Route::get('/admin/addons', [App\Http\Controllers\AdminController::class, 'addons'])->name('admin.addons');
    Route::post('/admin/addons/install', [App\Http\Controllers\AdminController::class, 'installAddon'])->name('admin.install-addon');
    Route::post('/admin/addon/{addonSlug}/toggle', [App\Http\Controllers\AdminController::class, 'toggleAddon'])->name('admin.toggle-addon');

    // Analytics
    Route::get('/admin/analytics', [App\Http\Controllers\AdminController::class, 'analytics'])->name('admin.analytics');

    // System Logs
    Route::get('/admin/logs', [App\Http\Controllers\AdminController::class, 'logs'])->name('admin.logs');

    // Subscription Plans
    Route::get('/admin/subscription-plans', [App\Http\Controllers\AdminController::class, 'subscriptionPlans'])->name('admin.subscription-plans');
    Route::get('/admin/subscription-plans/create', [App\Http\Controllers\AdminController::class, 'createSubscriptionPlan'])->name('admin.subscription-plans.create');
    Route::post('/admin/subscription-plans', [App\Http\Controllers\AdminController::class, 'storeSubscriptionPlan'])->name('admin.subscription-plans.store');
    Route::get('/admin/subscription-plans/{plan}/edit', [App\Http\Controllers\AdminController::class, 'editSubscriptionPlan'])->name('admin.subscription-plans.edit');
    Route::put('/admin/subscription-plans/{plan}', [App\Http\Controllers\AdminController::class, 'updateSubscriptionPlan'])->name('admin.subscription-plans.update');
    Route::post('/admin/subscription-plans/{plan}/toggle', [App\Http\Controllers\AdminController::class, 'toggleSubscriptionPlan'])->name('admin.subscription-plans.toggle');
    Route::delete('/admin/subscription-plans/{plan}', [App\Http\Controllers\AdminController::class, 'deleteSubscriptionPlan'])->name('admin.subscription-plans.delete');

    // Content Management
    Route::get('/admin/content-editor', [App\Http\Controllers\AdminController::class, 'contentEditor'])->name('admin.content-editor');
    Route::post('/admin/content-editor', [App\Http\Controllers\AdminController::class, 'updateContent'])->name('admin.update-content');
    Route::post('/admin/content-editor/create', [App\Http\Controllers\AdminController::class, 'createContent'])->name('admin.create-content');
    Route::post('/admin/content/{content}/toggle', [App\Http\Controllers\AdminController::class, 'toggleContent'])->name('admin.toggle-content');
    Route::delete('/admin/content/{content}', [App\Http\Controllers\AdminController::class, 'deleteContent'])->name('admin.delete-content');

    // Settings
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.update-settings');
    Route::post('/admin/payment-gateways', [App\Http\Controllers\AdminController::class, 'updatePaymentGateways'])->name('admin.update-payment-gateways');
    Route::post('/admin/clear-cache', [App\Http\Controllers\AdminController::class, 'clearCache'])->name('admin.clear-cache');
});

Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/auth/github', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGitHub'])->name('github.login');
Route::get('/auth/github/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGitHubCallback']);

require __DIR__.'/auth.php';
