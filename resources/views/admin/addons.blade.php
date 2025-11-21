@extends('layouts.admin')

@section('title', 'Addon Management')

@section('content')
    <div class="space-y-6">
        <!-- Upload New Addon -->
        <div class="stat-card">
            <h3 class="text-xl font-semibold text-white mb-4">Install New Addon</h3>
            <form action="{{ route('admin.install-addon') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="addon_zip" class="block text-sm font-medium text-gray-300">Addon ZIP File</label>
                    <input type="file" name="addon_zip" id="addon_zip" accept=".zip" required
                           class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <button type="submit" class="bg-yellow-600 text-black px-4 py-2 rounded-md hover:bg-yellow-500 font-semibold">
                    Install Addon
                </button>
            </form>
        </div>

        <!-- Installed Addons -->
        <div class="stat-card">
            <h3 class="text-xl font-semibold text-white mb-4">Installed Addons</h3>
            @if(count($addons) > 0)
                <div class="grid gap-4">
                    @foreach($addons as $slug => $addon)
                        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-semibold text-white">{{ $addon['name'] ?? $slug }}</h4>
                                    <p class="text-sm text-gray-300">{{ $addon['description'] ?? 'No description' }}</p>
                                    <p class="text-xs text-gray-400">Version: {{ $addon['version'] ?? 'N/A' }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $addon['enabled'] ? 'bg-green-800 text-green-200' : 'bg-red-800 text-red-200' }}">
                                        {{ $addon['enabled'] ? 'Enabled' : 'Disabled' }}
                                    </span>
                                    <form action="{{ route('admin.toggle-addon', $slug) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-yellow-400 hover:text-yellow-300">
                                            {{ $addon['enabled'] ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">No addons installed.</p>
            @endif
        </div>
    </div>
@endsection