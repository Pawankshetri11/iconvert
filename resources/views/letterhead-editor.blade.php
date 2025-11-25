@extends('layouts.frontend')

@section('styles')
<style>
.letterhead-maker-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: #ffffff;
    padding: 2rem 0;
}

.letterhead-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.letterhead-content {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.letterhead-header {
    text-align: center;
    margin-bottom: 3rem;
}

.letterhead-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.letterhead-subtitle {
    color: #b0b0b0;
    font-size: 1.2rem;
    max-width: 600px;
    margin: 0 auto;
}

.maker-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    margin-top: 2rem;
}

.maker-form {
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

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    color: #e0e0e0;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-input, .form-textarea, .form-select {
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
    min-height: 80px;
}

.logo-upload {
    border: 2px dashed rgba(255, 215, 0, 0.3);
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 1rem;
}

.logo-upload:hover {
    border-color: #ffd700;
    background: rgba(255, 215, 0, 0.05);
}

.logo-upload-icon {
    font-size: 3rem;
    color: #ffd700;
    margin-bottom: 1rem;
}

.logo-upload-text {
    color: #b0b0b0;
    margin-bottom: 0.5rem;
}

.logo-upload-btn {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #000;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logo-upload-btn:hover {
    transform: scale(1.05);
}

.logo-input {
    display: none;
}

.templates {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.template-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 8px;
    padding: 1rem;
    color: #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.template-btn:hover, .template-btn.active {
    border-color: #ffd700;
    background: rgba(255, 215, 0, 0.1);
    color: #ffd700;
}

.generate-btn {
    background: linear-gradient(45deg, #22c55e, #16a34a);
    color: #ffffff;
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
    box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
}

.letterhead-preview {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
    position: sticky;
    top: 2rem;
}

.preview-title {
    color: #ffd700;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    text-align: center;
}

.letterhead-sample {
    width: 100%;
    aspect-ratio: 1.4;
    background: #ffffff;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    color: #333;
    font-family: 'Times New Roman', serif;
}

.sample-header {
    border-bottom: 2px solid #333;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
    text-align: center;
}

.company-name {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.company-tagline {
    font-style: italic;
    color: #666;
}

.contact-info {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 2rem;
}

.logo-placeholder {
    width: 100px;
    height: 60px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 0.8rem;
    float: right;
    margin-left: 1rem;
}

@media (max-width: 768px) {
    .letterhead-container {
        padding: 0 1rem;
    }

    .letterhead-content {
        padding: 2rem;
    }

    .letterhead-title {
        font-size: 2.5rem;
    }

    .maker-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .templates {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection

@section('content')
<div class="letterhead-maker-page">
    <div class="letterhead-container">
        <div class="letterhead-content">
            <!-- Header -->
            <div class="letterhead-header">
                <h1 class="letterhead-title">Letterhead Maker</h1>
                <p class="letterhead-subtitle">Create professional letterheads with custom branding and company information</p>
            </div>

            <div class="maker-layout">
                <!-- Form Section -->
                <div class="maker-form">
                    <form id="letterheadForm">
                        <!-- Template Selection -->
                        <div class="form-section">
                            <h3>Choose Template</h3>
                            <div class="templates">
                                <button type="button" class="template-btn active" data-template="classic">Classic</button>
                                <button type="button" class="template-btn" data-template="modern">Modern</button>
                                <button type="button" class="template-btn" data-template="elegant">Elegant</button>
                                <button type="button" class="template-btn" data-template="corporate">Corporate</button>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="form-section">
                            <h3>Company Information</h3>
                            <div class="form-group">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-input" name="company_name" placeholder="Your Company Name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tagline/Slogan</label>
                                <input type="text" class="form-input" name="tagline" placeholder="Your company tagline">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" name="email" placeholder="info@company.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-input" name="phone" placeholder="+1 (555) 123-4567">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <textarea class="form-textarea" name="address" placeholder="123 Business St, City, State, ZIP"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-input" name="website" placeholder="https://www.company.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tax ID</label>
                                    <input type="text" class="form-input" name="tax_id" placeholder="Tax ID or Registration Number">
                                </div>
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="form-section">
                            <h3>Company Logo</h3>
                            <div class="logo-upload" onclick="document.getElementById('logoInput').click()">
                                <i class="fas fa-building logo-upload-icon"></i>
                                <div class="logo-upload-text">Click to upload company logo</div>
                                <div style="color: #888; font-size: 0.9rem;">PNG, JPG, SVG, Max 2MB</div>
                                <input type="file" id="logoInput" class="logo-input" accept="image/*">
                            </div>
                        </div>

                        <!-- Customization -->
                        <div class="form-section">
                            <h3>Customization</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-input" name="primary_color" value="#ffd700">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Font Style</label>
                                    <select class="form-select" name="font_style">
                                        <option value="serif">Serif (Classic)</option>
                                        <option value="sans-serif">Sans Serif (Modern)</option>
                                        <option value="script">Script (Elegant)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="generate-btn">Generate Letterhead</button>
                    </form>
                </div>

                <!-- Preview Section -->
                <div class="letterhead-preview">
                    <h3 class="preview-title">Live Preview</h3>
                    <div class="letterhead-sample">
                        <div class="sample-header">
                            <div class="logo-placeholder">Logo</div>
                            <div class="company-name" id="preview-company">Your Company Name</div>
                            <div class="company-tagline" id="preview-tagline">Your company tagline</div>
                            <div class="contact-info">
                                <span id="preview-email">info@company.com</span>
                                <span id="preview-phone">+1 (555) 123-4567</span>
                                <span id="preview-website">www.company.com</span>
                            </div>
                        </div>
                        <div style="margin-top: 2rem;">
                            <p style="font-size: 0.9rem; color: #666;">[Your letter content goes here...]</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('letterheadForm');
    const templates = document.querySelectorAll('.template-btn');

    // Template selection
    templates.forEach(btn => {
        btn.addEventListener('click', function() {
            templates.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Live preview updates
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    function updatePreview() {
        const formData = new FormData(form);
        document.getElementById('preview-company').textContent = formData.get('company_name') || 'Your Company Name';
        document.getElementById('preview-tagline').textContent = formData.get('tagline') || 'Your company tagline';
        document.getElementById('preview-email').textContent = formData.get('email') || 'info@company.com';
        document.getElementById('preview-phone').textContent = formData.get('phone') || '+1 (555) 123-4567';
        document.getElementById('preview-website').textContent = formData.get('website') || 'www.company.com';
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Letterhead generation feature coming soon! Your design has been saved.');
    });
});
</script>
@endsection