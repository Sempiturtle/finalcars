<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create Account - AutoCheck Enterprises</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }

            /* ── Step Wizard Animations ── */
            .step-container {
                position: relative;
                overflow: hidden;
                min-height: 280px;
            }
            .step-panel {
                position: absolute;
                inset: 0;
                opacity: 0;
                transform: translateX(80px) scale(0.96);
                pointer-events: none;
                transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .step-panel.active {
                opacity: 1;
                transform: translateX(0) scale(1);
                pointer-events: auto;
                position: relative;
            }
            .step-panel.exit-left {
                opacity: 0;
                transform: translateX(-80px) scale(0.96);
                pointer-events: none;
            }
            .step-panel.enter-right {
                opacity: 0;
                transform: translateX(80px) scale(0.96);
                pointer-events: none;
            }

            /* ── Progress Bar ── */
            .progress-track {
                height: 4px;
                border-radius: 999px;
                background: #e5e7eb;
                overflow: hidden;
            }
            .progress-fill {
                height: 100%;
                border-radius: 999px;
                background: linear-gradient(90deg, #3b82f6, #6366f1);
                transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }

            /* ── Step Indicators ── */
            .step-dot {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 800;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                border: 2px solid #e5e7eb;
                background: white;
                color: #9ca3af;
            }
            .step-dot.active {
                border-color: #3b82f6;
                background: #3b82f6;
                color: white;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
                transform: scale(1.1);
            }
            .step-dot.completed {
                border-color: #10b981;
                background: #10b981;
                color: white;
            }
            .step-connector {
                height: 2px;
                flex: 1;
                background: #e5e7eb;
                transition: background 0.4s ease;
            }
            .step-connector.completed {
                background: #10b981;
            }

            /* ── Input Focus Glow ── */
            .wizard-input {
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .wizard-input:focus {
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
                border-color: #3b82f6;
            }

            /* ── Button Pulse ── */
            .btn-next {
                position: relative;
                overflow: hidden;
            }
            .btn-next::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transform: translateX(-100%);
            }
            .btn-next:hover::after {
                animation: shimmer 1.2s ease forwards;
            }
            @keyframes shimmer {
                to { transform: translateX(100%); }
            }

            /* ── Success Animation ── */
            .success-ring {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                border: 4px solid #10b981;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: ringPop 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes ringPop {
                0%   { transform: scale(0); opacity: 0; }
                60%  { transform: scale(1.15); }
                100% { transform: scale(1); opacity: 1; }
            }
            .success-check {
                stroke-dasharray: 50;
                stroke-dashoffset: 50;
                animation: drawCheck 0.5s 0.4s ease forwards;
            }
            @keyframes drawCheck {
                to { stroke-dashoffset: 0; }
            }

            /* ── Floating particles BG ── */
            .particle {
                position: absolute;
                border-radius: 50%;
                opacity: 0;
                pointer-events: none;
            }
            .particle-1 {
                width: 6px; height: 6px;
                background: #3b82f6;
                animation: float1 8s infinite ease-in-out;
            }
            .particle-2 {
                width: 4px; height: 4px;
                background: #6366f1;
                animation: float2 10s infinite ease-in-out;
            }
            .particle-3 {
                width: 8px; height: 8px;
                background: #10b981;
                animation: float3 12s infinite ease-in-out;
            }
            @keyframes float1 {
                0%,100% { transform: translate(0, 0); opacity: 0; }
                25% { opacity: 0.3; }
                50% { transform: translate(60px, -80px); opacity: 0.15; }
                75% { opacity: 0.3; }
            }
            @keyframes float2 {
                0%,100% { transform: translate(0, 0); opacity: 0; }
                25% { opacity: 0.25; }
                50% { transform: translate(-40px, -100px); opacity: 0.1; }
                75% { opacity: 0.25; }
            }
            @keyframes float3 {
                0%,100% { transform: translate(0, 0); opacity: 0; }
                25% { opacity: 0.2; }
                50% { transform: translate(80px, -50px); opacity: 0.08; }
                75% { opacity: 0.2; }
            }

            /* ── Staggered field entrance ── */
            .field-enter { animation: fieldSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
            .field-enter:nth-child(2) { animation-delay: 0.08s; }
            .field-enter:nth-child(3) { animation-delay: 0.16s; }
            @keyframes fieldSlideUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Confetti burst on success ── */
            .confetti-piece {
                position: absolute;
                width: 8px;
                height: 8px;
                border-radius: 2px;
                opacity: 0;
            }
            .confetti-animate {
                animation: confettiFall 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes confettiFall {
                0%   { opacity: 1; transform: translate(0, 0) rotate(0deg) scale(1); }
                100% { opacity: 0; transform: translate(var(--tx), var(--ty)) rotate(var(--rot)) scale(0.3); }
            }
        </style>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    </head>
    <body class="antialiased bg-gray-50 flex items-center justify-center min-h-screen py-12 px-4">

        {{-- Floating Decorative Particles --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="particle particle-1" style="top:20%; left:10%;"></div>
            <div class="particle particle-2" style="top:60%; left:80%;"></div>
            <div class="particle particle-3" style="top:80%; left:30%;"></div>
            <div class="particle particle-1" style="top:40%; left:70%;"></div>
            <div class="particle particle-2" style="top:10%; left:50%;"></div>
        </div>

        <div class="max-w-lg w-full relative z-10"
             x-data="{
                currentStep: 1,
                totalSteps: 3,
                direction: 'forward',
                firstName: '{{ old('first_name', '') }}',
                lastName: '{{ old('last_name', '') }}',
                username: '{{ old('username', '') }}',
                email: '{{ old('email', '') }}',
                phone: '{{ old('phone', '') }}',
                password: '',
                password_confirmation: '',
                showSuccess: false,
                errors: {},

                get progressWidth() {
                    if (this.showSuccess) return '100%';
                    return ((this.currentStep - 1) / this.totalSteps * 100) + '%';
                },

                validateStep(step) {
                    this.errors = {};
                    if (step === 1) {
                        if (!this.firstName.trim()) this.errors.firstName = 'First name is required';
                        if (!this.lastName.trim()) this.errors.lastName = 'Last name is required';
                        if (!this.username.trim()) this.errors.username = 'Username is required';
                    } else if (step === 2) {
                        if (!this.email.trim()) this.errors.email = 'Email is required';
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) this.errors.email = 'Enter a valid email';
                        if (!this.password) this.errors.password = 'Password is required';
                        if (this.password.length < 8) this.errors.password = 'Minimum 8 characters';
                        if (this.password !== this.password_confirmation) this.errors.password_confirmation = 'Passwords don\'t match';
                    } else if (step === 3) {
                        if (!this.phone.trim()) this.errors.phone = 'Phone number is required';
                    }
                    return Object.keys(this.errors).length === 0;
                },

                nextStep() {
                    if (!this.validateStep(this.currentStep)) return;
                    this.direction = 'forward';
                    if (this.currentStep < this.totalSteps) {
                        this.currentStep++;
                    }
                },

                prevStep() {
                    this.direction = 'backward';
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                submitForm() {
                    if (!this.validateStep(3)) return;
                    // Set combined name
                    document.getElementById('hidden-name').value = this.firstName.trim() + ' ' + this.lastName.trim();
                    document.getElementById('hidden-username').value = this.username.trim();
                    document.getElementById('hidden-email').value = this.email.trim();
                    document.getElementById('hidden-phone').value = this.phone.trim();
                    document.getElementById('hidden-password').value = this.password;
                    document.getElementById('hidden-password-confirm').value = this.password_confirmation;

                    this.showSuccess = true;

                    setTimeout(() => {
                        document.getElementById('register-form').submit();
                    }, 2000);
                },

                stepLabel(step) {
                    const labels = { 1: 'Identity', 2: 'Security', 3: 'Contact' };
                    return labels[step];
                }
             }"
             x-init="
                {{-- If there are server-side validation errors, try to go to the right step --}}
                @if($errors->has('email') || $errors->has('password'))
                    currentStep = 2;
                @elseif($errors->has('phone'))
                    currentStep = 3;
                @endif
             "
        >
            {{-- Header --}}
            <div class="text-center mb-8">
                <a href="/" class="inline-flex flex-col items-center space-y-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-14 w-14 rounded-full object-cover border-2 border-blue-500 shadow-xl">
                    <span class="text-2xl font-black tracking-tight text-gray-900">AutoCheck</span>
                </a>
                <h2 class="text-lg font-bold text-gray-600">Join the Fleet</h2>
                <p class="text-xs text-gray-400 mt-1">Create your customer account to start tracking</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-500/10 border border-gray-100 p-8 md:p-10">

                {{-- Step Indicator Dots --}}
                <div class="flex items-center justify-center gap-0 mb-8 px-4" x-show="!showSuccess">
                    <template x-for="step in totalSteps" :key="step">
                        <div class="flex items-center" :class="step < totalSteps ? 'flex-1' : ''">
                            <div class="flex flex-col items-center">
                                <div class="step-dot"
                                     :class="{
                                         'active': currentStep === step && !showSuccess,
                                         'completed': currentStep > step || showSuccess
                                     }">
                                    <template x-if="currentStep > step || showSuccess">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </template>
                                    <template x-if="currentStep <= step && !showSuccess">
                                        <span x-text="step"></span>
                                    </template>
                                </div>
                                <span class="text-[9px] font-bold uppercase tracking-widest mt-1.5 transition-colors duration-300"
                                      :class="currentStep >= step ? 'text-blue-600' : 'text-gray-300'"
                                      x-text="stepLabel(step)"></span>
                            </div>
                            <template x-if="step < totalSteps">
                                <div class="step-connector mx-2 mt-[-14px]" :class="currentStep > step ? 'completed' : ''"></div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Progress Track --}}
                <div class="progress-track mb-6" x-show="!showSuccess">
                    <div class="progress-fill" :style="'width:' + progressWidth"></div>
                </div>

                {{-- Hidden Real Form --}}
                <form id="register-form" method="POST" action="{{ route('register') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="name" id="hidden-name">
                    <input type="hidden" name="username" id="hidden-username">
                    <input type="hidden" name="email" id="hidden-email">
                    <input type="hidden" name="phone" id="hidden-phone">
                    <input type="hidden" name="password" id="hidden-password">
                    <input type="hidden" name="password_confirmation" id="hidden-password-confirm">
                </form>

                {{-- Step Container --}}
                <div class="step-container" x-show="!showSuccess">

                    {{-- ═══ STEP 1: Identity ═══ --}}
                    <div class="step-panel"
                         :class="{
                             'active': currentStep === 1,
                             'exit-left': currentStep > 1
                         }">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-50 mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Who are you?</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Let's start with your name</p>
                        </div>
                        <div class="space-y-4 text-left">
                            <div class="grid grid-cols-2 gap-3 field-enter" :key="'s1-1-' + currentStep">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">First Name</label>
                                    <input x-model="firstName"
                                           type="text"
                                           class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                           placeholder="Juan"
                                           @keydown.enter="nextStep()">
                                    <p x-show="errors.firstName" x-text="errors.firstName" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                    @error('name') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Last Name</label>
                                    <input x-model="lastName"
                                           type="text"
                                           class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                           placeholder="Dela Cruz"
                                           @keydown.enter="nextStep()">
                                    <p x-show="errors.lastName" x-text="errors.lastName" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                </div>
                            </div>
                            <div class="field-enter" :key="'s1-2-' + currentStep">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Username</label>
                                <input x-model="username"
                                       type="text"
                                       class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                       placeholder="juandc123"
                                       @keydown.enter="nextStep()">
                                <p x-show="errors.username" x-text="errors.username" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                @error('username') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="pt-6">
                            <button @click="nextStep()" type="button"
                                    class="btn-next w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/20 active:scale-95 transform flex items-center justify-center gap-2">
                                Continue
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ═══ STEP 2: Email & Password ═══ --}}
                    <div class="step-panel"
                         :class="{
                             'active': currentStep === 2,
                             'exit-left': currentStep > 2,
                             'enter-right': currentStep < 2
                         }">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 mb-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Secure Your Account</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Set up your login credentials</p>
                        </div>
                        <div class="space-y-4 text-left">
                            <div class="field-enter" :key="'s2-1-' + currentStep">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Email Address</label>
                                <input x-model="email"
                                       type="email"
                                       class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                       placeholder="juan@example.com"
                                       @keydown.enter="nextStep()">
                                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                @error('email') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3 field-enter" :key="'s2-2-' + currentStep">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Password</label>
                                    <input x-model="password"
                                           type="password"
                                           class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                           placeholder="••••••••"
                                           @keydown.enter="nextStep()">
                                    <p x-show="errors.password" x-text="errors.password" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                    @error('password') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Confirm</label>
                                    <input x-model="password_confirmation"
                                           type="password"
                                           class="wizard-input w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-xs"
                                           placeholder="••••••••"
                                           @keydown.enter="nextStep()">
                                    <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6 flex gap-3">
                            <button @click="prevStep()" type="button"
                                    class="px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-[0.15em] transition-all active:scale-95 transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </button>
                            <button @click="nextStep()" type="button"
                                    class="btn-next flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/20 active:scale-95 transform flex items-center justify-center gap-2">
                                Continue
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ═══ STEP 3: Phone Number ═══ --}}
                    <div class="step-panel"
                         :class="{
                             'active': currentStep === 3,
                             'enter-right': currentStep < 3
                         }">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-50 mb-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Almost Done!</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Add your contact number</p>
                        </div>
                        <div class="space-y-4 text-left">
                            <div class="field-enter" :key="'s3-1-' + currentStep">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Phone Number</label>
                                <div class="flex items-center bg-gray-50 border border-gray-100 rounded-2xl overflow-hidden focus-within:ring-4 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all">
                                    <span class="pl-5 pr-2 py-3.5 text-sm font-black text-gray-400 select-none border-r border-gray-200 bg-gray-100/60">+63</span>
                                    <input x-model="phone"
                                           type="text"
                                           class="flex-1 px-3 py-3.5 bg-transparent outline-none font-bold text-sm tracking-wider"
                                           placeholder="9XX XXX XXXX"
                                           @keydown.enter="submitForm()">
                                </div>
                                <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-[10px] font-bold text-red-500 px-1"></p>
                                @error('phone') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Summary Preview --}}
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 field-enter" :key="'s3-2-' + currentStep">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Account Summary</p>
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="text-[11px] font-bold text-gray-600" x-text="firstName + ' ' + lastName"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                        <span class="text-[11px] font-bold text-gray-600" x-text="email"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-[11px] font-bold text-gray-600">@<span x-text="username"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6 flex gap-3">
                            <button @click="prevStep()" type="button"
                                    class="px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-[0.15em] transition-all active:scale-95 transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </button>
                            <button @click="submitForm()" type="button"
                                    class="btn-next flex-1 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/20 active:scale-95 transform flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Create Account
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══ SUCCESS STATE ═══ --}}
                <div x-show="showSuccess"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="text-center py-8 relative overflow-hidden">

                    {{-- Confetti pieces --}}
                    <template x-if="showSuccess">
                        <div>
                            <div class="confetti-piece confetti-animate" style="background:#3b82f6; top:40%; left:50%; --tx:-60px; --ty:-80px; --rot:240deg;"></div>
                            <div class="confetti-piece confetti-animate" style="background:#10b981; top:40%; left:50%; --tx:70px; --ty:-90px; --rot:-200deg; animation-delay:0.1s;"></div>
                            <div class="confetti-piece confetti-animate" style="background:#f59e0b; top:40%; left:50%; --tx:-40px; --ty:60px; --rot:160deg; animation-delay:0.05s;"></div>
                            <div class="confetti-piece confetti-animate" style="background:#6366f1; top:40%; left:50%; --tx:50px; --ty:40px; --rot:-120deg; animation-delay:0.15s;"></div>
                            <div class="confetti-piece confetti-animate" style="background:#ef4444; top:40%; left:50%; --tx:-80px; --ty:20px; --rot:300deg; animation-delay:0.08s;"></div>
                            <div class="confetti-piece confetti-animate" style="background:#14b8a6; top:40%; left:50%; --tx:90px; --ty:-30px; --rot:-260deg; animation-delay:0.12s;"></div>
                        </div>
                    </template>

                    <div class="flex justify-center mb-5">
                        <div class="success-ring">
                            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path class="success-check" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-1">Welcome to AutoCheck!</h3>
                    <p class="text-xs text-gray-400">Setting up your account...</p>
                    <div class="mt-5 flex justify-center">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay:0s"></span>
                            <span class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay:0.3s"></span>
                        </div>
                    </div>
                </div>

                {{-- Sign In Link --}}
                <div class="mt-6 text-center border-t border-gray-50 pt-6" x-show="!showSuccess">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">
                        Got an account?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 ml-1">Sign In</a>
                    </p>
                </div>
            </div>

            {{-- Back to Website --}}
            <div class="mt-8 text-center" x-show="!showSuccess">
                <a href="/" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest flex items-center justify-center group">
                    <svg class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Website
                </a>
            </div>
        </div>
    </body>
</html>
