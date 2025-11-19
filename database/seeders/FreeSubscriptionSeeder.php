<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreeSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create free subscriptions for all existing users
        $users = User::all();

        foreach ($users as $user) {
            // Check if user already has an active subscription
            $existingSubscription = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$existingSubscription) {
                Subscription::create([
                    'user_id' => $user->id,
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
            }
        }
    }
}
