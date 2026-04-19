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

        <!-- Vehicle Grid -->
        @if($vehicles->isEmpty())
            <div class="bg-white rounded-2xl p-10 border border-gray-100 shadow-sm text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"></path></svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 mb-1 uppercase tracking-tight">Fleet Empty</h3>
                <p class="text-[10px] text-gray-400 font-bold italic">No assets detected. Add your first vehicle to begin tracking.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($vehicles as $vehicle)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                        <div class="p-5 flex-grow">
                            <!-- Status & Actions -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ 
                                    match($vehicle->status) {
                                        'completed' => 'bg-green-50 text-green-600',
                                        'in progress' => 'bg-blue-50 text-blue-600',
                                        'scheduled' => 'bg-yellow-50 text-yellow-600',
                                        'overdue' => 'bg-red-50 text-autocheck-red border border-red-100',
                                        default => 'bg-gray-50 text-gray-600',
                                    }
                                }}">
                                    {{ $vehicle->status }}
                                </span>
                                <div class="flex space-x-1">
                                    <a href="{{ route('customer.vehicles.edit', $vehicle) }}" class="p-2 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('customer.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Remove asset?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="flex items-center space-x-4 mb-5">
                                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 border border-gray-100 text-autocheck-red font-black text-xs">
                                    {{ substr($vehicle->make, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-black text-gray-900 tracking-tight truncate uppercase">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                                    <p class="text-[9px] font-black text-gray-400 mt-0.5 tracking-widest italic">{{ $vehicle->year }} • {{ $vehicle->color ?? 'Standard' }}</p>
                                </div>
                            </div>

                            <!-- Specs -->
                            <div class="grid grid-cols-2 gap-2">
                                <div class="px-3 py-2 rounded-xl bg-gray-50/50 border border-gray-50">
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5 italic">Identifier</p>
                                    <p class="text-[10px] font-black text-gray-900 tracking-wider uppercase">{{ $vehicle->plate_number }}</p>
                                </div>
                                <div class="px-3 py-2 rounded-xl bg-gray-50/50 border border-gray-50">
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5 italic">Timeline</p>
                                    <p class="text-[10px] font-black {{ ($vehicle->next_service_date && $vehicle->next_service_date->isPast()) ? 'text-autocheck-red' : 'text-gray-900' }}">
                                        {{ $vehicle->next_service_date ? $vehicle->next_service_date->format('M d') : 'Pending' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer Info -->
                        <div class="px-5 py-3 bg-gray-50/30 border-t border-gray-50">
                            <a href="{{ route('customer.dashboard', ['vehicle_id' => $vehicle->id]) }}" class="flex items-center justify-center text-[9px] font-black text-autocheck-red uppercase tracking-widest hover:underline italic">
                                Full History
                                <svg class="ml-1.5 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $vehicles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-customer-layout>
