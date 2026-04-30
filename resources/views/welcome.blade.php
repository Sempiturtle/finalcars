<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AutoCheck Enterprises</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            .bg-autocheck-red {
                background-color: #F53003;
            }
            .text-autocheck-red {
                color: #F53003;
            }
            .border-autocheck-red {
                border-color: #F53003;
            }
        </style>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 border-t-4 border-autocheck-red">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100" x-data="{ open: false, loginOpen: false }">
            <nav class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-20">
                <div class="flex justify-between h-20">
                        <a href="/" class="flex-shrink-0 flex items-center space-x-3">
                            <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-12 w-12 rounded-full object-cover border-2 border-autocheck-red shadow-sm">
                            <span class="text-xl font-bold tracking-tight text-gray-900">
                                AutoCheck <span class="text-autocheck-red">Enterprises</span>
                            </span>
                        </a>

                    <!-- Desktop Nav -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="#home" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Home</a>
                        <a href="{{ route('about') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">About</a>
                        <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Announcements & Updates</a>
                        <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Services</a>
                        <a href="{{ route('features.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Features</a>

                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-autocheck-red hover:bg-red-700 focus:outline-none transition-all shadow-lg shadow-red-500/30">
                            Register
                            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </a>

                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-autocheck-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-lg shadow-red-500/30">
                            Login
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile Nav -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="md:hidden bg-white/95 backdrop-blur-xl border-b border-gray-200 py-6 px-6 space-y-4 shadow-2xl relative z-40">
                <a href="#home" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-gray-50 rounded-2xl transition-colors">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-gray-50 rounded-2xl transition-colors">About</a>
                <a href="{{ route('announcements.index') }}" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-gray-50 rounded-2xl transition-colors">Announcements</a>
                <a href="{{ route('services.index') }}" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-gray-50 rounded-2xl transition-colors">Services</a>
                <a href="{{ route('features.index') }}" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-gray-50 rounded-2xl transition-colors">Features</a>
                <div class="grid grid-cols-1 gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('register') }}" class="flex items-center justify-center px-6 py-4 rounded-2xl text-base font-black bg-autocheck-red text-white shadow-lg shadow-red-500/20 uppercase tracking-widest">Register Now</a>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-4 rounded-2xl text-base font-black bg-gray-900 text-white uppercase tracking-widest">Login</a>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section id="home" class="relative bg-white overflow-hidden py-16 sm:py-24 md:py-32">
                <div class="absolute inset-0 z-0">
                    <div class="absolute right-0 top-0 h-full w-full md:w-1/2 bg-gray-50 skew-x-0 md:skew-x-12 transform origin-right"></div>
                </div>
                
                <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-20 relative z-10">
                    <div class="lg:flex lg:items-center lg:space-x-16">
                        <div class="lg:w-1/2 text-center lg:text-left">
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-50 text-autocheck-red text-[10px] font-black mb-8 animate-pulse uppercase tracking-[0.3em]">
                                Est. 2017
                            </div>
                            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight leading-[1.1] mb-8">
                                Welcome to <br>
                                <span class="text-autocheck-red">AutoCheck Enterprises</span>
                            </h1>
                            <p class="text-lg sm:text-2xl text-gray-600 font-medium mb-10 max-w-lg mx-auto lg:mx-0">
                                Your Trusted Automotive Service Provider
                            </p>
                            
                            <div class="flex flex-col items-center lg:items-start space-y-4 mb-12">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 bg-white shadow-lg rounded-2xl border border-gray-100">
                                        <svg class="h-6 w-6 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-xs sm:text-sm font-bold text-gray-900">Aguinaldo Highway, Dasmariñas, Cavite</p>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-relaxed">Main Service Facility</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 border border-transparent text-sm font-black rounded-[1.5rem] text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-xl shadow-red-500/30 active:scale-95 transform uppercase tracking-widest">
                                    Get Started
                                </a>
                                <a href="{{ route('about') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 border-2 border-gray-100 text-sm font-black rounded-[1.5rem] text-gray-900 bg-white hover:bg-gray-50 transition-all active:scale-95 transform uppercase tracking-widest">
                                    Learn More
                                </a>
                            </div>
                        </div>

                        <div class="hidden lg:block lg:w-1/2 relative">
                            <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-transform duration-500">
                                <img src="{{ asset('images/hero-interior.jpg') }}" alt="AutoCheck Interior" class="w-full h-auto">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-8">
                                    <p class="text-white text-2xl font-bold">Premium Parts & Service.</p>
                                    <p class="text-gray-300">Equipping your ride for the journey ahead.</p>
                                </div>
                            </div>
                            <!-- Decorative elements -->
                            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-autocheck-red/10 rounded-full blur-3xl"></div>
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-red-500/10 rounded-full blur-3xl"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Quick Look -->
            <section id="features" class="bg-gray-50 py-20 md:py-24">
                <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center text-center transition-all hover:shadow-xl hover:border-autocheck-red/10">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Real-time Tracking</h3>
                            <p class="text-gray-600">Monitor your vehicle's maintenance status and history in real-time from anywhere.</p>
                        </div>
                        <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center text-center transition-all hover:shadow-xl hover:border-autocheck-red/10">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Smart Alerts</h3>
                            <p class="text-gray-600">Receive automated notifications when your next preventive maintenance is due.</p>
                        </div>
                        <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center text-center transition-all hover:shadow-xl hover:border-autocheck-red/10">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Certified Services</h3>
                            <p class="text-gray-600">All maintenance is carried out by certified automotive professionals since 2017.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials: Voices of Performance -->
            <section class="py-24 bg-white overflow-hidden">
                <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-20">
                    <div class="text-center mb-16">
                        <span class="text-autocheck-red font-black text-[10px] uppercase tracking-[0.5em] mb-6 block italic">Client Verdict</span>
                        <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tighter uppercase leading-none">VOICES OF <br><span class="text-autocheck-red italic">PERFORMANCE.</span></h2>
                    </div>

                    @if($reviews->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @foreach($reviews as $review)
                                <div class="bg-gray-50 p-10 rounded-[2.5rem] border border-gray-100 relative group hover:bg-white hover:shadow-2xl hover:border-autocheck-red/10 transition-all duration-500">
                                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-autocheck-red transform -rotate-12 group-hover:rotate-0 transition-transform">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11H12.017V9C12.017 6.79086 13.8079 5 16.017 5H19.017C21.2261 5 23.017 6.79086 23.017 9V15C23.017 18.3137 20.3307 21 17.017 21H14.017ZM2.017 21L2.017 18C2.017 16.8954 2.91243 16 4.017 16H7.017C7.56928 16 8.017 15.5523 8.017 15V9C8.017 8.44772 7.56928 8 7.017 8H3.017C2.46472 8 2.017 8.44772 2.017 9V11H0.017V9C0.017 6.79086 1.80786 5 4.017 5H7.017C9.22614 5 11.017 6.79086 11.017 9V15C11.017 18.3137 8.33071 21 5.017 21H2.017Z"/></svg>
                                    </div>
                                    <div class="flex items-center space-x-1 mb-6">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="h-4 w-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 font-medium leading-relaxed italic mb-8">"{{ $review->comment }}"</p>
                                    <div class="flex items-center space-x-4">
                                        <div class="h-10 w-10 bg-autocheck-red rounded-full flex items-center justify-center text-white font-black text-xs">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 leading-none">{{ $review->user->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Verified Client</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Mock Testimonials if none exist in DB -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-gray-50 p-10 rounded-[2.5rem] border border-gray-100">
                                <div class="flex items-center space-x-1 mb-6 text-yellow-400">
                                    @for($i = 0; $i < 5; $i++) <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                                </div>
                                <p class="text-gray-600 font-medium leading-relaxed italic mb-8">"AutoCheck has completely transformed how I manage my fleet. The real-time tracking is second to none."</p>
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 bg-gray-900 rounded-full flex items-center justify-center text-white font-black text-xs">J</div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900 leading-none">James Rodriguez</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Fleet Manager</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more mock cards here if needed -->
                        </div>
                    @endif
                </div>
            </section>

            <!-- Operational Ticker: Senior Product Architect Feature -->
            <section class="py-8 bg-gray-900 overflow-hidden relative border-y border-white/5">
                <div class="flex items-center animate-ticker whitespace-nowrap">
                    @for($i = 0; $i < 10; $i++)
                        <div class="flex items-center space-x-12 px-6">
                            <span class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em] flex items-center">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-3 animate-pulse"></span>
                                Live Performance Pulse
                            </span>
                            <span class="text-white text-xs font-bold uppercase tracking-widest">
                                <span class="text-autocheck-red">{{ number_format($recentServicesCount + 1500) }}+</span> Services Completed this month
                            </span>
                            <span class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">
                                Average Latency: 14ms
                            </span>
                            <span class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">
                                Technical Uptime: 99.99%
                            </span>
                        </div>
                    @endfor
                </div>
            </section>

            <style>
                @keyframes ticker {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
                .animate-ticker {
                    display: inline-flex;
                    animation: ticker 60s linear infinite;
                }
                .animate-ticker:hover {
                    animation-play-state: paused;
                }
            </style>

        </main>

        <!-- Footer -->
        <footer class="bg-gray-950 text-white pt-16 pb-10">
            <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-3 mb-8">
                            <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-10 w-10 rounded-full object-cover border border-autocheck-red">
                            <span class="text-2xl font-bold">AutoCheck <span class="text-autocheck-red">Enterprises</span></span>
                        </div>
                        <p class="text-gray-400 max-w-sm mb-8 leading-relaxed">
                            Your Trusted Automotive Service Provider Since 2017. Dedicated to keeping your vehicle in peak condition through professional preventive maintenance tracking.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center hover:bg-autocheck-red transition-colors border border-gray-800"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center hover:bg-autocheck-red transition-colors border border-gray-800"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.245 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.063 1.366-.333 2.633-1.308 3.608-.975.975-2.242 1.245-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.063-2.633-.333-3.608-1.308-.975-.975-1.245-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.245 3.608-1.308 1.266-.058 1.646-.07 4.85-.07m0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.058-1.689-.072-4.948-.072zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center hover:bg-autocheck-red transition-colors border border-gray-800"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-bold mb-8 uppercase tracking-widest text-autocheck-red">Quick Links</h4>
                        <ul class="space-y-4 text-gray-400 font-medium">
                            <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About</a></li>
                            <li><a href="{{ route('services.index') }}" class="hover:text-white transition-colors">Services</a></li>
                            <li><a href="{{ route('announcements.index') }}" class="hover:text-white transition-colors">Announcements & Updates</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold mb-8 uppercase tracking-widest text-autocheck-red">Contact Us</h4>
                        <ul class="space-y-4 text-gray-400 font-medium">
                            <li class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-autocheck-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Aguinaldo Highway, Dasmariñas, Cavite</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-autocheck-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>(046) 123-4567</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-900 pt-12 text-center text-sm text-gray-500 font-medium">
                    <p class="mb-4">© 2026 AutoCheck Enterprises.</p>
                    <p>All rights reserved. | Founded by Mr. Mark Paul Colocado</p>
                </div>
            </div>
        </footer>
    </body>
</html>
