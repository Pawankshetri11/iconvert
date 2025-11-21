@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); min-height: 100vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffd700" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffd700" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.3;"></div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 2; width: 100%;">
        <div style="text-align: center;" data-aos="fade-up">
            <h1 style="font-size: 4rem; font-weight: 700; font-family: 'Playfair Display', serif; background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 2rem; line-height: 1.1;" data-aos="fade-up" data-aos-delay="200">
                {{ \App\Models\Content::getValue('hero_title', 'PDF CONVERTER PRO') }}
            </h1>
            <p style="font-size: 1.5rem; color: #e0e0e0; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;" data-aos="fade-up" data-aos-delay="400">
                {{ \App\Models\Content::getValue('hero_subtitle', 'Transform your documents with elegance and precision. Professional PDF conversion tools powered by cutting-edge technology.') }}
            </p>
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;" data-aos="fade-up" data-aos-delay="600">
                @auth
                    <a href="{{ route('pdf-converter') }}" style="background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3); border: 2px solid transparent;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 40px rgba(255, 215, 0, 0.4)'; this.style.borderColor='#ffd700'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(255, 215, 0, 0.3)'; this.style.borderColor='transparent'">
                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            ⚡ {{ \App\Models\Content::getValue('hero_cta_primary', 'Start Converting') }}
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" style="background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3); border: 2px solid transparent;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 40px rgba(255, 215, 0, 0.4)'; this.style.borderColor='#ffd700'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(255, 215, 0, 0.3)'; this.style.borderColor='transparent'">
                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            🚀 Get Started Free
                        </span>
                    </a>
                    <a href="{{ route('register') }}" style="background: rgba(255, 255, 255, 0.1); color: #ffd700; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; border: 2px solid rgba(255, 215, 0, 0.3); backdrop-filter: blur(10px);" onmouseover="this.style.transform='translateY(-3px)'; this.style.background='rgba(255, 215, 0, 0.1)'; this.style.borderColor='#ffd700'" onmouseout="this.style.transform='translateY(0)'; this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(255, 215, 0, 0.3)'">
                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            ✨ Create Account
                        </span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Floating Elements -->
        <div style="position: absolute; top: 20%; left: 10%; animation: float 6s ease-in-out infinite;" data-aos="fade-right">
            <div style="width: 60px; height: 60px; background: linear-gradient(45deg, #ffd700, #ffed4e); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3);">📄</div>
        </div>
        <div style="position: absolute; top: 60%; right: 15%; animation: float 8s ease-in-out infinite reverse;" data-aos="fade-left" data-aos-delay="300">
            <div style="width: 50px; height: 50px; background: linear-gradient(45deg, #ff6b6b, #ffa500); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 32px rgba(255, 107, 107, 0.3);">📝</div>
        </div>
        <div style="position: absolute; bottom: 30%; left: 20%; animation: float 7s ease-in-out infinite;" data-aos="fade-up" data-aos-delay="500">
            <div style="width: 45px; height: 45px; background: linear-gradient(45deg, #4ecdc4, #44a08d); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 8px 32px rgba(78, 205, 196, 0.3);">🖼️</div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="background: rgba(255, 255, 255, 0.02); padding: 6rem 0; position: relative;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
        <div style="text-align: center; margin-bottom: 4rem;" data-aos="fade-up">
            <h2 style="font-size: 2.5rem; font-weight: 700; font-family: 'Playfair Display', serif; color: #ffd700; margin-bottom: 1rem;">{{ \App\Models\Content::getValue('features_title', 'Powerful Conversion Tools') }}</h2>
            <p style="font-size: 1.2rem; color: #b0b0b0; max-width: 600px; margin: 0 auto;">{{ \App\Models\Content::getValue('features_subtitle', 'Everything you need to convert and transform your documents with professional-grade precision') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; text-align: center; border: 1px solid rgba(255, 215, 0, 0.2); transition: all 0.3s ease; cursor: pointer;" data-aos="fade-up" data-aos-delay="200" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#ffd700'; this.style.boxShadow='0 20px 40px rgba(255, 215, 0, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 215, 0, 0.2)'; this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; background: linear-gradient(45deg, #ffd700, #ffed4e); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3);">📄</div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem;">HTML to PDF</h3>
                <p style="color: #b0b0b0; line-height: 1.6;">Convert HTML files to professional PDF documents with perfect formatting and styling preservation.</p>
            </div>

            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; text-align: center; border: 1px solid rgba(255, 215, 0, 0.2); transition: all 0.3s ease; cursor: pointer;" data-aos="fade-up" data-aos-delay="400" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#ffd700'; this.style.boxShadow='0 20px 40px rgba(255, 215, 0, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 215, 0, 0.2)'; this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; background: linear-gradient(45deg, #ff6b6b, #ffa500); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 32px rgba(255, 107, 107, 0.3);">📝</div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem;">PDF to Word</h3>
                <p style="color: #b0b0b0; line-height: 1.6;">Extract text from PDFs and convert to fully editable Word documents with OCR technology.</p>
            </div>

            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; text-align: center; border: 1px solid rgba(255, 215, 0, 0.2); transition: all 0.3s ease; cursor: pointer;" data-aos="fade-up" data-aos-delay="600" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#ffd700'; this.style.boxShadow='0 20px 40px rgba(255, 215, 0, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 215, 0, 0.2)'; this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; background: linear-gradient(45deg, #4ecdc4, #44a08d); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 32px rgba(78, 205, 196, 0.3);">📃</div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem;">PDF to Text</h3>
                <p style="color: #b0b0b0; line-height: 1.6;">Convert PDF files to editable text format with advanced OCR and layout preservation.</p>
            </div>

            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; text-align: center; border: 1px solid rgba(255, 215, 0, 0.2); transition: all 0.3s ease; cursor: pointer;" data-aos="fade-up" data-aos-delay="800" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#ffd700'; this.style.boxShadow='0 20px 40px rgba(255, 215, 0, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 215, 0, 0.2)'; this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; background: linear-gradient(45deg, #a855f7, #ec4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 32px rgba(168, 85, 247, 0.3);">🖼️</div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem;">Images to PDF</h3>
                <p style="color: #b0b0b0; line-height: 1.6;">Combine multiple images into a single PDF with customizable layouts and compression.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); padding: 6rem 0; position: relative;" data-aos="fade-up">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center;">
        <h2 style="font-size: 3rem; font-weight: 700; font-family: 'Playfair Display', serif; color: #000; margin-bottom: 1.5rem;">Ready to Transform Your Documents?</h2>
        <p style="font-size: 1.3rem; color: #333; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">Join thousands of professionals who trust PDF Converter Pro for their document conversion needs.</p>
        @auth
            <a href="{{ route('pdf-converter') }}" style="background: #000; color: #ffd700; padding: 1.2rem 3rem; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.2rem; transition: all 0.3s ease; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); display: inline-block;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 40px rgba(0, 0, 0, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(0, 0, 0, 0.3)'">
                ⚡ Start Converting Now
            </a>
        @else
            <a href="{{ route('register') }}" style="background: #000; color: #ffd700; padding: 1.2rem 3rem; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.2rem; transition: all 0.3s ease; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); display: inline-block;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 40px rgba(0, 0, 0, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(0, 0, 0, 0.3)'">
                🚀 Create Premium Account
            </a>
        @endauth
    </div>

    <!-- Decorative Elements -->
    <div style="position: absolute; top: -50px; left: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: pulse 4s ease-in-out infinite;"></div>
    <div style="position: absolute; bottom: -30px; right: -30px; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: pulse 6s ease-in-out infinite reverse;"></div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.1); opacity: 0.2; }
}
</style>
@endsection