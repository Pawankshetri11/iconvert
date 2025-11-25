@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black">
    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Contact Us</h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                Have a question or need support? We're here to help.
            </p>
        </div>
    </div>

    <!-- Contact Section -->
    <section class="py-20 bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Contact Form -->
                <div class="bg-gray-800 rounded-2xl p-8">
                    <h2 class="text-3xl font-bold text-white mb-6">Send us a Message</h2>
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-300 mb-2">First Name</label>
                                <input type="text" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none" style="border-color: rgba(255, 215, 0, 0.3);" placeholder="John">
                            </div>
                            <div>
                                <label class="block text-gray-300 mb-2">Last Name</label>
                                <input type="text" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none" style="border-color: rgba(255, 215, 0, 0.3);" placeholder="Doe">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Email Address</label>
                            <input type="email" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none" style="border-color: rgba(255, 215, 0, 0.3);" placeholder="john@example.com">
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Subject</label>
                            <select class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none" style="border-color: rgba(255, 215, 0, 0.3);">
                                <option value="">Select a topic</option>
                                <option value="support">Technical Support</option>
                                <option value="billing">Billing & Plans</option>
                                <option value="sales">Sales Inquiry</option>
                                <option value="partnership">Partnership</option>
                                <option value="feedback">Feedback</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Message</label>
                            <textarea rows="6" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none" style="border-color: rgba(255, 215, 0, 0.3);" placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit" class="w-full text-black py-4 px-6 rounded-lg font-semibold text-base transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- General Support -->
                    <div class="bg-gray-800 rounded-2xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">General Support</h3>
                                <p class="text-gray-400 text-sm">For questions about our service</p>
                            </div>
                        </div>
                        <div class="ml-16">
                            <p class="text-gray-300 mb-2 flex items-center">
                                <i class="fas fa-envelope mr-2" style="color: #ffd700;"></i>
                                support@iconvert.com
                            </p>
                            <p class="text-gray-300 flex items-center">
                                <i class="fas fa-phone mr-2" style="color: #ffd700;"></i>
                                +1 (555) 123-4567
                            </p>
                            <p class="text-gray-400 text-sm mt-2">Response time: Within 24 hours</p>
                        </div>
                    </div>

                    <!-- Sales -->
                    <div class="bg-gray-800 rounded-2xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Sales & Enterprise</h3>
                                <p class="text-gray-400 text-sm">For business inquiries and custom solutions</p>
                            </div>
                        </div>
                        <div class="ml-16">
                            <p class="text-gray-300 mb-2 flex items-center">
                                <i class="fas fa-envelope mr-2" style="color: #ffd700;"></i>
                                sales@iconvert.com
                            </p>
                            <p class="text-gray-300 flex items-center">
                                <i class="fas fa-phone mr-2" style="color: #ffd700;"></i>
                                +1 (555) 987-6543
                            </p>
                            <p class="text-gray-400 text-sm mt-2">Response time: Within 4 hours</p>
                        </div>
                    </div>

                    <!-- Office Address -->
                    <div class="bg-gray-800 rounded-2xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background: linear-gradient(45deg, #ffd700, #ffed4e);">
                                <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Office Address</h3>
                                <p class="text-gray-400 text-sm">Visit us or send mail</p>
                            </div>
                        </div>
                        <div class="ml-16">
                            <p class="text-gray-300 flex items-center mb-1">
                                <i class="fas fa-building mr-2" style="color: #ffd700;"></i>
                                iConvert Inc.
                            </p>
                            <p class="text-gray-300 ml-6">123 Tech Street, Suite 456</p>
                            <p class="text-gray-300 ml-6">San Francisco, CA 94105</p>
                            <p class="text-gray-300 ml-6">United States</p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-gray-800 rounded-2xl p-8">
                        <h3 class="text-lg font-semibold text-white mb-4">Follow Us</h3>
                        <div class="flex space-x-4 ml-16">
                            <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);" onmouseover="this.style.background='linear-gradient(45deg, #333, #555)'" onmouseout="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'">
                                <i class="fab fa-facebook-f text-black"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);" onmouseover="this.style.background='linear-gradient(45deg, #333, #555)'" onmouseout="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'">
                                <i class="fab fa-twitter text-black"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);" onmouseover="this.style.background='linear-gradient(45deg, #333, #555)'" onmouseout="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'">
                                <i class="fab fa-linkedin-in text-black"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center transition-colors" style="background: linear-gradient(45deg, #ffd700, #ffed4e);" onmouseover="this.style.background='linear-gradient(45deg, #333, #555)'" onmouseout="this.style.background='linear-gradient(45deg, #ffd700, #ffed4e)'">
                                <i class="fas fa-envelope text-black"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">Quick Answers</h2>
                <p class="text-base text-gray-300">Check these common questions before contacting us</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-base font-semibold text-white mb-2">How quickly do you respond?</h3>
                    <p class="text-gray-300 text-sm">We typically respond to all inquiries within 24 hours during business days. Enterprise inquiries get priority response within 4 hours.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-base font-semibold text-white mb-2">Do you offer phone support?</h3>
                    <p class="text-gray-300 text-sm">Yes, phone support is available for enterprise customers and urgent technical issues. General inquiries are handled via email and chat.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-base font-semibold text-white mb-2">Can I schedule a demo?</h3>
                    <p class="text-gray-300 text-sm">Absolutely! We offer personalized demos for enterprise customers. Contact our sales team to schedule a session.</p>
                </div>

                <div class="bg-gray-800 rounded-lg p-5">
                    <h3 class="text-base font-semibold text-white mb-2">What languages do you support?</h3>
                    <p class="text-gray-300 text-sm">Our support team speaks English, Spanish, French, German, and Portuguese. Additional languages available for enterprise clients.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection