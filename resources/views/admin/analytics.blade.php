@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-semibold text-gold-400 mb-2">Analytics Dashboard</h2>
    <p class="text-gray-400 text-sm">Monitor user activity, conversions, and system performance</p>
</div>

<!-- Analytics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">📈</div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $analytics['user_registrations']->sum('count') ?? 0 }}</div>
                <div class="text-gray-400 text-sm">New Users (30 days)</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">🔄</div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $analytics['conversions_by_type']->sum('count') ?? 0 }}</div>
                <div class="text-gray-400 text-sm">Total Conversions</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">👑</div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ $analytics['top_users']->first()->usage_logs_count ?? 0 }}</div>
                <div class="text-gray-400 text-sm">Top User Conversions</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <!-- User Registrations Chart -->
    <div class="stat-card">
        <h3 class="text-lg font-semibold text-gold-400 mb-3">User Registrations (Last 30 Days)</h3>
        <div class="h-32 flex items-end gap-1">
            @php
                $maxCount = $analytics['user_registrations']->max('count') ?: 1;
            @endphp
            @foreach($analytics['user_registrations'] as $registration)
                <div class="flex-1 bg-gradient-to-t from-gold-400 to-yellow-300 rounded-t-sm relative" style="height: {{ ($registration->count / $maxCount) * 100 }}%" title="{{ $registration->date }}: {{ $registration->count }} users">
                    <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($registration->date)->format('M d') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Conversion Types -->
    <div class="stat-card">
        <h3 class="text-lg font-semibold text-gold-400 mb-3">Conversions by Type</h3>
        <div class="space-y-2">
            @foreach($analytics['conversions_by_type'] as $conversion)
                <div class="flex justify-between items-center">
                    <span class="text-white text-sm capitalize">{{ str_replace('-', ' ', $conversion->type) }}</span>
                    <div class="flex items-center gap-2">
                        <div class="w-16 h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-gold-400 to-yellow-300" style="width: {{ ($conversion->count / ($analytics['conversions_by_type']->max('count') ?: 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-gold-400 font-semibold text-sm w-8 text-right">{{ $conversion->count }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top Users -->
<div class="stat-card mb-4">
    <h3 class="text-lg font-semibold text-gold-400 mb-3">Top Users by Conversions</h3>
    <div class="space-y-2">
        @foreach($analytics['top_users'] as $index => $user)
            <div class="flex justify-between items-center p-2 bg-white/5 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-gold-400 to-yellow-300 rounded-full flex items-center justify-center font-bold text-black text-sm">{{ $index + 1 }}</div>
                    <div>
                        <div class="font-medium text-white text-sm">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-gold-400">{{ $user->usage_logs_count }}</div>
                    <div class="text-xs text-gray-400">conversions</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- System Performance -->
<div class="stat-card">
    <h3 class="text-lg font-semibold text-gold-400 mb-3">System Performance</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="text-center p-3 bg-white/5 rounded-lg">
            <div class="text-2xl mb-1">⚡</div>
            <div class="text-lg font-bold text-green-400">{{ number_format(memory_get_peak_usage() / 1024 / 1024, 2) }} MB</div>
            <div class="text-gray-400 text-sm">Peak Memory</div>
        </div>

        <div class="text-center p-3 bg-white/5 rounded-lg">
            <div class="text-2xl mb-1">🕐</div>
            <div class="text-lg font-bold text-blue-400">{{ number_format(microtime(true) - LARAVEL_START, 4) }}s</div>
            <div class="text-gray-400 text-sm">Load Time</div>
        </div>

        <div class="text-center p-3 bg-white/5 rounded-lg">
            <div class="text-2xl mb-1">💾</div>
            <div class="text-lg font-bold text-yellow-400">{{ \Illuminate\Support\Facades\DB::table('information_schema.tables')->where('table_schema', config('database.connections.mysql.database'))->count() }}</div>
            <div class="text-gray-400 text-sm">Database Tables</div>
        </div>
    </div>
</div>
@endsection