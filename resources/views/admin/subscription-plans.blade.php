@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">Subscription Plans</h2>
        <a href="{{ route('admin.subscription-plans.create') }}" class="bg-yellow-600 text-black px-4 py-2 rounded hover:bg-yellow-500 font-semibold">
            Create New Plan
        </a>
    </div>

    <!-- Plans Grid -->
    @if($plans->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($plans as $plan)
                <div class="stat-card relative">
                    @if($plan->is_popular)
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">
                            Popular
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                        <p class="text-gray-300 text-sm">{{ $plan->description }}</p>
                    </div>

                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-yellow-500">
                            {{ $plan->currency }} {{ number_format($plan->price, 2) }}
                        </div>
                        <div class="text-gray-400 text-sm">per {{ $plan->billing_cycle }}</div>
                    </div>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300">Files per conversion:</span>
                            <span class="text-white">{{ $plan->max_files_per_conversion }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300">Conversions per month:</span>
                            <span class="text-white">{{ $plan->max_conversions_per_month ?: 'Unlimited' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300">Included addons:</span>
                            <span class="text-white">{{ count($plan->included_addons ?? []) }}</span>
                        </div>
                    </div>

                    @if($plan->features)
                        <div class="mb-6">
                            <h4 class="text-white font-semibold mb-2">Features:</h4>
                            <ul class="text-sm text-gray-300 space-y-1">
                                @foreach($plan->features as $feature)
                                    <li>• {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $plan->is_active ? 'bg-green-800 text-green-200' : 'bg-red-800 text-red-200' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <div class="flex space-x-2">
                            <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="text-yellow-400 hover:text-yellow-300 text-sm">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.subscription-plans.toggle', $plan) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-400 hover:text-blue-300 text-sm">
                                    {{ $plan->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            @if(!$plan->subscriptions()->exists())
                                <form method="POST" action="{{ route('admin.subscription-plans.delete', $plan) }}" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="stat-card text-center">
            <div class="text-6xl mb-4">💳</div>
            <h3 class="text-xl font-semibold text-white mb-2">No Subscription Plans</h3>
            <p class="text-gray-400 mb-4">Create your first subscription plan to get started.</p>
            <a href="{{ route('admin.subscription-plans.create') }}" class="bg-yellow-600 text-black px-6 py-3 rounded hover:bg-yellow-500 font-semibold inline-block">
                Create First Plan
            </a>
        </div>
    @endif
</div>
@endsection