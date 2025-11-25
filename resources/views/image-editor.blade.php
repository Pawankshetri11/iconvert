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
                        'spin-slow': 'spin 15s linear infinite',
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
        body { background-color: #020202; color: #e4e4e7; overflow: hidden; }

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

        /* Glow Orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.3;
            z-index: -1;
        }

        /* Dashed Upload Area */
        .upload-area {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%233F3F46FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%23FFD700FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            background-color: rgba(255, 215, 0, 0.02);
        }

        /* Custom Scrollbar for Select */
        select option {
            background-color: #0a0a0a;
            color: white;
            padding: 10px;
        }

        .tool-tab.active {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: #ffd700;
        }
        .tool-tab:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="h-screen w-full flex flex-col items-center justify-center relative">

    <!-- Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="glow-orb w-[600px] h-[600px] bg-purple-900/20 top-0 left-0 animate-blob"></div>
        <div class="glow-orb w-[600px] h-[600px] bg-[#ffd700]/10 bottom-0 right-0 animate-blob" style="animation-delay: 2s"></div>
    </div>

    <!-- Back Button -->
    <a href="{{ route('image-converter') }}" class="absolute top-8 left-8 z-50 flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white transition-all">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span class="text-xs font-medium uppercase tracking-wider">Back to Converter</span>
    </a>

    <!-- Main Interface -->
    <main class="relative z-10 w-full max-w-5xl px-6">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-display font-bold text-white mb-2">
                @if($tool === 'convert')
                    Image Converter
                @elseif($tool === 'compress')
                    Image Compressor
                @elseif($tool === 'resize')
                    Image Resizer
                @elseif($tool === 'crop')
                    Image Cropper
                @else
                    Advanced Image Editor
                @endif
            </h1>
            <p class="text-zinc-400">
                @if($tool === 'convert')
                    Convert images between different formats with advanced options
                @elseif($tool === 'compress')
                    Reduce image file sizes while maintaining quality
                @elseif($tool === 'resize')
                    Change image dimensions and aspect ratios
                @elseif($tool === 'crop')
                    Crop images to focus on specific areas
                @else
                    Professional image editing with multiple tools
                @endif
            </p>
        </div>

        @if($tool === 'convert' || $tool === 'compress')
            <!-- Converter/Compressor Interface -->
            <div class="glass-panel rounded-3xl p-8 md:p-10 relative overflow-hidden">

                <div class="flex flex-col md:flex-row gap-8 h-full">

                    <!-- Left: Upload Zone -->
                    <div class="flex-1">
                        <div class="upload-area rounded-2xl h-full min-h-[300px] flex flex-col items-center justify-center cursor-pointer group relative bg-black/20" id="uploadArea">
                            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 border border-white/10 group-hover:border-[#ffd700]/50">
                                <i data-lucide="upload-cloud" class="w-10 h-10 text-zinc-400 group-hover:text-[#ffd700] transition-colors"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-1">Upload Image</h3>
                            <p class="text-sm text-zinc-500">JPG, PNG, WEBP, HEIC, RAW...</p>
                            <input type="file" id="fileInput" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" multiple>
                        </div>
                    </div>

                    <!-- Right: Settings -->
                    <div class="w-full md:w-80 flex flex-col justify-center space-y-6">

                        <div>
                            <h2 class="text-2xl font-display font-bold text-white mb-1">Configuration</h2>
                            <p class="text-xs text-zinc-500">Customize your output settings</p>
                        </div>

                        <hr class="border-white/10">

                        @if($tool === 'convert')
                            <!-- Format Selection -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="file-type" class="w-3 h-3"></i> Target Format
                                </label>
                                <div class="relative">
                                    <select id="targetFormat" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-sm appearance-none focus:outline-none focus:border-[#ffd700] transition-colors cursor-pointer hover:bg-white/5">
                                        <optgroup label="Popular">
                                            <option value="jpg" selected>JPG / JPEG</option>
                                            <option value="png">PNG (Transparent)</option>
                                            <option value="webp">WEBP (Next-Gen)</option>
                                            <option value="pdf">PDF (Document)</option>
                                        </optgroup>
                                        <optgroup label="Vector & Design">
                                            <option value="svg">SVG (Vector)</option>
                                            <option value="eps">EPS</option>
                                            <option value="ai">AI (Illustrator)</option>
                                            <option value="psd">PSD (Photoshop)</option>
                                        </optgroup>
                                        <optgroup label="Specialized">
                                            <option value="gif">GIF (Animation)</option>
                                            <option value="tiff">TIFF (Print)</option>
                                            <option value="ico">ICO (Icon)</option>
                                            <option value="bmp">BMP</option>
                                            <option value="heic">HEIC (Apple)</option>
                                            <option value="avif">AVIF</option>
                                            <option value="tga">TGA</option>
                                            <option value="dds">DDS</option>
                                            <option value="raw">RAW</option>
                                        </optgroup>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500 pointer-events-none"></i>
                                </div>
                            </div>
                        @endif

                        <!-- Compression Slider -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-end">
                                <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="minimize-2" class="w-3 h-3"></i> Compression
                                </label>
                                <span class="text-xs font-mono text-[#ffd700] bg-[#ffd700]/10 px-2 py-0.5 rounded border border-[#ffd700]/20" id="compressionValue">85%</span>
                            </div>
                            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full w-[85%] bg-gradient-to-r from-[#ffd700] to-orange-500 rounded-full" id="compressionBar"></div>
                            </div>
                            <input type="range" id="compressionSlider" class="w-full h-1 bg-transparent appearance-none cursor-pointer opacity-0 absolute" style="margin-top: -10px;" min="1" max="100" value="85">
                            <p class="text-[10px] text-zinc-600">Balance between quality and file size.</p>
                        </div>

                        <!-- Convert Button -->
                        <button class="btn-gold w-full py-4 rounded-xl text-sm font-bold uppercase tracking-wide shadow-lg flex items-center justify-center gap-2 mt-4" id="convertBtn" disabled>
                            @if($tool === 'convert') Start Converting @else Start Compressing @endif <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>

                    </div>
                </div>

            </div>
        @else
            <!-- Advanced Editor Interface -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                <!-- Tools Panel -->
                <div class="lg:col-span-1">
                    <div class="glass-panel rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Tools</h3>

                        <div class="space-y-2">
                            <button class="tool-btn w-full p-3 rounded-lg text-left text-zinc-400 hover:text-white transition-all flex items-center gap-3 {{ $tool === 'crop' ? 'tool-active' : '' }}" data-tool="crop">
                                <i data-lucide="crop" class="w-4 h-4"></i>
                                <span class="text-sm">Crop</span>
                            </button>

                            <button class="tool-btn w-full p-3 rounded-lg text-left text-zinc-400 hover:text-white transition-all flex items-center gap-3 {{ $tool === 'resize' ? 'tool-active' : '' }}" data-tool="resize">
                                <i data-lucide="expand" class="w-4 h-4"></i>
                                <span class="text-sm">Resize</span>
                            </button>

                            <button class="tool-btn w-full p-3 rounded-lg text-left text-zinc-400 hover:text-white transition-all flex items-center gap-3" data-tool="rotate">
                                <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                                <span class="text-sm">Rotate</span>
                            </button>

                            <button class="tool-btn w-full p-3 rounded-lg text-left text-zinc-400 hover:text-white transition-all flex items-center gap-3" data-tool="flip">
                                <i data-lucide="flip-horizontal" class="w-4 h-4"></i>
                                <span class="text-sm">Flip</span>
                            </button>

                            <button class="tool-btn w-full p-3 rounded-lg text-left text-zinc-400 hover:text-white transition-all flex items-center gap-3" data-tool="filters">
                                <i data-lucide="palette" class="w-4 h-4"></i>
                                <span class="text-sm">Filters</span>
                            </button>
                        </div>

                        <hr class="border-white/10 my-6">

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button class="btn-gold w-full py-3 rounded-lg text-sm font-bold uppercase tracking-wide">
                                Apply Changes
                            </button>

                            <button class="w-full py-3 rounded-lg text-sm font-medium uppercase tracking-wide bg-white/5 border border-white/10 text-zinc-400 hover:text-white transition-colors">
                                Reset
                            </button>

                            <button class="w-full py-3 rounded-lg text-sm font-medium uppercase tracking-wide bg-green-500/10 border border-green-500/20 text-green-400 hover:bg-green-500/20 transition-colors">
                                Download
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Canvas Area -->
                <div class="lg:col-span-2">
                    <div class="glass-panel rounded-2xl p-6">
                        <div class="upload-area rounded-2xl h-full min-h-[400px] flex flex-col items-center justify-center cursor-pointer group relative bg-black/20" id="canvasContainer">
                            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 border border-white/10 group-hover:border-[#ffd700]/50">
                                <i data-lucide="image" class="w-10 h-10 text-zinc-400 group-hover:text-[#ffd700] transition-colors"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Upload an Image</h3>
                            <p class="text-zinc-500 mb-4">Drag & drop or click to select</p>
                            <input type="file" id="imageInput" accept="image/*" class="hidden">
                            <button class="btn-gold px-6 py-3 rounded-lg text-sm font-bold" onclick="document.getElementById('imageInput').click()">
                                Choose Image
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Properties Panel -->
                <div class="lg:col-span-1">
                    <div class="glass-panel rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Properties</h3>

                        <!-- Tool Options Container -->
                        <div id="toolOptions" class="space-y-4">
                            <!-- Crop Options -->
                            <div id="cropOptions" class="tool-options space-y-3 {{ $tool !== 'crop' ? 'hidden' : '' }}">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Aspect Ratio</label>
                                    <select class="w-full mt-2 bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                                        <option value="free">Free Form</option>
                                        <option value="1:1">Square (1:1)</option>
                                        <option value="4:3">Standard (4:3)</option>
                                        <option value="16:9">Widescreen (16:9)</option>
                                        <option value="3:2">Photo (3:2)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Resize Options -->
                            <div id="resizeOptions" class="tool-options space-y-3 {{ $tool !== 'resize' ? 'hidden' : '' }}">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Width (px)</label>
                                    <input type="number" class="w-full mt-2 bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white text-sm" placeholder="800">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Height (px)</label>
                                    <input type="number" class="w-full mt-2 bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white text-sm" placeholder="600">
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="maintainRatio" checked class="rounded">
                                    <label for="maintainRatio" class="text-xs text-zinc-400">Maintain aspect ratio</label>
                                </div>
                            </div>

                            <!-- Rotate Options -->
                            <div id="rotateOptions" class="tool-options space-y-3 hidden">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Rotation</label>
                                    <div class="flex gap-2 mt-2">
                                        <button class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">90°</button>
                                        <button class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">180°</button>
                                        <button class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">-90°</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Flip Options -->
                            <div id="flipOptions" class="tool-options space-y-3 hidden">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Flip Direction</label>
                                    <div class="flex gap-2 mt-2">
                                        <button class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs flex items-center justify-center gap-1">
                                            <i data-lucide="arrow-left-right" class="w-3 h-3"></i> Horizontal
                                        </button>
                                        <button class="flex-1 py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs flex items-center justify-center gap-1">
                                            <i data-lucide="arrow-up-down" class="w-3 h-3"></i> Vertical
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Options -->
                            <div id="filterOptions" class="tool-options space-y-3 hidden">
                                <div>
                                    <label class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Filters</label>
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Brightness</button>
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Contrast</button>
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Saturation</button>
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Blur</button>
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Sepia</button>
                                        <button class="py-2 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-white text-xs">Grayscale</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Features Footer -->
        <div class="mt-10 grid grid-cols-3 gap-4 text-center">
            <div class="p-3 rounded-xl border border-white/5 bg-white/5 backdrop-blur-sm">
                <div class="text-[#ffd700] text-lg font-bold mb-1">
                    @if($tool === 'convert') 15+ @elseif($tool === 'compress') Lossless @elseif($tool === 'resize') Smart @elseif($tool === 'crop') Precise @else Advanced @endif
                </div>
                <div class="text-[10px] text-zinc-500 uppercase tracking-wider">
                    @if($tool === 'convert') Formats Supported @elseif($tool === 'compress') Compression Mode @elseif($tool === 'resize') Resizing @elseif($tool === 'crop') Cropping @else Editing @endif
                </div>
            </div>
            <div class="p-3 rounded-xl border border-white/5 bg-white/5 backdrop-blur-sm">
                <div class="text-[#ffd700] text-lg font-bold mb-1">Fast</div>
                <div class="text-[10px] text-zinc-500 uppercase tracking-wider">Processing Speed</div>
            </div>
            <div class="p-3 rounded-xl border border-white/5 bg-white/5 backdrop-blur-sm">
                <div class="text-[#ffd700] text-lg font-bold mb-1">Secure</div>
                <div class="text-[10px] text-zinc-500 uppercase tracking-wider">256-bit Encrypted</div>
            </div>
        </div>

    </main>

    <!-- Messages Container -->
    <div id="messages-container" class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50"></div>

    <script>
        lucide.createIcons();

        // Initialize variables
        let uploadedFiles = [];
        let selectedFormat = 'jpg';
        let compressionLevel = 85;
        const currentTool = '{{ $tool }}';

        // Tool switching (for advanced editor)
        const toolBtns = document.querySelectorAll('.tool-btn');
        const toolOptions = document.querySelectorAll('.tool-options');

        toolBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                toolBtns.forEach(b => b.classList.remove('tool-active'));
                // Add active class to clicked button
                btn.classList.add('tool-active');

                // Hide all tool options
                toolOptions.forEach(opt => opt.classList.add('hidden'));

                // Show selected tool options
                const tool = btn.dataset.tool;
                const optionsEl = document.getElementById(tool + 'Options');
                if (optionsEl) {
                    optionsEl.classList.remove('hidden');
                }
            });
        });

        // File upload handling
        const fileInput = document.getElementById('fileInput');
        const uploadArea = document.getElementById('uploadArea') || document.getElementById('canvasContainer');
        const convertBtn = document.getElementById('convertBtn');

        if (uploadArea) {
            uploadArea.addEventListener('click', () => fileInput && fileInput.click());
        }
        if (fileInput) {
            fileInput.addEventListener('change', handleFileSelect);
        }

        // Drag and drop
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            uploadArea.addEventListener('drop', handleDrop, false);
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            if (uploadArea) uploadArea.classList.add('border-[#ffd700]', 'bg-[#ffd700]/5');
        }

        function unhighlight() {
            if (uploadArea) uploadArea.classList.remove('border-[#ffd700]', 'bg-[#ffd700]/5');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            [...files].forEach(file => {
                if (file.type.startsWith('image/')) {
                    uploadedFiles.push(file);
                }
            });
            updateUI();
        }

        function updateUI() {
            if (convertBtn) {
                convertBtn.disabled = uploadedFiles.length === 0;
            }
            // Update upload area text
            if (uploadArea) {
                const uploadText = uploadArea.querySelector('h3');
                const uploadSubtext = uploadArea.querySelector('p');

                if (uploadedFiles.length > 0) {
                    if (uploadText) uploadText.textContent = `${uploadedFiles.length} file${uploadedFiles.length > 1 ? 's' : ''} selected`;
                    if (uploadSubtext) uploadSubtext.textContent = uploadedFiles.map(f => f.name).join(', ');
                } else {
                    if (uploadText) uploadText.textContent = 'Upload Image';
                    if (uploadSubtext) uploadSubtext.textContent = 'JPG, PNG, WEBP, HEIC, RAW...';
                }
            }
        }

        // Format selection
        const targetFormat = document.getElementById('targetFormat');
        if (targetFormat) {
            targetFormat.addEventListener('change', (e) => {
                selectedFormat = e.target.value;
            });
        }

        // Compression slider
        const compressionSlider = document.getElementById('compressionSlider');
        const compressionValue = document.getElementById('compressionValue');
        const compressionBar = document.getElementById('compressionBar');

        if (compressionSlider) {
            compressionSlider.addEventListener('input', (e) => {
                compressionLevel = e.target.value;
                if (compressionValue) compressionValue.textContent = compressionLevel + '%';
                if (compressionBar) compressionBar.style.width = compressionLevel + '%';
            });
        }

        // Convert button
        if (convertBtn) {
            convertBtn.addEventListener('click', processImages);
        }

        function processImages() {
            if (uploadedFiles.length === 0) return;

            showMessage('Processing images...', 'info');

            const formData = new FormData();
            formData.append('tool', currentTool);
            if (currentTool === 'convert') {
                formData.append('target_format', selectedFormat);
            }
            formData.append('compression', compressionLevel);

            uploadedFiles.forEach(file => {
                formData.append('files[]', file);
            });

            fetch('/image-tools/process', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            throw new Error(data.error || 'Processing failed');
                        } catch {
                            throw new Error(text || 'Processing failed');
                        }
                    });
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `processed_images.${currentTool === 'convert' ? selectedFormat : 'zip'}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                showMessage('Images processed successfully!', 'success');
                uploadedFiles = [];
                updateUI();
            })
            .catch(error => {
                console.error('Processing error:', error);
                showMessage(error.message || 'An error occurred while processing images.', 'error');
            });
        }

        function showMessage(message, type = 'info') {
            const messageEl = document.createElement('div');
            messageEl.className = `px-6 py-3 rounded-lg text-sm font-medium ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-black' :
                'bg-blue-500 text-white'
            }`;
            messageEl.textContent = message;

            const container = document.getElementById('messages-container');
            if (container) {
                container.appendChild(messageEl);

                setTimeout(() => {
                    if (messageEl.parentNode) {
                        messageEl.remove();
                    }
                }, 5000);
            }
        }
    </script>
</div>
@endsection