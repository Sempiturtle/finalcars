<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About Us - AutoCheck Enterprises</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .bg-autocheck-red { background-color: #F53003; }
            .text-autocheck-red { color: #F53003; }
            .border-autocheck-red { border-color: #F53003; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 border-t-4 border-autocheck-red">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100" x-data="{ open: false }">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                        <a href="/" class="flex-shrink-0 flex items-center space-x-3">
                            <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-12 w-12 rounded-full object-cover border-2 border-autocheck-red shadow-sm">
                            <span class="text-xl font-bold tracking-tight text-gray-900">
                                AutoCheck <span class="text-autocheck-red">Enterprises</span>
                            </span>
                        </a>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="/" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Home</a>
                        <a href="{{ route('about') }}" class="text-sm font-bold text-autocheck-red transition-colors">About</a>
                        <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Announcements & Updates</a>
                        <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Services</a>
                        <a href="{{ route('features.index') }}" class="text-sm font-medium text-gray-700 hover:text-autocheck-red transition-colors">Features</a>

                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-lg shadow-red-500/30">
                            Login
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
            <div x-show="open" class="md:hidden bg-white border-b border-gray-200 py-4 px-4 space-y-2">
                <a href="/" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-2 text-base font-bold text-autocheck-red bg-red-50 rounded-lg">About</a>
                <a href="{{ route('announcements.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Announcements</a>
                <a href="{{ route('services.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Services</a>
                <a href="{{ route('features.index') }}" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg">Features</a>
                <hr class="my-2 border-gray-100">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-bold text-autocheck-red uppercase tracking-wider">Login</a>
            </div>
        </header>

        <main>
            <!-- Page Header -->
            <section class="bg-white py-20 border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl sm:text-6xl font-black text-gray-900 mb-6 tracking-tight">
                        About <span class="text-autocheck-red">AutoCheck Enterprises</span>
                    </h1>
                    <p class="text-xl text-gray-500 font-medium max-w-3xl mx-auto">
                        Your Trusted Automotive Service Provider Since 2017.
                    </p>
                </div>
            </section>

            <!-- Content Section -->
            <section class="py-24 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                        <!-- Text Content -->
                        <div class="lg:w-1/2 space-y-8 order-2 lg:order-1">
                            <div class="space-y-6 text-gray-600 font-medium leading-relaxed text-lg text-justify">
                                <p>
                                    AutoCheck Enterprises is a trusted automotive service provider located along Aguinaldo Highway, Dasmariñas, Cavite. Established in 2017 by Mr. Mark Paul Colocado, the company has grown from a small vehicle parts business into a full-service automotive maintenance and repair shop.
                                </p>
                                <p>
                                    With a strong commitment to quality, safety, and customer satisfaction, AutoCheck Enterprises delivers reliable solutions to meet the growing demands of vehicle owners in the community.
                                </p>
                                <p>
                                    AutoCheck Enterprises was founded with the goal of providing professional, reliable, and honest automotive services. The company understands the importance of vehicles in everyday life—whether for work, business, or personal use. That is why AutoCheck is committed to extending vehicle lifespan, improving performance, and ensuring road safety.
                                </p>
                                <p>
                                    From a single-branch service provider, AutoCheck Enterprises has built a solid reputation based on transparency, integrity, and customer care. The company continuously adapts to new automotive technologies to remain competitive in the ever-evolving automotive industry.
                                </p>
                            </div>
                            
                            <div class="flex items-center space-x-6 pt-6 border-t border-gray-200">
                                <div class="flex -space-x-4">
                                    <div class="w-14 h-14 rounded-full border-4 border-white bg-autocheck-red text-white flex items-center justify-center font-bold shadow-lg">9Y+</div>
                                    <div class="w-14 h-14 rounded-full border-4 border-white bg-gray-900 text-white flex items-center justify-center font-bold shadow-lg">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.45l8.15 14.1H3.85L12 5.45z"/></svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-black text-lg">Serving Since 2017</p>
                                    <p class="text-gray-500 text-sm font-bold uppercase tracking-widest">Excellence in Service</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Section -->
                        <div class="lg:w-1/2 order-1 lg:order-2">
                            <div class="relative group">
                                <div class="aspect-[4/3] bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-gray-100 p-4">
                                    <div class="w-full h-full rounded-[2rem] bg-gray-100 overflow-hidden relative">
                                         <img src="{{ asset('images/autocheck-facility.jpg') }}" alt="AutoCheck Facility" class="w-full h-full object-cover transform scale-100 group-hover:scale-110 transition-transform duration-700">
                                         <!-- Overlay for better contrast -->
                                         <div class="absolute inset-0 bg-gradient-to-tr from-black/20 to-transparent pointer-events-none"></div>
                                    </div>
                                </div>
                                <!-- Circular Badge -->
                                <div class="absolute -bottom-8 -right-8 w-44 h-44 bg-autocheck-red rounded-full flex items-center justify-center text-white text-center p-6 shadow-2xl transform rotate-12 transition-all hover:rotate-0 hover:scale-105 duration-500 z-10">
                                    <p class="font-black text-lg leading-tight uppercase tracking-tighter">Your Road Safety Partner</p>
                                </div>
                                <!-- Decorative Shape -->
                                <div class="absolute -top-10 -left-10 w-40 h-40 bg-red-100 rounded-full blur-3xl -z-10 opacity-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mission/Vision Cards -->
            <section class="py-24 bg-white">
                 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                        <div class="p-10 lg:p-12 rounded-[3.5rem] bg-gray-50 border-2 border-gray-200 flex flex-col justify-center space-y-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="w-20 h-20 bg-white shadow-xl rounded-2xl flex items-center justify-center text-autocheck-red border border-gray-100">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black text-gray-900 mb-4">Our Mission</h3>
                                <p class="text-lg text-gray-600 font-medium leading-relaxed">To provide professional, reliable, and honest automotive services that extend vehicle lifespan and ensure the safety of every driver in our community.</p>
                            </div>
                        </div>
                        <div class="p-10 lg:p-12 rounded-[3.5rem] bg-autocheck-red text-white flex flex-col justify-center space-y-6 shadow-2xl shadow-red-500/20 border-2 border-white/30 hover:scale-[1.02] transition-transform duration-300">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/30">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black mb-4">Our Vision</h3>
                                <p class="text-lg font-medium opacity-90 leading-relaxed">To be the most trusted name in automotive care in Cavite, setting the standard for transparency and technical excellence.</p>
                            </div>
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
                        <p class="text-gray-400 max-sm mb-8 leading-relaxed">
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
                            <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
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
