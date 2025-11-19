@extends('layouts.frontend')

@section('content')
<div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-light text-yellow-500 mb-2">PDF CONVERTER PRO</h1>
                    <p class="text-gray-600">Transform your documents with elegance</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <label class="conversion-option bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-yellow-400 transition" for="html-to-pdf">
                        <input type="radio" id="html-to-pdf" name="conversionType" value="html-to-pdf" checked class="hidden">
                        <span class="text-2xl mb-2 block">📄</span>
                        <div class="font-semibold text-gray-700">HTML to PDF</div>
                    </label>
                    <label class="conversion-option bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-yellow-400 transition" for="pdf-to-word">
                        <input type="radio" id="pdf-to-word" name="conversionType" value="pdf-to-word" class="hidden">
                        <span class="text-2xl mb-2 block">📝</span>
                        <div class="font-semibold text-gray-700">PDF to Word</div>
                    </label>
                    <label class="conversion-option bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-yellow-400 transition" for="pdf-to-text">
                        <input type="radio" id="pdf-to-text" name="conversionType" value="pdf-to-text" class="hidden">
                        <span class="text-2xl mb-2 block">📃</span>
                        <div class="font-semibold text-gray-700">PDF to Text</div>
                    </label>
                    <label class="conversion-option bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-yellow-400 transition" for="images-to-pdf">
                        <input type="radio" id="images-to-pdf" name="conversionType" value="images-to-pdf" class="hidden">
                        <span class="text-2xl mb-2 block">🖼️</span>
                        <div class="font-semibold text-gray-700">Images to PDF</div>
                    </label>
                </div>

                <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-400 transition" id="uploadArea">
                    <div class="text-6xl text-yellow-500 mb-4">📄</div>
                    <div class="text-xl text-gray-700 mb-2">Drop your files here or click to browse</div>
                    <div class="text-gray-500 mb-4" id="uploadSubtext">Supports HTML, TXT files</div>
                    <input type="file" id="fileInput" class="hidden" multiple accept=".html,.txt">
                    <button class="bg-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition" onclick="document.getElementById('fileInput').click()">Choose Files</button>
                </div>

                <div class="file-list mt-4" id="fileList"></div>

                <button class="convert-btn bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition mt-8 disabled:opacity-50 disabled:cursor-not-allowed" id="convertBtn" disabled onclick="convertFiles()">Convert to PDF</button>

                <div class="progress-container mt-8 hidden" id="progressContainer">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" id="progressFill" style="width: 0%"></div>
                    </div>
                    <div class="text-center mt-4 text-gray-600" id="status">Converting...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PDF Converter JavaScript (simplified from original)
        let selectedFiles = [];
        let selectedConversionType = 'html-to-pdf';

        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');
        const convertBtn = document.getElementById('convertBtn');
        const progressContainer = document.getElementById('progressContainer');
        const progressFill = document.getElementById('progressFill');
        const status = document.getElementById('status');
        const uploadSubtext = document.getElementById('uploadSubtext');
        const conversionOptions = document.querySelectorAll('.conversion-option');

        // Conversion type selection
        conversionOptions.forEach(option => {
            option.addEventListener('click', function() {
                conversionOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
                selectedConversionType = this.querySelector('input[type="radio"]').value;
                updateFileInputForConversionType();
            });
        });

        function updateFileInputForConversionType() {
            const config = {
                'html-to-pdf': { accept: '.html,.htm,.txt', text: 'Supports HTML, HTM, TXT files' },
                'pdf-to-word': { accept: '.pdf', text: 'Supports PDF files' },
                'pdf-to-text': { accept: '.pdf', text: 'Supports PDF files' },
                'images-to-pdf': { accept: '.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp', text: 'Supports JPG, PNG, GIF, BMP, TIFF, WEBP images' }
            };

            const currentConfig = config[selectedConversionType];
            fileInput.accept = currentConfig.accept;
            uploadSubtext.textContent = currentConfig.text;

            selectedFiles = [];
            updateFileList();
        }

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-yellow-400');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-yellow-400');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-yellow-400');
            const files = Array.from(e.dataTransfer.files);
            addFiles(files);
        });

        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            addFiles(files);
        });

        function addFiles(files) {
            files.forEach(file => {
                if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });
            updateFileList();
        }

        function updateFileList() {
            fileList.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'bg-gray-50 p-4 rounded-lg mb-2 flex justify-between items-center';
                fileItem.innerHTML = `
                    <div>
                        <div class="font-medium text-gray-900">${file.name}</div>
                        <div class="text-gray-500 text-sm">${formatFileSize(file.size)}</div>
                    </div>
                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600" onclick="removeFile(${index})">Remove</button>
                `;
                fileList.appendChild(fileItem);
            });
            convertBtn.disabled = selectedFiles.length === 0;
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function convertFiles() {
            if (selectedFiles.length === 0) return;

            progressContainer.classList.remove('hidden');
            convertBtn.disabled = true;
            status.textContent = 'Uploading files...';

            const formData = new FormData();
            formData.append('conversion_type', selectedConversionType);
            selectedFiles.forEach((file, index) => {
                formData.append('files[]', file);
            });

            fetch('/pdf-converter/convert', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    if (response.headers.get('content-type') === 'application/pdf') {
                        return response.blob().then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = selectedFiles[0].name.replace(/\.[^/.]+$/, '') + '.pdf';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            status.textContent = 'Download started!';
                            setTimeout(() => resetForm(), 2000);
                        });
                    } else {
                        return response.json().then(data => {
                            status.textContent = data.message || 'Conversion completed!';
                            setTimeout(() => resetForm(), 2000);
                        });
                    }
                } else {
                    throw new Error('Conversion failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                status.textContent = 'Conversion failed. Please try again.';
                convertBtn.disabled = false;
            });
        }

        function resetForm() {
            selectedFiles = [];
            updateFileList();
            progressContainer.classList.add('hidden');
            progressFill.style.width = '0%';
            convertBtn.disabled = false;
        }
    </script>
@endsection