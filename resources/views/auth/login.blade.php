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
        <div class="max-w-md w-full" x-data="{ type: 'customer' }">
            <div class="text-center mb-10">
                <a href="/" class="inline-flex flex-col items-center space-y-4 mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-16 w-16 rounded-full object-cover border-2 border-autocheck-red shadow-xl">
                    <span class="text-3xl font-black tracking-tight text-gray-900">AutoCheck</span>
                </a>
                <h2 class="text-xl font-bold text-gray-600">Access your portal</h2>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-red-500/10 border border-gray-100 p-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Type Selector -->
                <div class="flex p-1.5 bg-gray-100 rounded-2xl mb-10">
                    <button 
                        @click="type = 'customer'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="type === 'customer' ? 'bg-white text-gray-900 shadow-lg' : 'text-gray-500 hover:text-gray-700'"
                    >
                        Customer Login
                    </button>
                    <button 
                        @click="type = 'admin'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="type === 'admin' ? 'bg-autocheck-red text-white shadow-lg shadow-red-500/30' : 'text-gray-500 hover:text-gray-700'"
                    >
                        Admin Login
                    </button>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2 px-1">Email Address</label>
                        <input id="email" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-medium" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between px-1 mb-2">
                            <label for="password" class="text-sm font-bold text-gray-700 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-bold text-autocheck-red hover:underline" href="{{ route('password.request') }}">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-medium"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center px-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-autocheck-red shadow-sm focus:ring-autocheck-red focus:ring-offset-0 w-5 h-5" name="remember">
                            <span class="ms-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Keep me signed in</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-autocheck-red text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 active:scale-95 transform">
                            Secure Log In
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest flex items-center justify-center group">
                    <svg class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Website
                </a>
            </div>
        </div>
    </body>
</html>
