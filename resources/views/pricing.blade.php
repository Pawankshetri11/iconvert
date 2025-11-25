@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Header -->
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-3">Choose Your Plan</h1>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                Select the perfect plan for your document conversion needs. Upgrade or downgrade anytime.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
            @foreach($plans->where('name', '!=', 'Enterprise')->take(3) as $plan)
                <div class="relative bg-gray-800 rounded-2xl p-8 border-2 {{ $plan->is_popular ? 'border-yellow-500' : 'border-gray-700' }} hover:border-yellow-400 transition-all duration-300 transform hover:scale-105">
                    @if($plan->is_popular)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-bold">MOST POPULAR</span>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ $plan->description }}</p>
                    </div>

                    <div class="text-center mb-8">
                        <div class="flex items-baseline justify-center">
                            <span class="text-4xl font-bold text-white">{{ $plan->currency }}</span>
                            <span class="text-5xl font-bold text-white">{{ number_format($plan->price, 0) }}</span>
                            @if($plan->price > 0)
                                <span class="text-gray-400 ml-2">/{{ $plan->billing_cycle }}</span>
                            @endif
                        </div>
                        @if($plan->billing_cycle === 'yearly' && $plan->price > 0)
                            <p class="text-sm mt-1" style="color: #ffd700;">Save with yearly billing</p>
                        @endif
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Files per conversion</span>
                            <span class="text-white font-semibold">{{ $plan->max_files_per_conversion }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Conversions per month</span>
                            <span class="text-white font-semibold">{{ $plan->max_conversions_per_month ?: 'Unlimited' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Available tools</span>
                            <span class="text-white font-semibold">{{ count($plan->included_addons ?? []) }}</span>
                        </div>
                    </div>

                    @if($plan->features)
                        <div class="mb-8">
                            <h4 class="text-white font-semibold mb-3">What's included:</h4>
                            <ul class="space-y-2">
                                @foreach($plan->features as $feature)
                                    <li class="flex items-center text-gray-300">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color: #ffd700;">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center">
                        @auth
                            @php
                                $currentPlan = auth()->user()->activeSubscription?->plan;
                                $isCurrentPlan = $currentPlan && $currentPlan->id === $plan->id;
                            @endphp

                            @if($isCurrentPlan)
                                <button class="w-full bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed" disabled>
                                    Current Plan
                                </button>
                            @elseif($plan->price == 0)
                                <form method="POST" action="{{ route('subscribe', $plan) }}">
                                    @csrf
                                    <button type="submit" class="w-full text-black py-3 px-6 rounded-lg font-semibold transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                        Subscribe Free
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('subscribe', $plan) }}">
                                    @csrf
                                    <button type="submit" class="w-full {{ $plan->is_popular ? 'bg-yellow-500 hover:bg-yellow-600 text-black' : 'bg-blue-600 hover:bg-blue-700 text-white' }} py-3 px-6 rounded-lg font-semibold transition-colors">
                                        Subscribe Now
                                    </button>
                                </form>
                            @endif
                        @else
                            @if($plan->price == 0)
                                <a href="{{ route('register') }}" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors inline-block text-center">
                                    Get Started Free
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full {{ $plan->is_popular ? 'bg-yellow-500 hover:bg-yellow-600 text-black' : 'bg-blue-600 hover:bg-blue-700 text-white' }} py-3 px-6 rounded-lg font-semibold transition-colors inline-block text-center">
                                    Start Free Trial
                                </a>
                            @endif
                        @endauth

                        @guest
                            @if($plan->price == 0)
                                <a href="{{ route('register') }}" class="w-full text-black py-3 px-6 rounded-lg font-semibold transition-colors inline-block text-center" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                    Get Started Free
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full text-black py-3 px-6 rounded-lg font-semibold transition-colors inline-block text-center" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                    Start Free Trial
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Enterprise Card -->
        <div class="max-w-4xl mx-auto">
            @php
                $enterprisePlan = $plans->where('name', 'Enterprise')->first();
            @endphp
            @if($enterprisePlan)
                <div class="relative rounded-2xl p-8 border-2 transition-all duration-300 transform hover:scale-105" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border-color: #ffd700;">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="text-black px-4 py-2 rounded-full text-sm font-bold" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">ENTERPRISE</span>
                    </div>

                    <div class="text-center mb-6">
                        <h3 class="text-3xl font-bold text-white mb-2">{{ $enterprisePlan->name }}</h3>
                        <p class="text-gray-300 text-lg">{{ $enterprisePlan->description }}</p>
                    </div>

                    <div class="text-center mb-8">
                        <div class="text-2xl text-gray-300 mb-2">Custom Pricing</div>
                        <p class="text-gray-400">Contact us for enterprise pricing tailored to your needs</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="text-white font-semibold mb-3">What's included:</h4>
                            <ul class="space-y-2">
                                @foreach($enterprisePlan->features as $feature)
                                    <li class="flex items-center text-gray-300">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color: #ffd700;">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="text-center">
                            <div class="space-y-4">
                                <div class="bg-gray-800/50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-white">{{ $enterprisePlan->max_files_per_conversion }}</div>
                                    <div class="text-gray-400">Files per conversion</div>
                                </div>
                                <div class="bg-gray-800/50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-white">Unlimited</div>
                                    <div class="text-gray-400">Conversions per month</div>
                                </div>
                                <div class="bg-gray-800/50 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-white">{{ count($enterprisePlan->included_addons ?? []) }}</div>
                                    <div class="text-gray-400">Available tools</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('enterprise') }}" class="inline-block text-black py-4 px-8 rounded-lg font-semibold transition-colors text-base" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                            Learn More About Enterprise
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- FAQ Section -->
        <div class="mt-16 max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-white text-center mb-10">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">Can I change my plan anytime?</h3>
                    <p class="text-gray-300 text-sm">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">What happens to my files?</h3>
                    <p class="text-gray-300 text-sm">Your converted files are available for download immediately and stored temporarily for your convenience.</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">Is there a free trial?</h3>
                    <p class="text-gray-300 text-sm">Yes! Sign up for any paid plan and get 3 free conversions to try our service.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection