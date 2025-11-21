@extends('layouts.frontend')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pdf-converter.css') }}">
@endsection

@section('content')
<div class="pdf-converter-page">
    <!-- Messages Container -->
    <div id="messages-container"></div>

    <div class="pdf-container">
        <div class="pdf-content">
            <!-- Header -->
            <div class="pdf-header" data-aos="fade-down" data-aos-duration="800">
                <h1 class="pdf-title">PDF Tools Suite</h1>
                <p class="pdf-subtitle">A complete set of tools to work with PDF files. Drag and drop your files or click to browse.</p>
            </div>

            <!-- Tools Grid -->
            <div class="tools-grid" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <!-- PDF Conversion Tools -->
                <div class="tool-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                    <div class="tool-card-content">
                        <div class="tool-icon" style="background: var(--gradient-primary);">
                            <span>📄</span>
                        </div>
                        <h3 class="tool-title">PDF Converters</h3>
                        <p class="tool-description">Transform PDFs into Word, Excel, PowerPoint, and more</p>
                        <div class="tool-buttons">
                            <button onclick="selectTool('pdf-to-word')" class="tool-button">PDF → Word</button>
                            <button onclick="selectTool('pdf-to-excel')" class="tool-button">PDF → Excel</button>
                            <button onclick="selectTool('pdf-to-ppt')" class="tool-button">PDF → PowerPoint</button>
                            <button onclick="selectTool('pdf-to-text')" class="tool-button">PDF → Text</button>
                            <button onclick="selectTool('pdf-to-html')" class="tool-button">PDF → HTML</button>
                            <button onclick="selectTool('pdf-to-images')" class="tool-button">PDF → Images</button>
                        </div>
                    </div>
                </div>

                <!-- Document to PDF -->
                <div class="tool-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                    <div class="tool-card-content">
                        <div class="tool-icon" style="background: var(--gradient-success);">
                            <span>📝</span>
                        </div>
                        <h3 class="tool-title">Create PDFs</h3>
                        <p class="tool-description">Transform documents into professional PDFs</p>
                        <div class="tool-buttons">
                            <button onclick="selectTool('word-to-pdf')" class="tool-button">Word → PDF</button>
                            <button onclick="selectTool('excel-to-pdf')" class="tool-button">Excel → PDF</button>
                            <button onclick="selectTool('ppt-to-pdf')" class="tool-button">PowerPoint → PDF</button>
                            <button onclick="selectTool('html-to-pdf')" class="tool-button">HTML → PDF</button>
                            <button onclick="selectTool('images-to-pdf')" class="tool-button">Images → PDF</button>
                            <button onclick="selectTool('text-to-pdf')" class="tool-button">Text → PDF</button>
                        </div>
                    </div>
                </div>

                <!-- PDF Editor Tools -->
                <div class="tool-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
                    <div class="tool-card-content">
                        <div class="tool-icon" style="background: var(--gradient-secondary);">
                            <span>✏️</span>
                        </div>
                        <h3 class="tool-title">PDF Editor</h3>
                        <p class="tool-description">Advanced PDF editing and modification tools</p>
                        <div class="tool-buttons">
                            <button onclick="selectTool('pdf-editor')" class="tool-button">PDF Editor</button>
                            <button onclick="selectTool('pdf-rotate')" class="tool-button">Rotate PDF</button>
                            <button onclick="selectTool('pdf-watermark')" class="tool-button">Add Watermark</button>
                            <button onclick="selectTool('pdf-protect')" class="tool-button">Protect PDF</button>
                            <button onclick="selectTool('pdf-unlock')" class="tool-button">Unlock PDF</button>
                        </div>
                    </div>
                </div>

                <!-- PDF Utilities -->
                <div class="tool-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="600">
                    <div class="tool-card-content">
                        <div class="tool-icon" style="background: var(--gradient-warning);">
                            <span>🔧</span>
                        </div>
                        <h3 class="tool-title">PDF Utilities</h3>
                        <p class="tool-description">Powerful tools to organize and optimize your PDFs</p>
                        <div class="tool-buttons">
                            <button onclick="selectTool('pdf-merge')" class="tool-button">Merge PDFs</button>
                            <button onclick="selectTool('pdf-split')" class="tool-button">Split PDF</button>
                            <button onclick="selectTool('pdf-compress')" class="tool-button">Compress PDF</button>
                            <button onclick="selectTool('pdf-repair')" class="tool-button">Repair PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tool Modal -->
    <div id="tool-modal" class="tool-modal" role="dialog" aria-modal="true" aria-labelledby="tool-title">
        <div id="tool-interface" class="tool-interface">
            <!-- Tool Header -->
            <div class="tool-header">
                <div>
                    <h2 id="tool-title" class="tool-modal-title">Select a Tool</h2>
                    <p id="tool-description" class="tool-modal-description">Choose a tool from the list to get started</p>
                </div>
                <button onclick="closeTool()" class="close-button" aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Tool Options -->
            <div id="tool-options" class="mb-6"></div>

            <!-- File Upload Area -->
            <div id="upload-section" class="mb-6">
                <div class="upload-area" id="uploadArea" role="button" tabindex="0" aria-label="File upload area">
                    <div class="text-blue-500 mb-4" aria-hidden="true">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-xl text-white font-semibold mb-2">Drop your files here or click to browse</div>
                    <div class="text-gray-400 mb-4" id="uploadSubtext">Select files to process</div>
                    <input type="file" id="fileInput" class="hidden" multiple aria-label="File input">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-bold transition-colors" onclick="document.getElementById('fileInput').click()" aria-label="Choose files">Choose Files</button>
                </div>
            </div>

            <!-- File List -->
            <div class="file-list mb-6" id="fileList"></div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="convert-btn" id="convertBtn" disabled onclick="processTool()" aria-label="Process selected files">
                    <span class="button-text">Process Files</span>
                    <span class="loading-spinner">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <button onclick="closeTool()" class="cancel-btn" aria-label="Cancel and close">Cancel</button>
            </div>

            <!-- Progress -->
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-status">
                    <div class="status-text" id="status">Processing your files...</div>
                </div>
            </div>

            <!-- Usage Limits Display -->
            @guest
                <div class="usage-limit-display free-tier">
                    <p>Free Access: 3 conversions per day</p>
                    <p class="text-sm">Register for unlimited access and more features!</p>
                    <a href="{{ route('register') }}" class="register-btn">Register Now</a>
                </div>
            @else
                @php
                    $remaining = auth()->user()->getRemainingConversions();
                @endphp
                <div class="usage-limit-display {{ $remaining === -1 ? 'premium-tier' : ($remaining > 0 ? 'standard-tier' : 'limit-reached') }}">
                    @if($remaining === -1)
                        <p>Unlimited Conversions</p>
                        <p class="text-sm">Enjoy unlimited access with your premium plan!</p>
                    @elseif($remaining > 0)
                        <p>{{ $remaining }} conversions remaining this month</p>
                        <p class="text-sm">Upgrade for unlimited access!</p>
                    @else
                        <p>Conversion limit reached</p>
                        <p class="text-sm">Upgrade your plan to continue converting!</p>
                        <a href="{{ route('pricing') }}" class="upgrade-btn">Upgrade Plan</a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/pdf-converter.js') }}"></script>
@endsection