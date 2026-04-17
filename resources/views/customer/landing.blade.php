<x-customer-layout>
    <div class="space-y-24 pb-20">
        <!-- Hero Section -->
        <section class="relative h-[85vh] -mt-6 -mx-6 rounded-b-[4rem] overflow-hidden group">
            <div class="absolute inset-0 bg-gray-900">
                <div class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center mix-blend-overlay"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-gray-900 via-gray-900/40 to-transparent"></div>
            </div>
            
            <div class="relative h-full flex flex-col items-center justify-center text-center px-6 max-w-5xl mx-auto">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] mb-8 animate-fade-in shadow-2xl">
                    <span class="w-2 h-2 bg-autocheck-red rounded-full mr-3 animate-pulse"></span>
                    Next-Gen Fleet Management
                </div>
                <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter leading-none uppercase mb-8">
                    Elevate Your <span class="text-autocheck-red italic">Drive.</span>
                </h1>
                <p class="text-xl text-gray-300 font-medium max-w-2xl mb-12 leading-relaxed">
                    AutoCheck is your professional partner in automotive precision. Track your fleet, earn elite rewards, and experience maintenance at the speed of thought.
                </p>
                <div class="flex flex-col md:flex-row gap-6">
                    <a href="{{ route('customer.vehicles.create') }}" class="px-12 py-5 bg-autocheck-red text-white text-xs font-black rounded-2xl hover:bg-white hover:text-black transition-all shadow-2xl shadow-red-500/20 uppercase tracking-[0.2em] transform hover:-translate-y-1">
                        Register Primary Vehicle
                    </a>
                    <a href="{{ route('customer.vehicles.index') }}" class="px-12 py-5 bg-white/10 backdrop-blur-md text-white text-xs font-black rounded-2xl border border-white/20 hover:bg-white/20 transition-all uppercase tracking-[0.2em]">
                        Manage garage
                    </a>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="h-6 w-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
        </section>

        <!-- Dynamic Metrics (Subtle Integration) -->
        <section class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2 italic">Active Fleet</p>
                    <p class="text-5xl font-black text-gray-900">{{ number_format($highlights['total_vehicles']) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2 italic">Loyalty Power</p>
                    <p class="text-5xl font-black text-autocheck-red">{{ number_format($highlights['available_points']) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2 italic">Service Access</p>
                    <p class="text-5xl font-black text-gray-900">24/7</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2 italic">System Uptime</p>
                    <p class="text-5xl font-black text-gray-900">{{ $highlights['system_uptime'] }}</p>
                </div>
            </div>
        </section>

        <!-- Core System Features -->
        <section class="max-w-7xl mx-auto px-6 space-y-20">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-xs font-black text-autocheck-red uppercase tracking-[0.4em] mb-4 italic">The Ecosystem</h2>
                <h3 class="text-4xl font-black text-gray-900 tracking-tight uppercase">Intelligent Architecture.</h3>
                <p class="text-gray-500 font-bold mt-4 italic">Precision tools designed to keep you on the road with absolute confidence.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="group">
                    <div class="w-16 h-16 bg-gray-900 text-white rounded-[1.5rem] flex items-center justify-center mb-8 transform group-hover:rotate-12 transition-transform shadow-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-4 group-hover:text-autocheck-red transition-colors">Real-time Pulse</h4>
                    <p class="text-sm text-gray-500 leading-relaxed font-medium">Automatic monitoring of your vehicle's health and service timelines. Receive alerts before issues become obstacles.</p>
                </div>
                <div class="group">
                    <div class="w-16 h-16 bg-gray-900 text-white rounded-[1.5rem] flex items-center justify-center mb-8 transform group-hover:rotate-12 transition-transform shadow-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-4 group-hover:text-autocheck-red transition-colors">Loyalty Engine</h4>
                    <p class="text-sm text-gray-500 leading-relaxed font-medium">Earn points for every service and redeem them for premium rewards. Your maintenance literally pays for itself.</p>
                </div>
                <div class="group">
                    <div class="w-16 h-16 bg-gray-900 text-white rounded-[1.5rem] flex items-center justify-center mb-8 transform group-hover:rotate-12 transition-transform shadow-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-4 group-hover:text-autocheck-red transition-colors">Direct Comms</h4>
                    <p class="text-sm text-gray-500 leading-relaxed font-medium">Instant messaging with our expert administrative team. Professional advice is always just one click away.</p>
                </div>
            </div>
        </section>

        <!-- Professional Service Menu -->
        <section class="max-w-7xl mx-auto px-6 py-24 bg-white rounded-[4rem] shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 px-10">
                <div class="max-w-xl">
                    <h2 class="text-xs font-black text-autocheck-red uppercase tracking-[0.4em] mb-4 italic">Our Expertise</h2>
                    <h3 class="text-5xl font-black text-gray-900 tracking-tighter uppercase">Service <span class="text-gray-300">Catalog.</span></h3>
                    <p class="text-gray-500 font-bold mt-4 italic">Explore our range of professional care programs tailored for your vehicle's longevity.</p>
                </div>
                <a href="{{ route('customer.rewards.index') }}" class="mt-8 md:mt-0 text-[10px] font-black text-autocheck-red uppercase tracking-widest hover:underline italic">View Point Requirements →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-10">
                @foreach($featuredServices as $service)
                    <div class="p-10 rounded-[2.5rem] bg-gray-50/50 hover:bg-gray-900 hover:text-white transition-all duration-500 group border border-transparent hover:border-gray-800">
                        <div class="flex items-center justify-between mb-8">
                            <span class="text-[9px] font-black text-autocheck-red uppercase tracking-[0.2em] italic border border-autocheck-red/30 px-3 py-1 rounded-full group-hover:border-white/30 group-hover:text-white transition-colors">Elite Care</span>
                            <p class="text-sm font-black italic tracking-tighter group-hover:text-autocheck-red">₱{{ number_format($service->base_cost, 0) }}</p>
                        </div>
                        <h4 class="text-2xl font-black uppercase tracking-tight mb-4">{{ $service->name }}</h4>
                        <p class="text-xs text-gray-500 font-medium group-hover:text-gray-400 transition-colors leading-relaxed line-clamp-2">
                            {{ $service->description ?? 'Professional maintenance protocol performed by certified AutoCheck engineers.' }}
                        </p>
                        <div class="mt-8 pt-8 border-t border-gray-200 group-hover:border-gray-800 flex items-center justify-between">
                            <p class="text-[10px] font-black uppercase tracking-widest">+{{ $service->points_awarded }} Points</p>
                            <svg class="h-5 w-5 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Onboarding Steps -->
        <section class="max-w-7xl mx-auto px-6 space-y-20">
            <div class="text-center">
                <h3 class="text-4xl font-black text-gray-900 tracking-tight uppercase">Operational <span class="text-autocheck-red italic">Workflow.</span></h3>
            </div>
            
            <div class="relative">
                <!-- Line -->
                <div class="absolute top-1/2 left-0 w-full h-px bg-gray-100 -translate-y-1/2 hidden md:block"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
                    <div class="text-center space-y-6">
                        <div class="w-12 h-12 bg-white border-4 border-gray-900 text-gray-900 rounded-full flex items-center justify-center mx-auto text-sm font-black italic mb-4">01</div>
                        <h5 class="text-sm font-black uppercase tracking-widest">Enroll Fleet</h5>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Register your vehicles via the garage.</p>
                    </div>
                    <div class="text-center space-y-6">
                        <div class="w-12 h-12 bg-white border-4 border-gray-900 text-gray-900 rounded-full flex items-center justify-center mx-auto text-sm font-black italic mb-4">02</div>
                        <h5 class="text-sm font-black uppercase tracking-widest">Schedule Care</h5>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Wait for admin to verify arrival.</p>
                    </div>
                    <div class="text-center space-y-6">
                        <div class="w-12 h-12 bg-white border-4 border-gray-900 text-gray-900 rounded-full flex items-center justify-center mx-auto text-sm font-black italic mb-4">03</div>
                        <h5 class="text-sm font-black uppercase tracking-widest">Earn Points</h5>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Collection starts on completion.</p>
                    </div>
                    <div class="text-center space-y-6">
                        <div class="w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center mx-auto text-sm font-black italic mb-4 animate-pulse shadow-xl shadow-black/30">04</div>
                        <h5 class="text-sm font-black uppercase tracking-widest text-autocheck-red">Elite Rewards</h5>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Redeem points for free services.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-5xl mx-auto px-6 py-20 bg-gray-900 rounded-[3rem] text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-autocheck-red/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 space-y-8">
                <h3 class="text-4xl font-black text-white uppercase tracking-tighter">Ready for the Next <span class="italic text-autocheck-red">Level?</span></h3>
                <p class="text-gray-400 font-medium max-w-sm mx-auto">Access your garage now to begin tracking your fleet's journey.</p>
                <div class="flex flex-col md:flex-row justify-center items-center gap-4">
                    <a href="{{ route('customer.vehicles.index') }}" class="px-10 py-4 bg-white text-black text-[10px] font-black rounded-xl hover:bg-autocheck-red hover:text-white transition-all uppercase tracking-widest">Enter Garage</a>
                    <a href="{{ route('customer.rewards.index') }}" class="px-10 py-4 bg-gray-800 text-white text-[10px] font-black rounded-xl hover:bg-gray-700 transition-all uppercase tracking-widest">Check Points</a>
                </div>
            </div>
        </section>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
        }
    </style>
</x-customer-layout>
