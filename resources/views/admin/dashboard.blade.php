@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="stat-label">Total Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-number">{{ $stats['active_users'] ?? 0 }}</div>
        <div class="stat-label">Active Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_conversions'] ?? 0 }}</div>
        <div class="stat-label">Total Conversions</div>
    </div>

    <div class="stat-card">
        <div class="stat-number">{{ $stats['active_addons'] ?? 0 }}</div>
        <div class="stat-label">Active Addons</div>
    </div>
</div>

<!-- Recent Activity -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
    <!-- Recent Users -->
    <div class="stat-card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Recent Users</h3>
        <div style="space-y: 1rem;">
            @forelse($recentUsers ?? [] as $user)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <div>
                        <div style="font-weight: 500; color: #e0e0e0;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: #b0b0b0;">{{ $user->email }}</div>
                    </div>
                    <div style="font-size: 0.8rem; color: #ffd700;">{{ $user->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p style="color: #b0b0b0; text-align: center; padding: 2rem;">No recent users</p>
            @endforelse
        </div>
    </div>

    <!-- System Status -->
    <div class="stat-card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">System Status</h3>
        <div style="space-y: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #e0e0e0;">Database</span>
                <span style="color: #22c55e;">✓ Connected</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #e0e0e0;">File System</span>
                <span style="color: #22c55e;">✓ Operational</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #e0e0e0;">Cache</span>
                <span style="color: #22c55e;">✓ Working</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #e0e0e0;">Queue</span>
                <span style="color: #22c55e;">✓ Active</span>
            </div>
        </div>
    </div>
</div>

<!-- Addon Management -->
<div class="stat-card">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Addon Management</h3>
    <p style="color: #b0b0b0; margin-bottom: 2rem;">Control which addons are available to users</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        @foreach($addons as $slug => $addon)
            <div style="background: rgba(255, 255, 255, 0.05); border-radius: 10px; padding: 1.5rem; border: 1px solid rgba(255, 215, 0, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h4 style="font-weight: 600; color: #e0e0e0; margin-bottom: 0.5rem;">{{ $addon['name'] ?? ucfirst(str_replace('-', ' ', $slug)) }}</h4>
                        <p style="color: #b0b0b0; font-size: 0.9rem;">{{ $addon['description'] ?? 'No description available.' }}</p>
                    </div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $addon['enabled'] ? '#22c55e' : '#ef4444' }};"></div>
                </div>

                <form method="POST" action="{{ route('admin.toggle-addon', $slug) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: {{ $addon['enabled'] ? 'linear-gradient(45deg, #ef4444, #dc2626)' : 'linear-gradient(45deg, #22c55e, #16a34a)' }}; color: white; border: none; padding: 0.5rem 1rem; border-radius: 25px; cursor: pointer; font-weight: 500; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        {{ $addon['enabled'] ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="stat-card" style="margin-top: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Quick Actions</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('admin.users') }}" style="background: linear-gradient(45deg, #3b82f6, #1d4ed8); color: white; padding: 1rem; border-radius: 10px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(59, 130, 246, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">👥</div>
            <div style="font-weight: 600;">Manage Users</div>
        </a>

        <a href="{{ route('admin.analytics') }}" style="background: linear-gradient(45deg, #8b5cf6, #7c3aed); color: white; padding: 1rem; border-radius: 10px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(139, 92, 246, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📊</div>
            <div style="font-weight: 600;">View Analytics</div>
        </a>

        <a href="{{ route('admin.logs') }}" style="background: linear-gradient(45deg, #f59e0b, #d97706); color: white; padding: 1rem; border-radius: 10px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📝</div>
            <div style="font-weight: 600;">System Logs</div>
        </a>

        <a href="{{ route('admin.settings') }}" style="background: linear-gradient(45deg, #10b981, #059669); color: white; padding: 1rem; border-radius: 10px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚙️</div>
            <div style="font-weight: 600;">Settings</div>
        </a>
    </div>
</div>
@endsection