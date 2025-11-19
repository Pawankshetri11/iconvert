<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Converter Pro - Luxury Edition</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 3rem;
            font-weight: 300;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #b0b0b0;
            font-weight: 300;
        }

        .upload-area {
            border: 2px dashed rgba(255, 215, 0, 0.3);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.02);
        }

        .conversion-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .conversion-option {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .conversion-option:hover,
        .conversion-option.selected {
            border-color: #ffd700;
            background: rgba(255, 215, 0, 0.1);
        }

        .conversion-option input[type="radio"] {
            display: none;
        }

        .conversion-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }

        .conversion-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #e0e0e0;
        }

        .upload-area:hover {
            border-color: #ffd700;
            background: rgba(255, 215, 0, 0.05);
        }

        .upload-area.dragover {
            border-color: #ffd700;
            background: rgba(255, 215, 0, 0.1);
        }

        .upload-icon {
            font-size: 4rem;
            color: #ffd700;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .upload-text {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #e0e0e0;
        }

        .upload-subtext {
            font-size: 0.9rem;
            color: #888;
        }

        .file-input {
            display: none;
        }

        .upload-btn {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        .convert-btn {
            background: linear-gradient(45deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .convert-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.5);
        }

        .convert-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .file-list {
            margin-top: 20px;
        }

        .file-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .file-name {
            font-weight: 500;
            color: #e0e0e0;
        }

        .file-size {
            color: #888;
            font-size: 0.9rem;
        }

        .remove-file {
            background: #dc2626;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .progress-container {
            margin-top: 30px;
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

        .status {
            text-align: center;
            margin-top: 15px;
            font-weight: 500;
            color: #b0b0b0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .title {
                font-size: 2rem;
            }

            .upload-area {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">PDF CONVERTER PRO</h1>
            <p class="subtitle">Transform your documents with elegance</p>
        </div>

        <div class="conversion-selector">
            <label class="conversion-option selected" for="html-to-pdf">
                <input type="radio" id="html-to-pdf" name="conversionType" value="html-to-pdf" checked>
                <span class="conversion-icon">📄</span>
                <div class="conversion-name">HTML to PDF</div>
            </label>
            <label class="conversion-option" for="pdf-to-word">
                <input type="radio" id="pdf-to-word" name="conversionType" value="pdf-to-word">
                <span class="conversion-icon">📝</span>
                <div class="conversion-name">PDF to Word</div>
            </label>
            <label class="conversion-option" for="pdf-to-text">
                <input type="radio" id="pdf-to-text" name="conversionType" value="pdf-to-text">
                <span class="conversion-icon">📃</span>
                <div class="conversion-name">PDF to Text</div>
            </label>
            <label class="conversion-option" for="images-to-pdf">
                <input type="radio" id="images-to-pdf" name="conversionType" value="images-to-pdf">
                <span class="conversion-icon">🖼️</span>
                <div class="conversion-name">Images to PDF</div>
            </label>
        </div>

        <div class="upload-area" id="uploadArea">
            <div class="upload-icon">📄</div>
            <div class="upload-text">Drop your files here or click to browse</div>
            <div class="upload-subtext" id="uploadSubtext">Supports HTML, TXT files</div>
            <input type="file" id="fileInput" class="file-input" multiple accept=".html,.txt">
            <button class="upload-btn" onclick="document.getElementById('fileInput').click()">Choose Files</button>
        </div>

        <div class="file-list" id="fileList"></div>

        <button class="convert-btn" id="convertBtn" disabled onclick="convertFiles()">Convert to PDF</button>

        <div class="progress-container" id="progressContainer">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="status" id="status">Converting...</div>
        </div>
    </div>

    <script>
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
                'html-to-pdf': {
                    accept: '.html,.htm,.txt',
                    text: 'Supports HTML, HTM, TXT files'
                },
                'pdf-to-word': {
                    accept: '.pdf',
                    text: 'Supports PDF files'
                },
                'pdf-to-text': {
                    accept: '.pdf',
                    text: 'Supports PDF files'
                },
                'images-to-pdf': {
                    accept: '.jpg,.jpeg,.png,.gif,.bmp,.tiff,.webp',
                    text: 'Supports JPG, PNG, GIF, BMP, TIFF, WEBP images'
                }
            };

            const currentConfig = config[selectedConversionType];
            fileInput.accept = currentConfig.accept;
            uploadSubtext.textContent = currentConfig.text;

            // Clear selected files when changing conversion type
            selectedFiles = [];
            updateFileList();
        }

        // Drag and drop functionality
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
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
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <div>
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                    <button class="remove-file" onclick="removeFile(${index})">Remove</button>
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

            progressContainer.style.display = 'block';
            convertBtn.disabled = true;
            status.textContent = 'Uploading files...';

            const formData = new FormData();
            formData.append('conversion_type', selectedConversionType);
            selectedFiles.forEach((file, index) => {
                formData.append('files[]', file);
            });

            fetch('/pdf-converter/convert', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    if (response.headers.get('content-type') === 'application/pdf') {
                        // Single PDF file
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
                        // Multiple files or JSON response
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
            progressContainer.style.display = 'none';
            progressFill.style.width = '0%';
            convertBtn.disabled = false;
        }
    </script>
</body>
</html>