@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-semibold text-gold-400 mb-2">User Management</h2>
    <p class="text-gray-400 text-sm">Manage user accounts, roles, and permissions</p>
</div>

<!-- User Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl"><i class="fas fa-users"></i></div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $users->total() }}</div>
                <div class="text-gray-400 text-sm">Total Users</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl"><i class="fas fa-crown"></i></div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $users->where('role', 'admin')->count() }}</div>
                <div class="text-gray-400 text-sm">Admins</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl"><i class="fas fa-chart-bar"></i></div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $users->sum('usage_logs_count') }}</div>
                <div class="text-gray-400 text-sm">Total Conversions</div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="stat-card">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gold-400/20">
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">User</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Email</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Role</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Plan</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Conversions</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Joined</th>
                    <th class="px-3 py-2 text-left text-gold-400 font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-white/10">
                    <td class="px-3 py-2 text-white">
                        <div class="font-medium text-sm">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">ID: {{ $user->id }}</div>
                    </td>
                    <td class="px-3 py-2 text-white text-sm">{{ $user->email }}</td>
                    <td class="px-3 py-2">
                        <form method="POST" action="{{ route('admin.update-user-role', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" class="bg-white/10 border border-gold-400/30 text-white px-2 py-1 rounded text-xs">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-3 py-2">
                        <form method="POST" action="{{ route('admin.assign-subscription', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="subscription_plan_id" onchange="this.form.submit()" class="bg-white/10 border border-gold-400/30 text-white px-2 py-1 rounded text-xs">
                                <option value="">None</option>
                                @foreach($subscriptionPlans as $plan)
                                    <option value="{{ $plan->id }}" {{ $user->activeSubscription?->subscription_plan_id === $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-3 py-2 text-white text-sm">{{ $user->usage_logs_count }}</td>
                    <td class="px-3 py-2 text-white text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-3 py-2">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.delete-user', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500/10 border border-red-500/30 text-red-400 px-2 py-1 rounded text-xs">Delete</button>
                        </form>
                        @else
                        <span class="text-gray-400 text-xs">Current User</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-4 flex justify-center">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection