@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-white mb-3">About iConvert</h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                Revolutionizing document processing with cutting-edge AI technology and user-friendly tools.
            </p>
        </div>

        <!-- Mission Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
            <div class="text-center">
                <div class="text-3xl font-bold text-white mb-1" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">10M+</div>
                <div class="text-gray-300 text-sm">Documents Processed</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white mb-1" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">50K+</div>
                <div class="text-gray-300 text-sm">Happy Users</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white mb-1" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">99.9%</div>
                <div class="text-gray-300 text-sm">Uptime</div>
            </div>
        </div>
    </div>

    <!-- Our Story -->
    <section class="py-16 bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-white text-center mb-8">Our Story</h2>
                <div class="prose prose-lg prose-invert mx-auto">
                    <p class="text-gray-300 text-base leading-relaxed mb-4">
                        Founded in 2024, iConvert emerged from a simple idea: document processing should be effortless, fast, and accessible to everyone. Our founders, a team of engineers and designers frustrated with complex conversion tools, set out to create the most intuitive document processing platform on the market.
                    </p>
                    <p class="text-gray-300 text-base leading-relaxed mb-4">
                        What started as a PDF converter has evolved into a comprehensive suite of tools that handles everything from image conversion to invoice generation. We leverage cutting-edge AI technology to ensure accuracy and speed, while maintaining the highest standards of security and privacy.
                    </p>
                    <p class="text-gray-300 text-base leading-relaxed">
                        Today, iConvert serves thousands of users worldwide, from individual freelancers to large enterprises, helping them streamline their document workflows and focus on what matters most - their work.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Our Values</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    The principles that guide everything we do.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Simplicity</h3>
                    <p class="text-gray-300 text-sm">We believe complex tasks should have simple solutions.</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Reliability</h3>
                    <p class="text-gray-300 text-sm">Your documents are safe with our enterprise-grade security.</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Innovation</h3>
                    <p class="text-gray-300 text-sm">We continuously improve our tools with the latest technology.</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Support</h3>
                    <p class="text-gray-300 text-sm">We're here to help you succeed with dedicated customer support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Meet Our Team</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    The passionate individuals behind iConvert.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <span class="text-xl text-black font-bold">JD</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-1">John Doe</h3>
                    <p class="text-white text-sm mb-2" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">CEO & Founder</p>
                    <p class="text-gray-300 text-xs">Former tech lead at major SaaS companies, passionate about simplifying complex workflows.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <span class="text-xl text-black font-bold">JS</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-1">Jane Smith</h3>
                    <p class="text-white text-sm mb-2" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">CTO</p>
                    <p class="text-gray-300 text-xs">AI and machine learning expert with 10+ years in document processing technology.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                        <span class="text-xl text-black font-bold">MJ</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-1">Mike Johnson</h3>
                    <p class="text-white text-sm mb-2" style="background: linear-gradient(45deg, #ffd700, #ffed4e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Head of Design</p>
                    <p class="text-gray-300 text-xs">Award-winning UX designer focused on creating intuitive and beautiful user experiences.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Transform Your Document Workflow?</h2>
            <p class="text-lg text-gray-300 mb-6 max-w-2xl mx-auto">
                Join thousands of users who trust iConvert for their document processing needs.
            </p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="text-black px-6 py-3 rounded-lg font-semibold text-base transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                    Get Started Free
                </a>
                <a href="{{ route('contact-us') }}" class="border-2 text-white hover:text-black px-6 py-3 rounded-lg font-semibold text-base transition-colors" style="border-color: #ffd700; color: #ffd700;" onmouseover="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'" onmouseout="this.style.background='transparent'">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</div>
@endsection