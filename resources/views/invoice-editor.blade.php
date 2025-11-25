@extends('layouts.frontend')

@section('styles')
<style>
.invoice-generator-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: #ffffff;
    padding: 2rem 0;
}

.invoice-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.invoice-content {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.invoice-header {
    text-align: center;
    margin-bottom: 3rem;
}

.invoice-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.invoice-subtitle {
    color: #b0b0b0;
    font-size: 1.2rem;
    max-width: 600px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.feature-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
    transition: all 0.3s ease;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 215, 0, 0.1);
    border-color: #ffd700;
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
}

.feature-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #ffd700;
}

.feature-description {
    color: #b0b0b0;
    line-height: 1.6;
}

.generator-form {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 3rem;
    border: 1px solid rgba(255, 215, 0, 0.2);
    margin-top: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    color: #ffd700;
    font-size: 1.5rem;
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
    min-height: 100px;
}

.add-item-btn {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #000;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.add-item-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
}

.generate-btn {
    background: linear-gradient(45deg, #22c55e, #16a34a);
    color: #ffffff;
    border: none;
    padding: 1rem 3rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
    margin: 2rem auto 0;
}

.generate-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
}

@media (max-width: 768px) {
    .invoice-container {
        padding: 0 1rem;
    }

    .invoice-content {
        padding: 2rem;
    }

    .invoice-title {
        font-size: 2.5rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="invoice-generator-page">
    <div class="invoice-container">
        <div class="invoice-content">
            <!-- Header -->
            <div class="invoice-header">
                <h1 class="invoice-title">Professional Invoice Generator</h1>
                <p class="invoice-subtitle">Create beautiful, professional invoices with customizable templates and PDF export</p>
            </div>

            <!-- Features -->
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-file-invoice feature-icon"></i>
                    <h3 class="feature-title">20+ Templates</h3>
                    <p class="feature-description">Choose from professional templates for different industries</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-qrcode feature-icon"></i>
                    <h3 class="feature-title">QR Codes</h3>
                    <p class="feature-description">Add payment QR codes and tracking information</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-dollar-sign feature-icon"></i>
                    <h3 class="feature-title">Multi-Currency</h3>
                    <p class="feature-description">Support for multiple currencies and tax calculations</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-file-pdf feature-icon"></i>
                    <h3 class="feature-title">PDF Export</h3>
                    <p class="feature-description">Export invoices as professional PDF documents</p>
                </div>
            </div>

            <!-- Invoice Form -->
            <div class="generator-form">
                <form id="invoiceForm">
                    <!-- Business Information -->
                    <div class="form-section">
                        <h3>Business Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-input" name="business_name" placeholder="Your Business Name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" name="business_email" placeholder="business@example.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <textarea class="form-textarea" name="business_address" placeholder="Business Address"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-input" name="business_phone" placeholder="+1 (555) 123-4567">
                            </div>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="form-section">
                        <h3>Client Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="form-input" name="client_name" placeholder="Client Name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Client Email</label>
                                <input type="email" class="form-input" name="client_email" placeholder="client@example.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Client Address</label>
                            <textarea class="form-textarea" name="client_address" placeholder="Client Address"></textarea>
                        </div>
                    </div>

                    <!-- Invoice Details -->
                    <div class="form-section">
                        <h3>Invoice Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-input" name="invoice_number" placeholder="INV-001">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-input" name="invoice_date" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-input" name="due_date">
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="form-section">
                        <h3>Invoice Items</h3>
                        <div id="itemsContainer">
                            <div class="form-row item-row">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-input" name="items[0][description]" placeholder="Item description">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-input" name="items[0][quantity]" placeholder="1" min="1">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Rate</label>
                                    <input type="number" class="form-input" name="items[0][rate]" placeholder="0.00" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-input" name="items[0][amount]" placeholder="0.00" step="0.01" readonly>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="add-item-btn" onclick="addItem()">+ Add Item</button>
                    </div>

                    <!-- Generate Button -->
                    <button type="submit" class="generate-btn">Generate Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let itemCount = 1;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const itemRow = document.createElement('div');
    itemRow.className = 'form-row item-row';
    itemRow.innerHTML = `
        <div class="form-group">
            <label class="form-label">Description</label>
            <input type="text" class="form-input" name="items[${itemCount}][description]" placeholder="Item description">
        </div>
        <div class="form-group">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-input" name="items[${itemCount}][quantity]" placeholder="1" min="1">
        </div>
        <div class="form-group">
            <label class="form-label">Rate</label>
            <input type="number" class="form-input" name="items[${itemCount}][rate]" placeholder="0.00" step="0.01">
        </div>
        <div class="form-group">
            <label class="form-label">Amount</label>
            <input type="number" class="form-input" name="items[${itemCount}][amount]" placeholder="0.00" step="0.01" readonly>
        </div>
    `;
    container.appendChild(itemRow);
    itemCount++;
}

document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Invoice generation feature coming soon! Your form data has been saved.');
});
</script>
@endsection