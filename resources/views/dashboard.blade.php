@extends('layouts.frontend')

@section('styles')
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif']
                    },
                    colors: {
                        royal: { 950: '#020202', 900: '#050505', 800: '#0a0a0a' },
                        gold: { 400: '#ffed4e', 500: '#ffd700', 600: '#d4b200' }
                    },
                    animation: {
                        'blob': 'blob 20s infinite',
                        'pulse-glow': 'pulse-glow 3s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #020202; color: #e4e4e7; overflow-x: hidden; }

        /* Hide header and footer for dashboard */
        header, footer { display: none !important; }

        /* Glass Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Gold Button */
        .btn-gold {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: black;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2);
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        /* Sidebar - Made scrollable */
        .sidebar {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-right: 1px solid rgba(255, 215, 0, 0.2);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Sleek Scrollbar */
        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.3);
            border-radius: 10px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 215, 0, 0.5);
        }

        /* Hide scrollbar on Firefox */
        .sidebar-content {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 215, 0, 0.3) rgba(255, 255, 255, 0.05);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 0.25rem;
        }

        .sidebar-item:hover {
            background: rgba(255, 215, 0, 0.1);
            color: #ffd700;
            border-left-color: #ffd700;
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

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }


        /* Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: rgba(255, 215, 0, 0.3);
            transform: translateY(-2px);
        }

        /* History Table */
        .history-table {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .history-table th,
        .history-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .history-table th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            color: #ffd700;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #e0e0e0;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: white;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #ffd700;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        /* Mobile Sidebar Specific CSS */
        .mobile-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-right: 1px solid rgba(255, 215, 0, 0.2);
            z-index: 50;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-menu-overlay.open {
            display: block;
            opacity: 1;
        }


        /* Hamburger Animation */
        #mobileMenuBtn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 6px);
        }

        #mobileMenuBtn.active span:nth-child(2) {
            opacity: 0;
        }

        #mobileMenuBtn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -6px);
        }

        /* Scrollbar for sidebar content */
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 215, 0, 0.3) rgba(255, 255, 255, 0.05);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }
            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none; /* Hide desktop sidebar on mobile */
            }
            .main-content {
                margin-left: 0;
            }
            .tab-nav {
                overflow-x: auto;
            }
            .tab-btn {
                white-space: nowrap;
            }
        }

        @media (max-width: 480px) {
            .tab-nav {
                padding: 0.5rem;
            }
            .tab-btn {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="flex min-h-screen">
    <!-- Mobile Menu Overlay & Sidebar -->
    <div id="dashboardMobileMenu" class="mobile-menu-overlay fixed inset-0 z-40 lg:hidden">
        <!-- Backdrop -->
        <div id="dashboardMobileBackdrop" class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <!-- Sidebar Drawer -->
        <aside class="mobile-sidebar">
            <!-- Header - Fixed at top -->
            <div class="p-6 border-b border-white/10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-xl">
                        i
                    </div>
                    <div>
                        <h2 class="text-xl font-display font-bold text-white">iConvert</h2>
                        <p class="text-xs text-zinc-400">Menu</p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="sidebar-content">
                <nav class="p-4">
                    <a href="#dashboard" class="sidebar-item active tab-link" data-tab="dashboard">
                        <i data-lucide="layout-dashboard" class="sidebar-icon"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="#subscription" class="sidebar-item tab-link" data-tab="subscription">
                        <i data-lucide="credit-card" class="sidebar-icon"></i>
                        <span>Subscription</span>
                    </a>

                    <a href="#history" class="sidebar-item tab-link" data-tab="history">
                        <i data-lucide="history" class="sidebar-icon"></i>
                        <span>History</span>
                    </a>

                    <a href="#profile" class="sidebar-item tab-link" data-tab="profile">
                        <i data-lucide="user" class="sidebar-icon"></i>
                        <span>Profile</span>
                    </a>

                    <hr class="border-white/10 my-4">

                    <div class="px-4 py-2">
                        <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-3">Tools</h3>
                        <div class="space-y-1">
                            <a href="{{ route('pdf-converter') }}" class="sidebar-item">
                                <i data-lucide="file-text" class="sidebar-icon"></i>
                                <span>PDF Tools</span>
                            </a>

                            <a href="{{ route('image-converter') }}" class="sidebar-item">
                                <i data-lucide="image" class="sidebar-icon"></i>
                                <span>Image Tools</span>
                            </a>

                            <a href="{{ route('mp3-converter') }}" class="sidebar-item">
                                <i data-lucide="music" class="sidebar-icon"></i>
                                <span>Audio Tools</span>
                            </a>

                            <a href="{{ route('invoice-generator') }}" class="sidebar-item">
                                <i data-lucide="file-text" class="sidebar-icon"></i>
                                <span>Invoice Generator</span>
                            </a>

                            <a href="{{ route('qr-generator') }}" class="sidebar-item">
                                <i data-lucide="qr-code" class="sidebar-icon"></i>
                                <span>QR Generator</span>
                            </a>

                            <a href="{{ route('id-card-creator') }}" class="sidebar-item">
                                <i data-lucide="id-card" class="sidebar-icon"></i>
                                <span>ID Card Creator</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- User Info - Fixed at bottom -->
            <div class="p-4 border-t border-white/10 mt-auto">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-gold-400 flex items-center justify-center text-black font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-zinc-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors text-sm font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
    </div>

    <!-- Desktop Sidebar -->
    <aside class="sidebar hidden lg:flex">
        <!-- Header - Fixed at top -->
        <div class="p-6 border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-xl">
                    i
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-white">iConvert</h2>
                    <p class="text-xs text-zinc-400">Dashboard</p>
                </div>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="sidebar-content">
            <nav class="p-4">
                <a href="#dashboard" class="sidebar-item active tab-link" data-tab="dashboard">
                    <i data-lucide="layout-dashboard" class="sidebar-icon"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#subscription" class="sidebar-item tab-link" data-tab="subscription">
                    <i data-lucide="credit-card" class="sidebar-icon"></i>
                    <span>Subscription</span>
                </a>

                <a href="#history" class="sidebar-item tab-link" data-tab="history">
                    <i data-lucide="history" class="sidebar-icon"></i>
                    <span>Conversion History</span>
                </a>

                <a href="#profile" class="sidebar-item tab-link" data-tab="profile">
                    <i data-lucide="user" class="sidebar-icon"></i>
                    <span>Profile Settings</span>
                </a>

                <hr class="border-white/10 my-4">

                <div class="px-4 py-2">
                    <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-3">Tools</h3>
                    <div class="space-y-1">
                        <a href="{{ route('pdf-converter') }}" class="sidebar-item">
                            <i data-lucide="file-text" class="sidebar-icon"></i>
                            <span>PDF Tools</span>
                        </a>

                        <a href="{{ route('image-converter') }}" class="sidebar-item">
                            <i data-lucide="image" class="sidebar-icon"></i>
                            <span>Image Tools</span>
                        </a>

                        <a href="{{ route('mp3-converter') }}" class="sidebar-item">
                            <i data-lucide="music" class="sidebar-icon"></i>
                            <span>Audio Tools</span>
                        </a>

                        <a href="{{ route('invoice-generator') }}" class="sidebar-item">
                            <i data-lucide="file-text" class="sidebar-icon"></i>
                            <span>Invoice Generator</span>
                        </a>

                        <a href="{{ route('qr-generator') }}" class="sidebar-item">
                            <i data-lucide="qr-code" class="sidebar-icon"></i>
                            <span>QR Generator</span>
                        </a>

                        <a href="{{ route('id-card-creator') }}" class="sidebar-item">
                            <i data-lucide="id-card" class="sidebar-icon"></i>
                            <span>ID Card Creator</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- User Info - Fixed at bottom -->
        <div class="sidebar-footer p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-gold-400 flex items-center justify-center text-black font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-zinc-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors text-sm font-medium">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="mobile-header fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-white/10 py-4 px-6 lg:hidden">
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
            <button id="dashboardMobileMenuBtn" class="text-white p-2 focus:outline-none group">
                <div class="w-6 h-5 flex flex-col justify-between relative">
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                    <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400"></span>
                </div>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content flex-1">
        <!-- Dashboard Section -->
        <div id="dashboard">
            <div class="p-6">
                <!-- Welcome Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-display font-bold text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-zinc-400">Manage your account and access your premium tools</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card">
                        <div class="text-2xl font-bold text-gold-400 mb-1">
                            {{ Auth::user()->usageLogs()->where('success', true)->count() }}
                        </div>
                        <div class="text-sm text-zinc-400">Total Conversions</div>
                    </div>

                    <div class="stat-card">
                        <div class="text-2xl font-bold text-gold-400 mb-1">
                            @php
                                $remaining = Auth::user()->getRemainingConversions();
                                echo $remaining === -1 ? '∞' : $remaining;
                            @endphp
                        </div>
                        <div class="text-sm text-zinc-400">Conversions Left</div>
                    </div>

                    <div class="stat-card">
                        <div class="text-2xl font-bold text-gold-400 mb-1">
                            {{ Auth::user()->usageLogs()->where('success', true)->where('created_at', '>=', now()->startOfMonth())->count() }}
                        </div>
                        <div class="text-sm text-zinc-400">This Month</div>
                    </div>

                    <div class="stat-card">
                        <div class="text-2xl font-bold text-gold-400 mb-1">
                            {{ Auth::user()->activeSubscription?->plan_name ?? 'Free' }}
                        </div>
                        <div class="text-sm text-zinc-400">Current Plan</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('pdf-converter') }}" class="glass-panel rounded-xl p-6 hover:border-gold-400/50 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center group-hover:bg-red-500/20 transition-colors">
                                    <i data-lucide="file-text" class="w-6 h-6 text-red-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">PDF Tools</h3>
                                    <p class="text-sm text-zinc-400">Convert, merge, split PDFs</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('image-converter') }}" class="glass-panel rounded-xl p-6 hover:border-gold-400/50 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                                    <i data-lucide="image" class="w-6 h-6 text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Image Tools</h3>
                                    <p class="text-sm text-zinc-400">Convert, resize, compress</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('invoice-generator') }}" class="glass-panel rounded-xl p-6 hover:border-gold-400/50 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center group-hover:bg-green-500/20 transition-colors">
                                    <i data-lucide="file-text" class="w-6 h-6 text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Invoice Generator</h3>
                                    <p class="text-sm text-zinc-400">Create professional invoices</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="glass-panel rounded-xl p-6">
                    <h2 class="text-xl font-bold text-white mb-4">Recent Activity</h2>
                    <div class="space-y-3">
                        @foreach(Auth::user()->usageLogs()->latest()->take(5)->get() as $log)
                        <div class="flex items-center gap-4 py-3 border-b border-white/5 last:border-b-0">
                            <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-4 h-4 text-gold-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ ucfirst(str_replace('-', ' ', $log->action)) }}</p>
                                <p class="text-xs text-zinc-400">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-xs text-zinc-500">{{ $log->metadata && isset($log->metadata['file_size']) ? number_format($log->metadata['file_size'] / 1024, 1) . ' KB' : 'N/A' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Section -->
        <div id="subscription" class="hidden">
            <div class="p-6">
                <div class="mb-8">
                    <h1 class="text-3xl font-display font-bold text-white mb-2">Subscription Management</h1>
                    <p class="text-zinc-400">Manage your subscription and billing</p>
                </div>

                <!-- Current Plan -->
                <div class="glass-panel rounded-xl p-6 mb-6">
                    <h2 class="text-xl font-bold text-white mb-4">Current Plan</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gold-400">{{ Auth::user()->activeSubscription?->plan_name ?? 'Free Plan' }}</h3>
                            <p class="text-zinc-400 mt-1">
                                @if(Auth::user()->activeSubscription)
                                    @if(Auth::user()->activeSubscription->conversion_limit === -1)
                                        Unlimited conversions
                                    @else
                                        {{ Auth::user()->activeSubscription->conversion_limit }} conversions per month
                                    @endif
                                @else
                                    Limited to 3 conversions per day
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            @if(Auth::user()->activeSubscription && Auth::user()->activeSubscription->ends_at)
                                <p class="text-sm text-zinc-400">Expires</p>
                                <p class="text-lg font-bold text-white">{{ Auth::user()->activeSubscription->ends_at->format('M d, Y') }}</p>
                            @elseif(Auth::user()->activeSubscription)
                                <p class="text-sm text-zinc-400">Status</p>
                                <p class="text-lg font-bold text-green-400">Lifetime</p>
                            @else
                                <p class="text-sm text-zinc-400">Status</p>
                                <p class="text-lg font-bold text-zinc-400">Free</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Plan Comparison -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="glass-panel rounded-xl p-6 border border-white/20">
                        <h3 class="text-xl font-bold text-white mb-2">Free</h3>
                        <p class="text-2xl font-bold text-gold-400 mb-4">$0</p>
                        <ul class="space-y-2 text-sm text-zinc-400 mb-4">
                            <li>• 3 conversions per day</li>
                            <li>• Basic tools only</li>
                            <li>• Community support</li>
                        </ul>
                        <p class="text-xs text-zinc-500">Current plan</p>
                    </div>

                    <div class="glass-panel rounded-xl p-6 border-2 border-gold-400/50 relative">
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-gold-400 text-black px-3 py-1 rounded-full text-xs font-bold">Popular</div>
                        <h3 class="text-xl font-bold text-white mb-2">Pro</h3>
                        <p class="text-2xl font-bold text-gold-400 mb-4">$9.99/mo</p>
                        <ul class="space-y-2 text-sm text-zinc-400 mb-4">
                            <li>• Unlimited conversions</li>
                            <li>• All premium tools</li>
                            <li>• Priority support</li>
                            <li>• API access</li>
                        </ul>
                        <a href="{{ route('pricing') }}" class="btn-gold w-full py-2 rounded-lg text-center block text-sm font-bold">Upgrade</a>
                    </div>

                    <div class="glass-panel rounded-xl p-6 border border-white/20">
                        <h3 class="text-xl font-bold text-white mb-2">Enterprise</h3>
                        <p class="text-2xl font-bold text-gold-400 mb-4">$29.99/mo</p>
                        <ul class="space-y-2 text-sm text-zinc-400 mb-4">
                            <li>• Everything in Pro</li>
                            <li>• Custom integrations</li>
                            <li>• Dedicated support</li>
                            <li>• White-label options</li>
                        </ul>
                        <a href="{{ route('pricing') }}" class="btn-gold w-full py-2 rounded-lg text-center block text-sm font-bold">Contact Sales</a>
                    </div>
                </div>

                <!-- Billing History -->
                @if(Auth::user()->activeSubscription)
                <div class="glass-panel rounded-xl p-6">
                    <h2 class="text-xl font-bold text-white mb-4">Billing History</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-3 border-b border-white/5">
                            <div>
                                <p class="text-sm font-medium text-white">{{ Auth::user()->activeSubscription->plan_name }}</p>
                                <p class="text-xs text-zinc-400">{{ Auth::user()->activeSubscription->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-400">Active</p>
                                <button class="text-xs text-red-400 hover:text-red-300 mt-1">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- History Section -->
        <div id="history" class="hidden">
            <div class="p-6">
                <div class="mb-8">
                    <h1 class="text-3xl font-display font-bold text-white mb-2">Conversion History</h1>
                    <p class="text-zinc-400">View all your past conversions and downloads</p>
                </div>

                <div class="glass-panel rounded-xl overflow-hidden">
                    <div class="p-6 border-b border-white/10">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-white">Recent Conversions</h2>
                            <div class="flex gap-2">
                                <select class="bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                                    <option>All Tools</option>
                                    <option>PDF Tools</option>
                                    <option>Image Tools</option>
                                    <option>Audio Tools</option>
                                </select>
                                <select class="bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                                    <option>Last 30 days</option>
                                    <option>Last 7 days</option>
                                    <option>Today</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block md:hidden space-y-3 px-2">
                        @foreach(Auth::user()->usageLogs()->latest()->take(10)->get() as $log)
                        <div class="glass-panel rounded-xl p-4 w-full overflow-hidden">
                            <div class="flex items-center justify-between mb-2 min-w-0">
                                <span class="font-medium text-white truncate flex-1 mr-2">{{ ucfirst(str_replace('-', ' ', $log->addon_slug)) }}</span>
                                @if($log->success)
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs flex-shrink-0">Success</span>
                                @else
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs flex-shrink-0">Failed</span>
                                @endif
                            </div>
                            <div class="text-sm text-zinc-400 space-y-1">
                                <div class="flex justify-between">
                                    <strong class="flex-shrink-0 mr-2">Action:</strong>
                                    <span class="truncate">{{ ucfirst(str_replace('-', ' ', $log->action)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <strong class="flex-shrink-0 mr-2">File:</strong>
                                    <span class="truncate">{{ $log->input_filename ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <strong class="flex-shrink-0 mr-2">Size:</strong>
                                    <span class="truncate">{{ $log->metadata && isset($log->metadata['file_size']) ? number_format($log->metadata['file_size'] / 1024, 1) . ' KB' : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <strong class="flex-shrink-0 mr-2">Date:</strong>
                                    <span class="truncate">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="history-table w-full min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="text-left">Tool</th>
                                    <th class="text-left">Action</th>
                                    <th class="text-left">File</th>
                                    <th class="text-left">Size</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(Auth::user()->usageLogs()->latest()->paginate(20) as $log)
                                <tr class="hover:bg-white/5">
                                    <td class="font-medium text-white">{{ ucfirst(str_replace('-', ' ', $log->addon_slug)) }}</td>
                                    <td class="text-zinc-300">{{ ucfirst(str_replace('-', ' ', $log->action)) }}</td>
                                    <td class="text-zinc-300">{{ $log->input_filename ?? 'N/A' }}</td>
                                    <td class="text-zinc-300">{{ $log->metadata && isset($log->metadata['file_size']) ? number_format($log->metadata['file_size'] / 1024, 1) . ' KB' : 'N/A' }}</td>
                                    <td class="text-zinc-300">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($log->success)
                                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Success</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(Auth::user()->usageLogs()->count() > 20)
                    <div class="p-6 border-t border-white/10 text-center">
                        <button class="btn-gold px-6 py-2 rounded-lg text-sm font-bold">Load More</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div id="profile" class="hidden">
            <div class="p-6">
                <div class="mb-8">
                    <h1 class="text-3xl font-display font-bold text-white mb-2">Profile Settings</h1>
                    <p class="text-zinc-400">Manage your account information and preferences</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Form -->
                    <div class="lg:col-span-2">
                        <div class="glass-panel rounded-xl p-6">
                            <h2 class="text-xl font-bold text-white mb-6">Account Information</h2>

                            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-input" required>
                                    </div>
                                </div>

                                <hr class="border-white/10">

                                <h3 class="text-lg font-bold text-white mb-4">Change Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" class="form-input">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-input">
                                    </div>

                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-input">
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <button type="submit" class="btn-gold px-6 py-3 rounded-lg font-bold">Save Changes</button>
                                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white/10 border border-white/20 text-zinc-400 rounded-lg hover:bg-white/20 transition-colors">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Sidebar -->
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <div class="glass-panel rounded-xl p-6 text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-2xl mx-auto mb-4">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <h3 class="text-lg font-bold text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-zinc-400">{{ Auth::user()->email }}</p>
                            <p class="text-sm text-zinc-500 mt-2">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                        </div>

                        <!-- Account Stats -->
                        <div class="glass-panel rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Account Stats</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-zinc-400">Total Conversions</span>
                                    <span class="text-white font-bold">{{ Auth::user()->usageLogs()->where('success', true)->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-400">Files Processed</span>
                                    <span class="text-white font-bold">{{ Auth::user()->usageLogs()->get()->sum(function($log) { return $log->metadata['file_size'] ?? 0; }) ? number_format(Auth::user()->usageLogs()->get()->sum(function($log) { return $log->metadata['file_size'] ?? 0; }) / 1024 / 1024, 1) . ' MB' : '0 MB' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-zinc-400">Account Type</span>
                                    <span class="text-gold-400 font-bold">{{ Auth::user()->activeSubscription ? 'Premium' : 'Free' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="glass-panel rounded-xl p-6 border border-red-500/20">
                            <h3 class="text-lg font-bold text-red-400 mb-4">Danger Zone</h3>
                            <p class="text-zinc-400 text-sm mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 px-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors text-sm font-medium">
                                    Delete Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Initialize Icons
    lucide.createIcons();

    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById('dashboardMobileMenuBtn');
    const mobileMenu = document.getElementById('dashboardMobileMenu');
    const mobileBackdrop = document.getElementById('dashboardMobileBackdrop');
    const mobileSidebar = document.querySelector('.mobile-sidebar');

    function toggleMobileMenu() {
        const isOpen = mobileMenu.classList.contains('open');
    
        if (isOpen) {
            // Close menu
            mobileMenu.classList.remove('open');
            mobileSidebar.classList.remove('open');
            mobileMenuBtn.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        } else {
            // Open menu
            mobileMenu.classList.add('open');
            mobileSidebar.classList.add('open');
            mobileMenuBtn.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }

    if (mobileBackdrop) {
        mobileBackdrop.addEventListener('click', toggleMobileMenu);
    }

    // Sidebar navigation functionality
    const sidebarLinks = document.querySelectorAll('.tab-link');
    const contentSections = ['dashboard', 'subscription', 'history', 'profile'];

    function switchSection(sectionName) {
        // Update sidebar active state
        sidebarLinks.forEach(link => {
            link.classList.remove('active');
        });
        document.querySelector(`[data-tab="${sectionName}"]`).classList.add('active');

        // Hide all sections
        contentSections.forEach(section => {
            const element = document.getElementById(section);
            if (element) {
                element.classList.add('hidden');
            }
        });

        // Show selected section
        const activeSection = document.getElementById(sectionName);
        if (activeSection) {
            activeSection.classList.remove('hidden');
        }

        // Close mobile menu if open
        if (mobileMenu.classList.contains('open')) {
            toggleMobileMenu();
        }

        // Update URL hash
        window.location.hash = sectionName;
    }

    // Sidebar link clicks
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const tabName = link.getAttribute('data-tab');
            switchSection(tabName);
        });
    });

    // Close sidebar when clicking a link
    const allSidebarLinks = document.querySelectorAll('.mobile-sidebar a');
    allSidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (mobileMenu.classList.contains('open')) {
                toggleMobileMenu();
            }
        });
    });

    // Handle URL hash on page load
    const hash = window.location.hash.substring(1);
    if (hash && contentSections.includes(hash)) {
        switchSection(hash);
    } else {
        // Default to dashboard
        switchSection('dashboard');
    }
</script>
@endsection
