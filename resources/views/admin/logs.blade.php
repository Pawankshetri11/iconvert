@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">System Logs</h2>
    <p style="color: #b0b0b0;">Monitor system activity and debug information</p>
</div>

<!-- Log Controls -->
<div class="stat-card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700;">Recent Log Entries</h3>
        <div style="display: flex; gap: 1rem;">
            <button onclick="refreshLogs()" style="background: linear-gradient(45deg, #3b82f6, #1d4ed8); color: white; border: none; padding: 0.5rem 1rem; border-radius: 25px; cursor: pointer; font-weight: 500;">Refresh</button>
            <button onclick="clearLogs()" style="background: linear-gradient(45deg, #ef4444, #dc2626); color: white; border: none; padding: 0.5rem 1rem; border-radius: 25px; cursor: pointer; font-weight: 500;">Clear Logs</button>
        </div>
    </div>

    <div style="background: rgba(0, 0, 0, 0.5); border-radius: 8px; padding: 1rem; font-family: 'Courier New', monospace; font-size: 0.9rem; max-height: 500px; overflow-y: auto;" id="logContainer">
        @if(empty($logs))
            <div style="color: #b0b0b0; text-align: center; padding: 2rem;">
                No log entries found. Logs will appear here as system activity occurs.
            </div>
        @else
            @foreach(array_reverse($logs) as $log)
                <div style="margin-bottom: 0.5rem; padding: 0.5rem; border-radius: 4px; background: rgba(255, 255, 255, 0.05);">
                    <div style="color: #888; font-size: 0.8rem; margin-bottom: 0.25rem;">{{ $log }}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Log Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">📄</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ count($logs) }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Log Entries</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">📊</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #22c55e;">{{ file_exists(storage_path('logs/laravel.log')) ? number_format(filesize(storage_path('logs/laravel.log')) / 1024, 1) . ' KB' : '0 KB' }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Log File Size</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">⚠️</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #f59e0b;">{{ count(array_filter($logs, fn($log) => str_contains(strtolower($log), 'error') || str_contains(strtolower($log), 'exception'))) }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Error Entries</div>
            </div>
        </div>
    </div>
</div>

<!-- Log Levels Info -->
<div class="stat-card" style="margin-top: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Log Levels</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
        <div style="text-align: center; padding: 1rem; background: rgba(239, 68, 68, 0.1); border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.3);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🚨</div>
            <div style="font-weight: 600; color: #ef4444;">ERROR</div>
            <div style="font-size: 0.8rem; color: #b0b0b0;">Critical errors</div>
        </div>

        <div style="text-align: center; padding: 1rem; background: rgba(245, 158, 11, 0.1); border-radius: 8px; border: 1px solid rgba(245, 158, 11, 0.3);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚠️</div>
            <div style="font-weight: 600; color: #f59e0b;">WARNING</div>
            <div style="font-size: 0.8rem; color: #b0b0b0;">Warnings</div>
        </div>

        <div style="text-align: center; padding: 1rem; background: rgba(34, 197, 94, 0.1); border-radius: 8px; border: 1px solid rgba(34, 197, 94, 0.3);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">ℹ️</div>
            <div style="font-weight: 600; color: #22c55e;">INFO</div>
            <div style="font-size: 0.8rem; color: #b0b0b0;">General info</div>
        </div>

        <div style="text-align: center; padding: 1rem; background: rgba(59, 130, 246, 0.1); border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.3);">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔍</div>
            <div style="font-weight: 600; color: #3b82f6;">DEBUG</div>
            <div style="font-size: 0.8rem; color: #b0b0b0;">Debug info</div>
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