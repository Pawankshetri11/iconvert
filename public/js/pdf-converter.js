// PDF Converter JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializePDFConverter();
});

function initializePDFConverter() {
    // Initialize variables
    let selectedTool = null;
    let uploadedFiles = [];
    let isProcessing = false;

    // Tool configurations
    const toolConfigs = {
        'pdf-to-word': {
            title: 'PDF to Word Converter',
            description: 'Convert PDF files to editable Word documents',
            accept: '.pdf',
            multiple: false,
            maxSize: 50 * 1024 * 1024 // 50MB
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

    // Initialize drag and drop
    initializeDragAndDrop();

    // Tool selection handlers
    document.querySelectorAll('.tool-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tool = this.getAttribute('onclick').match(/selectTool\('([^']+)'\)/)[1];
            selectTool(tool);
        });
    });

    function selectTool(tool) {
        selectedTool = tool;
        const config = toolConfigs[tool];

        if (!config) {
            showMessage('Tool configuration not found', 'error');
            return;
        }

        // Update modal content
        document.getElementById('tool-title').textContent = config.title;
        document.getElementById('tool-description').textContent = config.description;

        // Update upload area
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const uploadSubtext = document.getElementById('uploadSubtext');

        fileInput.accept = config.accept;
        fileInput.multiple = config.multiple;

        const maxSizeMB = Math.round(config.maxSize / (1024 * 1024));
        uploadSubtext.textContent = `Select ${config.multiple ? 'files' : 'a file'} (max ${maxSizeMB}MB)`;

        // Generate tool options
        const toolOptions = document.getElementById('tool-options');
        toolOptions.innerHTML = '';

        if (config.options) {
            config.options.forEach(option => {
                const optionGroup = document.createElement('div');
                optionGroup.className = 'tool-option-group';

                const label = document.createElement('label');
                label.textContent = option.label;
                if (option.required) {
                    label.innerHTML += ' <span style="color: #ef4444;">*</span>';
                }
                optionGroup.appendChild(label);

                let input;
                switch (option.type) {
                    case 'select':
                        input = document.createElement('select');
                        input.name = option.name;
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
                        break;
                    case 'checkbox':
                        input = document.createElement('input');
                        input.type = 'checkbox';
                        input.name = option.name;
                        input.value = '1';
                        const checkboxWrapper = document.createElement('div');
                        checkboxWrapper.className = 'checkbox-item';
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
        }

        // Clear file list
        uploadedFiles = [];
        updateFileList();

        // Show modal
        document.getElementById('tool-modal').classList.remove('hidden');
    }

    function closeTool() {
        document.getElementById('tool-modal').classList.add('hidden');
        selectedTool = null;
        uploadedFiles = [];
        updateFileList();
        hideProgress();
    }

    function initializeDragAndDrop() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');

        // Drag and drop events
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

    function handleFiles(files) {
        if (!selectedTool) return;

        const config = toolConfigs[selectedTool];
        const maxSize = config.maxSize;
        const accept = config.accept.split(',').map(ext => ext.trim().toLowerCase());

        [...files].forEach(file => {
            // Check file size
            if (file.size > maxSize) {
                showMessage(`File "${file.name}" is too large. Maximum size is ${Math.round(maxSize / (1024 * 1024))}MB.`, 'error');
                return;
            }

            // Check file type
            const fileExt = '.' + file.name.split('.').pop().toLowerCase();
            if (!accept.includes(fileExt) && !accept.includes('*')) {
                showMessage(`File type "${fileExt}" is not supported for this tool.`, 'error');
                return;
            }

            // Check for duplicates
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
            fileList.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 20px;">No files selected</p>';
            convertBtn.disabled = true;
            return;
        }

        uploadedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';

            const fileExt = file.name.split('.').pop().toLowerCase();
            const fileIconClass = `file-type-${fileExt}`;

            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon ${fileIconClass}">
                        <span>${getFileIcon(fileExt)}</span>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                </div>
                <button class="remove-file" onclick="removeFile(${index})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;

            fileList.appendChild(fileItem);
        });

        // Check minimum files requirement
        const config = toolConfigs[selectedTool];
        const minFiles = config.minFiles || 1;
        convertBtn.disabled = uploadedFiles.length < minFiles || isProcessing;
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
        if (!selectedTool || uploadedFiles.length === 0 || isProcessing) return;

        isProcessing = true;
        const convertBtn = document.getElementById('convertBtn');
        convertBtn.classList.add('processing');
        convertBtn.disabled = true;
        showProgress();

        const formData = new FormData();
        formData.append('tool', selectedTool);

        // Add files
        uploadedFiles.forEach(file => {
            formData.append('files[]', file);
        });

        // Add tool options
        const config = toolConfigs[selectedTool];
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

        // Add CSRF token
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
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;

            // Get filename from response headers or generate one
            const contentDisposition = response.headers.get('Content-Disposition');
            let filename = 'converted_file';
            if (contentDisposition) {
                const matches = contentDisposition.match(/filename="([^"]+)"/);
                if (matches) filename = matches[1];
            } else {
                // Generate filename based on tool and original file
                const originalName = uploadedFiles[0].name;
                const baseName = originalName.substring(0, originalName.lastIndexOf('.'));
                const ext = getOutputExtension(selectedTool);
                filename = `${baseName}_converted.${ext}`;
            }

            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            showMessage('File processed successfully!', 'success');
            closeTool();
        })
        .catch(error => {
            console.error('Processing error:', error);
            showMessage(error.message || 'An error occurred while processing the file.', 'error');
        })
        .finally(() => {
            isProcessing = false;
            const convertBtn = document.getElementById('convertBtn');
            convertBtn.classList.remove('processing');
            hideProgress();
            updateFileList();
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
        const status = document.getElementById('status');

        progressContainer.classList.remove('hidden');
        progressFill.style.width = '0%';
        status.textContent = 'Processing your files...';

        // Simulate progress (in a real app, you'd get progress from server)
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressFill.style.width = progress + '%';

            if (!isProcessing) {
                clearInterval(interval);
                progressFill.style.width = '100%';
                status.textContent = 'Complete!';
            }
        }, 500);
    }

    function hideProgress() {
        const progressContainer = document.getElementById('progressContainer');
        progressContainer.classList.add('hidden');
    }

    function showMessage(message, type = 'info') {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => msg.remove());

        const messageEl = document.createElement('div');
        messageEl.className = `message ${type} slide-up`;
        messageEl.textContent = message;
        messageEl.setAttribute('role', 'alert');
        messageEl.setAttribute('aria-live', 'assertive');

        const container = document.getElementById('messages-container');
        container.appendChild(messageEl);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.remove();
            }
        }, 5000);
    }

    // Global functions for HTML onclick handlers
    window.selectTool = selectTool;
    window.closeTool = closeTool;
    window.removeFile = removeFile;
    window.processTool = processTool;
}