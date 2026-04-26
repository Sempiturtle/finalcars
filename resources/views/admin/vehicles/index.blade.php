<x-admin-layout>
    <div x-data="{ 
        showVerifyModal: false, 
        showStartModal: false,
        currentVehicleId: null, 
        currentPlate: '', 
        pendingServices: [], 
        scheduledServices: [],
        selectedServices: [], 
        notes: '',
        openVerify(id, plate, services) {
            this.currentVehicleId = id;
            this.currentPlate = plate;
            this.pendingServices = services;
            this.selectedServices = services.map(s => String(s.original_index));
            this.notes = '';
            this.showVerifyModal = true;
        },
        openStart(id, plate, services) {
            this.currentVehicleId = id;
            this.currentPlate = plate;
            this.scheduledServices = services.filter(s => s.status === 'scheduled' || !s.status);
            this.selectedServices = this.scheduledServices.map(s => String(s.original_index));
            this.showStartModal = true;
        }
    }" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Fleet <span class="text-autocheck-red italic">Management</span></h1>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 italic">Real-time monitoring and verification of your vehicle network.</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative z-10">
            <form action="{{ route('admin.vehicles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search vehicles by plate, owner, or model..." 
                        class="block w-full pl-10 pr-4 py-2 bg-gray-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                    >
                </div>
                <div class="flex gap-1.5 p-1 bg-gray-50 rounded-xl overflow-x-auto">
                    @foreach(['all' => 'All Status', 'completed' => 'Completed', 'in progress' => 'In Progress', 'scheduled' => 'Scheduled', 'inactive' => 'Inactive', 'overdue' => 'Overdue'] as $value => $label)
                        <a 
                            href="{{ route('admin.vehicles.index', array_merge(request()->query(), ['status' => $value])) }}" 
                            class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ (request('status', 'all') == $value) ? 'bg-white text-autocheck-red shadow-sm' : 'text-gray-400 hover:text-gray-600' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        <!-- Vehicle Table -->
        @if($vehicles->isEmpty())
            <div class="bg-white rounded-[2rem] p-12 border border-gray-100 shadow-sm text-center relative z-10">
                <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8">
                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">No Vehicles Found</h3>
                <p class="text-gray-500 font-medium">Try adjusting your search or filters to find what you're looking for.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden relative z-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Vehicle</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hidden sm:table-cell">Owner</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Plate</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right hidden md:table-cell">Total</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($vehicles as $vehicle)
                                @php
                                    $pendingServices = collect($vehicle->services ?? [])
                                        ->map(fn($s, $i) => array_merge($s, ['original_index' => $i]))
                                        ->where('status', '!=', 'completed')
                                        ->values();
                                    
                                    $displayStatus = $vehicle->calculated_status;
                                    $progress = $vehicle->service_progress;
                                @endphp
                                <tr class="group hover:bg-gray-50/30 transition-all duration-300">
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-2">
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ 
                                                    match($displayStatus) {
                                                        'completed' => 'bg-green-50 text-green-600',
                                                        'in progress' => 'bg-blue-50 text-blue-600',
                                                        'due today' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                                        'scheduled' => 'bg-yellow-50 text-yellow-600',
                                                        'overdue' => 'bg-red-50 text-autocheck-red',
                                                        default => 'bg-gray-50 text-gray-600',
                                                    }
                                                }}">
                                                    {{ $displayStatus }}
                                                </span>
                                                <span class="text-[9px] font-black text-gray-400 italic">{{ $progress['completed'] }}/{{ $progress['total'] }} Done</span>
                                            </div>
                                            
                                            <!-- Progress Bar -->
                                            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                                <div class="h-full transition-all duration-500 {{ $displayStatus === 'completed' ? 'bg-green-500' : 'bg-autocheck-red' }}" 
                                                     style="width: {{ $progress['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Vehicle Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="hidden sm:flex w-9 h-9 bg-gray-50 rounded-lg items-center justify-center shrink-0 border border-gray-100 group-hover:bg-white group-hover:border-red-100 transition-colors">
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-autocheck-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $vehicle->year }} • {{ $vehicle->color ?? 'Standard' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Owner -->
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-7 h-7 bg-white rounded flex items-center justify-center text-[10px] font-black text-autocheck-red border border-gray-100 shadow-sm group-hover:bg-red-50 transition-colors">
                                                {{ substr($vehicle->owner_name, 0, 1) }}
                                            </div>
                                            <span class="text-[13px] font-bold text-gray-700">{{ $vehicle->owner_name }}</span>
                                        </div>
                                    </td>

                                    <!-- Plate Number -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 bg-gray-50 rounded text-[11px] font-black text-gray-900 italic tracking-widest border border-gray-100 group-hover:bg-white transition-colors">
                                            {{ $vehicle->plate_number }}
                                        </span>
                                    </td>

                                    <!-- Total Maintenance Cost -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right hidden md:table-cell">
                                        <p class="text-[13px] font-black text-autocheck-red tracking-tight">₱{{ number_format($vehicle->total_cost ?? 0, 2) }}</p>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end space-x-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all duration-300 lg:transform lg:translate-x-2 lg:group-hover:translate-x-0">
                                            <!-- Quick Actions -->
                                                @if($pendingServices->filter(fn($s) => in_array($s['status'] ?? '', ['scheduled', '']))->count() > 0)
                                                    <button 
                                                        type="button"
                                                        @click="openStart({{ $vehicle->id }}, '{{ $vehicle->plate_number }}', {{ collect($vehicle->services ?? [])->map(fn($s, $i) => array_merge($s, ['original_index' => $i]))->toJson() }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100"
                                                        title="Start Specific Services"
                                                    >
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span class="hidden sm:inline">Start</span>
                                                    </button>
                                                @endif

                                                <button 
                                                    type="button"
                                                    @click="openVerify({{ $vehicle->id }}, '{{ $vehicle->plate_number }}', {{ $pendingServices->toJson() }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-100"
                                                    title="Quick Verify Completed Services"
                                                >
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    <span class="hidden sm:inline">Verify</span>
                                                </button>

                                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-lg transition-all" title="Edit Vehicle">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this vehicle?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Vehicle">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $vehicles->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Quick Actions Modals -->
        @include('admin.vehicles.partials.modals')
    </div>
</x-admin-layout>
