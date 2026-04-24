<x-customer-layout>
    <div class="max-w-4xl mx-auto space-y-8" 
         x-data="{
            services: {{ json_encode(old('services', $vehicle->services ?? [])) }},
            serviceTypes: {{ json_encode($serviceTypes->pluck('name')) }},
            prices: {{ json_encode($serviceTypes->pluck('base_cost', 'name')) }},
            addService() {
                this.services.push({ type: '', mode: 'Walk-in', cost: '' });
            },
            removeService(index) {
                this.services.splice(index, 1);
            },
            get totalCost() {
                return this.services.reduce((sum, s) => sum + (parseFloat(s.cost) || 0), 0);
            }
         }" class="relative">
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
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Update <span class="text-autocheck-red italic">Vehicle Profile</span></h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1 italic">Manage your vehicle details and service history.</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-64 h-64 bg-autocheck-red/5 rounded-full"></div>
            
            <form action="{{ route('customer.vehicles.update', $vehicle) }}" method="POST" class="relative z-10 space-y-12 text-left">
                @csrf
                @method('PUT')
                
                <!-- Section 01: Core Details -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-autocheck-red text-white flex items-center justify-center text-xs font-black">01</div>
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">Basic Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 px-1">Plate Number</label>
                            <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-black uppercase tracking-widest focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="ABC 1234">
                            @error('plate_number') <p class="text-[10px] font-bold text-red-500 ml-2 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 px-1">Vehicle Make</label>
                            <input type="text" name="make" value="{{ old('make', $vehicle->make) }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. Toyota">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 px-1">Vehicle Model</label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. Vios">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Year</label>
                            <input type="text" name="year" value="{{ old('year', $vehicle->year) }}" required
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="2024">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Color</label>
                            <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all"
                                   placeholder="e.g. White">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Registration Date</label>
                            <input type="date" name="registration_date" value="{{ old('registration_date', $vehicle->registration_date ? \Carbon\Carbon::parse($vehicle->registration_date)->format('Y-m-d') : '') }}"
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Next Service Date</label>
                            <input type="date" name="next_service_date" value="{{ old('next_service_date', $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->format('Y-m-d') : '') }}"
                                   class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>
                    </div>
                </div>

                <!-- Section 02: Service History -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-autocheck-red text-white flex items-center justify-center text-xs font-black">02</div>
                            <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">Service Records</h2>
                        </div>
                        <button type="button" @click="addService()" 
                                class="inline-flex items-center px-4 py-2 border-2 border-autocheck-red text-[10px] font-black uppercase tracking-widest rounded-xl text-autocheck-red hover:bg-autocheck-red hover:text-white transition-all transform active:scale-95">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Add Record
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(service, index) in services" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50/50 p-6 rounded-3xl border border-gray-100 group animate-fade-in text-left">
                                <div class="md:col-span-12 space-y-3 text-left">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Service Description</label>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="type in serviceTypes">
                                            <button type="button" 
                                                @click="service.type = type; service.cost = prices[type] || 0"
                                                :class="service.type === type ? 'bg-autocheck-red text-white shadow-lg shadow-red-500/30' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100'"
                                                class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all transform active:scale-95">
                                                <span x-text="type"></span>
                                            </button>
                                        </template>
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
                                <div class="md:col-span-1 flex justify-center pb-2">
                                    <button type="button" @click="removeService(index)" class="p-2 text-gray-300 hover:text-red-500 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="services.length === 0" class="text-center py-12 bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-[2rem]">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No service records added yet</p>
                        </div>
                    </div>

                    <div class="flex justify-end p-6 bg-gray-50/50 rounded-3xl" x-show="services.length > 0">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Maintenance Value</p>
                            <p class="text-3xl font-black text-autocheck-red italic" x-text="'₱' + totalCost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit" class="px-12 py-5 bg-autocheck-red text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-2xl shadow-red-500/30 active:scale-95 transform">
                        Save Transitions
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-customer-layout>
