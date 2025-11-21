<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'description' => 'Basic plan for getting started',
                'price' => 0.00,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'max_files_per_conversion' => 1,
                'max_conversions_per_month' => 10,
                'included_addons' => ['pdf-converter'],
                'features' => [
                    'PDF Converter',
                    '10 conversions per month',
                    '1 file per conversion',
                    'Basic support'
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'description' => 'Perfect for individual users',
                'price' => 9.99,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'max_files_per_conversion' => 5,
                'max_conversions_per_month' => 100,
                'included_addons' => ['pdf-converter', 'image-converter'],
                'features' => [
                    'PDF Converter',
                    'Image Converter',
                    '100 conversions per month',
                    '5 files per conversion',
                    'Email support',
                    'Basic analytics'
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'description' => 'Advanced features for power users',
                'price' => 19.99,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'max_files_per_conversion' => 20,
                'max_conversions_per_month' => 500,
                'included_addons' => ['pdf-converter', 'image-converter', 'qr-generator', 'invoice-generator'],
                'features' => [
                    'All Converters',
                    '500 conversions per month',
                    '20 files per conversion',
                    'Priority support',
                    'Advanced analytics',
                    'API access',
                    'Custom branding'
                ],
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Unlimited access for businesses',
                'price' => 49.99,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'max_files_per_conversion' => 100,
                'max_conversions_per_month' => 0, // Unlimited
                'included_addons' => ['pdf-converter', 'image-converter', 'qr-generator', 'invoice-generator', 'letterhead-generator', 'id-card-generator', 'mp3-converter'],
                'features' => [
                    'All Converters & Tools',
                    'Unlimited conversions',
                    '100 files per conversion',
                    '24/7 priority support',
                    'White-label solution',
                    'API access',
                    'Custom integrations',
                    'Dedicated account manager'
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
