<x-admin-layout>
    <div x-data="{ 
        showVerifyModal: false, 
        showStartModal: false,
        showViewModal: false,
        currentVehicleId: null, 
        currentPlate: '', 
        pendingServices: [], 
        scheduledServices: [],
        selectedServices: [], 
        notes: '',
        selectedVehicle: null,
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
        },
        openView(vehicle) {
            this.selectedVehicle = vehicle;
            this.showViewModal = true;
        }
    }" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Fleet <span class="text-autocheck-red italic">Archives</span></h1>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 italic">Historical record of completed and closed vehicles.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-250 text-gray-700 text-xs font-black rounded-xl hover:bg-gray-100 transition-colors uppercase tracking-widest">
                    <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Fleet
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative z-10">
            <form action="{{ route('admin.vehicles.archived') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search archived vehicles by plate, owner, or model..." 
                        class="block w-full pl-10 pr-4 py-2 bg-gray-55 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                    >
                </div>
                <div class="flex gap-1.5 p-1 bg-gray-50 rounded-xl overflow-x-auto">
                    @foreach(['all' => 'All Status', 'completed' => 'Completed', 'in progress' => 'In Progress', 'scheduled' => 'Scheduled', 'inactive' => 'Inactive', 'overdue' => 'Overdue'] as $value => $label)
                        <a 
                            href="{{ route('admin.vehicles.archived', array_merge(request()->query(), ['status' => $value])) }}" 
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
                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">No Archived Vehicles</h3>
                <p class="text-gray-500 font-medium">There are no archived vehicles matching your search criteria.</p>
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

                                    $allServices = collect($vehicle->services ?? [])->map(function($s) {
                                        $sType = \App\Models\ServiceType::where('name', $s['type'] ?? '')->first();
                                        $pts = $sType ? $sType->points_awarded : floor(($s['cost'] ?? 0) / 10);
                                        return array_merge($s, ['points' => $pts]);
                                    })->values()->all();

                                    $vehicleData = [
                                        'id' => $vehicle->id,
                                        'make' => $vehicle->make,
                                        'model' => $vehicle->model,
                                        'year' => $vehicle->year,
                                        'color' => $vehicle->color ?? 'Standard',
                                        'plate_number' => $vehicle->plate_number,
                                        'owner_name' => $vehicle->owner_name,
                                        'mechanic_name' => $vehicle->mechanic_name ?? 'Not Assigned',
                                        'status' => $displayStatus,
                                        'progress' => $progress,
                                        'registration_date' => $vehicle->registration_date ? $vehicle->registration_date->format('F j, Y') : 'N/A',
                                        'next_service_date' => $vehicle->next_service_date ? $vehicle->next_service_date->format('F j, Y') : 'N/A',
                                        'reliability_index' => $vehicle->reliability_index,
                                        'health_trend' => ucfirst($vehicle->health_trend),
                                        'avg_interval' => $vehicle->average_service_interval,
                                        'predictive_date' => $vehicle->predictive_service_date ? $vehicle->predictive_service_date->format('F j, Y') : 'N/A',
                                        'services' => $allServices,
                                        'total_cost' => number_format($vehicle->total_cost ?? 0, 2),
                                        'show_url' => route('admin.vehicles.show', $vehicle->id),
                                        'edit_url' => route('admin.vehicles.edit', $vehicle->id),
                                    ];
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
                                            <button type="button" @click="openView({{ json_encode($vehicleData) }})" class="hidden sm:flex w-9 h-9 bg-gray-50 rounded-lg items-center justify-center shrink-0 border border-gray-150 group-hover:bg-white group-hover:border-red-100 transition-colors focus:outline-none">
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-autocheck-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </button>
                                            <button type="button" @click="openView({{ json_encode($vehicleData) }})" class="text-left block focus:outline-none group/link">
                                                <div class="flex items-center gap-1.5">
                                                    <p class="text-sm font-black text-gray-900 tracking-tight group-hover/link:text-autocheck-red transition-colors">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                                    <svg class="w-3.5 h-3.5 text-gray-300 opacity-0 group-hover/link:opacity-100 group-hover/link:text-autocheck-red transition-all transform -translate-x-1 group-hover/link:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $vehicle->year }} • {{ $vehicle->color ?? 'Standard' }}</p>
                                                <div class="flex items-center gap-1.5 mt-1">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-gray-50 text-gray-550 border border-gray-150 shadow-sm group-hover:bg-white transition-colors">
                                                        <svg class="w-2.5 h-2.5 mr-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Mech: {{ $vehicle->mechanic_name ?? 'Unassigned' }}
                                                    </span>
                                                </div>
                                            </button>
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
                                        <div class="flex items-center justify-end space-x-2 transition-all duration-300">
                                            <!-- View Details Modal -->
                                            <button 
                                                type="button"
                                                @click="openView({{ json_encode($vehicleData) }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-purple-600 hover:text-white transition-all shadow-sm border border-purple-100"
                                                title="View All Vehicle Info"
                                            >
                                                <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <span class="hidden sm:inline">View Details</span>
                                            </button>

                                            <!-- Receipt link -->
                                            <a 
                                                href="{{ route('admin.vehicles.receipt', $vehicle) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all shadow-sm border border-amber-100"
                                                title="Print Invoice / Bill Out"
                                            >
                                                <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="hidden sm:inline">Receipt</span>
                                            </a>

                                            <!-- Restore Action -->
                                            <form action="{{ route('admin.vehicles.restore', $vehicle) }}" method="POST" class="inline" id="admin-restore-vehicle-{{ $vehicle->id }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all shadow-sm border border-green-100"
                                                    title="Restore Vehicle to Fleet"
                                                >
                                                    <svg class="w-3.5 h-3.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    <span class="hidden sm:inline">Restore</span>
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

        <!-- View Modals -->
        @include('admin.vehicles.partials.modals')
    </div>
</x-admin-layout>
