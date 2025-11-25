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
        body {
            background-color: #020202;
            color: #e4e4e7;
            overflow-x: hidden;
        }

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

        /* Upload Area Styles */
        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.01);
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: rgba(255, 215, 0, 0.5);
            background: rgba(255, 215, 0, 0.05);
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 16px;
        }

        .progress-container {
            margin-top: 20px;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ffd700, #ffed4e);
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
@endsection

@section('content')
<div class="pdf-editor-page">
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
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-12" data-aos="fade-down">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <a href="{{ route('pdf-converter') }}" class="text-zinc-400 hover:text-white transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-3xl md:text-4xl font-display font-bold text-white">
                        <span id="tool-title-display">PDF Tool</span>
                    </h1>
                </div>
                <p class="text-zinc-400 max-w-2xl mx-auto text-lg" id="tool-description-display">
                    Upload your files to get started
                </p>
            </div>

            <!-- Tool Options -->
            <div id="tool-options-section" class="mb-8" style="display: none;">
                <div class="glass-panel rounded-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Tool Options</h3>
                    <div id="tool-options" class="space-y-4"></div>
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="glass-panel rounded-xl p-8 mb-8">
                <div class="upload-area" id="uploadArea" role="button" tabindex="0" aria-label="File upload area">
                    <div class="text-[#ffd700] mb-6" aria-hidden="true">
                        <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl text-white font-semibold mb-3">Drop your files here or click to browse</div>
                    <div class="text-zinc-400 mb-6" id="uploadSubtext">Select files to process</div>
                    <input type="file" id="fileInput" class="hidden" multiple aria-label="File input">
                    <button class="bg-[#ffd700] hover:bg-[#ffed4e] text-black px-8 py-4 rounded-lg font-bold transition-colors text-lg" onclick="document.getElementById('fileInput').click()" aria-label="Choose files">
                        Choose Files
                    </button>
                </div>
            </div>

            <!-- File List -->
            <div class="file-list mb-8" id="fileList" style="display: none;"></div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4 mb-8">
                <button class="bg-[#ffd700] hover:bg-[#ffed4e] text-black px-8 py-4 rounded-lg font-bold transition-colors text-lg disabled:opacity-50 disabled:cursor-not-allowed" id="convertBtn" disabled onclick="processTool()">
                    <span class="button-text">Process Files</span>
                    <span class="loading-spinner hidden">
                        <svg class="w-5 h-5 animate-spin ml-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <a href="{{ route('pdf-converter') }}" class="bg-zinc-700 hover:bg-zinc-600 text-white px-8 py-4 rounded-lg font-bold transition-colors text-lg">Cancel</a>
            </div>

            <!-- Progress -->
            <div class="progress-container" id="progressContainer">
                <div class="glass-panel rounded-xl p-6">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="text-center mt-4">
                        <div class="text-white font-semibold" id="status">Processing your files...</div>
                    </div>
                </div>
            </div>

            <!-- Usage Limits Display -->
            @guest
                <div class="glass-panel rounded-xl p-6 mt-8 text-center">
                    <p class="text-white font-semibold mb-2">Free Access: 3 conversions per day</p>
                    <p class="text-zinc-400 mb-4">Register for unlimited access and more features!</p>
                    <a href="{{ route('register') }}" class="bg-[#ffd700] hover:bg-[#ffed4e] text-black px-6 py-3 rounded-lg font-bold transition-colors inline-block">Register Now</a>
                </div>
            @else
                @php
                    $remaining = auth()->user()->getRemainingConversions();
                @endphp
                <div class="glass-panel rounded-xl p-6 mt-8 text-center {{ $remaining === -1 ? 'border-[#ffd700]/20' : ($remaining > 0 ? 'border-green-500/20' : 'border-red-500/20') }}">
                    @if($remaining === -1)
                        <p class="text-white font-semibold mb-2">Unlimited Conversions</p>
                        <p class="text-zinc-400">Enjoy unlimited access with your premium plan!</p>
                    @elseif($remaining > 0)
                        <p class="text-white font-semibold mb-2">{{ $remaining }} conversions remaining this month</p>
                        <p class="text-zinc-400">Upgrade for unlimited access!</p>
                    @else
                        <p class="text-red-400 font-semibold mb-2">Conversion limit reached</p>
                        <p class="text-zinc-400 mb-4">Upgrade your plan to continue converting!</p>
                        <a href="{{ route('pricing') }}" class="bg-[#ffd700] hover:bg-[#ffed4e] text-black px-6 py-3 rounded-lg font-bold transition-colors inline-block">Upgrade Plan</a>
                    @endif
                </div>
            @endguest

        </div>
    </main>
</div>
@endsection

@section('scripts')
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <script>
        // Tool configurations (same as pdf-converter.js)
        const toolConfigs = {
            'pdf-to-word': {
                title: 'PDF to Word Converter',
                description: 'Convert PDF files to editable Word documents',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            },
            'pdf-to-excel': {
                title: 'PDF to Excel Converter',
                description: 'Extract tables and data from PDF to Excel spreadsheets',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            },
            'pdf-to-ppt': {
                title: 'PDF to PowerPoint Converter',
                description: 'Convert PDF pages to PowerPoint slides',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            },
            'pdf-to-text': {
                title: 'PDF to Text Converter',
                description: 'Extract text content from PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            },
            'pdf-to-html': {
                title: 'PDF to HTML Converter',
                description: 'Convert PDF content to HTML format',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            },
            'pdf-to-images': {
                title: 'PDF to Images Converter',
                description: 'Convert PDF pages to image files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'select', name: 'image-format', label: 'Image Format', options: ['png', 'jpg', 'jpeg'] },
                    { type: 'number', name: 'resolution', label: 'Resolution (DPI)', min: 72, max: 600, value: 150 }
                ]
            },
            'word-to-pdf': {
                title: 'Word to PDF Converter',
                description: 'Convert Word documents to PDF format',
                accept: '.doc,.docx',
                multiple: true,
                maxSize: 50 * 1024 * 1024
            },
            'excel-to-pdf': {
                title: 'Excel to PDF Converter',
                description: 'Convert Excel spreadsheets to PDF format',
                accept: '.xls,.xlsx',
                multiple: true,
                maxSize: 50 * 1024 * 1024
            },
            'ppt-to-pdf': {
                title: 'PowerPoint to PDF Converter',
                description: 'Convert PowerPoint presentations to PDF format',
                accept: '.ppt,.pptx',
                multiple: true,
                maxSize: 50 * 1024 * 1024
            },
            'html-to-pdf': {
                title: 'HTML to PDF Converter',
                description: 'Convert HTML files to PDF format',
                accept: '.html,.htm',
                multiple: true,
                maxSize: 10 * 1024 * 1024
            },
            'images-to-pdf': {
                title: 'Images to PDF Converter',
                description: 'Combine multiple images into a single PDF',
                accept: '.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp',
                multiple: true,
                maxSize: 100 * 1024 * 1024
            },
            'text-to-pdf': {
                title: 'Text to PDF Converter',
                description: 'Convert text files to PDF format',
                accept: '.txt',
                multiple: true,
                maxSize: 10 * 1024 * 1024
            },
            'pdf-editor': {
                title: 'PDF Editor',
                description: 'Edit and modify PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'textarea', name: 'editor-content', label: 'Content to Add', placeholder: 'Enter text to add to PDF...' },
                    { type: 'select', name: 'position', label: 'Position', options: ['Top Left', 'Top Right', 'Bottom Left', 'Bottom Right', 'Center'] }
                ]
            },
            'pdf-rotate': {
                title: 'Rotate PDF',
                description: 'Rotate PDF pages',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'select', name: 'rotation', label: 'Rotation', options: ['90° Clockwise', '90° Counter-clockwise', '180°'] },
                    { type: 'select', name: 'pages', label: 'Pages to Rotate', options: ['All Pages', 'Even Pages', 'Odd Pages', 'Custom Range'] },
                    { type: 'text', name: 'page-range', label: 'Page Range (e.g., 1-5,8,10-15)', placeholder: '1-5,8,10-15' }
                ]
            },
            'pdf-watermark': {
                title: 'Add Watermark to PDF',
                description: 'Add text or image watermarks to PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'text', name: 'watermark-text', label: 'Watermark Text', required: true },
                    { type: 'select', name: 'position', label: 'Position', options: ['Center', 'Top Left', 'Top Right', 'Bottom Left', 'Bottom Right', 'Diagonal'] },
                    { type: 'color', name: 'color', label: 'Text Color', value: '#000000' },
                    { type: 'number', name: 'opacity', label: 'Opacity (%)', min: 1, max: 100, value: 50 }
                ]
            },
            'pdf-protect': {
                title: 'Protect PDF',
                description: 'Add password protection to PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'password', name: 'user-password', label: 'User Password (to open PDF)' },
                    { type: 'password', name: 'owner-password', label: 'Owner Password (full access)' },
                    { type: 'checkbox', name: 'print', label: 'Allow Printing' },
                    { type: 'checkbox', name: 'copy', label: 'Allow Copying' },
                    { type: 'checkbox', name: 'modify', label: 'Allow Modifying' }
                ]
            },
            'pdf-unlock': {
                title: 'Unlock PDF',
                description: 'Remove password protection from PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'password', name: 'password', label: 'PDF Password', required: true }
                ]
            },
            'pdf-merge': {
                title: 'Merge PDFs',
                description: 'Combine multiple PDF files into one',
                accept: '.pdf',
                multiple: true,
                maxSize: 100 * 1024 * 1024,
                minFiles: 2
            },
            'pdf-split': {
                title: 'Split PDF',
                description: 'Split PDF files into multiple documents',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024,
                options: [
                    { type: 'select', name: 'split-method', label: 'Split Method', options: ['By Page Range', 'Extract Single Pages'] },
                    { type: 'text', name: 'page-ranges', label: 'Page Ranges (e.g., 1-5,8,10-15)', placeholder: '1-5,8,10-15' }
                ]
            },
            'pdf-compress': {
                title: 'Compress PDF',
                description: 'Reduce PDF file size',
                accept: '.pdf',
                multiple: false,
                maxSize: 100 * 1024 * 1024,
                options: [
                    { type: 'select', name: 'compression-level', label: 'Compression Level', options: ['Low (Better Quality)', 'Medium', 'High (Smaller Size)', 'Maximum'] }
                ]
            },
            'pdf-repair': {
                title: 'Repair PDF',
                description: 'Fix corrupted PDF files',
                accept: '.pdf',
                multiple: false,
                maxSize: 50 * 1024 * 1024
            }
        };

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializePDFEditor();
        });

        function initializePDFEditor() {
            // Get tool from URL path
            const pathSegments = window.location.pathname.split('/');
            const toolIndex = pathSegments.indexOf('pdf-editor');
            const tool = toolIndex !== -1 && pathSegments[toolIndex + 1] ? pathSegments[toolIndex + 1] : '{{ $tool }}';

            if (tool && toolConfigs[tool]) {
                loadToolConfig(tool);
            }

            initializeDragAndDrop();
            initializeAOS();
        }

        function loadToolConfig(tool) {
            const config = toolConfigs[tool];

            document.getElementById('tool-title-display').textContent = config.title;
            document.getElementById('tool-description-display').textContent = config.description;

            document.getElementById('fileInput').accept = config.accept;
            document.getElementById('fileInput').multiple = config.multiple;

            const maxSizeMB = Math.round(config.maxSize / (1024 * 1024));
            document.getElementById('uploadSubtext').textContent = `Select ${config.multiple ? 'files' : 'a file'} (max ${maxSizeMB}MB)`;

            // Generate tool options
            if (config.options) {
                const toolOptions = document.getElementById('tool-options');
                toolOptions.innerHTML = '';

                config.options.forEach(option => {
                    const optionGroup = document.createElement('div');
                    optionGroup.className = 'flex flex-col gap-2';

                    const label = document.createElement('label');
                    label.textContent = option.label;
                    label.className = 'text-white font-medium';
                    if (option.required) {
                        label.innerHTML += ' <span class="text-red-400">*</span>';
                    }
                    optionGroup.appendChild(label);

                    let input;
                    switch (option.type) {
                        case 'select':
                            input = document.createElement('select');
                            input.name = option.name;
                            input.className = 'bg-zinc-800 border border-zinc-600 rounded-lg px-3 py-2 text-white';
                            option.options.forEach(opt => {
                                const optionEl = document.createElement('option');
                                optionEl.value = opt;
                                optionEl.textContent = opt;
                                input.appendChild(optionEl);
                            });
                            break;
                        case 'textarea':
                            input = document.createElement('textarea');
                            input.name = option.name;
                            input.placeholder = option.placeholder || '';
                            input.className = 'bg-zinc-800 border border-zinc-600 rounded-lg px-3 py-2 text-white min-h-[100px]';
                            break;
                        case 'checkbox':
                            input = document.createElement('input');
                            input.type = 'checkbox';
                            input.name = option.name;
                            input.value = '1';
                            input.className = 'mr-2';
                            const checkboxWrapper = document.createElement('div');
                            checkboxWrapper.className = 'flex items-center';
                            checkboxWrapper.appendChild(input);
                            checkboxWrapper.appendChild(label);
                            optionGroup.innerHTML = '';
                            optionGroup.appendChild(checkboxWrapper);
                            break;
                        default:
                            input = document.createElement('input');
                            input.type = option.type;
                            input.name = option.name;
                            input.placeholder = option.placeholder || '';
                            input.className = 'bg-zinc-800 border border-zinc-600 rounded-lg px-3 py-2 text-white';
                            if (option.min !== undefined) input.min = option.min;
                            if (option.max !== undefined) input.max = option.max;
                            if (option.value !== undefined) input.value = option.value;
                            break;
                    }

                    if (option.type !== 'checkbox') {
                        optionGroup.appendChild(input);
                    }

                    toolOptions.appendChild(optionGroup);
                });

                document.getElementById('tool-options-section').style.display = 'block';
            }
        }

        function initializeDragAndDrop() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');

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
            fileInput.addEventListener('change', handleFileSelect, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                uploadArea.classList.add('dragover');
            }

            function unhighlight() {
                uploadArea.classList.remove('dragover');
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
        }

        let uploadedFiles = [];

        function handleFiles(files) {
            const urlParams = new URLSearchParams(window.location.search);
            const tool = urlParams.get('tool') || '{{ $tool }}';
            const config = toolConfigs[tool];
            const maxSize = config.maxSize;
            const accept = config.accept.split(',').map(ext => ext.trim().toLowerCase());

            [...files].forEach(file => {
                if (file.size > maxSize) {
                    showMessage(`File "${file.name}" is too large. Maximum size is ${Math.round(maxSize / (1024 * 1024))}MB.`, 'error');
                    return;
                }

                const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                if (!accept.includes(fileExt) && !accept.includes('*')) {
                    showMessage(`File type "${fileExt}" is not supported for this tool.`, 'error');
                    return;
                }

                if (uploadedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    showMessage(`File "${file.name}" is already selected.`, 'warning');
                    return;
                }

                uploadedFiles.push(file);
            });

            updateFileList();
        }

        function updateFileList() {
            const fileList = document.getElementById('fileList');
            const convertBtn = document.getElementById('convertBtn');

            fileList.innerHTML = '';

            if (uploadedFiles.length === 0) {
                fileList.style.display = 'none';
                convertBtn.disabled = true;
                return;
            }

            fileList.style.display = 'block';

            uploadedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

                const fileExt = file.name.split('.').pop().toLowerCase();
                const fileIcon = getFileIcon(fileExt);

                fileItem.innerHTML = `
                    <div class="file-info">
                        <div class="file-icon">${fileIcon}</div>
                        <div class="file-details">
                            <div class="file-name text-white font-medium">${file.name}</div>
                            <div class="file-size text-zinc-400 text-sm">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                    <button class="text-zinc-400 hover:text-red-400 transition-colors" onclick="removeFile(${index})">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;

                fileList.appendChild(fileItem);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const tool = urlParams.get('tool') || '{{ $tool }}';
            const config = toolConfigs[tool];
            const minFiles = config.minFiles || 1;
            convertBtn.disabled = uploadedFiles.length < minFiles;
        }

        function removeFile(index) {
            uploadedFiles.splice(index, 1);
            updateFileList();
        }

        function getFileIcon(ext) {
            const icons = {
                pdf: '📄',
                doc: '📝',
                docx: '📝',
                xls: '📊',
                xlsx: '📊',
                ppt: '📽️',
                pptx: '📽️',
                txt: '📄',
                html: '🌐',
                htm: '🌐',
                jpg: '🖼️',
                jpeg: '🖼️',
                png: '🖼️',
                gif: '🖼️',
                bmp: '🖼️',
                tiff: '🖼️',
                webp: '🖼️'
            };
            return icons[ext] || '📄';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function processTool() {
            // Get tool from URL path
            const pathSegments = window.location.pathname.split('/');
            const toolIndex = pathSegments.indexOf('pdf-editor');
            const tool = toolIndex !== -1 && pathSegments[toolIndex + 1] ? pathSegments[toolIndex + 1] : '{{ $tool }}';

            if (!tool || uploadedFiles.length === 0) return;

            const convertBtn = document.getElementById('convertBtn');
            const buttonText = convertBtn.querySelector('.button-text');
            const loadingSpinner = convertBtn.querySelector('.loading-spinner');

            convertBtn.disabled = true;
            buttonText.textContent = 'Processing...';
            loadingSpinner.classList.remove('hidden');
            showProgress();

            const formData = new FormData();
            formData.append('tool', tool);

            uploadedFiles.forEach(file => {
                formData.append('files[]', file);
            });

            const config = toolConfigs[tool];
            if (config.options) {
                config.options.forEach(option => {
                    const element = document.querySelector(`[name="${option.name}"]`);
                    if (element) {
                        if (element.type === 'checkbox') {
                            formData.append(option.name, element.checked ? '1' : '0');
                        } else {
                            formData.append(option.name, element.value);
                        }
                    }
                });
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }

            fetch('/pdf-tools/process', {
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

                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = 'converted_file';
                if (contentDisposition) {
                    const matches = contentDisposition.match(/filename="([^"]+)"/);
                    if (matches) filename = matches[1];
                } else {
                    const originalName = uploadedFiles[0].name;
                    const baseName = originalName.substring(0, originalName.lastIndexOf('.'));
                    const ext = getOutputExtension(tool);
                    filename = `${baseName}_converted.${ext}`;
                }

                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                showMessage('File processed successfully!', 'success');
                uploadedFiles = [];
                updateFileList();
            })
            .catch(error => {
                console.error('Processing error:', error);
                showMessage(error.message || 'An error occurred while processing the file.', 'error');
            })
            .finally(() => {
                convertBtn.disabled = false;
                buttonText.textContent = 'Process Files';
                loadingSpinner.classList.add('hidden');
                hideProgress();
            });
        }

        function getOutputExtension(tool) {
            const extensions = {
                'pdf-to-word': 'docx',
                'pdf-to-excel': 'xlsx',
                'pdf-to-ppt': 'pptx',
                'pdf-to-text': 'txt',
                'pdf-to-html': 'html',
                'pdf-to-images': 'zip',
                'word-to-pdf': 'pdf',
                'excel-to-pdf': 'pdf',
                'ppt-to-pdf': 'pdf',
                'html-to-pdf': 'pdf',
                'images-to-pdf': 'pdf',
                'text-to-pdf': 'pdf',
                'pdf-editor': 'pdf',
                'pdf-rotate': 'pdf',
                'pdf-watermark': 'pdf',
                'pdf-protect': 'pdf',
                'pdf-unlock': 'pdf',
                'pdf-merge': 'pdf',
                'pdf-split': 'zip',
                'pdf-compress': 'pdf',
                'pdf-repair': 'pdf'
            };
            return extensions[tool] || 'file';
        }

        function showProgress() {
            const progressContainer = document.getElementById('progressContainer');
            const progressFill = document.getElementById('progressFill');

            progressContainer.style.display = 'block';
            progressFill.style.width = '0%';

            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressFill.style.width = progress + '%';

                if (!convertBtn.disabled) {
                    clearInterval(interval);
                    progressFill.style.width = '100%';
                }
            }, 500);
        }

        function hideProgress() {
            const progressContainer = document.getElementById('progressContainer');
            progressContainer.style.display = 'none';
        }

        function showMessage(message, type = 'info') {
            const existingMessages = document.querySelectorAll('.message');
            existingMessages.forEach(msg => msg.remove());

            const messageEl = document.createElement('div');
            messageEl.className = `message fixed top-20 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg font-medium text-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-black' :
                'bg-blue-500 text-white'
            }`;
            messageEl.textContent = message;
            messageEl.setAttribute('role', 'alert');

            document.body.appendChild(messageEl);

            setTimeout(() => {
                if (messageEl.parentNode) {
                    messageEl.remove();
                }
            }, 5000);
        }

        function initializeAOS() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });
        }

        // Global functions
        window.removeFile = removeFile;
        window.processTool = processTool;
    </script>
@endsection