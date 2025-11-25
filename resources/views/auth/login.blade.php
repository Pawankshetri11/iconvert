<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal SaaS Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        dark: { 800: '#18181b', 900: '#09090b' }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #09090b; color: #e4e4e7; }

        /* Refined Glass Effect */
        .glass-card {
            background: rgba(24, 24, 27, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Gold Button Gradient */
        .btn-gold {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: black;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
            filter: brightness(1.05);
        }
        .btn-gold:active {
            transform: translateY(0);
        }

        /* Compact Input Styles */
        .input-wrapper {
            position: relative;
            transition: all 0.2s ease;
        }
        .input-field {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s ease;
        }
        .input-field:focus {
            background: rgba(255, 215, 0, 0.05);
            border-color: #ffd700;
            box-shadow: 0 0 0 1px #ffd700;
        }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #71717a; transition: color 0.2s;
        }
        .input-field:focus ~ .input-icon { color: #ffd700; }

        /* Smooth Form Switcher */
        .forms-container {
            position: relative;
            overflow: hidden;
            transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-slide {
            position: absolute; top: 0; left: 0; width: 100%;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0; pointer-events: none;
            transform: translateX(20px);
        }
        .form-slide.active {
            opacity: 1; pointer-events: auto; position: relative;
            transform: translateX(0);
        }
        .form-slide.exit-left { transform: translateX(-20px); opacity: 0; }
        .form-slide.exit-right { transform: translateX(20px); opacity: 0; }

        /* Tab Glider */
        .tab-glider { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Social Buttons */
        .social-btn {
            display: flex; align-items: center; justify-content: center;
            padding: 0.5rem; border-radius: 0.5rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.2s;
        }
        .social-btn:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,215,0,0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 selection:bg-yellow-500/30 overflow-hidden">

    <!-- Background Animation -->
    <canvas id="bgCanvas" class="fixed inset-0 z-0"></canvas>
    <div class="fixed inset-0 bg-gradient-to-tr from-yellow-900/10 via-transparent to-purple-900/20 z-0 pointer-events-none mix-blend-overlay"></div>

    <!-- Compact Card -->
    <div class="w-full max-w-[380px] relative z-10">
        <div class="glass-card rounded-2xl overflow-hidden">

            <!-- Header -->
            <div class="pt-6 pb-4 text-center">
                <h1 class="text-2xl font-bold tracking-tight text-white">ROYAL <span class="text-[#ffd700]">SAAS</span></h1>
                <p class="text-xs text-zinc-500 mt-1 font-medium">PREMIUM ACCESS</p>
            </div>

            <!-- Compact Tabs -->
            <div class="px-6 mb-5">
                <div class="bg-black/30 p-1 rounded-lg flex relative border border-white/5">
                    <div id="glider" class="tab-glider absolute top-1 bottom-1 {{ $is_register ?? false ? 'right-1' : 'left-1' }} w-[calc(50%-4px)] bg-zinc-800 rounded shadow-sm border border-white/10"></div>
                    <button id="btn-login" class="flex-1 z-10 py-1.5 text-xs font-medium {{ $is_register ?? false ? 'text-zinc-500' : 'text-white' }} transition-colors">Log In</button>
                    <button id="btn-signup" class="flex-1 z-10 py-1.5 text-xs font-medium {{ $is_register ?? false ? 'text-white' : 'text-zinc-500' }} transition-colors">Sign Up</button>
                </div>
            </div>

            <!-- Forms -->
            <div id="container" class="forms-container px-6 pb-6 min-h-[300px]">

                <!-- LOGIN -->
                <form id="login" class="form-slide {{ $is_register ?? false ? '' : 'active' }} flex flex-col gap-3" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required autofocus>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </div>

                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="Password" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>

                    <div class="flex justify-between items-center text-[11px] mt-1">
                        <label class="flex items-center gap-1.5 cursor-pointer text-zinc-400 hover:text-zinc-300">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="rounded bg-zinc-800 border-zinc-700 text-[#ffd700] focus:ring-0 w-3.5 h-3.5"> Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[#ffd700] hover:text-[#ffed4e] transition-colors">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-gold mt-2 w-full font-bold py-2.5 rounded-lg active:scale-[0.98] text-sm uppercase tracking-wide">
                        Sign In
                    </button>

                    <div class="relative flex py-2 items-center mt-2">
                        <div class="flex-grow border-t border-white/10"></div>
                        <span class="flex-shrink mx-2 text-zinc-600 text-[10px] uppercase">Or continue with</span>
                        <div class="flex-grow border-t border-white/10"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('google.login') }}" class="social-btn group">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span class="text-xs text-zinc-300 group-hover:text-white">Google</span>
                        </a>
                        <a href="{{ route('github.login') }}" class="social-btn group">
                            <svg class="w-5 h-5 mr-2 text-zinc-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-xs text-zinc-300 group-hover:text-white">GitHub</span>
                        </a>
                    </div>
                </form>

                <!-- SIGNUP -->
                <form id="signup" class="form-slide {{ $is_register ?? false ? 'active' : '' }} flex flex-col gap-3" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-wrapper">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </div>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="Password" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="input-field w-full pl-9 pr-3 py-2.5 rounded-lg text-sm bg-transparent text-white placeholder-zinc-600 focus:outline-none" required>
                        <svg class="input-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>

                    <button type="submit" class="btn-gold mt-2 w-full font-bold py-2.5 rounded-lg active:scale-[0.98] text-sm uppercase tracking-wide">
                        Create Account
                    </button>

                    <div class="relative flex py-2 items-center mt-2">
                        <div class="flex-grow border-t border-white/10"></div>
                        <span class="flex-shrink mx-2 text-zinc-600 text-[10px] uppercase">Or continue with</span>
                        <div class="flex-grow border-t border-white/10"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('google.login') }}" class="social-btn group">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span class="text-xs text-zinc-300 group-hover:text-white">Google</span>
                        </a>
                        <a href="{{ route('github.login') }}" class="social-btn group">
                            <svg class="w-5 h-5 mr-2 text-zinc-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-xs text-zinc-300 group-hover:text-white">GitHub</span>
                        </a>
                    </div>

                    <p class="text-center text-[10px] text-zinc-500 mt-2">
                        By joining, you agree to our <a href="#" class="text-[#ffd700] hover:underline">Terms</a>.
                    </p>
                </form>
            </div>
        </div>
        <p class="text-center text-[10px] text-zinc-500 mt-4 opacity-60 tracking-wider uppercase">
            © 2024 ROYAL SAAS INC. I AGREE TO TERMS AND CONDITIONS
        </p>
    </div>

    <script>
        (function() {
            // --- Form Logic ---
            const els = {
                loginBtn: document.getElementById('btn-login'),
                signupBtn: document.getElementById('btn-signup'),
                glider: document.getElementById('glider'),
                loginForm: document.getElementById('login'),
                signupForm: document.getElementById('signup'),
                container: document.getElementById('container')
            };

            let isLogin = @if($is_register ?? false) false @else true @endif;

            els.loginBtn.addEventListener('click', () => toggle(true));
            els.signupBtn.addEventListener('click', () => toggle(false));

            const updateHeight = () => els.container.style.height = (isLogin ? els.loginForm.offsetHeight : els.signupForm.offsetHeight) + 'px';
            window.onload = updateHeight;

            function toggle(state) {
                if (isLogin === state) return;
                isLogin = state;

                els.glider.style.transform = isLogin ? 'translateX(0)' : 'translateX(104%)';
                els.loginBtn.className = isLogin ? 'flex-1 z-10 py-1.5 text-xs font-medium text-white transition-colors' : 'flex-1 z-10 py-1.5 text-xs font-medium text-zinc-500 transition-colors';
                els.signupBtn.className = !isLogin ? 'flex-1 z-10 py-1.5 text-xs font-medium text-white transition-colors' : 'flex-1 z-10 py-1.5 text-xs font-medium text-zinc-500 transition-colors';

                const current = isLogin ? els.signupForm : els.loginForm;
                const next = isLogin ? els.loginForm : els.signupForm;

                current.classList.remove('active');
                current.classList.add(isLogin ? 'exit-right' : 'exit-left');

                setTimeout(() => {
                    current.classList.remove(isLogin ? 'exit-right' : 'exit-left');
                    next.classList.add('active');
                    updateHeight();
                }, 200);
            }

            // --- Enhanced Constellation Animation ---
            const canvas = document.getElementById('bgCanvas');
            const ctx = canvas.getContext('2d');
            let w, h, particles = [];

            const mouse = { x: null, y: null, radius: 150 };

            window.addEventListener('mousemove', (event) => {
                mouse.x = event.x;
                mouse.y = event.y;
            });

            const resize = () => {
                w = canvas.width = window.innerWidth;
                h = canvas.height = window.innerHeight;
            };
            window.addEventListener('resize', resize);
            resize();

            class Particle {
                constructor() {
                    this.x = Math.random() * w;
                    this.y = Math.random() * h;
                    this.directionX = (Math.random() * 2) - 1;
                    this.directionY = (Math.random() * 2) - 1;
                    this.size = (Math.random() * 2) + 0.5; // Slightly smaller stars
                    this.color = '#ffd700';
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
                    ctx.fillStyle = this.color;
                    ctx.fill();
                }

                update() {
                    // Check if particle is still within canvas
                    if (this.x > w || this.x < 0) this.directionX = -this.directionX;
                    if (this.y > h || this.y < 0) this.directionY = -this.directionY;

                    // Mouse interaction - push/pull effect or connection
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx*dx + dy*dy);

                    if (distance < mouse.radius) {
                        if (mouse.x < this.x && this.x < w - this.size * 10) {
                            this.x += 2;
                        }
                        if (mouse.x > this.x && this.x > this.size * 10) {
                            this.x -= 2;
                        }
                        if (mouse.y < this.y && this.y < h - this.size * 10) {
                            this.y += 2;
                        }
                        if (mouse.y > this.y && this.y > this.size * 10) {
                            this.y -= 2;
                        }
                    }

                    this.x += this.directionX * 0.4;
                    this.y += this.directionY * 0.4;
                    this.draw();
                }
            }

            function init() {
                particles = [];
                // INCREASED PARTICLE COUNT (More Animation)
                let numberOfParticles = (w * h) / 9000;
                for(let i = 0; i < numberOfParticles * 1.5; i++) {
                    particles.push(new Particle());
                }
            }

            function animate() {
                requestAnimationFrame(animate);
                ctx.clearRect(0, 0, w, h);

                for(let i = 0; i < particles.length; i++) {
                    particles[i].update();
                }
                connect();
            }

            // Connect particles with lines
            function connect() {
                let opacityValue = 1;
                for(let a = 0; a < particles.length; a++) {
                    for(let b = a; b < particles.length; b++) {
                        let distance = ((particles[a].x - particles[b].x) * (particles[a].x - particles[b].x)) + ((particles[a].y - particles[b].y) * (particles[a].y - particles[b].y));
                        if (distance < (w/7) * (h/7)) {
                            opacityValue = 1 - (distance / 20000);
                            ctx.strokeStyle = 'rgba(255, 215, 0,' + opacityValue * 0.15 + ')'; // Gold lines
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            ctx.moveTo(particles[a].x, particles[a].y);
                            ctx.lineTo(particles[b].x, particles[b].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            init();
            animate();
            window.addEventListener('resize', () => {
                resize();
                init();
            });
        })();
    </script>
</body>
</html>
