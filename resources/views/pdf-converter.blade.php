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
                            card: '#0c0c0c',
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

        /* PDF Converter specific overrides */
        .pdf-converter-page {
            position: relative;
            min-height: calc(100vh - 80px);
        }
    </style>
@endsection

@section('content')
@if(isAddonEnabled('pdf-converter'))
<div class="pdf-converter-page">
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
                    Every tool you need to <br>
                    <span class="text-gold-gradient">Master Your PDFs</span>
                </h1>
                <p class="text-zinc-400 max-w-2xl mx-auto text-lg font-light">
                    Merge, split, compress, convert, rotate, unlock and watermark PDFs with just a few clicks.
                </p>
            </div>

            <!-- 1. ORGANIZE & OPTIMIZE -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Organize & Optimize</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Merge -->
                    <a href="{{ route('pdf-editor', 'pdf-merge') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center mb-4 group-hover:bg-red-500/20 transition-colors">
                            <i data-lucide="files" class="w-6 h-6 text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Merge PDF</h3>
                        <p class="text-xs text-zinc-400">Combine PDFs in the order you want with the easiest PDF merger.</p>
                    </a>

                    <!-- Split -->
                    <a href="{{ route('pdf-editor', 'pdf-split') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center mb-4 group-hover:bg-red-500/20 transition-colors">
                            <i data-lucide="scissors" class="w-6 h-6 text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Split PDF</h3>
                        <p class="text-xs text-zinc-400">Separate one page or a whole set for easy conversion into independent files.</p>
                    </a>

                    <!-- Compress -->
                    <a href="{{ route('pdf-editor', 'pdf-compress') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center mb-4 group-hover:bg-green-500/20 transition-colors">
                            <i data-lucide="minimize-2" class="w-6 h-6 text-green-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Compress PDF</h3>
                        <p class="text-xs text-zinc-400">Reduce file size while optimizing for maximal PDF quality.</p>
                    </a>

                    <!-- Repair -->
                    <a href="{{ route('pdf-editor', 'pdf-repair') }}" class="glass-panel rounded-xl p-6 group cursor-pointer block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center mb-4 group-hover:bg-orange-500/20 transition-colors">
                            <i data-lucide="wrench" class="w-6 h-6 text-orange-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Repair PDF</h3>
                        <p class="text-xs text-zinc-400">Recover data from a corrupted or damaged PDF document.</p>
                    </a>
                </div>
            </div>

            <!-- 2. CONVERT TO PDF -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Convert To PDF</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                    <!-- JPG to PDF -->
                    <a href="{{ route('pdf-editor', 'images-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="image" class="w-5 h-5 text-yellow-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">JPG to PDF</h3>
                    </a>

                    <!-- Word to PDF -->
                    <a href="{{ route('pdf-editor', 'word-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="file-text" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Word to PDF</h3>
                    </a>

                    <!-- PowerPoint to PDF -->
                    <a href="{{ route('pdf-editor', 'ppt-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="presentation" class="w-5 h-5 text-orange-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PPT to PDF</h3>
                    </a>

                    <!-- Excel to PDF -->
                    <a href="{{ route('pdf-editor', 'excel-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="table" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excel to PDF</h3>
                    </a>

                    <!-- HTML to PDF -->
                    <a href="{{ route('pdf-editor', 'html-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-gray-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="code" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">HTML to PDF</h3>
                    </a>

                    <!-- Text to PDF -->
                    <a href="{{ route('pdf-editor', 'text-to-pdf') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3">
                            <i data-lucide="align-left" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Text to PDF</h3>
                    </a>
                </div>
            </div>

            <!-- 3. CONVERT FROM PDF -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Convert From PDF</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                    <!-- PDF to JPG -->
                    <a href="{{ route('pdf-editor', 'pdf-to-images') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="image" class="w-5 h-5 text-yellow-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to JPG</h3>
                    </a>

                    <!-- PDF to Word -->
                    <a href="{{ route('pdf-editor', 'pdf-to-word') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="file-edit" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to Word</h3>
                    </a>

                    <!-- PDF to PPT -->
                    <a href="{{ route('pdf-editor', 'pdf-to-ppt') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="presentation" class="w-5 h-5 text-orange-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to PPT</h3>
                    </a>

                    <!-- PDF to Excel -->
                    <a href="{{ route('pdf-editor', 'pdf-to-excel') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="table" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to Excel</h3>
                    </a>

                    <!-- PDF to HTML -->
                    <a href="{{ route('pdf-editor', 'pdf-to-html') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-gray-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="code" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to HTML</h3>
                    </a>

                    <!-- PDF to Text -->
                    <a href="{{ route('pdf-editor', 'pdf-to-text') }}" class="glass-panel rounded-xl p-5 group cursor-pointer text-center flex flex-col items-center block" data-tilt>
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3">
                            <i data-lucide="align-left" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">PDF to Text</h3>
                    </a>
                </div>
            </div>

            <!-- 4. EDIT & SECURITY -->
            <div class="mb-16">
                <div class="section-divider" data-aos="fade-right">
                    <h2 class="text-[#ffd700] font-bold tracking-widest text-sm uppercase">Edit & Security</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Rotate -->
                    <a href="{{ route('pdf-editor', 'pdf-rotate') }}" class="glass-panel rounded-xl p-5 group cursor-pointer flex flex-col items-center text-center block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="rotate-cw" class="w-6 h-6 text-purple-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1">Rotate PDF</h3>
                        <p class="text-[10px] text-zinc-500">Rotate pages individually</p>
                    </a>

                    <!-- Add Watermark -->
                    <a href="{{ route('pdf-editor', 'pdf-watermark') }}" class="glass-panel rounded-xl p-5 group cursor-pointer flex flex-col items-center text-center block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="stamp" class="w-6 h-6 text-purple-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1">Add Watermark</h3>
                        <p class="text-[10px] text-zinc-500">Stamp text or images</p>
                    </a>

                    <!-- Edit PDF -->
                    <a href="{{ route('pdf-editor', 'pdf-editor') }}" class="glass-panel rounded-xl p-5 group cursor-pointer flex flex-col items-center text-center border-[#ffd700]/20 block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-[#ffd700]/10 flex items-center justify-center mb-3">
                            <i data-lucide="pen-tool" class="w-6 h-6 text-[#ffd700]"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1">Edit PDF</h3>
                        <p class="text-[10px] text-zinc-500">Add text, shapes & images</p>
                    </a>

                    <!-- Protect -->
                    <a href="{{ route('pdf-editor', 'pdf-protect') }}" class="glass-panel rounded-xl p-5 group cursor-pointer flex flex-col items-center text-center block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="lock" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1">Protect PDF</h3>
                        <p class="text-[10px] text-zinc-500">Encrypt with password</p>
                    </a>

                    <!-- Unlock -->
                    <a href="{{ route('pdf-editor', 'pdf-unlock') }}" class="glass-panel rounded-xl p-5 group cursor-pointer flex flex-col items-center text-center block" data-tilt>
                        <div class="w-12 h-12 rounded-lg bg-pink-500/10 flex items-center justify-center mb-3">
                            <i data-lucide="unlock" class="w-6 h-6 text-pink-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1">Unlock PDF</h3>
                        <p class="text-[10px] text-zinc-500">Remove security</p>
                    </a>
                </div>
            </div>

        </div>
    </main>
</div>
@else
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-white mb-4">Addon Not Available</h1>
        <p class="text-zinc-400">This feature is currently disabled. Please contact administrator.</p>
    </div>
</div>
@endif
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