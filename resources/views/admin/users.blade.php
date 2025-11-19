@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">User Management</h2>
    <p style="color: #b0b0b0;">Manage user accounts, roles, and permissions</p>
</div>

<!-- Users Table -->
<div class="stat-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255, 215, 0, 0.2);">
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">User</th>
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">Role</th>
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">Conversions</th>
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">Joined</th>
                    <th style="padding: 1rem; text-align: left; color: #ffd700; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <td style="padding: 1rem; color: #e0e0e0;">
                        <div style="font-weight: 500;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: #b0b0b0;">ID: {{ $user->id }}</div>
                    </td>
                    <td style="padding: 1rem; color: #e0e0e0;">{{ $user->email }}</td>
                    <td style="padding: 1rem;">
                        <form method="POST" action="{{ route('admin.update-user-role', $user) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); color: #e0e0e0; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 1rem; color: #e0e0e0;">{{ $user->usage_logs_count }}</td>
                    <td style="padding: 1rem; color: #e0e0e0;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="padding: 1rem;">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.delete-user', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 0.25rem 0.75rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">Delete</button>
                        </form>
                        @else
                        <span style="color: #b0b0b0; font-size: 0.8rem;">Current User</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- User Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">👥</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $users->total() }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Total Users</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">👑</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $users->where('role', 'admin')->count() }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Admins</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">📊</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffd700;">{{ $users->sum('usage_logs_count') }}</div>
                <div style="color: #b0b0b0; font-size: 0.9rem;">Total Conversions</div>
            </div>
        </div>
    </div>
</div>
@endsection