<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Royal SaaS Platform</title>
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
        }

        .header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 300;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            color: #e0e0e0;
        }

        .logout-btn {
            background: rgba(220, 38, 38, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(220, 38, 38, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(220, 38, 38, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: #b0b0b0;
            font-size: 1.2rem;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .tool-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.1);
            border-color: #ffd700;
        }

        .tool-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .tool-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #ffd700;
        }

        .tool-description {
            color: #b0b0b0;
            line-height: 1.6;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .stats-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #ffd700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">ROYAL SAAS</div>
        <div class="user-menu">
            <span class="user-name">Welcome, {{ Auth::user()->name }}!</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <div class="container">
        <section class="welcome-section">
            <h1 class="welcome-title">Welcome to Your Dashboard</h1>
            <p class="welcome-subtitle">Access your premium tools and manage your account</p>
        </section>

        <section class="tools-grid">
            <a href="{{ route('pdf-converter') }}" class="tool-card">
                <span class="tool-icon">📄</span>
                <h3 class="tool-title">PDF Converter Pro</h3>
                <p class="tool-description">Convert documents to PDF with professional quality. Supports HTML, text, and various formats with luxury styling.</p>
            </a>

            <div class="tool-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="tool-icon">🖼️</span>
                <h3 class="tool-title">Image Converter Suite</h3>
                <p class="tool-description">Convert between JPG, PNG, WEBP, and more. Resize, compress, and optimize your images. (Coming Soon)</p>
            </div>

            <div class="tool-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="tool-icon">🎵</span>
                <h3 class="tool-title">Audio Converter Suite</h3>
                <p class="tool-description">Convert MP3, WAV, AAC, FLAC with bitrate control and audio editing features. (Coming Soon)</p>
            </div>

            <div class="tool-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="tool-icon">📋</span>
                <h3 class="tool-title">Invoice Generator</h3>
                <p class="tool-description">Create professional invoices with 20+ luxury templates, QR codes, and PDF export. (Coming Soon)</p>
            </div>

            <div class="tool-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="tool-icon">🏷️</span>
                <h3 class="tool-title">QR Code Generator</h3>
                <p class="tool-description">Generate QR codes for URLs, WiFi, payments with custom styling and bulk creation. (Coming Soon)</p>
            </div>

            <div class="tool-card" style="opacity: 0.6; cursor: not-allowed;">
                <span class="tool-icon">🆔</span>
                <h3 class="tool-title">ID Card Generator</h3>
                <p class="tool-description">Create professional ID cards with 25+ templates, QR codes, and bulk processing. (Coming Soon)</p>
            </div>
        </section>

        <section class="stats-section">
            <h2 class="stats-title">Your Usage Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ Auth::user()->usageLogs()->where('addon_slug', 'pdf-converter')->where('success', true)->count() }}</div>
                    <div class="stat-label">PDF Conversions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        @php
                            $remaining = Auth::user()->getRemainingConversions();
                            echo $remaining === -1 ? '∞' : $remaining;
                        @endphp
                    </div>
                    <div class="stat-label">Conversions Left</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ Auth::user()->activeSubscription?->plan_name ?? 'None' }}</div>
                    <div class="stat-label">Current Plan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ count(Auth::user()->activeSubscription?->enabled_addons ?? []) }}</div>
                    <div class="stat-label">Active Tools</div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
