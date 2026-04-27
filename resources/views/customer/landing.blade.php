<x-customer-layout>
    <div class="bg-[#0F172A] min-h-screen text-white space-y-20 pb-20 overflow-x-hidden">
        <!-- Hero Section with Advanced Parallax -->
        <section class="relative h-[75vh] -mt-10 -mx-6 rounded-b-[4rem] overflow-hidden group select-none" aria-label="Customer Hero">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/pic6.png') }}" 
                     alt="Premium Automotive Performance"
                     class="w-full h-[130%] object-cover opacity-50 parallax-bg" 
                     id="customer-hero-parallax"
                     loading="eager"
                     decoding="async"
                     style="--scroll-offset: 0px;">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/40 to-transparent"></div>
            </div>
            
            <div class="relative h-full flex flex-col items-center justify-center text-center px-6 max-w-5xl mx-auto z-10">
                <div class="reveal inline-flex items-center px-5 py-2 bg-white/5 backdrop-blur-xl rounded-full border border-white/10 text-white text-[10px] font-black uppercase tracking-[0.4em] mb-10 shadow-2xl">
                    <span class="relative flex h-2 w-2 mr-3" aria-hidden="true">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-autocheck-red"></span>
                    </span>
                    Authenticated Premium Access
                </div>
                
                <h1 class="reveal text-6xl md:text-9xl font-black text-white tracking-tighter leading-none mb-10 uppercase transition-all duration-700 hover:tracking-normal cursor-default select-none">
                    COMMAND <br><span class="text-autocheck-red italic">CENTRAL.</span>
                </h1>
                
                <p class="reveal text-lg md:text-xl text-gray-400 font-medium max-w-2xl mb-14 leading-relaxed italic" style="transition-delay: 100ms">
                    Welcome back, <span class="text-white">{{ $user->name }}</span>. Your fleet is primed and your performance metrics are ready for review.
                </p>
                
                <div class="reveal flex flex-col md:flex-row gap-6" style="transition-delay: 200ms">
                    <a href="{{ route('customer.vehicles.index') }}" 
                       class="group relative px-14 py-6 bg-autocheck-red text-white text-xs font-black rounded-3xl overflow-hidden shadow-2xl shadow-red-500/30 uppercase tracking-[0.3em] transition-all hover:scale-105 active:scale-95 focus:ring-2 focus:ring-red-500 focus:outline-none"
                       aria-label="Enter your vehicle garage">
                        <span class="relative z-10">Enter Garage</span>
                        <div class="absolute inset-0 bg-white transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 opacity-20" aria-hidden="true"></div>
                    </a>
                    <a href="{{ route('customer.vehicles.create') }}" 
                       class="px-14 py-6 bg-white/5 backdrop-blur-xl text-white text-xs font-black rounded-3xl border border-white/10 hover:bg-white/10 transition-all uppercase tracking-[0.3em] shadow-xl focus:ring-2 focus:ring-white/20 focus:outline-none"
                       aria-label="Add a new vehicle to your fleet">
                        Add Vehicle
                    </a>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center space-y-2 opacity-40 animate-bounce-slow" aria-hidden="true">
                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-white">Scroll</span>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
        </section>

        <!-- Dynamic Metrics Grid (Bento Style) -->
        <section class="max-w-7xl mx-auto px-6" aria-label="Quick Metrics">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <article class="reveal glass p-6 rounded-[1.5rem] relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-autocheck-red/10 rounded-full blur-3xl group-hover:bg-autocheck-red/20 transition-colors" aria-hidden="true"></div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">Total Fleet</p>
                    <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($highlights['total_vehicles']) }}</p>
                    <p class="text-[9px] text-gray-400 mt-2 font-bold">Active Units</p>
                </article>
                
                <article class="reveal glass p-6 rounded-[1.5rem] border-autocheck-red/20" style="transition-delay: 100ms">
                    <p class="text-[10px] font-black text-autocheck-red uppercase tracking-[0.3em] mb-2">Loyalty Power</p>
                    <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($highlights['available_points']) }}</p>
                    <div class="mt-2 flex items-center text-[9px] text-red-400 font-bold uppercase tracking-widest">
                        <span>Points Available</span>
                        <svg class="w-3 h-3 ml-2 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </article>

                <article class="reveal glass p-6 rounded-[1.5rem] md:col-span-2 flex items-center justify-between group overflow-hidden" style="transition-delay: 200ms">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">System Status</p>
                        <p class="text-2xl font-black text-white tracking-tighter uppercase">Fully <span class="text-green-500">Operational</span></p>
                        <p class="text-[9px] text-gray-500 mt-2 font-bold">Latency: 14ms | Uptime: 99.9%</p>
                    </div>
                    <div class="relative w-20 h-20 opacity-20 group-hover:opacity-40 transition-opacity" aria-hidden="true">
                        <svg class="w-full h-full text-white animate-spin-slow" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="10 5" />
                        </svg>
                    </div>
                </article>
            </div>
        </section>

        <!-- Intelligent Service Bento Grid -->
        <section class="max-w-7xl mx-auto px-6 space-y-16" id="services" aria-label="Featured Services">
            <header class="flex flex-col md:flex-row md:items-end justify-between px-4">
                <div class="max-w-xl">
                    <h2 class="reveal text-xs font-black text-autocheck-red uppercase tracking-[0.5em] mb-6 block italic">The Service Catalog</h2>
                    <h3 class="reveal text-4xl md:text-7xl font-black text-white tracking-tighter uppercase leading-none" style="transition-delay: 100ms">PREMIUM <br>MAINTENANCE.</h3>
                </div>
                <a href="{{ route('customer.rewards.index') }}" class="reveal mt-8 md:mt-0 text-[11px] font-black text-gray-500 uppercase tracking-[0.3em] hover:text-white transition-colors border-b border-gray-800 pb-2" style="transition-delay: 200ms">
                    Redeem Loyalty Rewards →
                </a>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[300px]">
                <!-- Main Service Card -->
                <article class="reveal md:col-span-8 md:row-span-2 rounded-[3.5rem] overflow-hidden group relative">
                    <img src="{{ asset('images/pciture3.jfif') }}" 
                         alt="{{ $featuredServices[0]->name ?? 'Featured Service' }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-60"
                         loading="lazy"
                         decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-midnight via-midnight/40 to-transparent" aria-hidden="true"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <span class="px-4 py-1 rounded-full bg-autocheck-red text-white text-[10px] font-black uppercase tracking-widest">Featured Service</span>
                            <span class="text-white/60 text-xs font-bold">₱{{ number_format($featuredServices[0]->base_cost ?? 2500) }}</span>
                        </div>
                        <h4 class="text-4xl md:text-5xl font-black text-white mb-6 uppercase tracking-tighter">{{ $featuredServices[0]->name ?? 'Full Diagnostics' }}</h4>
                        <p class="text-gray-300 max-w-md font-medium leading-relaxed">
                            {{ $featuredServices[0]->description ?? 'Complete vehicle systems analysis using advanced digital diagnostic equipment.' }}
                        </p>
                        <button class="mt-10 px-8 py-4 glass rounded-2xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-white hover:text-black transition-all focus:outline-none focus:ring-2 focus:ring-white/50">Schedule Now</button>
                    </div>
                </article>

                <!-- Secondary Cards -->
                <article class="reveal md:col-span-4 rounded-[3rem] overflow-hidden group relative" style="transition-delay: 100ms">
                    <img src="{{ asset('images/pic3.png') }}" 
                         alt="Digital Pulse Service"
                         class="absolute inset-0 w-full h-full object-cover opacity-40 transition-transform duration-1000 group-hover:scale-110"
                         loading="lazy"
                         decoding="async">
                    <div class="absolute inset-0 bg-midnight/60" aria-hidden="true"></div>
                    <div class="absolute inset-0 p-10 flex flex-col justify-end">
                        <h4 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter">Digital Pulse</h4>
                        <p class="text-xs text-gray-400 font-medium italic">Real-time health monitoring.</p>
                    </div>
                </article>

                <article class="reveal md:col-span-4 rounded-[3rem] overflow-hidden group relative" style="transition-delay: 200ms">
                    <img src="{{ asset('images/pic4.png') }}" 
                         alt="Certified Parts"
                         class="absolute inset-0 w-full h-full object-cover opacity-40 transition-transform duration-1000 group-hover:scale-110"
                         loading="lazy"
                         decoding="async">
                    <div class="absolute inset-0 bg-autocheck-red/20" aria-hidden="true"></div>
                    <div class="absolute inset-0 p-10 flex flex-col justify-end">
                        <h4 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter">Certified Parts</h4>
                        <p class="text-xs text-white/60 font-medium italic">Genuine OEM Guaranteed.</p>
                    </div>
                </article>
            </div>

            <!-- Scrollable Service Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                @foreach($featuredServices->skip(1) as $service)
                    <article class="reveal glass p-10 rounded-[2.5rem] hover:bg-white/5 transition-all duration-500 group relative overflow-hidden" style="transition-delay: {{ $loop->index * 100 }}ms">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:bg-autocheck-red/5" aria-hidden="true"></div>
                        <div class="flex justify-between items-start mb-10">
                            <div class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-autocheck-red">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="text-xs font-black text-white italic">₱{{ number_format($service->base_cost, 0) }}</span>
                        </div>
                        <h4 class="text-xl font-black text-white uppercase tracking-tight mb-4 group-hover:text-autocheck-red transition-colors">{{ $service->name }}</h4>
                        <p class="text-xs text-gray-500 font-medium leading-relaxed mb-8 line-clamp-2">{{ $service->description }}</p>
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-600 group-hover:text-white transition-colors">
                            <span>+{{ $service->points_awarded }} Loyalty Points</span>
                            <svg class="w-4 h-4 transform translate-x-2 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Dynamic Timeline & Facility Showcase -->
        <section class="relative py-24 bg-slate-900 overflow-hidden" aria-label="Facility In-Depth">
            <div class="absolute inset-0 z-0 overflow-hidden">
                <img src="{{ asset('images/pic7.jpg') }}" 
                     alt="Facility Interior Background"
                     class="w-full h-[150%] object-cover opacity-20 parallax-bg" 
                     id="customer-facility-parallax"
                     loading="lazy"
                     decoding="async"
                     style="--scroll-offset: 0px;">
            </div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                <div>
                    <span class="reveal text-autocheck-red font-black text-[10px] uppercase tracking-[0.5em] mb-8 block italic">Operational Architecture</span>
                    <h3 class="reveal text-5xl md:text-7xl font-black text-white tracking-tighter uppercase mb-10 leading-none" style="transition-delay: 100ms">BUILT FOR <br><span class="italic">SPEED.</span></h3>
                    <p class="reveal text-gray-400 text-lg font-medium leading-relaxed mb-12" style="transition-delay: 200ms">
                        Our facility is a clinical ecosystem designed for maximum efficiency. Every technician is tracked, every part is logged, and every second is optimized for your vehicle's peak performance.
                    </p>
                    
                    <div class="space-y-6">
                        <article class="reveal glass-light p-6 rounded-3xl border-l-4 border-autocheck-red group hover:bg-white/10 transition-all" style="transition-delay: 300ms">
                            <h5 class="text-base font-black text-white mb-2 uppercase tracking-tight">Precision Workflow</h5>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">Standardized protocols ensuring 100% service consistency across all vehicles.</p>
                        </article>
                        <article class="reveal glass-light p-6 rounded-3xl group hover:bg-white/10 transition-all" style="transition-delay: 400ms">
                            <h5 class="text-base font-black text-white mb-2 uppercase tracking-tight">Next-Gen Tools</h5>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">Automated maintenance logs synced directly to your digital garage in real-time.</p>
                        </article>
                    </div>
                </div>

                <div class="reveal relative h-full min-h-[400px]" style="transition-delay: 500ms">
                    <div class="rounded-[4rem] overflow-hidden shadow-2xl border-4 border-white/5 relative group h-full">
                        <!-- Carousel Container -->
                        <div class="absolute inset-0 carousel-container">
                            <div class="carousel-slide active absolute inset-0 transition-opacity duration-1000 opacity-100">
                                <img src="{{ asset('images/picture5.jfif') }}" alt="Facility Highlight 1" class="w-full h-full object-cover">
                            </div>
                            <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                                <img src="{{ asset('images/picture6.jfif') }}" alt="Facility Highlight 2" class="w-full h-full object-cover">
                            </div>
                            <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                                <img src="{{ asset('images/picture7.jfif') }}" alt="Facility Highlight 3" class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Carousel Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-midnight via-transparent to-transparent pointer-events-none" aria-hidden="true"></div>
                        
                        <!-- Navigation Dots -->
                        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex space-x-3 z-20">
                            <button class="carousel-dot w-2 h-2 rounded-full bg-white opacity-100 transition-all" data-slide="0" aria-label="Slide 1"></button>
                            <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" data-slide="1" aria-label="Slide 2"></button>
                            <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" data-slide="2" aria-label="Slide 3"></button>
                        </div>
                    </div>
                    <!-- Decorative element -->
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-autocheck-red rounded-full blur-[100px] opacity-20" aria-hidden="true"></div>
                </div>
            </div>
        </section>

        <!-- Final CTA with Background Image -->
        <section class="max-w-7xl mx-auto px-6">
            <div class="relative rounded-[4rem] overflow-hidden group py-24 text-center border border-white/5">
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/picture8.jfif') }}" 
                         alt="Facility Exterior Dusk"
                         class="w-full h-full object-cover opacity-30 transition-transform duration-[3s] group-hover:scale-110"
                         loading="lazy"
                         decoding="async">
                    <div class="absolute inset-0 bg-midnight/80" aria-hidden="true"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-midnight/60 to-midnight" aria-hidden="true"></div>
                </div>

                <div class="relative z-10 max-w-3xl mx-auto px-6">
                    <h3 class="reveal text-5xl md:text-8xl font-black text-white tracking-tighter uppercase mb-10 leading-none">THE ROAD <br><span class="text-autocheck-red">AWAITS.</span></h3>
                    <p class="reveal text-gray-400 text-xl font-medium mb-12 italic" style="transition-delay: 100ms">Take the next step in professional vehicle management. Your garage is ready.</p>
                    <nav class="reveal flex flex-col sm:flex-row items-center justify-center gap-6" style="transition-delay: 200ms" aria-label="CTA Navigation">
                        <a href="{{ route('customer.vehicles.index') }}" 
                           class="w-full sm:w-auto px-16 py-6 bg-autocheck-red text-white text-xs font-black rounded-3xl uppercase tracking-[0.4em] shadow-2xl shadow-red-600/30 hover:scale-105 active:scale-95 transition-all focus:ring-2 focus:ring-red-500 focus:outline-none">
                           Enter Garage
                        </a>
                        <a href="{{ route('customer.profile.index') }}" 
                           class="w-full sm:w-auto px-16 py-6 glass text-white text-xs font-black rounded-3xl uppercase tracking-[0.4em] border border-white/10 hover:bg-white/5 transition-all focus:ring-2 focus:ring-white/20 focus:outline-none">
                           Profile Settings
                        </a>
                    </nav>
                </div>
            </div>
        </section>

    </div>

    <!-- Styles & Logic -->
    <style>
        .parallax-bg {
            transform: translateY(var(--scroll-offset, 0));
            will-change: transform;
            transition: transform 0.1s cubic-bezier(0, 0, 0.2, 1);
        }
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            will-change: transform, opacity;
            transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-light {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 20s linear infinite;
        }
        @keyframes bounce-x {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        .animate-bounce-x {
            animation: bounce-x 1s ease-in-out infinite;
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s ease-in-out infinite;
        }
        
        /* Smooth Scrollbar for Premium Feel */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #0F172A;
        }
        ::-webkit-scrollbar-thumb {
            background: #1E293B;
            border-radius: 5px;
            border: 3px solid #0F172A;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #F53003;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // optimized Parallax with requestAnimationFrame
            const heroParallax = document.getElementById('customer-hero-parallax');
            const facilityParallax = document.getElementById('customer-facility-parallax');
            let ticking = false;
            
            const updateParallax = () => {
                const scrolled = window.pageYOffset;
                
                if (heroParallax) {
                    heroParallax.style.setProperty('--scroll-offset', (scrolled * 0.4) + 'px');
                }
                
                if (facilityParallax) {
                    const section = facilityParallax.closest('section');
                    const rect = section.getBoundingClientRect();
                    const visible = rect.top < window.innerHeight && rect.bottom > 0;
                    if (visible) {
                        const offset = (window.innerHeight - rect.top) * 0.15;
                        facilityParallax.style.setProperty('--scroll-offset', (offset - 100) + 'px');
                    }
                }
                ticking = false;
            };

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            });

            // Intersection Observer with once: true for performance
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Optional: unobserve once visible for minor performance gain
                        // observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

            // Carousel Logic
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            let currentSlide = 0;

            const showSlide = (index) => {
                slides.forEach(s => {
                    s.classList.replace('opacity-100', 'opacity-0');
                    s.classList.remove('active');
                });
                dots.forEach(d => {
                    d.classList.replace('opacity-100', 'opacity-30');
                });

                slides[index].classList.replace('opacity-0', 'opacity-100');
                slides[index].classList.add('active');
                dots[index].classList.replace('opacity-30', 'opacity-100');
                currentSlide = index;
            };

            const nextSlide = () => {
                showSlide((currentSlide + 1) % slides.length);
            };

            let carouselInterval = setInterval(nextSlide, 4000);

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    clearInterval(carouselInterval);
                    showSlide(parseInt(dot.dataset.slide));
                    carouselInterval = setInterval(nextSlide, 4000);
                });
            });
        });
    </script>
    </div>
</x-customer-layout>

