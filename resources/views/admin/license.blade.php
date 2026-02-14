@extends('layouts.admin')

@section('styles')
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        royal: {
                            950: '#020202',
                            900: '#050505',
                            800: '#0a0a0a',
                        },
                        gold: {
                            400: '#ffed4e',
                            500: '#ffd700',
                            600: '#d4b200',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* --- Glass Panel --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-panel:hover {
            border-color: rgba(255, 215, 0, 0.3);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.1);
            background: rgba(255, 255, 255, 0.04);
            transform: translateY(-2px);
        }

        .text-gold-gradient {
            background: linear-gradient(135deg, #fff 20%, #ffd700 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection

@section('content')
<div class="min-h-screen bg-royal-950 text-white p-6">
    <div class="max-w-4xl mx-auto">

        <div class="glass-panel rounded-xl p-6">
            {{-- Debug info --}}
            @if(config('app.debug'))
                <div class="mb-4 p-2 bg-gray-800 rounded text-xs font-mono">
                    Domain: {{ request()->getHost() }}<br>
                    License: {{ $license ? 'Found (ID: ' . $license->id . ', Status: ' . $license->status . ', Valid: ' . ($license->isValid() ? 'Yes' : 'No') . ')' : 'Not found' }}
                </div>
            @endif

            @if($license && $license->isValid())
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gold-500/20 text-gold-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gold-gradient mb-2">License Active</h2>
                    <p class="text-zinc-400 mb-6">Thank you for purchasing Royal SaaS Starter.</p>

                    @if(session('generated_key'))
                        <div class="glass-panel rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gold-400 mb-2">Generated License Key</h3>
                            <p class="text-white font-mono text-sm">{{ session('generated_key') }}</p>
                            <p class="text-sm text-zinc-400 mt-2">Copy this key and provide it to your customer for activation.</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left max-w-lg mx-auto glass-panel p-4 rounded-lg">
                        <div>
                            <span class="text-sm text-zinc-400">License Key</span>
                            <div class="font-mono text-sm text-white">{{ substr($license->license_key, 0, 8) }}...</div>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-400">Licensed To</span>
                            <div class="text-white">{{ $license->client_name }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-400">Domain</span>
                            <div class="text-white">{{ $license->domain }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-400">Type</span>
                            <div class="capitalize text-white">{{ $license->type }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-400">Activated On</span>
                            <div class="text-white">{{ $license->activated_at->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.license.deactivate') }}" method="POST" class="mt-8">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 underline text-sm" onclick="return confirm('Are you sure you want to deactivate this license?')">
                            Deactivate License
                        </button>
                    </form>
                </div>
            @else
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gold-gradient">Activate Your License</h2>
                        <p class="text-zinc-400 text-sm mt-2">Enter your purchase code to unlock all features.</p>
                    </div>

                    <form action="{{ route('admin.license.activate') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-1">Client Name</label>
                                <input type="text" name="client_name" class="w-full rounded-lg glass-panel text-white placeholder-zinc-400 focus:border-gold-500 focus:ring-gold-500 p-3" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white mb-1">Email Address <span class="text-red-400">*</span></label>
                                <input type="email" name="email" required class="w-full rounded-lg glass-panel text-white placeholder-zinc-400 focus:border-gold-500 focus:ring-gold-500 p-3">
                                <p class="text-xs text-zinc-400 mt-1">Email domain will be used for domain locking validation</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white mb-1">License Key</label>
                                <input type="text" name="license_key" placeholder="e.g., ROYAL-XXXX-XXXX-XXXX" class="w-full rounded-lg glass-panel text-white placeholder-zinc-400 focus:border-gold-500 focus:ring-gold-500 p-3" required>
                            </div>

                            <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-black font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-gold-500/25">
                                Activate License
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
        
        <!-- Server Connection Status -->
        <div class="mt-4 text-center">
            <div id="server-status" class="inline-flex items-center px-4 py-2 rounded-full glass-panel text-sm">
                <span class="w-2 h-2 rounded-full bg-gray-400 mr-2 animate-pulse"></span>
                <span class="text-zinc-400">Checking server connection...</span>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("admin.license.check") }}')
            .then(response => response.json())
            .then(data => {
                const statusEl = document.getElementById('server-status');
                if (data.status === 'connected') {
                    statusEl.innerHTML = `
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-green-400">License Server Connected</span>
                    `;
                } else {
                    statusEl.innerHTML = `
                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                        <span class="text-red-400">Server Connection Failed</span>
                    `;
                    // Optional: Show specific error in a tooltip or console
                    console.error('License Server Error:', data.message);
                }
            })
            .catch(error => {
                const statusEl = document.getElementById('server-status');
                statusEl.innerHTML = `
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    <span class="text-red-400">Connection Error</span>
                `;
            });
    });
</script>
@endsection
