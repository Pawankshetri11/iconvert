<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Content::getValue('site_name', config('app.name', 'Laravel')) }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body style="font-family: 'Inter', sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #ffffff; min-height: 100vh;">
    <!-- Header -->
    <header style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 215, 0, 0.2); position: fixed; top: 0; width: 100%; z-index: 1000; transition: all 0.3s ease;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center;">
                <h1 style="font-size: 1.8rem; font-weight: 700; font-family: 'Playfair Display', serif; background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0;">{{ \App\Models\Content::getValue('header_logo_text', 'PDF CONVERTER PRO') }}</h1>
            </div>
            <nav style="display: flex; gap: 2rem; align-items: center;">
                <a href="{{ route('home') }}" style="color: #e0e0e0; text-decoration: none; font-weight: 500; transition: all 0.3s ease; position: relative;" onmouseover="this.style.color='#ffd700'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='#e0e0e0'; this.style.transform='translateY(0)'">{{ \App\Models\Content::getValue('nav_home', 'Home') }}</a>
                <a href="{{ route('pdf-converter') }}" style="color: #e0e0e0; text-decoration: none; font-weight: 500; transition: all 0.3s ease; position: relative;" onmouseover="this.style.color='#ffd700'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='#e0e0e0'; this.style.transform='translateY(0)'">{{ \App\Models\Content::getValue('nav_pdf_converter', 'PDF Converter') }}</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" style="color: #e0e0e0; text-decoration: none; font-weight: 500; transition: all 0.3s ease; position: relative;" onmouseover="this.style.color='#ffd700'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='#e0e0e0'; this.style.transform='translateY(0)'">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="color: #e0e0e0; background: none; border: none; font-weight: 500; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.color='#ffd700'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='#e0e0e0'; this.style.transform='translateY(0)'">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="color: #e0e0e0; text-decoration: none; font-weight: 500; transition: all 0.3s ease; position: relative;" onmouseover="this.style.color='#ffd700'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='#e0e0e0'; this.style.transform='translateY(0)'">Login</a>
                    <a href="{{ route('register') }}" style="background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; padding: 0.5rem 1rem; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(255, 215, 0, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 215, 0, 0.3)'">Get Started</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main style="padding-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-top: 1px solid rgba(255, 215, 0, 0.2); padding: 2rem 0; margin-top: 4rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 1rem; color: #ffd700;">{{ \App\Models\Content::getValue('site_name', 'PDF CONVERTER PRO') }}</h3>
                    <p style="color: #b0b0b0; line-height: 1.6;">{{ \App\Models\Content::getValue('footer_description', 'Transform your documents with elegance and precision. Professional PDF conversion tools.') }}</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem; color: #e0e0e0;">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('home') }}" style="color: #b0b0b0; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#ffd700'" onmouseout="this.style.color='#b0b0b0'">Home</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('pdf-converter') }}" style="color: #b0b0b0; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#ffd700'" onmouseout="this.style.color='#b0b0b0'">PDF Converter</a></li>
                        @auth
                        @else
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('login') }}" style="color: #b0b0b0; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#ffd700'" onmouseout="this.style.color='#b0b0b0'">Login</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem; color: #e0e0e0;">Contact</h4>
                    <p style="color: #b0b0b0;">support@pdfconverterpro.com</p>
                    <p style="color: #b0b0b0;">24/7 Premium Support</p>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1rem;">
                <p style="color: #888; font-size: 0.9rem;">{{ \App\Models\Content::getValue('footer_copyright', '&copy; ' . date('Y') . ' PDF CONVERTER PRO. All rights reserved. | Premium Document Conversion') }}</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>