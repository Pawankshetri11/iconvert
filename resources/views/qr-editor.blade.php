@extends('layouts.frontend')

@section('styles')
<style>
.qr-generator-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: #ffffff;
    padding: 2rem 0;
}

.qr-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.qr-content {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.qr-header {
    text-align: center;
    margin-bottom: 3rem;
}

.qr-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.qr-subtitle {
    color: #b0b0b0;
    font-size: 1.2rem;
    max-width: 600px;
    margin: 0 auto;
}

.generator-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.generator-form {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    color: #ffd700;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 215, 0, 0.3);
    padding-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: #e0e0e0;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 8px;
    padding: 0.75rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none;
    border-color: #ffd700;
    box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.qr-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.qr-type-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 8px;
    padding: 1rem;
    color: #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.qr-type-btn:hover, .qr-type-btn.active {
    border-color: #ffd700;
    background: rgba(255, 215, 0, 0.1);
    color: #ffd700;
}

.generate-btn {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #000;
    border: none;
    padding: 1rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.generate-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
}

.qr-preview {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
    text-align: center;
}

.qr-placeholder {
    width: 256px;
    height: 256px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px dashed rgba(255, 215, 0, 0.3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: #b0b0b0;
    font-size: 1.2rem;
}

.qr-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.download-btn {
    background: linear-gradient(45deg, #22c55e, #16a34a);
    color: #ffffff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.download-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
}

@media (max-width: 768px) {
    .qr-container {
        padding: 0 1rem;
    }

    .qr-content {
        padding: 2rem;
    }

    .qr-title {
        font-size: 2.5rem;
    }

    .generator-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .qr-types {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection

@section('content')
<div class="qr-generator-page">
    <div class="qr-container">
        <div class="qr-content">
            <!-- Header -->
            <div class="qr-header">
                <h1 class="qr-title">QR Code Generator</h1>
                <p class="qr-subtitle">Generate QR codes for URLs, WiFi, payments, and more with custom styling</p>
            </div>

            <div class="generator-layout">
                <!-- Form Section -->
                <div class="generator-form">
                    <form id="qrForm">
                        <!-- QR Type Selection -->
                        <div class="form-section">
                            <h3>QR Code Type</h3>
                            <div class="qr-types">
                                <button type="button" class="qr-type-btn active" data-type="url">URL</button>
                                <button type="button" class="qr-type-btn" data-type="text">Text</button>
                                <button type="button" class="qr-type-btn" data-type="wifi">WiFi</button>
                                <button type="button" class="qr-type-btn" data-type="phone">Phone</button>
                                <button type="button" class="qr-type-btn" data-type="email">Email</button>
                                <button type="button" class="qr-type-btn" data-type="sms">SMS</button>
                            </div>
                        </div>

                        <!-- Dynamic Content Fields -->
                        <div id="contentFields">
                            <div class="form-section">
                                <h3>Content</h3>
                                <div class="form-group">
                                    <label class="form-label">URL</label>
                                    <input type="url" class="form-input" id="urlInput" placeholder="https://example.com" required>
                                </div>
                            </div>
                        </div>

                        <!-- Customization -->
                        <div class="form-section">
                            <h3>Customization</h3>
                            <div class="form-group">
                                <label class="form-label">Size</label>
                                <select class="form-select" id="sizeSelect">
                                    <option value="256">256x256 (Default)</option>
                                    <option value="128">128x128</option>
                                    <option value="512">512x512</option>
                                    <option value="1024">1024x1024</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Error Correction</label>
                                <select class="form-select" id="errorSelect">
                                    <option value="L">Low (7%)</option>
                                    <option value="M" selected>Medium (15%)</option>
                                    <option value="Q">Quartile (25%)</option>
                                    <option value="H">High (30%)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="generate-btn">Generate QR Code</button>
                    </form>
                </div>

                <!-- Preview Section -->
                <div class="qr-preview">
                    <h3 style="color: #ffd700; margin-bottom: 1rem;">QR Code Preview</h3>
                    <div class="qr-placeholder" id="qrPlaceholder">
                        <div>
                            <i class="fas fa-qrcode" style="font-size: 3rem; margin-bottom: 1rem; color: #ffd700;"></i>
                            <div>Your QR code will appear here</div>
                        </div>
                    </div>
                    <div class="qr-actions">
                        <button class="download-btn" id="downloadPNG" disabled>Download PNG</button>
                        <button class="download-btn" id="downloadSVG" disabled>Download SVG</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrTypes = document.querySelectorAll('.qr-type-btn');
    const contentFields = document.getElementById('contentFields');
    const qrForm = document.getElementById('qrForm');

    // QR Type selection
    qrTypes.forEach(btn => {
        btn.addEventListener('click', function() {
            qrTypes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateContentFields(this.dataset.type);
        });
    });

    function updateContentFields(type) {
        let fields = '';

        switch(type) {
            case 'url':
                fields = `
                    <div class="form-section">
                        <h3>Content</h3>
                        <div class="form-group">
                            <label class="form-label">URL</label>
                            <input type="url" class="form-input" id="urlInput" placeholder="https://example.com" required>
                        </div>
                    </div>
                `;
                break;
            case 'text':
                fields = `
                    <div class="form-section">
                        <h3>Content</h3>
                        <div class="form-group">
                            <label class="form-label">Text</label>
                            <textarea class="form-textarea" id="textInput" placeholder="Enter your text here..." required></textarea>
                        </div>
                    </div>
                `;
                break;
            case 'wifi':
                fields = `
                    <div class="form-section">
                        <h3>WiFi Network</h3>
                        <div class="form-group">
                            <label class="form-label">Network Name (SSID)</label>
                            <input type="text" class="form-input" id="wifiSSID" placeholder="MyWiFiNetwork" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-input" id="wifiPassword" placeholder="WiFiPassword">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Security Type</label>
                            <select class="form-select" id="wifiSecurity">
                                <option value="WPA">WPA/WPA2</option>
                                <option value="WEP">WEP</option>
                                <option value="nopass">No Password</option>
                            </select>
                        </div>
                    </div>
                `;
                break;
            case 'phone':
                fields = `
                    <div class="form-section">
                        <h3>Content</h3>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-input" id="phoneInput" placeholder="+1234567890" required>
                        </div>
                    </div>
                `;
                break;
            case 'email':
                fields = `
                    <div class="form-section">
                        <h3>Email</h3>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-input" id="emailInput" placeholder="example@email.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subject (Optional)</label>
                            <input type="text" class="form-input" id="emailSubject" placeholder="Email subject">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Body (Optional)</label>
                            <textarea class="form-textarea" id="emailBody" placeholder="Email body..."></textarea>
                        </div>
                    </div>
                `;
                break;
            case 'sms':
                fields = `
                    <div class="form-section">
                        <h3>SMS</h3>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-input" id="smsPhone" placeholder="+1234567890" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message (Optional)</label>
                            <textarea class="form-textarea" id="smsMessage" placeholder="Your SMS message..."></textarea>
                        </div>
                    </div>
                `;
                break;
        }

        contentFields.innerHTML = fields;
    }

    // Form submission
    qrForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('QR Code generation feature coming soon! Your settings have been saved.');
    });
});
</script>
@endsection