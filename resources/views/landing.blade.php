@extends('layouts.frontend')

@section('styles')
<style>
    /* --- Refined Glass Effect --- */
    .glass-card {
        background: rgba(24, 24, 27, 0.7);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transition: all 0.4s ease;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.4s ease;
    }

    .glass-panel:hover {
        border-color: rgba(255, 215, 0, 0.5);
        box-shadow: 0 0 40px rgba(255, 215, 0, 0.2);
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-5px);
    }

    /* --- Text Gradients --- */
    .text-gold-gradient {
        background: linear-gradient(135deg, #fff 20%, #ffd700 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-size: 200% auto;
        animation: shine 4s linear infinite;
    }

    @keyframes shine {
        to { background-position: 200% center; }
    }

    /* --- Custom Scrollbar --- */
    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: #020202; }
    ::-webkit-scrollbar-thumb { background: #333; border-radius: 5px; }
    ::-webkit-scrollbar-thumb:hover { background: #ffd700; }

    /* --- Background Glows --- */
    .glow-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.3;
        z-index: -1;
    }

    /* --- Homepage specific styles --- */
    body {
        overflow-x: hidden;
    }

    .homepage-hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .homepage-content {
        max-width: 95%;
        width: 100%;
        margin: 0 auto;
        padding: 2rem;
        position: relative;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<!-- Interactive Background -->
<div class="fixed inset-0 z-0 pointer-events-none">
    <canvas id="meshCanvas" class="opacity-50"></canvas>
    <div class="glow-orb w-[600px] h-[600px] bg-purple-900/20 top-0 left-0 animate-blob"></div>
    <div class="glow-orb w-[600px] h-[600px] bg-[#ffd700]/10 bottom-0 right-0 animate-blob" style="animation-delay: 2s"></div>
</div>

    <!-- 1. HERO SECTION -->
    <section class="homepage-hero">
        <div class="homepage-content grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- Left: Typography -->
            <div class="text-center lg:text-left space-y-6 md:space-y-8">
                <div data-aos="fade-right" class="inline-flex items-center gap-3 px-4 py-2 rounded-full border border-[#ffd700]/30 bg-[#ffd700]/5 backdrop-blur-md">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ffd700] opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-[#ffd700]"></span>
                    </span>
                    <span class="text-[#ffd700] text-xs md:text-sm font-bold tracking-widest uppercase">System Operational</span>
                </div>

                <h1 data-aos="fade-up" data-aos-delay="100" class="text-5xl sm:text-6xl md:text-8xl font-display font-bold leading-none tracking-tight text-white">
                    One Suite. <br>
                    <span class="text-gold-gradient">Infinite Tools.</span>
                </h1>

                <p data-aos="fade-up" data-aos-delay="200" class="text-base md:text-xl text-zinc-400 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-light">
                    The complete digital ecosystem. From enterprise PDF processing to instant media conversion. Fast, Secure, and Cloud-Based.
                </p>

                <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row items-center gap-4 md:gap-6 justify-center lg:justify-start pt-4 md:pt-6">
                    <button onclick="document.getElementById('tools-grid').scrollIntoView()" class="w-full sm:w-auto group relative px-8 py-4 bg-[#ffd700] rounded-md overflow-hidden shadow-[0_0_20px_rgba(255,215,0,0.3)] hover:shadow-[0_0_40px_rgba(255,215,0,0.6)] transition-all">
                        <div class="absolute inset-0 w-full h-full bg-white/30 group-hover:translate-x-full transition-transform duration-500 ease-in-out -translate-x-full skew-x-12"></div>
                        <span class="relative text-black font-bold text-lg flex items-center justify-center gap-2">
                            Start Creating <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </span>
                    </button>
                    <button onclick="window.location.href='{{ route('pricing') }}'" class="w-full sm:w-auto px-8 py-4 rounded-md border border-white/10 hover:border-white text-white text-lg transition-all backdrop-blur-sm hover:bg-white/5">
                        View Pricing
                    </button>
                </div>
            </div>

            <!-- Right: Floating Visuals -->
            <div class="relative h-[400px] md:h-[600px] w-full hidden lg:flex items-center justify-center" data-aos="zoom-in" data-aos-duration="1000">
                <!-- Center Core -->
                <div class="absolute z-20 w-72 h-80 md:w-80 md:h-96 glass-panel rounded-3xl flex flex-col items-center justify-center p-8 border-t-2 border-[#ffd700] animate-float shadow-2xl">
                    <div class="absolute inset-0 w-full h-full bg-white/5 rounded-3xl"></div>
                    <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-[#ffd700] to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                        <i data-lucide="layers" class="w-10 h-10 md:w-12 md:h-12 text-black"></i>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-display font-bold text-white mb-2">iConvert</h3>
                    <p class="text-center text-zinc-400 text-sm">Processing Engine</p>
                </div>

                <!-- Satellite 1: QR -->
                <div class="absolute top-10 right-0 md:right-10 p-4 md:p-5 glass-panel rounded-2xl flex items-center gap-3 md:gap-4 animate-float-delayed z-10 border-l-4 border-blue-500 bg-black/80">
                    <i data-lucide="qr-code" class="w-6 h-6 md:w-8 md:h-8 text-blue-400"></i>
                    <div>
                        <div class="text-sm font-bold text-white">QR Creator</div>
                        <div class="text-[10px] md:text-xs text-zinc-500">Dynamic Codes</div>
                    </div>
                </div>

                <!-- Satellite 2: Image -->
                <div class="absolute bottom-10 left-0 md:bottom-20 md:left-0 p-4 md:p-5 glass-panel rounded-2xl flex items-center gap-3 md:gap-4 animate-float z-30 border-l-4 border-green-500 bg-black/80" style="animation-duration: 7s;">
                    <i data-lucide="image" class="w-6 h-6 md:w-8 md:h-8 text-green-400"></i>
                    <div>
                        <div class="text-sm font-bold text-white">Img Convert</div>
                        <div class="text-[10px] md:text-xs text-zinc-500">Lossless Quality</div>
                    </div>
                </div>

                <!-- Satellite 3: ID Card -->
                <div class="absolute top-20 left-4 md:left-10 p-3 md:p-4 glass-panel rounded-2xl flex items-center gap-3 animate-float-reverse z-0 border-l-4 border-cyan-500 bg-black/60 blur-[1px] hover:blur-0 transition-all duration-300">
                    <i data-lucide="id-card" class="w-5 h-5 md:w-6 md:h-6 text-cyan-400"></i>
                    <div>
                        <div class="text-xs md:text-sm font-bold text-white">ID Maker</div>
                    </div>
                </div>

                <!-- Satellite 4: Invoice -->
                <div class="absolute bottom-32 right-8 md:right-20 p-3 md:p-4 glass-panel rounded-2xl flex items-center gap-3 animate-float-delayed z-30 border-l-4 border-yellow-500 bg-black/80" style="animation-delay: 1.5s;">
                    <i data-lucide="receipt" class="w-5 h-5 md:w-6 md:h-6 text-yellow-400"></i>
                    <div>
                        <div class="text-xs md:text-sm font-bold text-white">Invoice Gen</div>
                    </div>
                </div>

                <!-- Rotating Ring -->
                <div class="absolute w-[400px] h-[400px] md:w-[500px] md:h-[500px] border border-dashed border-white/10 rounded-full animate-spin-slow z-0"></div>
            </div>
        </div>
    </section>

    <!-- 2. LIVE STATS -->
    <section class="relative z-10 py-10 md:py-12 border-y border-white/5 bg-white/[0.02]">
        <div class="max-w-[95%] mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" data-aos-delay="0">
                <h3 class="text-3xl md:text-5xl font-display font-bold text-white mb-2">1M+</h3>
                <p class="text-zinc-500 text-xs md:text-sm uppercase tracking-widest">Files Processed</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-3xl md:text-5xl font-display font-bold text-white mb-2">99.9%</h3>
                <p class="text-zinc-500 text-xs md:text-sm uppercase tracking-widest">Uptime SLA</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-3xl md:text-5xl font-display font-bold text-white mb-2">0s</h3>
                <p class="text-zinc-500 text-xs md:text-sm uppercase tracking-widest">Data Retention</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-3xl md:text-5xl font-display font-bold text-white mb-2">24/7</h3>
                <p class="text-zinc-500 text-xs md:text-sm uppercase tracking-widest">Support</p>
            </div>
        </div>
    </section>

    <!-- 3. INTELLIGENT PROCESSING -->
    <section class="relative z-10 py-20 px-6">
        <div class="max-w-[95%] mx-auto" data-aos="fade-up">
            <div class="glass-panel rounded-2xl p-8 md:p-12 border border-white/10 relative overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">

                    <!-- Left: Process Simulation Visual -->
                    <div class="relative h-64 w-full bg-black/40 rounded-xl border border-white/10 flex flex-col items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>

                        <div class="relative z-10 flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center text-zinc-400">
                                    <i data-lucide="file" class="w-6 h-6"></i>
                                </div>
                                <span class="text-[10px] text-zinc-500 mt-2">DOCX</span>
                            </div>

                            <div class="flex flex-col items-center gap-1">
                                <div class="w-24 h-1 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#ffd700] animate-process w-1/2"></div>
                                </div>
                                <span class="text-[8px] text-[#ffd700] uppercase tracking-widest font-mono">Processing</span>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-lg bg-[#ffd700]/10 border border-[#ffd700]/20 flex items-center justify-center text-[#ffd700] shadow-[0_0_15px_rgba(255,215,0,0.2)]">
                                    <i data-lucide="file-check" class="w-6 h-6"></i>
                                </div>
                                <span class="text-[10px] text-[#ffd700] mt-2">PDF</span>
                            </div>
                        </div>

                        <div class="absolute bottom-4 text-center w-full">
                            <p class="text-xs text-zinc-600 font-mono">Status: <span class="text-green-500">Conversion Complete</span></p>
                        </div>
                    </div>

                    <!-- Right: Text Content -->
                    <div class="space-y-6 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-mono tracking-widest uppercase">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Intelligent Processing
                        </div>
                        <h2 class="text-3xl md:text-4xl font-display font-bold text-white leading-tight">
                            Seamless <span class="text-gold-gradient">File Transformation</span>
                        </h2>
                        <p class="text-zinc-400 leading-relaxed text-sm md:text-base">
                            Our advanced algorithms handle complex file structures with precision. Experience automated format detection and error-free output generation.
                        </p>

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10">
                                <i data-lucide="zap" class="w-5 h-5 text-[#ffd700]"></i>
                                <div class="text-left">
                                    <div class="text-white font-bold text-sm">Instant</div>
                                    <div class="text-[10px] text-zinc-500">Zero Latency</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10">
                                <i data-lucide="shield" class="w-5 h-5 text-[#ffd700]"></i>
                                <div class="text-left">
                                    <div class="text-white font-bold text-sm">Private</div>
                                    <div class="text-[10px] text-zinc-500">Auto-Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. WHY CHOOSE US -->
    <section class="relative z-10 py-16 md:py-24 bg-black/50">
        <div class="max-w-[95%] mx-auto">
            <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-display font-bold text-white mb-4 md:mb-6">Why Professionals Trust Us</h2>
                <p class="text-zinc-400 max-w-2xl mx-auto">Built for speed, privacy, and reliability.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div data-aos="fade-up" data-aos-delay="0" class="glass-panel p-8 md:p-10 rounded-2xl text-center group">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-4">100% Secure</h3>
                    <p class="text-sm md:text-base text-zinc-400 leading-relaxed">
                        We use 256-bit SSL encryption. Your files are automatically deleted from our servers after 1 hour.
                    </p>
                </div>

                <div data-aos="fade-up" data-aos-delay="100" class="glass-panel p-8 md:p-10 rounded-2xl text-center group border-[#ffd700]/30">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#ffd700] to-orange-500 rounded-xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i data-lucide="zap" class="w-8 h-8 text-black"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-4">Lightning Fast</h3>
                    <p class="text-sm md:text-base text-zinc-400 leading-relaxed">
                        Our distributed cloud architecture ensures your files are processed in seconds, regardless of size.
                    </p>
                </div>

                <div data-aos="fade-up" data-aos-delay="200" class="glass-panel p-8 md:p-10 rounded-2xl text-center group">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                        <i data-lucide="cloud" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-4">Cloud Based</h3>
                    <p class="text-sm md:text-base text-zinc-400 leading-relaxed">
                        No software installation required. Access iConvert from any device, anywhere in the world.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. TOOLS GRID -->
    <section id="tools-grid" class="relative z-10 py-16 md:py-24">
        <div class="max-w-[95%] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 md:mb-16 px-4" data-aos="fade-right">
                <div>
                    <span class="text-[#ffd700] font-bold tracking-widest text-xs uppercase mb-2 block">System Modules</span>
                    <h2 class="text-3xl md:text-5xl font-display font-bold text-white">Expansion Tools</h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <p class="text-zinc-400 text-right">6 Premium Add-ons Available</p>
                </div>
            </div>

            <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Javascript will inject cards here -->
            </div>
        </div>
    </section>

    <!-- 6. HOW IT WORKS -->
    <section class="relative z-10 py-16 md:py-24 bg-white/[0.02]">
        <div class="max-w-[95%] mx-auto">
            <h2 class="text-3xl md:text-5xl font-display font-bold text-center text-white mb-16" data-aos="fade-up">Effortless Workflow</h2>

            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-[#ffd700]/30 to-transparent"></div>

                <div class="text-center relative z-10" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto bg-[#0a0a0a] border border-[#ffd700]/30 rounded-full flex items-center justify-center text-3xl md:text-4xl font-bold text-[#ffd700] mb-6 shadow-[0_0_20px_rgba(255,215,0,0.1)]">1</div>
                    <h3 class="text-xl font-bold text-white mb-3">Choose Tool</h3>
                    <p class="text-zinc-400 text-sm">Select from our wide range of conversion and generation utilities.</p>
                </div>

                <div class="text-center relative z-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto bg-[#0a0a0a] border border-[#ffd700]/30 rounded-full flex items-center justify-center text-3xl md:text-4xl font-bold text-[#ffd700] mb-6 shadow-[0_0_20px_rgba(255,215,0,0.1)]">2</div>
                    <h3 class="text-xl font-bold text-white mb-3">Upload File</h3>
                    <p class="text-zinc-400 text-sm">Drag and drop your documents. We support all major formats.</p>
                </div>

                <div class="text-center relative z-10" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto bg-[#0a0a0a] border border-[#ffd700]/30 rounded-full flex items-center justify-center text-3xl md:text-4xl font-bold text-[#ffd700] mb-6 shadow-[0_0_20px_rgba(255,215,0,0.1)]">3</div>
                    <h3 class="text-xl font-bold text-white mb-3">Get Results</h3>
                    <p class="text-zinc-400 text-sm">Download your processed files instantly. High quality guaranteed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. FAQ SECTION -->
    <section class="relative z-10 py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-center text-white mb-12" data-aos="fade-up">Frequently Asked Questions</h2>

            <div class="space-y-4" data-aos="fade-up">
                <div class="glass-panel rounded-2xl overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center text-white font-semibold hover:text-[#ffd700] transition-colors" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        Is iConvert free to use?
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                    </button>
                    <div class="hidden p-6 pt-0 text-zinc-400 text-sm leading-relaxed border-t border-white/5">
                        Yes! We offer a guest mode that allows you to use basic tools for free. For higher limits and advanced features, check out our Pro plans.
                    </div>
                </div>

                <div class="glass-panel rounded-2xl overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center text-white font-semibold hover:text-[#ffd700] transition-colors" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        Are my files safe?
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                    </button>
                    <div class="hidden p-6 pt-0 text-zinc-400 text-sm leading-relaxed border-t border-white/5">
                        Absolutely. We use end-to-end encryption and automatically delete all files from our servers after 1 hour. We never sell your data.
                    </div>
                </div>

                <div class="glass-panel rounded-2xl overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center text-white font-semibold hover:text-[#ffd700] transition-colors" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        Can I use this on mobile?
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                    </button>
                    <div class="hidden p-6 pt-0 text-zinc-400 text-sm leading-relaxed border-t border-white/5">
                        Yes, iConvert is fully responsive and works perfectly on smartphones and tablets directly from your browser.
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <script>
        // Init AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // --- THE 6 TOOLS ---
        const tools = [
            {
                title: 'Letterhead Creator',
                desc: 'Design official docs with drag & drop.',
                icon: 'layout-template',
                color: 'text-purple-400',
                route: '{{ route("letterhead-maker") }}'
            },
            {
                title: 'QR Generator',
                desc: 'Create dynamic QR codes with logos.',
                icon: 'qr-code',
                color: 'text-blue-400',
                route: '{{ route("qr-generator") }}'
            },
            {
                title: 'Image Converter',
                desc: 'Transform PNG, JPG, WEBP instantly.',
                icon: 'image',
                color: 'text-green-400',
                route: '{{ route("image-converter") }}'
            },
            {
                title: 'MP3 Converter',
                desc: 'Extract audio from video files.',
                icon: 'music',
                color: 'text-pink-400',
                route: '{{ route("mp3-converter") }}'
            },
            {
                title: 'Invoice Generator',
                desc: 'Professional billing made simple.',
                icon: 'receipt',
                color: 'text-yellow-400',
                route: '{{ route("invoice-generator") }}'
            },
            {
                title: 'ID Card Maker',
                desc: 'Generate employee badges in bulk.',
                icon: 'id-card',
                color: 'text-cyan-400',
                route: '{{ route("id-card-creator") }}'
            }
        ];

        const gridContainer = document.getElementById('grid-container');

        // Render Cards
        tools.forEach((tool, index) => {
            const card = document.createElement('div');
            card.dataset.aos = "fade-up";
            card.dataset.aosDelay = index * 100;

            // Apply Tilt
            card.setAttribute('data-tilt', '');
            card.setAttribute('data-tilt-scale', '1.02');

            card.className = "glass-panel rounded-2xl p-6 group cursor-pointer relative overflow-hidden flex flex-col h-full min-h-[260px]";

            card.innerHTML = `
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </div>

                <div class="w-12 h-12 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:bg-[#ffd700]/20 group-hover:border-[#ffd700]/50 transition-colors">
                    <i data-lucide="${tool.icon}" class="w-6 h-6 ${tool.color} group-hover:text-[#ffd700] transition-colors"></i>
                </div>

                <h3 class="text-lg font-bold text-white mb-2 group-hover:text-[#ffd700] transition-colors">${tool.title}</h3>
                <p class="text-sm text-zinc-400 flex-grow">${tool.desc}</p>

                <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center">
                    <span class="text-[10px] uppercase tracking-widest text-zinc-500">Add-on Module</span>
                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_5px_#22c55e]"></span>
                </div>
            `;

            card.addEventListener('click', () => {
                window.location.href = tool.route;
            });

            gridContainer.appendChild(card);
        });

        lucide.createIcons();
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"));

        // --- ENHANCED MESH ANIMATION ---
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
            const count = Math.floor(width * height / 12000);
            for (let i = 0; i < count; i++) {
                points.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.6,
                    vy: (Math.random() - 0.5) * 0.6,
                    size: Math.random() * 2.5
                });
            }
        }

        function draw() {
            ctx.clearRect(0, 0, width, height);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
            ctx.lineWidth = 0.8;

            for (let i = 0; i < points.length; i++) {
                let p = points[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                const dx = target.x - p.x;
                const dy = target.y - p.y;
                const dist = Math.sqrt(dx*dx + dy*dy);
                if (dist < 250) {
                    p.x += dx * 0.02;
                    p.y += dy * 0.02;
                }

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255, 215, 0, 0.8)';
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
        window.addEventListener('mousemove', (e) => {
            target.x = e.clientX;
            target.y = e.clientY;
        });

        resize();
        draw();
    </script>
@endsection

@section('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

<script>
    // Init AOS
    AOS.init({
        duration: 1000,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });

    // --- THE 6 TOOLS ---
    const tools = [
        {
            title: 'Letterhead Creator',
            desc: 'Design official docs with drag & drop.',
            icon: 'layout-template',
            color: 'text-purple-400',
            route: '{{ route("letterhead-maker") }}'
        },
        {
            title: 'QR Generator',
            desc: 'Create dynamic QR codes with logos.',
            icon: 'qr-code',
            color: 'text-blue-400',
            route: '{{ route("qr-generator") }}'
        },
        {
            title: 'Image Converter',
            desc: 'Transform PNG, JPG, WEBP instantly.',
            icon: 'image',
            color: 'text-green-400',
            route: '{{ route("image-converter") }}'
        },
        {
            title: 'MP3 Converter',
            desc: 'Extract audio from video files.',
            icon: 'music',
            color: 'text-pink-400',
            route: '{{ route("mp3-converter") }}'
        },
        {
            title: 'Invoice Generator',
            desc: 'Professional billing made simple.',
            icon: 'receipt',
            color: 'text-yellow-400',
            route: '{{ route("invoice-generator") }}'
        },
        {
            title: 'ID Card Maker',
            desc: 'Generate employee badges in bulk.',
            icon: 'id-card',
            color: 'text-cyan-400',
            route: '{{ route("id-card-creator") }}'
        }
    ];

    const gridContainer = document.getElementById('grid-container');

    // Render Cards
    tools.forEach((tool, index) => {
        const card = document.createElement('div');
        card.dataset.aos = "fade-up";
        card.dataset.aosDelay = index * 100;

        // Apply Tilt
        card.setAttribute('data-tilt', '');
        card.setAttribute('data-tilt-scale', '1.02');

        card.className = "glass-panel rounded-2xl p-6 group cursor-pointer relative overflow-hidden flex flex-col h-full min-h-[260px]";

        card.innerHTML = `
            <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </div>

            <div class="w-12 h-12 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:bg-[#ffd700]/20 group-hover:border-[#ffd700]/50 transition-colors">
                <i data-lucide="${tool.icon}" class="w-6 h-6 ${tool.color} group-hover:text-[#ffd700] transition-colors"></i>
            </div>

            <h3 class="text-lg font-bold text-white mb-2 group-hover:text-[#ffd700] transition-colors">${tool.title}</h3>
            <p class="text-sm text-zinc-400 flex-grow">${tool.desc}</p>

            <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center">
                <span class="text-[10px] uppercase tracking-widest text-zinc-500">Add-on Module</span>
                <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_5px_#22c55e]"></span>
            </div>
        `;

        card.addEventListener('click', () => {
            window.location.href = tool.route;
        });

        gridContainer.appendChild(card);
    });

    lucide.createIcons();
    VanillaTilt.init(document.querySelectorAll("[data-tilt]"));

    // --- ENHANCED MESH ANIMATION ---
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
        const count = Math.floor(width * height / 12000);
        for (let i = 0; i < count; i++) {
            points.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.6,
                vy: (Math.random() - 0.5) * 0.6,
                size: Math.random() * 2.5
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, width, height);
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
        ctx.lineWidth = 0.8;

        for (let i = 0; i < points.length; i++) {
            let p = points[i];
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > width) p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;

            const dx = target.x - p.x;
            const dy = target.y - p.y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 250) {
                p.x += dx * 0.02;
                p.y += dy * 0.02;
            }

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255, 215, 0, 0.8)';
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
    window.addEventListener('mousemove', (e) => {
        target.x = e.clientX;
        target.y = e.clientY;
    });

    resize();
    draw();
</script>
@endsection