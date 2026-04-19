<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Fleet Management</h1>
                <p class="text-[13px] text-gray-500 font-medium mt-0.5">Manage and monitor all vehicles in your fleet.</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
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
                <div class="flex gap-1.5 p-1 bg-gray-50 rounded-xl">
                    @foreach(['all' => 'All Status', 'completed' => 'Completed', 'in progress' => 'In Progress', 'scheduled' => 'Scheduled', 'inactive' => 'Inactive', 'overdue' => 'Overdue'] as $value => $label)
                        <a 
                            href="{{ route('admin.vehicles.index', array_merge(request()->query(), ['status' => $value])) }}" 
                            class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ (request('status', 'all') == $value) ? 'bg-white text-autocheck-red shadow-sm' : 'text-gray-400 hover:text-gray-600' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        <!-- Vehicle Grid -->
        @if($vehicles->isEmpty())
            <div class="bg-white rounded-[2rem] p-12 border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8">
                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">No Vehicles Found</h3>
                <p class="text-gray-500 font-medium">Try adjusting your search or filters to find what you're looking for.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vehicles as $vehicle)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500">
                        <div class="p-4">
                            <!-- Status & Actions -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ 
                                    match($vehicle->status) {
                                        'completed' => 'bg-green-50 text-green-600',
                                        'in progress' => 'bg-blue-50 text-blue-600',
                                        'scheduled' => 'bg-yellow-50 text-yellow-600',
                                        'overdue' => 'bg-red-50 text-autocheck-red',
                                        default => 'bg-gray-50 text-gray-600',
                                    }
                                }}">
                                    {{ $vehicle->status }}
                                </span>
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="flex space-x-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this vehicle?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="flex items-start space-x-4 mb-4">
                                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 border border-gray-100">
                                    <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                                    <p class="text-[11px] font-bold text-gray-500 mt-0.5">{{ $vehicle->year }} • {{ $vehicle->color ?? 'Standard' }}</p>
                                </div>
                            </div>

                            <!-- Personnel Info -->
                            <div class="bg-gray-50/50 rounded-xl p-3 mb-4 border border-gray-50 space-y-2">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Vehicle Owner</p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-white rounded flex items-center justify-center text-[11px] font-black text-autocheck-red border border-gray-100 shadow-sm">
                                            {{ substr($vehicle->owner_name, 0, 1) }}
                                        </div>
                                        <span class="text-[13px] font-bold text-gray-700">{{ $vehicle->owner_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Specs -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-white border border-gray-100 shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Plate Number</p>
                                    <p class="text-xs font-black text-gray-900 italic tracking-wider">{{ $vehicle->plate_number }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-white border border-gray-100 shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Cost</p>
                                    <p class="text-xs font-black text-autocheck-red">₱{{ number_format($vehicle->total_cost ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer Info -->
                        <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                <svg class="h-3 w-3 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Registered: {{ $vehicle->registration_date?->format('m/d/Y') ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $vehicles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
