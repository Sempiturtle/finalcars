<x-customer-layout>
    <div class="max-w-4xl mx-auto space-y-8" 
         x-data="{
            services: {{ json_encode(old('services', $vehicle->services ?? [])) }},
            serviceTypes: {{ json_encode($serviceTypes->pluck('name')) }},
            prices: {{ json_encode($serviceTypes->pluck('base_cost', 'name')) }},
            addService() {
                this.services.push({ type: '', mode: 'Walk-in', cost: '', status: 'scheduled', notes: '', date: '{{ date('Y-m-d') }}' });
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

        <div class="bg-white rounded-[2rem] md:rounded-[3rem] p-6 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden z-10">
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

                        <!-- Next Service Date -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="next_service_date" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Next Service Date</label>
                                <span class="text-[8px] font-black text-autocheck-red uppercase tracking-tighter bg-red-50 px-2 py-0.5 rounded-full border border-red-100">Auto-calculated</span>
                            </div>
                            <input type="date" id="next_service_date" value="{{ $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->format('Y-m-d') : '' }}" disabled
                                   class="block w-full px-6 py-4 bg-gray-100 border-transparent rounded-2xl text-sm font-bold text-gray-400 cursor-not-allowed opacity-70">
                            <p class="text-[9px] text-gray-400 font-bold italic ml-1 mt-1">Based on the earliest pending service in your records.</p>
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
                                class="inline-flex items-center px-4 py-2 bg-red-50 border-2 border-red-100 text-[10px] font-black uppercase tracking-widest rounded-xl text-autocheck-red hover:bg-autocheck-red hover:text-white transition-all transform active:scale-95 shadow-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Add Record
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Section: Active Services (Pending) -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Active Job Order</h4>
                            <template x-for="(service, index) in services" :key="index">
                                <div x-show="service.status !== 'completed'" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50/50 p-6 rounded-2xl md:rounded-3xl border border-gray-100 group animate-fade-in text-left">
                                    <div class="md:col-span-12 space-y-3 text-left">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Service Description</label>
                                        <div class="relative" x-data="{ open: false }">
                                            <button type="button" @click="open = !open" 
                                                    class="flex items-center justify-between w-full px-6 py-4 bg-white border-2 border-gray-100 rounded-2xl text-xs font-black uppercase tracking-widest focus:border-autocheck-red transition-all group text-left">
                                                <span x-text="service.type || 'Select Type'"></span>
                                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 shrink-0" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
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

                                    <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-3 gap-6 py-4 border-t border-gray-100/50 mt-2">
                                        <!-- Service Mode -->
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Mode</label>
                                            <select :name="`services[${index}][mode]`" x-model="service.mode"
                                                class="block w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[11px] font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all uppercase">
                                                <option value="Walk-in">Walk-in</option>
                                                <option value="Towing">Towing</option>
                                                <option value="Home Service">Home Service</option>
                                            </select>
                                        </div>

                                        <!-- Service Date -->
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Date</label>
                                            <input type="date" :name="`services[${index}][date]`" x-model="service.date"
                                                class="block w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[11px] font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                        </div>

                                        <!-- Service Cost -->
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cost (PHP)</label>
                                            <input type="number" :name="`services[${index}][cost]`" x-model="service.cost" readonly
                                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-[11px] font-bold text-gray-400 cursor-not-allowed uppercase">
                                        </div>
                                    </div>

                                    <!-- Service Notes -->
                                    <div class="md:col-span-12 flex flex-col md:flex-row items-start md:items-center gap-4">
                                        <div class="flex-1 w-full">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Service Notes</label>
                                            <input type="text" :name="`services[${index}][notes]`" x-model="service.notes"
                                                class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[10px] font-bold focus:ring-1 focus:ring-autocheck-red transition-all"
                                                placeholder="Describe the issue or work needed (optional)">
                                        </div>
                                        <div class="pt-0 md:pt-6 w-full md:w-auto text-right">
                                            <button type="button" @click="removeService(index)" class="p-3 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-xl border border-gray-100 group w-full md:w-auto flex justify-center">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Verification Status (Read-only) -->
                                    <div class="md:col-span-12 flex items-center gap-2 mt-2">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Verification Status:</span>
                                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full" 
                                            :class="{
                                                'bg-green-100 text-green-600': service.status === 'completed',
                                                'bg-blue-100 text-blue-600': service.status === 'in progress',
                                                'bg-gray-100 text-gray-600': service.status === 'scheduled' || !service.status
                                            }" x-text="service.status || 'scheduled'"></span>
                                        <input type="hidden" :name="`services[${index}][status]`" x-model="service.status">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Section: Maintenance History (Completed) -->
                        <div class="space-y-4 pt-6 border-t border-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Maintenance History (Locked)</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <template x-for="(service, index) in services" :key="index">
                                    <div x-show="service.status === 'completed'" class="bg-green-50/30 border border-green-100/50 p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group transition-all hover:bg-green-50/50 text-left">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-green-100 shadow-sm shrink-0">
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-black text-gray-900 uppercase" x-text="service.type"></p>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">
                                                    <span x-text="service.date"></span> • <span x-text="service.mode"></span> • ₱<span x-text="parseFloat(service.cost).toLocaleString()"></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-left sm:text-right w-full sm:w-auto border-t sm:border-t-0 border-green-100/50 pt-3 sm:pt-0">
                                            <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest italic" x-text="service.notes || 'Service verified and completed'"></p>
                                        </div>
                                        <!-- Keep hidden inputs so data persists on save -->
                                        <input type="hidden" :name="`services[${index}][type]`" x-model="service.type">
                                        <input type="hidden" :name="`services[${index}][mode]`" x-model="service.mode">
                                        <input type="hidden" :name="`services[${index}][status]`" x-model="service.status">
                                        <input type="hidden" :name="`services[${index}][date]`" x-model="service.date">
                                        <input type="hidden" :name="`services[${index}][cost]`" x-model="service.cost">
                                        <input type="hidden" :name="`services[${index}][notes]`" x-model="service.notes">
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="services.filter(s => s.status === 'completed').length === 0" class="text-center py-6 bg-gray-50/30 rounded-2xl border border-dashed border-gray-100">
                                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">No completed records yet</p>
                            </div>
                        </div>

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
