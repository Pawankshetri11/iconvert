@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Analytics Dashboard</h2>
    <p style="color: #b0b0b0;">Monitor user activity, conversions, and system performance</p>
</div>

<!-- Analytics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">📈</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $analytics['user_registrations']->sum('count') ?? 0 }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">New Users (30 days)</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">🔄</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $analytics['conversions_by_type']->sum('count') ?? 0 }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Total Conversions</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">👑</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $analytics['top_users']->first()->usage_logs_count ?? 0 }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Top User Conversions</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
    <!-- User Registrations Chart -->
    <div class="stat-card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">User Registrations (Last 30 Days)</h3>
        <div style="height: 200px; display: flex; align-items: end; gap: 2px;">
            @php
                $maxCount = $analytics['user_registrations']->max('count') ?: 1;
            @endphp
            @foreach($analytics['user_registrations'] as $registration)
                <div style="flex: 1; background: linear-gradient(to top, #ffd700, #ffed4e); height: {{ ($registration->count / $maxCount) * 180 }}px; border-radius: 2px 2px 0 0; position: relative;" title="{{ $registration->date }}: {{ $registration->count }} users">
                    <div style="position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: #b0b0b0; white-space: nowrap;">{{ \Carbon\Carbon::parse($registration->date)->format('M d') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Conversion Types -->
    <div class="stat-card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Conversions by Type</h3>
        <div style="space-y: 1rem;">
            @foreach($analytics['conversions_by_type'] as $conversion)
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #e0e0e0; text-transform: capitalize;">{{ str_replace('-', ' ', $conversion->type) }}</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 100px; height: 8px; background: rgba(255, 255, 255, 0.1); border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ ($conversion->count / ($analytics['conversions_by_type']->max('count') ?: 1)) * 100 }}%; height: 100%; background: linear-gradient(45deg, #ffd700, #ffed4e);"></div>
                        </div>
                        <span style="color: #ffd700; font-weight: 600; min-width: 30px; text-align: right;">{{ $conversion->count }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top Users -->
<div class="stat-card">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Top Users by Conversions</h3>
    <div style="space-y: 1rem;">
        @foreach($analytics['top_users'] as $index => $user)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(45deg, #ffd700, #ffed4e); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #000;">{{ $index + 1 }}</div>
                    <div>
                        <div style="font-weight: 600; color: #e0e0e0;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: #b0b0b0;">{{ $user->email }}</div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.2rem; font-weight: 700; color: #ffd700;">{{ $user->usage_logs_count }}</div>
                    <div style="font-size: 0.8rem; color: #b0b0b0;">conversions</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- System Performance -->
<div class="stat-card" style="margin-top: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">System Performance</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="text-align: center; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #22c55e;">{{ number_format(memory_get_peak_usage() / 1024 / 1024, 2) }} MB</div>
            <div style="color: #b0b0b0; font-size: 0.9rem;">Peak Memory</div>
        </div>

        <div style="text-align: center; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🕐</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #3b82f6;">{{ number_format(microtime(true) - LARAVEL_START, 4) }}s</div>
            <div style="color: #b0b0b0; font-size: 0.9rem;">Load Time</div>
        </div>

        <div style="text-align: center; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">💾</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #f59e0b;">{{ \Illuminate\Support\Facades\DB::table('information_schema.tables')->where('table_schema', config('database.connections.mysql.database'))->count() }}</div>
            <div style="color: #b0b0b0; font-size: 0.9rem;">Database Tables</div>
        </div>
    </div>
</div>
@endsection