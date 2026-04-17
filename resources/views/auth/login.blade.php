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
    </head>
    <body class="antialiased bg-gray-50 flex items-center justify-center min-h-screen p-4 border-t-8 border-autocheck-red">
        @php $loginType = request('type', 'customer'); @endphp
        <div class="max-w-[400px] w-full">
            <div class="text-center mb-8">
                <a href="/" class="inline-flex flex-col items-center space-y-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-14 w-14 rounded-full object-cover border-2 {{ $loginType === 'admin' ? 'border-autocheck-red' : 'border-blue-500' }} shadow-xl">
                    <span class="text-2xl font-black tracking-tight text-gray-900">AutoCheck</span>
                </a>
                <h2 class="text-lg font-bold text-gray-600">
                    {{ $loginType === 'admin' ? 'Admin Portal' : 'Customer Portal' }}
                </h2>
                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Sign in to access your account</p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl {{ $loginType === 'admin' ? 'shadow-red-500/10' : 'shadow-blue-500/10' }} border border-gray-100 p-8 md:p-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Portal Badge -->
                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-xl {{ $loginType === 'admin' ? 'bg-red-50 text-autocheck-red' : 'bg-blue-50 text-blue-600' }} text-[10px] font-black uppercase tracking-[0.2em]">
                        @if($loginType === 'admin')
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Administrator
                        @else
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Customer
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Email Address</label>
                        <input id="email" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-xs" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px]" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between px-1 mb-1.5">
                            <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold text-autocheck-red hover:underline" href="{{ route('password.request') }}">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-xs"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
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
                        <button type="submit" class="w-full py-4 {{ $loginType === 'admin' ? 'bg-autocheck-red shadow-red-500/20 hover:bg-red-700' : 'bg-blue-600 shadow-blue-500/20 hover:bg-blue-700' }} text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 transform">
                            Secure Log In
                        </button>
                    </div>

                    @if($loginType === 'customer')
                        <div class="mt-6 text-center border-t border-gray-50 pt-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">New here?</p>
                            <a href="{{ route('register') }}" class="inline-flex items-center text-[10px] font-black text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-widest">
                                Create Account
                                <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="text-[10px] font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest flex items-center justify-center group">
                    <svg class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Home
                </a>
            </div>
        </div>
    </body>
</html>
