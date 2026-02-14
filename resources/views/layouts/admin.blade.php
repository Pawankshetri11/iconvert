<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        gold: { 400: '#ffd700', 500: '#ffed4e', 600: '#d4af37' },
                        dark: { 800: '#18181b', 900: '#09090b' }
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'shimmer': 'shimmer 2.5s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .gold-text-gradient {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .admin-sidebar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border-right: 1px solid rgba(255, 215, 0, 0.2);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .admin-sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .admin-sidebar-footer {
            flex-shrink: 0;
            padding: 2rem 1.5rem 2rem 1.5rem;
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

        /* Sleek Scrollbar for Admin Sidebar */
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.3);
            border-radius: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 215, 0, 0.5);
        }

        /* Hide scrollbar on Firefox */
        .admin-sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 215, 0, 0.3) rgba(255, 255, 255, 0.05);
        }

        /* Mobile Admin Sidebar */
        .admin-mobile-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border-right: 1px solid rgba(255, 215, 0, 0.2);
            z-index: 2; /* Above backdrop */
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .admin-mobile-sidebar.open {
            transform: translateX(0);
        }

        .admin-mobile-menu {
            position: fixed;
            inset: 0;
            z-index: 1000; /* Behind mobile header */
            display: none;
        }

        .admin-mobile-menu.open {
            display: block !important;
        }

        .admin-mobile-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .admin-mobile-menu.open .admin-mobile-backdrop {
            opacity: 1;
        }

        /* Mobile Header */
        .admin-mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001; /* Higher than mobile menu */
            background: rgba(9, 9, 11, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        /* Hamburger Animation */
        #adminMobileMenuBtn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 6px);
        }

        #adminMobileMenuBtn.active span:nth-child(2) {
            opacity: 0;
        }

        #adminMobileMenuBtn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -6px);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                display: none; /* Hide desktop sidebar */
            }

            .admin-mobile-header {
                display: block;
            }

            .admin-main {
                margin-left: 0;
                padding-top: 5rem; /* Account for mobile header */
            }

            .admin-header {
                display: none; /* Hide desktop header on mobile */
            }

            .admin-content {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .admin-mobile-sidebar {
                width: 100%;
                max-width: 320px;
            }

            .admin-content {
                padding: 0.75rem;
            }

            .stat-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #ffffff;">
    <!-- Mobile Menu Overlay -->
    <div class="admin-mobile-menu" id="adminMobileMenu">
        <div class="admin-mobile-backdrop" id="adminMobileBackdrop"></div>

        <!-- Mobile Sidebar -->
        <aside class="admin-mobile-sidebar" id="adminMobileSidebar">
            <!-- Header - Fixed at top -->
            <div style="padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 215, 0, 0.2); flex-shrink: 0;">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group relative z-50">
                    <div class="relative w-9 h-9">
                        <div class="absolute inset-0 bg-gold-400 rounded-md blur opacity-40 group-hover:opacity-70 transition-opacity duration-300 animate-pulse"></div>
                        <div class="relative w-full h-full rounded-md bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-xl group-hover:rotate-6 transition-transform duration-300 shadow-lg border border-white/20">
                            i
                        </div>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white group-hover:tracking-wide transition-all duration-300">i<span class="gold-text-gradient">Convert</span></span>
                </a>
                <p class="text-xs text-zinc-500 mt-1">Admin Panel</p>
            </div>

            <!-- Scrollable Content -->
            <div class="admin-sidebar-content">
                <nav style="padding: 1rem 0;">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar sidebar-icon"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users sidebar-icon"></i>
                        <span class="sidebar-text">Users</span>
                    </a>

                    <a href="{{ route('admin.addons') }}" class="sidebar-item {{ request()->routeIs('admin.addons*') ? 'active' : '' }}">
                        <i class="fas fa-tools sidebar-icon"></i>
                        <span class="sidebar-text">Addons</span>
                    </a>

                    <a href="{{ route('admin.subscription-plans') }}" class="sidebar-item {{ request()->routeIs('admin.subscription-plans*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card sidebar-icon"></i>
                        <span class="sidebar-text">Subscription Plans</span>
                    </a>

                    <a href="{{ route('admin.content-editor') }}" class="sidebar-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                        <i class="fas fa-edit sidebar-icon"></i>
                        <span class="sidebar-text">Content Editor</span>
                    </a>

                    <a href="{{ route('admin.analytics') }}" class="sidebar-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-line sidebar-icon"></i>
                        <span class="sidebar-text">Analytics</span>
                    </a>

                    <a href="{{ route('admin.logs') }}" class="sidebar-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                        <i class="fas fa-file-alt sidebar-icon"></i>
                        <span class="sidebar-text">System Logs</span>
                    </a>

                    <a href="{{ route('admin.settings') }}" class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog sidebar-icon"></i>
                        <span class="sidebar-text">Settings</span>
                    </a>

                    <a href="{{ route('admin.license.index') }}" class="sidebar-item {{ request()->routeIs('admin.license*') ? 'active' : '' }}">
                        <i class="fas fa-key sidebar-icon"></i>
                        <span class="sidebar-text">License</span>
                    </a>

                    <a href="{{ route('admin.updates.index') }}" class="sidebar-item {{ request()->routeIs('admin.updates*') ? 'active' : '' }}">
                        <i class="fas fa-sync sidebar-icon"></i>
                        <span class="sidebar-text">Updates</span>
                    </a>
                </nav>
            </div>

            <!-- Footer - Fixed at bottom -->
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="sidebar-item">
                    <i class="fas fa-home sidebar-icon"></i>
                    <span class="sidebar-text">Back to Site</span>
                </a>
            </div>
        </aside>
    </div>

    <!-- Mobile Header -->
    <header class="admin-mobile-header">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="relative w-8 h-8">
                    <div class="absolute inset-0 bg-gold-400 rounded-md blur opacity-40 group-hover:opacity-70 transition-opacity duration-300"></div>
                    <div class="relative w-full h-full rounded-md bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-lg group-hover:rotate-6 transition-transform duration-300">
                        i
                    </div>
                </div>
                <span class="text-xl font-bold tracking-tight text-white group-hover:tracking-wide transition-all duration-300">i<span class="gold-text-gradient">Convert</span></span>
            </a>

            <!-- Hamburger Menu -->
            <button id="adminMobileMenuBtn" class="text-white p-2 focus:outline-none group">
                <div class="w-6 h-5 flex flex-col justify-between relative">
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                </div>
            </button>
        </div>
    </header>

    <!-- Desktop Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Header - Fixed at top -->
        <div style="padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 215, 0, 0.2); flex-shrink: 0;">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group relative z-50">
                <div class="relative w-9 h-9">
                    <div class="absolute inset-0 bg-gold-400 rounded-md blur opacity-40 group-hover:opacity-70 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative w-full h-full rounded-md bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-xl group-hover:rotate-6 transition-transform duration-300 shadow-lg border border-white/20">
                        i
                    </div>
                </div>
                <span class="text-2xl font-bold tracking-tight text-white group-hover:tracking-wide transition-all duration-300 sidebar-text">i<span class="gold-text-gradient">Convert</span></span>
            </a>
            <p class="text-xs text-zinc-500 mt-1 sidebar-text">Admin Panel</p>
        </div>

        <!-- Scrollable Content -->
        <div class="admin-sidebar-content">
            <nav style="padding: 1rem 0;">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar sidebar-icon"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users sidebar-icon"></i>
                    <span class="sidebar-text">Users</span>
                </a>

                <a href="{{ route('admin.addons') }}" class="sidebar-item {{ request()->routeIs('admin.addons*') ? 'active' : '' }}">
                    <i class="fas fa-tools sidebar-icon"></i>
                    <span class="sidebar-text">Addons</span>
                </a>

                <a href="{{ route('admin.subscription-plans') }}" class="sidebar-item {{ request()->routeIs('admin.subscription-plans*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card sidebar-icon"></i>
                    <span class="sidebar-text">Subscription Plans</span>
                </a>

                <a href="{{ route('admin.content-editor') }}" class="sidebar-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                    <i class="fas fa-edit sidebar-icon"></i>
                    <span class="sidebar-text">Content Editor</span>
                </a>

                <a href="{{ route('admin.analytics') }}" class="sidebar-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-line sidebar-icon"></i>
                    <span class="sidebar-text">Analytics</span>
                </a>

                <a href="{{ route('admin.logs') }}" class="sidebar-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <i class="fas fa-file-alt sidebar-icon"></i>
                    <span class="sidebar-text">System Logs</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog sidebar-icon"></i>
                    <span class="sidebar-text">Settings</span>
                </a>

                <a href="{{ route('admin.license.index') }}" class="sidebar-item {{ request()->routeIs('admin.license*') ? 'active' : '' }}">
                    <i class="fas fa-key sidebar-icon"></i>
                    <span class="sidebar-text">License</span>
                </a>

                <a href="{{ route('admin.updates.index') }}" class="sidebar-item {{ request()->routeIs('admin.updates*') ? 'active' : '' }}">
                    <i class="fas fa-sync sidebar-icon"></i>
                    <span class="sidebar-text">Updates</span>
                </a>
            </nav>
        </div>

        <!-- Footer - Fixed at bottom -->
        <div class="admin-sidebar-footer">
            <a href="{{ route('home') }}" class="sidebar-item">
                <i class="fas fa-home sidebar-icon"></i>
                <span class="sidebar-text">Back to Site</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main" id="adminMain">
        <!-- Header -->
        <header class="admin-header">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-1">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-zinc-400 text-sm">Welcome back, {{ auth()->user()->name }}</p>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="toggle-sidebar" id="toggleSidebar" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-2 rounded-md hover:bg-red-500/20 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Desktop sidebar toggle
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('adminSidebar');
        const main = document.getElementById('adminMain');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
            });
        }

        // Mobile menu functionality
        const adminMobileMenuBtn = document.getElementById('adminMobileMenuBtn');
        const adminMobileMenu = document.getElementById('adminMobileMenu');
        const adminMobileBackdrop = document.getElementById('adminMobileBackdrop');
        const adminMobileSidebar = document.getElementById('adminMobileSidebar');

        function toggleAdminMobileMenu() {
            const isOpen = adminMobileMenu.classList.contains('open');
            adminMobileMenuBtn.classList.toggle('active');

            if (isOpen) {
                // Close menu
                adminMobileMenu.classList.remove('open');
                adminMobileSidebar.classList.remove('open');
                document.body.style.overflow = '';
            } else {
                // Open menu
                adminMobileMenu.classList.add('open');
                adminMobileSidebar.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        if (adminMobileMenuBtn) {
            adminMobileMenuBtn.addEventListener('click', toggleAdminMobileMenu);
        }

        // Close menu when clicking on overlay (anywhere except sidebar content)
        if (adminMobileMenu) {
            adminMobileMenu.addEventListener('click', function(e) {
                // Only close if clicking on the overlay itself, not on sidebar content
                if (e.target === adminMobileMenu || e.target === adminMobileBackdrop) {
                    toggleAdminMobileMenu();
                }
            });
        }
    </script>
</body>
</html>