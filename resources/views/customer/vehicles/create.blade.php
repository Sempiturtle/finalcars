<x-customer-layout>
    <div class="max-w-4xl mx-auto space-y-8 relative" 
        x-data="{
            services: [],
            serviceTypes: {{ json_encode($serviceTypes->pluck('name')) }},
            prices: {{ json_encode($serviceTypes->pluck('base_cost', 'name')) }},
            addService() {
                this.services.push({ type: '', mode: 'Walk-in', cost: '' });
            },
            removeService(index) {
                this.services.splice(index, 1);
            },
            get totalCost() {
                return this.services.reduce((sum, service) => sum + (parseFloat(service.cost) || 0), 0).toFixed(2);
            }
        }"
    >
        <!-- Watermark Background -->
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.2] overflow-hidden">
            <img src="{{ asset('images/background.jfif') }}" alt="" class="w-full h-full object-cover grayscale brightness-90">
        </div>

        <!-- Header -->
        <div class="flex items-center space-x-6 relative z-10">
            <a href="{{ route('customer.vehicles.index') }}" class="p-3 bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-autocheck-red transition-all shadow-sm">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Register <span class="text-autocheck-red italic">Your Vehicle</span></h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1 italic">Add vehicle details and initial service history.</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-64 h-64 bg-autocheck-red/5 rounded-full"></div>
            
            <form action="{{ route('customer.vehicles.store') }}" method="POST" class="relative z-10 space-y-10">
                @csrf
                
                <!-- Section: Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3 text-autocheck-red">01</span>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 px-1">Plate Number</label>
                            <input type="text" name="plate_number" value="{{ old('plate_number') }}" required
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-black uppercase tracking-widest focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="ABC 1234">
                            @error('plate_number') <p class="text-[10px] font-bold text-red-500 ml-2 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 px-1">Vehicle Make</label>
                            <input type="text" name="make" value="{{ old('make') }}" required
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. Toyota">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Vehicle Model</label>
                            <input type="text" name="model" value="{{ old('model') }}" required
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. Vios">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Year</label>
                            <input type="text" name="year" value="{{ old('year') }}" required
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="2024">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Color</label>
                            <input type="text" name="color" value="{{ old('color') }}"
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. White">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Registration Date</label>
                            <input type="date" name="registration_date" value="{{ old('registration_date') }}"
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Next Service Date</label>
                            <input type="date" name="next_service_date" value="{{ old('next_service_date') }}"
                                   class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <!-- Section: Initial Service History (Ported from Admin) -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3 text-autocheck-red">02</span>
                            Service Records
                        </h3>
                        <button type="button" @click="addService()" class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black rounded-xl hover:bg-black transition-all uppercase tracking-widest flex items-center shadow-xl shadow-black/10">
                            <svg class="h-3 w-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Add Service Log
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(service, index) in services" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50/50 p-6 rounded-3xl border border-gray-100 group animate-fade-in text-left">
                                <div class="md:col-span-12 space-y-3 text-left">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Service Description</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button type="button" @click="open = !open" 
                                                class="flex items-center justify-between w-full px-6 py-4 bg-white border-2 border-gray-100 rounded-2xl text-xs font-black uppercase tracking-widest focus:border-autocheck-red transition-all group">
                                            <span x-text="service.type || 'Select Type'"></span>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden">
                                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                                <template x-for="type in serviceTypes">
                                                    <button type="button" 
                                                            @click="service.type = type; service.cost = prices[type] || 0; open = false"
                                                            class="w-full px-6 py-3.5 text-left text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors flex items-center justify-between group"
                                                            :class="service.type === type ? 'bg-gray-50 text-autocheck-red' : 'text-gray-500'">
                                                        <span x-text="type"></span>
                                                        <template x-if="service.type === type">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                        </template>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" :name="`services[${index}][type]`" x-model="service.type" required>
                                </div>

                                <div class="md:col-span-12 space-y-3 text-left">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Service Mode</label>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="mode in ['Walk-in', 'Towing', 'Home Service']">
                                            <button type="button" 
                                                @click="service.mode = mode"
                                                :class="service.mode === mode ? 'bg-gray-900 text-white shadow-lg shadow-black/20' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100'"
                                                class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all transform active:scale-95">
                                                <span x-text="mode"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <input type="hidden" :name="`services[${index}][mode]`" x-model="service.mode" required>
                                </div>
                                <div class="md:col-span-3 space-y-2 text-left">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Cost (PHP)</label>
                                    <input type="number" :name="`services[${index}][cost]`" x-model="service.cost" readonly step="0.1" min="0"
                                        class="block w-full px-5 py-3 bg-gray-100 border-transparent rounded-2xl text-xs font-black italic focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all cursor-not-allowed"
                                        placeholder="0.00">
                                </div>
                                <div class="md:col-span-1 flex items-center justify-center pb-2">
                                    <button type="button" @click="removeService(index)" class="p-2 text-gray-400 hover:text-autocheck-red transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="services.length === 0" class="text-center py-10 text-gray-300 text-[10px] font-black uppercase tracking-[0.2em] bg-gray-50 border-2 border-dashed border-gray-100 rounded-[2.5rem]">
                            No initial services logged.
                        </div>

                        <div class="flex justify-end items-center gap-6 pt-4" x-show="services.length > 0">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Cumulative Maintenance Investment</span>
                            <span class="text-2xl font-black text-autocheck-red" x-text="'₱' + parseFloat(totalCost).toLocaleString()"></span>
                            <input type="hidden" name="total_cost" :value="totalCost">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-xs">
                        <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase tracking-widest">By registering, you agree to receive maintenance notifications based on the timeline generated.</p>
                    </div>
                    <button type="submit" class="px-12 py-5 bg-autocheck-red text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-2xl shadow-red-500/30 active:scale-95 transform">
                        Complete Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-customer-layout>
