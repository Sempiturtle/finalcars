<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - AutoCheck Enterprises</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .bg-autocheck-red { background-color: #F53003; }
            .text-autocheck-red { color: #F53003; }
        </style>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    </head>
    <body class="antialiased bg-gray-50 flex items-center justify-center min-h-screen py-12 px-4 border-t-8 border-autocheck-red">
        <!-- Watermark Background -->
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.2] overflow-hidden">
            <img src="{{ asset('images/background.jfif') }}" alt="" class="w-full h-full object-cover grayscale brightness-90">
        </div>

        <div class="max-w-[400px] w-full relative z-10">
            <div class="text-center mb-3">
                <a href="/" class="inline-flex flex-col items-center space-y-1 mb-1">
                    <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-10 w-10 rounded-full object-cover border-2 border-autocheck-red shadow-xl">
                    <span class="text-lg font-black tracking-tight text-gray-900 leading-none">AutoCheck</span>
                </a>
                <h2 class="text-[11px] font-bold text-gray-600 uppercase tracking-widest leading-none">
                    Secure Portal Login
                </h2>
                <p class="text-[8px] text-gray-400 mt-0.5 uppercase tracking-widest">Sign in to access your account</p>
            </div>

            <div class="bg-white rounded-[1.5rem] shadow-2xl shadow-red-500/10 border border-gray-100 p-5 md:p-7">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Portal Badge -->
                <div class="flex justify-center mb-5">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-xl bg-red-50 text-autocheck-red text-[9px] font-black uppercase tracking-[0.2em]">
                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Unified Login Access
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Email Address</label>
                        <input id="email" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-[11px]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px]" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between px-1 mb-1">
                            <label for="password" class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-[8px] font-bold text-autocheck-red hover:underline" href="{{ route('password.request') }}">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" class="w-full pl-4 pr-12 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-[11px]"
                                            :type="showPassword ? 'text' : 'password'"
                                            name="password"
                                            required autocomplete="current-password" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors z-10">
                                <template x-if="!showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </template>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px]" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center px-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-autocheck-red shadow-sm focus:ring-autocheck-red focus:ring-offset-0 w-4 h-4" name="remember">
                            <span class="ms-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Keep me signed in</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 bg-autocheck-red shadow-red-500/20 hover:bg-red-700 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 transform">
                            Secure Log In
                        </button>
                    </div>

                    <div class="mt-4 text-center border-t border-gray-50 pt-4">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">New here?</p>
                        <a href="{{ route('register') }}" class="inline-flex items-center text-[10px] font-black text-autocheck-red hover:text-red-700 transition-colors uppercase tracking-widest">
                            Create Customer Account
                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </form>
            </div>

            {{-- Back to Website --}}
            <div class="mt-8 text-center pb-6">
                <a href="/" class="text-[10px] font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest flex items-center justify-center group">
                    <svg class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Home
                </a>
            </div>
        </div>
    </body>
</html>
