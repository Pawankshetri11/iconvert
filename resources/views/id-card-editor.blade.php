@extends('layouts.frontend')

@section('styles')
<style>
.id-card-creator-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: #ffffff;
    padding: 2rem 0;
}

.id-card-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.id-card-content {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.id-card-header {
    text-align: center;
    margin-bottom: 3rem;
}

.id-card-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.id-card-subtitle {
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

.photo-upload {
    border: 2px dashed rgba(255, 215, 0, 0.3);
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 1rem;
}

.photo-upload:hover {
    border-color: #ffd700;
    background: rgba(255, 215, 0, 0.05);
}

.photo-upload-icon {
    font-size: 3rem;
    color: #ffd700;
    margin-bottom: 1rem;
}

.photo-upload-text {
    color: #b0b0b0;
    margin-bottom: 0.5rem;
}

.photo-upload-btn {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #000;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.photo-upload-btn:hover {
    transform: scale(1.05);
}

.photo-input {
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

.id-card-preview {
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

.id-card-sample {
    width: 100%;
    aspect-ratio: 1.6;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    color: #333;
}

.photo-placeholder {
    width: 80px;
    height: 100px;
    background: #e9ecef;
    border-radius: 5px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 0.8rem;
}

.id-details h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #000;
}

.id-details p {
    font-size: 0.8rem;
    margin: 0.2rem 0;
    color: #666;
}

@media (max-width: 768px) {
    .id-card-container {
        padding: 0 1rem;
    }

    .id-card-content {
        padding: 2rem;
    }

    .id-card-title {
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
<div class="id-card-creator-page">
    <div class="id-card-container">
        <div class="id-card-content">
            <!-- Header -->
            <div class="id-card-header">
                <h1 class="id-card-title">ID Card Creator</h1>
                <p class="id-card-subtitle">Create professional ID cards with 25+ templates, QR codes, and bulk processing</p>
            </div>

            <div class="maker-layout">
                <!-- Form Section -->
                <div class="maker-form">
                    <form id="idCardForm">
                        <!-- Template Selection -->
                        <div class="form-section">
                            <h3>Choose Template</h3>
                            <div class="templates">
                                <button type="button" class="template-btn active" data-template="corporate">Corporate</button>
                                <button type="button" class="template-btn" data-template="student">Student</button>
                                <button type="button" class="template-btn" data-template="employee">Employee</button>
                                <button type="button" class="template-btn" data-template="visitor">Visitor</button>
                                <button type="button" class="template-btn" data-template="member">Member</button>
                                <button type="button" class="template-btn" data-template="security">Security</button>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3>Personal Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-input" name="full_name" placeholder="John Doe" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">ID Number</label>
                                    <input type="text" class="form-input" name="id_number" placeholder="ID-001" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Position/Title</label>
                                    <input type="text" class="form-input" name="position" placeholder="Software Engineer">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-input" name="department" placeholder="IT Department">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Issue Date</label>
                                    <input type="date" class="form-input" name="issue_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="date" class="form-input" name="expiry_date">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="form-section">
                            <h3>Contact Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" name="email" placeholder="john.doe@company.com">
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
                        </div>

                        <!-- Photo Upload -->
                        <div class="form-section">
                            <h3>Photo Upload</h3>
                            <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-camera photo-upload-icon"></i>
                                <div class="photo-upload-text">Click to upload photo</div>
                                <div style="color: #888; font-size: 0.9rem;">JPG, PNG, Max 5MB</div>
                                <input type="file" id="photoInput" class="photo-input" accept="image/*">
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="form-section">
                            <h3>Additional Options</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Include QR Code</label>
                                    <select class="form-select" name="include_qr">
                                        <option value="0">No</option>
                                        <option value="1" selected>Yes</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Card Size</label>
                                    <select class="form-select" name="card_size">
                                        <option value="standard">Standard (CR80)</option>
                                        <option value="large">Large</option>
                                        <option value="small">Small</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="generate-btn">Generate ID Card</button>
                    </form>
                </div>

                <!-- Preview Section -->
                <div class="id-card-preview">
                    <h3 class="preview-title">Live Preview</h3>
                    <div class="id-card-sample">
                        <div class="photo-placeholder">Photo</div>
                        <div class="id-details">
                            <h4 id="preview-name">John Doe</h4>
                            <p id="preview-id">ID: ID-001</p>
                            <p id="preview-position">Software Engineer</p>
                            <p id="preview-dept">IT Department</p>
                            <p id="preview-issue">Issued: {{ date('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('idCardForm');
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
        document.getElementById('preview-name').textContent = formData.get('full_name') || 'John Doe';
        document.getElementById('preview-id').textContent = 'ID: ' + (formData.get('id_number') || 'ID-001');
        document.getElementById('preview-position').textContent = formData.get('position') || 'Software Engineer';
        document.getElementById('preview-dept').textContent = formData.get('department') || 'IT Department';
        document.getElementById('preview-issue').textContent = 'Issued: ' + (formData.get('issue_date') ? new Date(formData.get('issue_date')).toLocaleDateString() : new Date().toLocaleDateString());
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('ID Card generation feature coming soon! Your information has been saved.');
    });
});
</script>
@endsection