<x-customer-layout>
    <div class="max-w-7xl mx-auto space-y-6 pb-20">
        <!-- Compact Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tighter uppercase">Garage <span class="text-autocheck-red">Fleet</span></h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic mt-1">Manage your active automotive assets and maintenance timelines.</p>
            </div>
            <a href="{{ route('customer.vehicles.create') }}" class="inline-flex items-center px-6 py-2.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-autocheck-red transition-all shadow-lg italic">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Add Asset
            </a>
        </div>

        @if(session('success'))
            <div class="bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm flex items-center animate-fade-in">
                <svg class="h-4 w-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-[10px] font-black text-gray-900 tracking-tight italic uppercase">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Vehicle Table -->
        @if($vehicles->isEmpty())
            <div class="bg-white rounded-2xl p-10 border border-gray-100 shadow-sm text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"></path></svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 mb-1 uppercase tracking-tight">Fleet Empty</h3>
                <p class="text-[10px] text-gray-400 font-bold italic">No assets detected. Add your first vehicle to begin tracking.</p>
            </div>
        @else
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Plate</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-center hidden md:table-cell">Next Service</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-right hidden sm:table-cell">Investment</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($vehicles as $vehicle)
                                <tr class="group hover:bg-gray-50/30 transition-all duration-300">
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $displayStatus = $vehicle->calculated_status;
                                            $progress = $vehicle->service_progress;
                                        @endphp
                                        <div class="flex flex-col space-y-2">
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="px-2.5 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ 
                                                    match($displayStatus) {
                                                        'completed' => 'bg-green-50 text-green-600',
                                                        'in progress' => 'bg-blue-50 text-blue-600',
                                                        'due today' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                                        'scheduled' => 'bg-yellow-50 text-yellow-600',
                                                        'overdue' => 'bg-red-50 text-autocheck-red border border-red-100',
                                                        default => 'bg-gray-50 text-gray-600',
                                                    }
                                                }}">
                                                    {{ $displayStatus }}
                                                </span>
                                                <span class="text-[8px] font-black text-gray-400 italic">{{ $progress['completed'] }}/{{ $progress['total'] }} Done</span>
                                            </div>
                                            <!-- Progress Bar -->
                                            <div class="w-full bg-gray-100 h-1 rounded-full overflow-hidden">
                                                <div class="h-full transition-all duration-500 {{ $displayStatus === 'completed' ? 'bg-green-500' : 'bg-autocheck-red' }}" 
                                                     style="width: {{ $progress['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Vehicle Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="hidden sm:flex w-9 h-9 bg-gray-50 rounded-lg items-center justify-center shrink-0 border border-gray-100 group-hover:bg-white group-hover:border-red-100 transition-colors">
                                                <span class="text-[10px] font-black text-autocheck-red uppercase">{{ substr($vehicle->make, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-black text-gray-900 tracking-tight uppercase">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $vehicle->year }} • {{ $vehicle->color ?? 'Standard' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Plate Number -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 bg-gray-50 rounded text-[10px] font-black text-gray-900 italic tracking-widest border border-gray-100 group-hover:bg-white transition-colors uppercase">
                                            {{ $vehicle->plate_number }}
                                        </span>
                                    </td>

                                    <!-- Next Service -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[11px] font-black {{ ($vehicle->next_service_date && $vehicle->next_service_date->isPast()) ? 'text-autocheck-red' : 'text-gray-900' }} tracking-wider">
                                                {{ $vehicle->next_service_date ? $vehicle->next_service_date->format('F j, Y') : 'N/A' }}
                                            </span>
                                            <span class="text-[8px] font-black {{ ($vehicle->next_service_date && $vehicle->next_service_date->isPast() && !in_array(strtolower($vehicle->calculated_status), ['in progress', 'completed', 'due today'])) ? 'text-autocheck-red' : 'text-gray-400' }} uppercase tracking-widest mt-0.5">
                                                @if(strtolower($vehicle->calculated_status) === 'due today')
                                                    Due Today
                                                @elseif($vehicle->next_service_date && $vehicle->next_service_date->isPast() && !in_array(strtolower($vehicle->calculated_status), ['in progress', 'completed']))
                                                    Overdue
                                                @elseif(strtolower($vehicle->calculated_status) === 'in progress')
                                                    In Progress
                                                @else
                                                    Scheduled
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Total Investment -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right hidden sm:table-cell">
                                        <p class="text-[11px] font-black text-autocheck-red tracking-tight">₱{{ number_format($vehicle->total_cost ?? 0, 2) }}</p>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end space-x-1.5 lg:opacity-0 lg:group-hover:opacity-100 transition-all duration-300 lg:transform lg:translate-x-2 lg:group-hover:translate-x-0">
                                            <a href="{{ route('customer.dashboard', ['vehicle_id' => $vehicle->id]) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-lg transition-all" title="View History">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                            <a href="{{ route('customer.vehicles.edit', $vehicle) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-lg transition-all" title="Edit Vehicle">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('customer.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this vehicle?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Vehicle">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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

    </div>
</x-customer-layout>
