<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border-right: 1px solid rgba(255, 215, 0, 0.2);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .admin-sidebar.collapsed {
            width: 70px;
        }

        .admin-main {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        .admin-main.expanded {
            margin-left: 70px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 0.5rem;
        }

        .sidebar-item:hover {
            background: rgba(255, 215, 0, 0.1);
            color: #ffd700;
            border-left-color: #ffd700;
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: rgba(255, 215, 0, 0.15);
            color: #ffd700;
            border-left-color: #ffd700;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .sidebar-text {
            flex: 1;
            transition: opacity 0.3s ease;
        }

        .admin-sidebar.collapsed .sidebar-text {
            opacity: 0;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-content {
            padding: 2rem;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            min-height: calc(100vh - 80px);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #ffd700;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffd700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #b0b0b0;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: #ffd700;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .toggle-sidebar:hover {
            background: rgba(255, 215, 0, 0.1);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body style="font-family: 'Inter', sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #ffffff;">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div style="padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 215, 0, 0.2);">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: #ffd700; margin: 0;">
                <span class="sidebar-text">Admin Panel</span>
            </h2>
        </div>

        <nav style="padding: 1rem 0;">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon">📊</span>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="sidebar-icon">👥</span>
                <span class="sidebar-text">Users</span>
            </a>

            <a href="{{ route('admin.addons') }}" class="sidebar-item {{ request()->routeIs('admin.addons*') ? 'active' : '' }}">
                <span class="sidebar-icon">🔧</span>
                <span class="sidebar-text">Addons</span>
            </a>

            <a href="{{ route('admin.subscription-plans') }}" class="sidebar-item {{ request()->routeIs('admin.subscription-plans*') ? 'active' : '' }}">
                <span class="sidebar-icon">💳</span>
                <span class="sidebar-text">Subscription Plans</span>
            </a>

            <a href="{{ route('admin.content-editor') }}" class="sidebar-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                <span class="sidebar-icon">📝</span>
                <span class="sidebar-text">Content Editor</span>
            </a>

            <a href="{{ route('admin.analytics') }}" class="sidebar-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <span class="sidebar-icon">📈</span>
                <span class="sidebar-text">Analytics</span>
            </a>

            <a href="{{ route('admin.logs') }}" class="sidebar-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <span class="sidebar-icon">📝</span>
                <span class="sidebar-text">System Logs</span>
            </a>

            <a href="{{ route('admin.settings') }}" class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <span class="sidebar-icon">⚙️</span>
                <span class="sidebar-text">Settings</span>
            </a>
        </nav>

        <div style="position: absolute; bottom: 2rem; left: 1.5rem; right: 1.5rem;">
            <a href="{{ route('home') }}" class="sidebar-item">
                <span class="sidebar-icon">🏠</span>
                <span class="sidebar-text">Back to Site</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main" id="adminMain">
        <!-- Header -->
        <header class="admin-header">
            <div>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #ffd700; margin: 0;">{{ $title ?? 'Dashboard' }}</h1>
                <p style="color: #b0b0b0; margin: 0.25rem 0 0 0; font-size: 0.9rem;">Welcome back, {{ auth()->user()->name }}</p>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="toggle-sidebar" id="toggleSidebar" title="Toggle Sidebar">
                    ☰
                </button>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: rgba(255, 107, 107, 0.1); color: #ff6b6b; border: 1px solid rgba(255, 107, 107, 0.3); padding: 0.5rem 1rem; border-radius: 25px; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 107, 107, 0.2)'" onmouseout="this.style.background='rgba(255, 107, 107, 0.1)'">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content">
            @if(session('success'))
                <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle sidebar
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('adminSidebar');
        const main = document.getElementById('adminMain');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }
    </script>
</body>
</html>