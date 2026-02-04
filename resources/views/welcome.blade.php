<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AutoCheck Enterprises – Preventive Maintenance Tracking System</title>
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
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 border-t-4 border-autocheck-red">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100" x-data="{ open: false, loginOpen: false }">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                        <a href="/" class="flex-shrink-0 flex items-center space-x-3">
                            <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-12 w-12 rounded-full object-cover border-2 border-autocheck-red shadow-sm">
                            <span class="text-xl font-bold tracking-tight text-gray-900">
                                AutoCheck <span class="text-autocheck-red">Enterprises</span>
                                <span class="hidden lg:inline text-gray-400 font-normal text-sm ml-2">| Preventive Maintenance Tracking System</span>
                            </span>
                        </a>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Home</a>
                        <a href="{{ route('about') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">About</a>
                        <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Announcements & Updates</a>
                        <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Services</a>
                        <a href="{{ route('features.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Features</a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-autocheck-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-lg shadow-red-500/30">
                                Login
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                                <div class="py-1">
                                    <a href="{{ route('login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-3">
                                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span>Customer Login</span>
                                    </a>
                                    <a href="{{ route('login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-3">
                                        <div class="p-2 bg-red-50 rounded-lg text-autocheck-red">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <span>Admin Login</span>
                                    </a>
                                </div>
                            </div>
                        </div>
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
            <div x-show="open" class="md:hidden bg-white border-b border-gray-200 py-4 px-4 space-y-2">
                <a href="#home" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">About</a>
                <a href="{{ route('announcements.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Announcements</a>
                <a href="{{ route('services.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Services</a>
                <a href="{{ route('features.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Features</a>
                <hr class="my-2 border-gray-100">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-bold text-autocheck-red uppercase tracking-wider">Customer Login</a>
                <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-bold text-autocheck-red uppercase tracking-wider">Admin Login</a>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section id="home" class="relative bg-white overflow-hidden py-24 sm:py-32">
                <div class="absolute inset-0 z-0">
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gray-50 skew-x-12 transform origin-right"></div>
                </div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="lg:flex lg:items-center lg:space-x-16">
                        <div class="lg:w-1/2">
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-50 text-autocheck-red text-sm font-bold mb-8 animate-pulse">
                                <span class="uppercase tracking-widest">Est. 2017</span>
                            </div>
                            <h1 class="text-5xl sm:text-7xl font-extrabold text-gray-900 tracking-tight leading-main mb-8">
                                Welcome to <br>
                                <span class="text-autocheck-red">AutoCheck Enterprises</span>
                            </h1>
                            <p class="text-2xl text-gray-600 font-medium mb-12 max-w-lg">
                                Your Trusted Automotive Service Provider
                            </p>
                            
                            <div class="flex items-center space-x-4 mb-12">
                                <div class="p-3 bg-white shadow-lg rounded-2xl border border-gray-100">
                                    <svg class="h-6 w-6 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Aguinaldo Highway, Dasmariñas, Cavite</p>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold leading-relaxed">Main Service Facility</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-2xl text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-xl shadow-red-500/20">
                                    Explore Services
                                </a>
                                <a href="{{ route('about') }}" class="inline-flex items-center justify-center px-8 py-4 border border-gray-200 text-lg font-bold rounded-2xl text-gray-900 bg-white hover:bg-gray-50 transition-all">
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
            <section id="features" class="bg-gray-50 py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Real-time Tracking</h3>
                            <p class="text-gray-600">Monitor your vehicle's maintenance status and history in real-time from anywhere.</p>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Smart Alerts</h3>
                            <p class="text-gray-600">Receive automated notifications when your next preventive maintenance is due.</p>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Certified Services</h3>
                            <p class="text-gray-600">All maintenance is carried out by certified automotive professionals since 2017.</p>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="bg-gray-950 text-white pt-20 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <p class="mb-4">© 2026 AutoCheck Enterprises. Vehicle Preventive Maintenance Tracking System.</p>
                    <p>All rights reserved. | Founded by Mr. Mark Paul Colocado</p>
                </div>
            </div>
        </footer>
    </body>
</html>
