<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Edit Vehicle</h1>
                <p class="text-gray-500 font-medium mt-1">Update information for vehicle: {{ $vehicle->plate_number }}</p>
            </div>
            <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-bold text-gray-600 hover:text-autocheck-red transition-all">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Fleet
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="p-8 md:p-12 space-y-8">
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

                    <!-- Assigned Mechanic -->
                    <div class="space-y-2">
                        <label for="mechanic_name" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Assigned Mechanic</label>
                        <input type="text" name="mechanic_name" id="mechanic_name" value="{{ old('mechanic_name', $vehicle->mechanic_name) }}"
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. Juan Mechanic">
                        @error('mechanic_name') <p class="text-xs text-autocheck-red font-bold mt-1 ml-1">{{ $message }}</p> @enderror
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
                        <label for="next_service_date" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Next Service Date</label>
                        <input type="date" name="next_service_date" id="next_service_date" value="{{ old('next_service_date', $vehicle->next_service_date?->format('Y-m-d')) }}"
                            class="block w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                    </div>

                    <!-- Services & Cost Section -->
                    <div class="md:col-span-2 space-y-6" 
                        x-data="{
                            services: {{ json_encode(old('services', $vehicle->services ?? [])) }},
                            serviceTypes: [
                                'Transmission Service (Transmission fluid change and inspection)',
                                'General Inspection (Comprehensive vehicle safety inspection)',
                                'Oil Change (Regular oil change and filter replacement)',
                                'Brake Inspection (Complete brake system inspection)',
                                'Engine Tune-up (Full engine diagnostic and tune-up)',
                                'Regular Maintenance service'
                            ],
                            addService() {
                                this.services.push({ type: '', cost: '' });
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
                                <p class="text-gray-500 text-xs mt-1">Update services performed on this vehicle.</p>
                            </div>
                            <button type="button" @click="addService()" class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition-colors uppercase tracking-widest">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Service
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(service, index) in services" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start bg-gray-50/50 p-4 rounded-2xl border border-gray-50">
                                    <div class="md:col-span-7 space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Service Type</label>
                                        <select :name="`services[${index}][type]`" x-model="service.type" required
                                            class="block w-full px-4 py-3 bg-white border-transparent rounded-xl text-sm font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                            <option value="">Select Service Type</option>
                                            <template x-for="type in serviceTypes">
                                                <option :value="type" x-text="type" :selected="service.type === type"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="md:col-span-4 space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cost (PHP)</label>
                                        <input type="number" :name="`services[${index}][cost]`" x-model="service.cost" required step="0.01" min="0"
                                            class="block w-full px-4 py-3 bg-white border-transparent rounded-xl text-sm font-bold focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                                            placeholder="0.00">
                                    </div>
                                    <div class="md:col-span-1 flex items-center justify-center pt-6">
                                        <button type="button" @click="removeService(index)" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="services.length === 0" class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed border-gray-200 rounded-2xl">
                                No services added yet. Click "Add Service" to start.
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-4 pt-4 border-t border-gray-100" x-show="services.length > 0">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Estimated Cost</span>
                            <span class="text-2xl font-black text-autocheck-red" x-text="'₱' + totalCost"></span>
                            <input type="hidden" name="total_cost" :value="totalCost">
                        </div>
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
