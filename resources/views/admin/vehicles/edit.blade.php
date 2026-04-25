<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8 relative">
        <!-- Watermark Background -->
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.2] overflow-hidden">
            <img src="{{ asset('images/background.jfif') }}" alt="" class="w-full h-full object-cover grayscale brightness-90">
        </div>
        <!-- Header -->
        <div class="flex items-center justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Edit Vehicle</h1>
                <p class="text-gray-500 font-medium mt-1">Update information for vehicle: {{ $vehicle->plate_number }}</p>
            </div>
            <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-bold text-gray-600 hover:text-autocheck-red transition-all">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Fleet
            </a>
        </div>

        <div class="bg-white rounded-[2rem] md:rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden relative z-10">
            <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="p-6 md:p-12 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Plate Number -->
                    <div class="space-y-2">
                        <label for="plate_number" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Plate Number</label>
                        <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. ABC-1234">
                        @error('plate_number') <p class="text-xs text-autocheck-red font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Owner Name -->
                    <div class="space-y-2">
                        <label for="user_id" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Owner Name</label>
                        <select name="user_id" id="user_id" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                            <option value="">Select Owner</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $vehicle->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-xs text-autocheck-red font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>



                    <!-- Make -->
                    <div class="space-y-2">
                        <label for="make" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Make</label>
                        <input type="text" name="make" id="make" value="{{ old('make', $vehicle->make) }}" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. Toyota">
                    </div>

                    <!-- Model -->
                    <div class="space-y-2">
                        <label for="model" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. Camry">
                    </div>

                    <!-- Year -->
                    <div class="space-y-2">
                        <label for="year" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Year</label>
                        <input type="text" name="year" id="year" value="{{ old('year', $vehicle->year) }}" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. 2024">
                    </div>

                    <!-- Color -->
                    <div class="space-y-2">
                        <label for="color" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}"
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. Silver">
                    </div>

                    <!-- Registration Date -->
                    <div class="space-y-2">
                        <label for="registration_date" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Registration Date</label>
                        <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date', $vehicle->registration_date?->format('Y-m-d')) }}"
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                    </div>

                    <!-- Next Service Date -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="next_service_date" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Next Service Date</label>
                            <span class="text-[8px] font-black text-autocheck-red uppercase tracking-tighter bg-red-50 px-2 py-0.5 rounded-full border border-red-100">Auto-calculated</span>
                        </div>
                        <input type="date" id="next_service_date" value="{{ $vehicle->next_service_date ? $vehicle->next_service_date->format('Y-m-d') : '' }}" disabled
                            class="block w-full px-6 py-4 bg-gray-100 border-transparent rounded-2xl text-sm font-bold text-gray-400 cursor-not-allowed opacity-70">
                        <p class="text-[9px] text-gray-400 font-bold italic ml-1 mt-1">Based on the earliest pending service in your Job Order.</p>
                    </div>

                    <!-- Services & Cost Section -->
                    <div class="md:col-span-2 space-y-6" 
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
                                return this.services.reduce((sum, service) => sum + (parseFloat(service.cost) || 0), 0).toFixed(2);
                            }
                        }"
                    >
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-lg font-black text-gray-900">Service Records</h3>
                                <p class="text-gray-500 text-xs mt-1">Review and verify the services requested for this vehicle.</p>
                            </div>
                            <!-- Add Service button removed as requested -->
                        </div>

                        <div class="space-y-4">
                        <!-- Section: Active Services (Pending) -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Active Job Order</h4>
                            <template x-for="(service, index) in services" :key="index">
                                <div x-show="service.status !== 'completed'" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start bg-gray-50/50 p-6 rounded-3xl border border-gray-100 animate-fade-in">
                                    <div class="md:col-span-12 space-y-3 text-left">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Type</label>
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

                                        <!-- Service Status -->
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Status</label>
                                            <select :name="`services[${index}][status]`" x-model="service.status"
                                                class="block w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[11px] font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all uppercase"
                                                :class="{
                                                    'text-green-600': service.status === 'completed',
                                                    'text-blue-600': service.status === 'in progress',
                                                    'text-yellow-600': service.status === 'scheduled'
                                                }">
                                                <option value="scheduled">Scheduled</option>
                                                <option value="in progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                            </select>
                                        </div>

                                        <!-- Service Date -->
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Date</label>
                                            <input type="date" :name="`services[${index}][date]`" x-model="service.date"
                                                class="block w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[11px] font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                        </div>
                                    </div>

                                    <!-- Service Notes -->
                                    <div class="md:col-span-12 flex items-center gap-4">
                                        <div class="flex-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Internal Audit Notes</label>
                                            <input type="text" :name="`services[${index}][notes]`" x-model="service.notes"
                                                class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[10px] font-bold focus:ring-1 focus:ring-autocheck-red transition-all"
                                                placeholder="Service Notes (e.g. Oil level checked, Brakes adjusted)">
                                        </div>
                                        <div class="pt-6">
                                            <button type="button" @click="removeService(index)" class="p-3 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-xl border border-gray-100 group">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        <input type="hidden" :name="`services[${index}][cost]`" x-model="service.cost">
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
                                    <div x-show="service.status === 'completed'" class="bg-green-50/30 border border-green-100/50 p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group transition-all hover:bg-green-50/50">
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

                        <div x-show="services.length === 0" class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed border-gray-200 rounded-2xl">
                            No services added yet. Click "Add Service" to start.
                        </div>

                            <div x-show="services.length === 0" class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed border-gray-200 rounded-2xl">
                                No services added yet. Click "Add Service" to start.
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-4 pt-4 border-t border-gray-100" x-show="services.length > 0">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Estimated Cost</span>
                            <span class="text-2xl font-black text-autocheck-red" x-text="'₱' + totalCost"></span>
                            <input type="hidden" name="total_cost" :value="totalCost">
                        </div>

                        <!-- Manage Service Types Removed (Now in Sidebar) -->
                    </div>

                    <!-- Status -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="status" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status" id="status" required
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                            <option value="completed" {{ old('status', $vehicle->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="in progress" {{ old('status', $vehicle->status) == 'in progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="scheduled" {{ old('status', $vehicle->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="overdue" {{ old('status', $vehicle->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-10 py-4 bg-autocheck-red text-white font-black rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/30 uppercase tracking-widest text-xs">
                        Update Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
