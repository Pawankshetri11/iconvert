@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-number text-lg md:text-2xl">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="stat-label text-xs md:text-sm">Total Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-number text-lg md:text-2xl">{{ $stats['active_users'] ?? 0 }}</div>
        <div class="stat-label text-xs md:text-sm">Active Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-number text-lg md:text-2xl">{{ $stats['total_conversions'] ?? 0 }}</div>
        <div class="stat-label text-xs md:text-sm">Conversions</div>
    </div>

    <div class="stat-card">
        <div class="stat-number text-lg md:text-2xl">{{ $stats['active_addons'] ?? 0 }}</div>
        <div class="stat-label text-xs md:text-sm">Active Addons</div>
    </div>
</div>

<!-- Recent Activity & System Status -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Recent Users -->
    <div class="stat-card">
        <h3 class="text-lg font-semibold text-gold-400 mb-3">Recent Users</h3>
        <div class="space-y-2">
            @forelse($recentUsers ?? [] as $user)
                <div class="flex justify-between items-center p-2 bg-white/5 rounded-lg">
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-white text-sm truncate">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                    </div>
                    <div class="text-xs text-gold-400 ml-2 flex-shrink-0">{{ $user->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-4 text-sm">No recent users</p>
            @endforelse
        </div>
    </div>

    <!-- System Status -->
    <div class="stat-card">
        <h3 class="text-lg font-semibold text-gold-400 mb-3">System Status</h3>
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-white text-sm">Database</span>
                <span class="text-green-400 text-sm">✓ Connected</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white text-sm">File System</span>
                <span class="text-green-400 text-sm">✓ Operational</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white text-sm">Cache</span>
                <span class="text-green-400 text-sm">✓ Working</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white text-sm">Queue</span>
                <span class="text-green-400 text-sm">✓ Active</span>
            </div>
        </div>
    </div>
</div>

<!-- Addon Management -->
<div class="stat-card mb-4">
    <h3 class="text-lg font-semibold text-gold-400 mb-2">Addon Management</h3>
    <p class="text-gray-400 text-sm mb-4">Control which addons are available to users</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($addons as $slug => $addon)
            <div class="bg-white/5 border border-gold-400/20 rounded-lg p-3">
                <div class="flex justify-between items-start mb-2">
                    <div class="min-w-0 flex-1">
                        <h4 class="font-semibold text-white text-sm truncate">{{ $addon['name'] ?? ucfirst(str_replace('-', ' ', $slug)) }}</h4>
                        <p class="text-gray-400 text-xs truncate">{{ $addon['description'] ?? 'No description available.' }}</p>
                    </div>
                    <div class="w-3 h-3 rounded-full {{ $addon['enabled'] ? 'bg-green-400' : 'bg-red-400' }} ml-2 flex-shrink-0"></div>
                </div>

                <form method="POST" action="{{ route('admin.toggle-addon', $slug) }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs px-3 py-1 rounded-full font-medium transition-all {{ $addon['enabled'] ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                        {{ $addon['enabled'] ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="stat-card">
    <h3 class="text-lg font-semibold text-gold-400 mb-3">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('admin.users') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg text-center transition-all hover:transform hover:scale-105">
            <div class="text-xl mb-1"><i class="fas fa-users"></i></div>
            <div class="font-semibold text-sm">Users</div>
        </a>

        <a href="{{ route('admin.analytics') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-3 rounded-lg text-center transition-all hover:transform hover:scale-105">
            <div class="text-xl mb-1"><i class="fas fa-chart-bar"></i></div>
            <div class="font-semibold text-sm">Analytics</div>
        </a>

        <a href="{{ route('admin.logs') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-3 rounded-lg text-center transition-all hover:transform hover:scale-105">
            <div class="text-xl mb-1"><i class="fas fa-file-alt"></i></div>
            <div class="font-semibold text-sm">Logs</div>
        </a>

        <a href="{{ route('admin.settings') }}" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg text-center transition-all hover:transform hover:scale-105">
            <div class="text-xl mb-1"><i class="fas fa-cog"></i></div>
            <div class="font-semibold text-sm">Settings</div>
        </a>
    </div>
</div>
@endsection