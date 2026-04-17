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
            .bg-autocheck-red { background-color: #F53003; }
            .text-autocheck-red { color: #F53003; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 flex items-center justify-center min-h-screen py-12 px-4 border-t-8 border-autocheck-red">
        <div class="max-w-lg w-full">
            <div class="text-center mb-8">
                <a href="/" class="inline-flex flex-col items-center space-y-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-14 w-14 rounded-full object-cover border-2 border-blue-500 shadow-xl">
                    <span class="text-2xl font-black tracking-tight text-gray-900">AutoCheck</span>
                </a>
                <h2 class="text-lg font-bold text-gray-600">Join the Fleet</h2>
                <p class="text-xs text-gray-400 mt-1">Create your customer account to start tracking</p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-500/10 border border-gray-100 p-8 md:p-10">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Full Name</label>
                            <input id="name" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" />
                            @error('name') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Username</label>
                            <input id="username" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="text" name="username" value="{{ old('username') }}" required placeholder="johndoe123" />
                            @error('username') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Phone</label>
                            <input id="phone" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="text" name="phone" value="{{ old('phone') }}" required placeholder="09XX XXX XXXX" />
                            @error('phone') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Email Address</label>
                            <input id="email" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com" />
                            @error('email') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Password</label>
                            <input id="password" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="password" name="password" required />
                            @error('password') <p class="mt-1 text-[10px] font-bold text-red-500 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Confirm</label>
                            <input id="password_confirmation" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" type="password" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-blue-600 shadow-blue-500/20 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 transform">
                            Create Account
                        </button>
                    </div>

                    <div class="mt-6 text-center border-t border-gray-50 pt-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">
                            Got an account? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 ml-1">Sign In</a>
                        </p>
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
