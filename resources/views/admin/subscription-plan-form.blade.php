@extends('layouts.admin')

@section('title', isset($plan) ? 'Edit Subscription Plan' : 'Create Subscription Plan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="stat-card">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white">{{ isset($plan) ? 'Edit' : 'Create' }} Subscription Plan</h2>
            <p class="text-gray-400">Configure plan details, pricing, and included features</p>
        </div>

        <form method="POST" action="{{ isset($plan) ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}">
            @csrf
            @if(isset($plan))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white">Basic Information</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Plan Name</label>
                        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">{{ old('description', $plan->description ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Price</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $plan->price ?? '') }}" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Currency</label>
                            <select name="currency" required
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="USD" {{ old('currency', $plan->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $plan->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency', $plan->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                <option value="INR" {{ old('currency', $plan->currency ?? 'USD') == 'INR' ? 'selected' : '' }}>INR</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Billing Cycle</label>
                        <select name="billing_cycle" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? 'monthly') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                </div>

                <!-- Limits and Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white">Limits & Settings</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Max Files per Conversion</label>
                        <input type="number" name="max_files_per_conversion" min="1" value="{{ old('max_files_per_conversion', $plan->max_files_per_conversion ?? 1) }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Max Conversions per Month (0 = Unlimited)</label>
                        <input type="number" name="max_conversions_per_month" min="0" value="{{ old('max_conversions_per_month', $plan->max_conversions_per_month ?? 0) }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_popular" value="1" id="is_popular" {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                        <label for="is_popular" class="ml-2 block text-sm text-gray-300">
                            Mark as Popular Plan
                        </label>
                    </div>
                </div>
            </div>

            <!-- Included Addons -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-white mb-4">Included Addons</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($addons as $slug => $addon)
                        <div class="flex items-center">
                            <input type="checkbox" name="included_addons[]" value="{{ $slug }}" id="addon_{{ $slug }}"
                                   {{ in_array($slug, old('included_addons', $plan->included_addons ?? [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                            <label for="addon_{{ $slug }}" class="ml-2 block text-sm text-gray-300">
                                {{ $addon['name'] ?? $slug }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Features -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-white mb-4">Features</h3>
                <div id="features-container">
                    @php
                        $features = old('features', $plan->features ?? []);
                        if (!is_array($features)) $features = [];
                    @endphp
                    @foreach($features as $index => $feature)
                        <div class="flex gap-2 mb-2 feature-item">
                            <input type="text" name="features[]" value="{{ $feature }}" placeholder="Enter feature"
                                   class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                            <button type="button" onclick="removeFeature(this)" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
                                Remove
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addFeature()" class="mt-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Add Feature
                </button>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.subscription-plans') }}" class="bg-gray-600 text-white px-6 py-3 rounded hover:bg-gray-700 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-yellow-600 text-black px-6 py-3 rounded hover:bg-yellow-500 font-semibold">
                    {{ isset($plan) ? 'Update' : 'Create' }} Plan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const featureDiv = document.createElement('div');
    featureDiv.className = 'flex gap-2 mb-2 feature-item';
    featureDiv.innerHTML = `
        <input type="text" name="features[]" placeholder="Enter feature"
               class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
        <button type="button" onclick="removeFeature(this)" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
            Remove
        </button>
    `;
    container.appendChild(featureDiv);
}

function removeFeature(button) {
    button.closest('.feature-item').remove();
}
</script>
@endsection