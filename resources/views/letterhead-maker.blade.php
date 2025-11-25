@extends('layouts.frontend')

@section('styles')
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                        royal: {
                            950: '#020202',
                            900: '#050505',
                            800: '#0a0a0a',
                        },
                        gold: {
                            400: '#ffed4e',
                            500: '#ffd700',
                            600: '#d4b200',
                        }
                    },
                    animation: {
                        'blob': 'blob 20s infinite',
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
        /* --- Glass Panel --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-panel:hover {
            border-color: rgba(255, 215, 0, 0.3);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.1);
            background: rgba(255, 255, 255, 0.04);
            transform: translateY(-2px);
        }

        .text-gold-gradient {
            background: linear-gradient(135deg, #fff 20%, #ffd700 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.2;
            z-index: -1;
        }

        /* --- Section Separator --- */
        .section-divider {
            display: flex;
            align-items: center;
            margin: 2rem 0 1.5rem 0;
        }
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, rgba(255,255,255,0.08), transparent);
            margin-left: 1rem;
        }

        /* Letterhead Maker specific overrides */
        .letterhead-maker-page {
            position: relative;
            min-height: calc(100vh - 80px);
        }
    </style>
@endsection

@section('content')
<div class="letterhead-maker-page">
    <!-- Messages Container -->
    <div id="messages-container"></div>

    <!-- Interactive Background -->
    <div class="fixed inset-0 z-0 pointer-events-none" style="top: 80px;">
        <canvas id="meshCanvas" class="opacity-20"></canvas>
        <div class="glow-orb w-[400px] h-[400px] bg-purple-900/15 top-20 left-0 animate-blob"></div>
        <div class="glow-orb w-[400px] h-[400px] bg-[#ffd700]/8 bottom-0 right-0 animate-blob" style="animation-delay: 2s"></div>
    </div>

    <!-- Main Content -->
    <main class="relative z-10 pt-16 pb-20 px-6">
        <div class="max-w-[95%] mx-auto">

            <!-- Header -->
            <div class="text-center mb-16" data-aos="fade-down">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-white mb-4">
                    Professional Letterhead <br>
                    <span class="text-gold-gradient">Maker Suite</span>
                </h1>
                <p class="text-zinc-400 max-w-2xl mx-auto text-lg font-light">
                    Create elegant letterheads with custom branding, logos, and professional layouts for your business correspondence.
                </p>
            </div>

            <!-- 1. LETTERHEAD STYLES -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Letterhead Styles</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Classic -->
                    <a href="{{ route('letterhead-editor', 'classic') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center mb-4 group-hover:bg-blue-500/20 transition-colors">
                            <i data-lucide="file-text" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Classic</h3>
                        <p class="text-xs text-zinc-400">Traditional letterhead with clean, professional design.</p>
                    </a>

                    <!-- Modern -->
                    <a href="{{ route('letterhead-editor', 'modern') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center mb-4 group-hover:bg-green-500/20 transition-colors">
                            <i data-lucide="sparkles" class="w-6 h-6 text-green-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Modern</h3>
                        <p class="text-xs text-zinc-400">Contemporary designs with bold typography.</p>
                    </a>

                    <!-- Elegant -->
                    <a href="{{ route('letterhead-editor', 'elegant') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors">
                            <i data-lucide="crown" class="w-6 h-6 text-purple-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Elegant</h3>
                        <p class="text-xs text-zinc-400">Sophisticated layouts with decorative elements.</p>
                    </a>

                    <!-- Corporate -->
                    <a href="{{ route('letterhead-editor', 'corporate') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center mb-4 group-hover:bg-orange-500/20 transition-colors">
                            <i data-lucide="building-2" class="w-6 h-6 text-orange-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Corporate</h3>
                        <p class="text-xs text-zinc-400">Business-focused designs with company branding.</p>
                    </a>
                </div>
            </div>

            <!-- 2. BUSINESS TYPES -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Business Types</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Law Firm -->
                    <a href="{{ route('letterhead-editor', 'law') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center mb-4 group-hover:bg-red-500/20 transition-colors">
                            <i data-lucide="scale" class="w-6 h-6 text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Law Firm</h3>
                        <p class="text-xs text-zinc-400">Professional legal letterheads with formal styling.</p>
                    </a>

                    <!-- Medical -->
                    <a href="{{ route('letterhead-editor', 'medical') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-cyan-500/10 flex items-center justify-center mb-4 group-hover:bg-cyan-500/20 transition-colors">
                            <i data-lucide="stethoscope" class="w-6 h-6 text-cyan-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Medical</h3>
                        <p class="text-xs text-zinc-400">Healthcare letterheads with trust and care focus.</p>
                    </a>

                    <!-- Real Estate -->
                    <a href="{{ route('letterhead-editor', 'real-estate') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-yellow-500/10 flex items-center justify-center mb-4 group-hover:bg-yellow-500/20 transition-colors">
                            <i data-lucide="home" class="w-6 h-6 text-yellow-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Real Estate</h3>
                        <p class="text-xs text-zinc-400">Property and real estate professional letterheads.</p>
                    </a>

                    <!-- Consulting -->
                    <a href="{{ route('letterhead-editor', 'consulting') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-pink-500/10 flex items-center justify-center mb-4 group-hover:bg-pink-500/20 transition-colors">
                            <i data-lucide="users" class="w-6 h-6 text-pink-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Consulting</h3>
                        <p class="text-xs text-zinc-400">Business consulting and advisory letterheads.</p>
                    </a>
                </div>
            </div>


        </div>
    </main>
</div>
@endsection

@section('scripts')
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <script>
        // Init AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        // Init Icons
        lucide.createIcons();

        // Init Tilt
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
            max: 15,
            speed: 400,
            glare: true,
            "max-glare": 0.1
        });

        // --- Background Animation ---
        const canvas = document.getElementById('meshCanvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let points = [];
        const target = { x: 0, y: 0 };

        function resize() {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
            initPoints();
        }

        function initPoints() {
            points = [];
            const count = Math.floor(width * height / 15000);
            for (let i = 0; i < count; i++) {
                points.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.3,
                    vy: (Math.random() - 0.5) * 0.3,
                    size: Math.random() * 2
                });
            }
        }

        function draw() {
            ctx.clearRect(0, 0, width, height);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
            ctx.lineWidth = 0.8;

            for (let i = 0; i < points.length; i++) {
                let p = points[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255, 215, 0, 0.5)';
                ctx.fill();

                for (let j = i + 1; j < points.length; j++) {
                    const p2 = points[j];
                    const d2 = (p.x - p2.x)**2 + (p.y - p2.y)**2;
                    if (d2 < 9000) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(draw);
        }

        window.addEventListener('resize', resize);
        resize();
        draw();
    </script>
@endsection