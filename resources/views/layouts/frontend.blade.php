<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Content::getValue('site_name', config('app.name', 'Laravel')) }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        gold: { 400: '#ffd700', 500: '#ffed4e' },
                        dark: { 800: '#18181b', 900: '#09090b' }
                    }
                }
            }
        }
    </script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <style>
        /* Gold Text Gradient */
        .gold-text-gradient {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glass Header State */
        .header-scrolled {
            background: rgba(9, 9, 11, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        /* Navigation Link & Dropdown Trigger */
        .nav-item { position: relative; height: 100%; display: flex; align-items: center; }

        .nav-link {
            position: relative;
            color: #a1a1aa;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 8px 0;
        }
        .nav-link:hover, .nav-item:hover .nav-link { color: white; }

        /* Animated Underline */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: linear-gradient(90deg, #ffd700, #ffed4e);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 8px rgba(255, 215, 0, 0.5);
        }
        .nav-link:hover::after, .nav-item:hover .nav-link::after { width: 100%; }

        /* Mega Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: -100px; /* Centered relative to parent roughly */
            width: 500px; /* Wider for 2 columns */
            background: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px; /* Reduced radius */
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6);
            pointer-events: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Show Dropdown on Hover */
        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(10px) scale(1);
            pointer-events: auto;
        }

        /* Dropdown Items */
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 4px; /* Reduced radius */
            color: #d4d4d8;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        .dropdown-icon {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px; /* Reduced radius */
            margin-right: 12px;
            color: #ffd700;
            transition: all 0.2s;
        }
        .dropdown-item:hover .dropdown-icon {
            background: rgba(255, 215, 0, 0.1);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.1);
        }

        /* Gold Button Shimmer */
        .btn-gold {
            background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700);
            background-size: 200% auto;
            color: black;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 rgba(255, 215, 0, 0);
            animation: shimmer 3s linear infinite;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(255, 215, 0, 0.4);
        }

        /* Mobile Menu Animations */
        .mobile-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mobile-submenu.open {
            max-height: 600px; /* Increased for more items */
        }
        .chevron-rotate {
            transition: transform 0.3s;
        }
        .chevron-rotate.open {
            transform: rotate(180deg);
        }

        .footer-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .footer-link:hover {
            color: #ffd700;
            padding-left: 5px;
        }
        .footer-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 2px;
            background-color: #ffd700;
            transition: width 0.3s ease;
        }
        .footer-link:hover::before {
            width: 0px;
        }
        .glass-badge {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.2);
            color: #ffd700;
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #ffffff; min-height: 100vh;">
    <!-- HEADER START -->
    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 py-6 border-b border-transparent">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">

                <!-- Logo (Animated) -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group relative z-50 animate-fade-in-down" style="animation-delay: 0ms;">
                    <div class="relative w-9 h-9">
                        <div class="absolute inset-0 bg-gold-400 rounded-md blur opacity-40 group-hover:opacity-70 transition-opacity duration-300 animate-pulse"></div>
                        <div class="relative w-full h-full rounded-md bg-gradient-to-br from-gold-400 to-yellow-600 flex items-center justify-center text-black font-bold text-xl group-hover:rotate-6 transition-transform duration-300 shadow-lg border border-white/20">
                            i
                        </div>
                    </div>
                    <span class="text-3xl font-bold tracking-tight text-white group-hover:tracking-wide transition-all duration-300">i<span class="gold-text-gradient">Convert</span></span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">

                    <!-- Tools Dropdown -->
                    <div class="nav-item group animate-fade-in-down" style="animation-delay: 100ms;">
                        <button class="nav-link text-base font-medium focus:outline-none">
                            Tools
                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-180 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Mega Dropdown Menu -->
                        <div class="dropdown-menu">

                            <!-- Column 1: Converters -->
                            <div class="flex flex-col gap-1">
                                <div class="mb-2 px-3 py-1 text-[10px] font-bold text-zinc-500 uppercase tracking-widest border-b border-white/5 pb-2">Converters</div>

                                <a href="{{ route('pdf-converter') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">PDF Converter</div>
                                    </div>
                                </a>
                                <a href="{{ route('image-converter') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">Image Converter</div>
                                    </div>
                                </a>
                                <a href="{{ route('mp3-converter') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">MP3 Converter</div>
                                    </div>
                                </a>
                            </div>

                            <!-- Column 2: Generators -->
                            <div class="flex flex-col gap-1">
                                <div class="mb-2 px-3 py-1 text-[10px] font-bold text-zinc-500 uppercase tracking-widest border-b border-white/5 pb-2">Generators</div>

                                <a href="{{ route('invoice-generator') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">Invoice Generator</div>
                                    </div>
                                </a>
                                <a href="{{ route('id-card-creator') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">ID Card Creator</div>
                                    </div>
                                </a>
                                <a href="{{ route('letterhead-maker') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">Letterhead Maker</div>
                                    </div>
                                </a>
                                <a href="{{ route('qr-generator') }}" class="dropdown-item">
                                    <div class="dropdown-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-semibold">QR Code Generator</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="nav-item animate-fade-in-down" style="animation-delay: 200ms;">
                        <a href="{{ route('pricing') }}" class="nav-link text-base font-medium">Pricing</a>
                    </div>
                    <div class="nav-item animate-fade-in-down" style="animation-delay: 300ms;">
                        <a href="#" class="nav-link text-base font-medium">Enterprise</a>
                    </div>
                    <div class="nav-item animate-fade-in-down" style="animation-delay: 400ms;">
                        <a href="{{ route('help-center') }}" class="nav-link text-base font-medium">Support</a>
                    </div>
                </nav>

                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center gap-4 animate-fade-in-down" style="animation-delay: 500ms;">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-base font-medium text-zinc-400 hover:text-white transition-colors px-4 py-2">Dashboard</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-base font-medium text-zinc-400 hover:text-white transition-colors px-4 py-2">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-base font-medium text-zinc-400 hover:text-white transition-colors px-4 py-2">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-base font-medium text-zinc-400 hover:text-white transition-colors px-4 py-2">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="btn-gold px-6 py-2.5 rounded-md text-base font-medium flex items-center gap-2 group relative overflow-hidden">
                            <span class="relative z-10">Get Started</span>
                            <svg class="w-4 h-4 relative z-10 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden text-white p-2 focus:outline-none z-50 relative group">
                    <div class="w-6 h-5 flex flex-col justify-between relative">
                        <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400" id="bar1"></span>
                        <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400" id="bar2"></span>
                        <span class="w-full h-0.5 bg-white rounded-full transition-all duration-300 group-hover:bg-gold-400" id="bar3"></span>
                    </div>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="fixed inset-0 z-40 hidden">
        <!-- Backdrop -->
        <div id="mobileBackdrop" class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <!-- Drawer -->
        <div class="absolute right-0 top-0 bottom-0 w-[80%] max-w-[300px] bg-[#0f0f0f] border-l border-white/10 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col" id="mobileDrawer">
            <div class="p-5 pt-20 flex flex-col h-full overflow-y-auto">

                <!-- Mobile Links -->
                <div class="space-y-1">

                    <!-- Tools Submenu Toggle -->
                    <div class="border-b border-white/5">
                        <button onclick="toggleMobileSubmenu()" class="w-full flex items-center justify-between py-3 text-base font-medium text-white hover:text-gold-400 transition-colors">
                            Tools
                            <svg id="mobileChevron" class="w-4 h-4 text-zinc-500 chevron-rotate" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Submenu Content -->
                        <div id="mobileToolsMenu" class="mobile-submenu bg-white/5 rounded-md mb-2">
                            <div class="p-1.5 space-y-0.5">
                                <a href="{{ route('pdf-converter') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> PDF Converter
                                </a>
                                <a href="{{ route('image-converter') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> Image Converter
                                </a>
                                <a href="{{ route('mp3-converter') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> MP3 Converter
                                </a>
                                <a href="{{ route('invoice-generator') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> Invoice Generator
                                </a>
                                 <a href="{{ route('id-card-creator') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> ID Card Creator
                                </a>
                                <a href="{{ route('letterhead-maker') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> Letterhead Maker
                                </a>
                                <a href="{{ route('qr-generator') }}" class="block px-3 py-2 text-xs text-zinc-300 hover:bg-white/5 rounded-md hover:text-white flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-gold-400"></span> QR Code Generator
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pricing') }}" class="block py-3 text-base font-medium text-white hover:text-gold-400 transition-colors border-b border-white/5">Pricing</a>
                    <a href="#" class="block py-3 text-base font-medium text-white hover:text-gold-400 transition-colors border-b border-white/5">Enterprise</a>
                    <a href="{{ route('help-center') }}" class="block py-3 text-base font-medium text-white hover:text-gold-400 transition-colors border-b border-white/5">Support</a>
                </div>

                <!-- Mobile Actions -->
                <div class="mt-auto pt-6 space-y-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full py-2.5 rounded-md text-center text-sm font-semibold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all">Dashboard</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block w-full py-2.5 rounded-md text-center text-sm font-semibold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full py-2.5 rounded-md text-center text-sm font-semibold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-2.5 rounded-md text-center text-sm font-semibold bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="block w-full py-2.5 rounded-md text-center text-sm font-semibold btn-gold shadow-[0_2px_10px_rgba(255,215,0,0.15)]">
                            Sign Up Free
                        </a>
                    @endauth
                    <p class="text-center text-[10px] text-zinc-600 mt-3">© 2024 iConvert Inc.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main style="padding-top: 80px;">
        @yield('content')
    </main>

    <!-- FOOTER START -->
    <footer id="mainFooter" class="relative bg-[#050505] border-t border-white/5 pt-16 pb-8 overflow-hidden">

        <!-- Animation Canvas -->
        <canvas id="footerCanvas" class="absolute inset-0 z-0 opacity-40 pointer-events-none"></canvas>

        <!-- Ambient Background Glows -->
        <div class="absolute top-0 left-1/4 w-64 h-64 bg-gold-400/5 blur-[120px] rounded-full pointer-events-none z-0"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-purple-900/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">

                <!-- Brand Column (Span 4) -->
                <div class="lg:col-span-4 space-y-6">
                    <a href="{{ route('home') }}" class="inline-block">
                        <h2 class="text-3xl font-bold tracking-tight text-white">i<span class="gold-text-gradient">Convert</span></h2>
                    </a>
                    <p class="text-zinc-500 text-sm leading-relaxed max-w-sm">
                        The ultimate suite for document management and creative generation. Start with our powerful PDF tools and expand with premium add-ons tailored for your business needs.
                    </p>

                    <!-- Social Links -->
                    <div class="flex gap-4 pt-2">
                        <!-- Twitter/X -->
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-zinc-400 hover:text-white hover:border-gold-400/50 hover:bg-gold-400/10 transition-all group">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <!-- Instagram -->
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-zinc-400 hover:text-white hover:border-gold-400/50 hover:bg-gold-400/10 transition-all group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <!-- LinkedIn -->
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-zinc-400 hover:text-white hover:border-gold-400/50 hover:bg-gold-400/10 transition-all group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <!-- YouTube -->
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-zinc-400 hover:text-white hover:border-gold-400/50 hover:bg-gold-400/10 transition-all group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Converters Column (Span 3) -->
                <div class="lg:col-span-3 lg:pl-8">
                    <h3 class="text-white font-semibold mb-6 tracking-wide">Converters</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('pdf-converter') }}" class="footer-link text-zinc-400 block">PDF Converter</a></li>
                        <li><a href="{{ route('image-converter') }}" class="footer-link text-zinc-400 block">Image Converter</a></li>
                        <li><a href="{{ route('mp3-converter') }}" class="footer-link text-zinc-400 block">MP3 Converter</a></li>
                    </ul>
                </div>

                <!-- Generators Column (Span 3) -->
                <div class="lg:col-span-3">
                    <h3 class="text-white font-semibold mb-6 tracking-wide">Generators</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('invoice-generator') }}" class="footer-link text-zinc-400 block">Invoice Generator</a></li>
                        <li><a href="{{ route('id-card-creator') }}" class="footer-link text-zinc-400 block">ID Card Creator</a></li>
                        <li><a href="{{ route('letterhead-maker') }}" class="footer-link text-zinc-400 block">Letterhead Maker</a></li>
                        <li><a href="{{ route('qr-generator') }}" class="footer-link text-zinc-400 block">QR Code Generator</a></li>
                    </ul>
                </div>

                <!-- Support Column (Span 2) -->
                <div class="lg:col-span-2">
                    <h3 class="text-white font-semibold mb-6 tracking-wide">Support</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('about-us') }}" class="footer-link text-zinc-400 block">About Us</a></li>
                        <li><a href="{{ route('help-center') }}" class="footer-link text-zinc-400 block">Help Center</a></li>
                        <li><a href="{{ route('pricing') }}" class="footer-link text-zinc-400 block">Pricing</a></li>
                        <li><a href="{{ route('contact-us') }}" class="footer-link text-zinc-400 block">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-white/10 my-8"></div>

            <!-- Bottom Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-zinc-600 text-xs">
                    © 2024 iConvert Inc. All rights reserved.
                </p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('privacy-policy') }}" class="text-xs text-zinc-500 hover:text-gold-400 transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms-of-service') }}" class="text-xs text-zinc-500 hover:text-gold-400 transition-colors">Terms of Service</a>
                    <a href="{{ route('cookies') }}" class="text-xs text-zinc-500 hover:text-gold-400 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // --- Sticky Header Logic ---
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('header-scrolled');
                navbar.classList.remove('py-6');
            } else {
                navbar.classList.remove('header-scrolled');
                navbar.classList.add('py-6');
            }
        });

        // --- Mobile Menu Logic ---
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const backdrop = document.getElementById('mobileBackdrop');
        const drawer = document.getElementById('mobileDrawer');
        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');

        let isMenuOpen = false;

        function toggleMenu() {
            isMenuOpen = !isMenuOpen;

            if (isMenuOpen) {
                menu.classList.remove('hidden');
                // Burger Animation
                bar1.style.transform = 'rotate(45deg) translate(5px, 6px)';
                bar2.style.opacity = '0';
                bar3.style.transform = 'rotate(-45deg) translate(5px, -6px)';

                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    drawer.classList.remove('translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                backdrop.classList.add('opacity-0');
                drawer.classList.add('translate-x-full');
                // Burger Reset
                bar1.style.transform = 'none';
                bar2.style.opacity = '1';
                bar3.style.transform = 'none';

                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }

        btn.addEventListener('click', toggleMenu);
        backdrop.addEventListener('click', toggleMenu);

        // --- Mobile Submenu Logic ---
        function toggleMobileSubmenu() {
            const submenu = document.getElementById('mobileToolsMenu');
            const chevron = document.getElementById('mobileChevron');
            submenu.classList.toggle('open');
            chevron.classList.toggle('open');
        }
    </script>

    <script>
        (function() {
            const canvas = document.getElementById('footerCanvas');
            const footer = document.getElementById('mainFooter');
            const ctx = canvas.getContext('2d');
            let w, h, particles = [];

            // Resize canvas to fit footer
            const resize = () => {
                w = canvas.width = footer.offsetWidth;
                h = canvas.height = footer.offsetHeight;
            };

            window.addEventListener('resize', resize);
            resize();

            class Particle {
                constructor() {
                    this.reset();
                }

                reset() {
                    this.x = Math.random() * w;
                    this.y = Math.random() * h;
                    this.vx = (Math.random() - 0.5) * 0.5;
                    this.vy = (Math.random() - 0.5) * 0.5;
                    this.size = Math.random() * 1.5;
                    this.opacity = Math.random() * 0.5 + 0.1;
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;

                    if (this.x < 0 || this.x > w) this.vx *= -1;
                    if (this.y < 0 || this.y > h) this.vy *= -1;
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 215, 0, ${this.opacity})`;
                    ctx.fill();
                }
            }

            function init() {
                particles = [];
                // Create fewer particles for footer area
                for(let i = 0; i < 30; i++) {
                    particles.push(new Particle());
                }
            }

            function animate() {
                ctx.clearRect(0, 0, w, h);

                particles.forEach(p => {
                    p.update();
                    p.draw();
                });

                // Connect nearby particles
                particles.forEach((a, i) => {
                    particles.slice(i + 1).forEach(b => {
                        const dx = a.x - b.x;
                        const dy = a.y - b.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 100) {
                            ctx.beginPath();
                            ctx.strokeStyle = `rgba(255, 215, 0, ${0.1 - dist/1000})`;
                            ctx.lineWidth = 0.5;
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.stroke();
                        }
                    });
                });

                requestAnimationFrame(animate);
            }

            init();
            animate();
        })();
    </script>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>