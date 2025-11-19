<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create free subscription for new user
        $user->subscriptions()->create([
            'plan_name' => 'Free',
            'price' => 0.00,
            'currency' => 'USD',
            'billing_cycle' => 'monthly',
            'conversion_limit' => 10, // 10 free conversions per month
            'enabled_addons' => ['pdf-converter'], // Only PDF converter for free plan
            'starts_at' => now(),
            'ends_at' => null, // Never expires
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
