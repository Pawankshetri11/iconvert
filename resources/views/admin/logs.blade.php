@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-semibold text-gold-400 mb-2">System Logs</h2>
    <p class="text-gray-400 text-sm">Monitor system activity and debug information</p>
</div>

<!-- Log Controls -->
<div class="stat-card mb-4">
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-semibold text-gold-400">Recent Log Entries</h3>
        <div class="flex gap-2">
            <button onclick="refreshLogs()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">Refresh</button>
            <button onclick="clearLogs()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">Clear Logs</button>
        </div>
    </div>

    <div class="bg-black/50 rounded-lg p-3 font-mono text-sm max-h-64 overflow-y-auto" id="logContainer">
        @if(empty($logs))
            <div class="text-gray-400 text-center py-8">
                No log entries found. Logs will appear here as system activity occurs.
            </div>
        @else
            @foreach(array_reverse($logs) as $log)
                <div class="mb-1 p-2 rounded bg-white/5">
                    <div class="text-gray-500 text-xs">{{ $log }}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Log Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">📄</div>
            <div>
                <div class="text-xl font-bold text-gold-400">{{ count($logs) }}</div>
                <div class="text-gray-400 text-sm">Log Entries</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">📊</div>
            <div>
                <div class="text-xl font-bold text-green-400">{{ file_exists(storage_path('logs/laravel.log')) ? number_format(filesize(storage_path('logs/laravel.log')) / 1024, 1) . ' KB' : '0 KB' }}</div>
                <div class="text-gray-400 text-sm">Log File Size</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center gap-3">
            <div class="text-2xl">⚠️</div>
            <div>
                <div class="text-xl font-bold text-yellow-400">{{ count(array_filter($logs, fn($log) => str_contains(strtolower($log), 'error') || str_contains(strtolower($log), 'exception'))) }}</div>
                <div class="text-gray-400 text-sm">Error Entries</div>
            </div>
        </div>
    </div>
</div>

<!-- Log Levels Info -->
<div class="stat-card">
    <h3 class="text-lg font-semibold text-gold-400 mb-3">Log Levels</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="text-center p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
            <div class="text-xl mb-1">🚨</div>
            <div class="font-semibold text-red-400">ERROR</div>
            <div class="text-xs text-gray-400">Critical errors</div>
        </div>

        <div class="text-center p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
            <div class="text-xl mb-1">⚠️</div>
            <div class="font-semibold text-yellow-400">WARNING</div>
            <div class="text-xs text-gray-400">Warnings</div>
        </div>

        <div class="text-center p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
            <div class="text-xl mb-1">ℹ️</div>
            <div class="font-semibold text-green-400">INFO</div>
            <div class="text-xs text-gray-400">General info</div>
        </div>

        <div class="text-center p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
            <div class="text-xl mb-1">🔍</div>
            <div class="font-semibold text-blue-400">DEBUG</div>
            <div class="text-xs text-gray-400">Debug info</div>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    location.reload();
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
        // In a real application, you'd make an AJAX call to clear logs
        alert('Log clearing functionality would be implemented here.');
    }
}
</script>
@endsection