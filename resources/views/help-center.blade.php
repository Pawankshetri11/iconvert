@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Help Center</h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                Find answers to your questions and get the help you need.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-10">
            <div class="relative">
                <input type="text" placeholder="Search for help..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-6 py-3 text-white placeholder-gray-400 focus:outline-none text-base" style="border-color: rgba(255, 215, 0, 0.3);">
                <button class="absolute right-3 top-3 text-black px-4 py-1 rounded-md transition-colors text-sm" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Help -->
    <section class="py-16 bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Quick Help</h2>
                <p class="text-lg text-gray-300">Most common questions and solutions</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-question-circle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Getting Started</h3>
                    <p class="text-gray-300 text-sm">Learn the basics of using iConvert and setting up your account.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">File Conversion</h3>
                    <p class="text-gray-300 text-sm">Step-by-step guides for converting different file types.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Billing & Plans</h3>
                    <p class="text-gray-300 text-sm">Understanding your subscription, billing, and plan features.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Troubleshooting</h3>
                    <p class="text-gray-300 text-sm">Common issues and how to resolve them.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-code text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">API Documentation</h3>
                    <p class="text-gray-300 text-sm">Technical documentation for developers and integrations.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-5 hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="mb-3" style="color: #ffd700;">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Contact Support</h3>
                    <p class="text-gray-300 text-sm">Can't find what you're looking for? Get in touch with our team.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-300">Quick answers to common questions</p>
            </div>

            <div class="max-w-4xl mx-auto space-y-6">
                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">How do I convert a PDF to Word?</h3>
                    <p class="text-gray-300 text-sm mb-3">1. Go to the PDF Converter tool<br>2. Select "PDF to Word" from the options<br>3. Upload your PDF file<br>4. Click "Convert" and download your Word document</p>
                    <a href="{{ route('pdf-converter') }}" class="text-sm" style="color: #ffd700;">Try it now →</a>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">What file formats do you support?</h3>
                    <p class="text-gray-300 text-sm">We support a wide range of formats including PDF, Word, Excel, PowerPoint, images (JPG, PNG, WEBP), audio files (MP3, WAV), and more. Check individual tool pages for specific format support.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">Is my data secure?</h3>
                    <p class="text-gray-300 text-sm">Yes, we take security seriously. All files are encrypted during processing and automatically deleted after conversion. We never store your files permanently.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">Can I cancel my subscription anytime?</h3>
                    <p class="text-gray-300 text-sm">Yes, you can cancel your subscription at any time from your dashboard. Your account will remain active until the end of your current billing period.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">Do you offer refunds?</h3>
                    <p class="text-gray-300 text-sm">We offer a 30-day money-back guarantee for all paid plans. Contact our support team to request a refund within 30 days of purchase.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-white mb-2">How do I contact support?</h3>
                    <p class="text-gray-300 text-sm mb-3">
                        <i class="fas fa-envelope mr-1" style="color: #ffd700;"></i> support@iconvert.com<br>
                        <i class="fas fa-comments mr-1" style="color: #ffd700;"></i> Live chat on our website<br>
                        <i class="fas fa-question-circle mr-1" style="color: #ffd700;"></i> Help center contact form
                    </p>
                    <a href="{{ route('contact-us') }}" class="text-sm" style="color: #ffd700;">Contact us →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Support -->
    <section class="py-16 bg-gray-900/50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Still Need Help?</h2>
            <p class="text-lg text-gray-300 mb-6 max-w-2xl mx-auto">
                Our support team is here to help you with any questions or issues you might have.
            </p>
            <div class="space-x-4">
                <a href="{{ route('contact-us') }}" class="text-black px-6 py-3 rounded-lg font-semibold text-base transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                    Contact Support
                </a>
                <a href="mailto:support@iconvert.com" class="border-2 text-white hover:text-black px-6 py-3 rounded-lg font-semibold text-base transition-colors" style="border-color: #ffd700; color: #ffd700;" onmouseover="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'" onmouseout="this.style.background='transparent'">
                    Email Us
                </a>
            </div>
        </div>
    </section>
</div>
@endsection