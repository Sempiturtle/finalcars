<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Service <span class="text-autocheck-red">History</span></h1>
                <p class="text-gray-500 font-medium mt-1">Track and manage all vehicle maintenance records.</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Records -->
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-autocheck-red group-hover:text-white transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Records</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $totalRecords }}</h3>
                    <p class="text-xs font-bold text-gray-400">Total entries</p>
                </div>
            </div>

            <!-- Completed Services -->
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-green-500 group-hover:text-white transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Completed Services</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl font-black text-gray-900 leading-none">{{ $completedServices }}</h3>
                    <p class="text-xs font-bold text-green-500">Successfully done</p>
                </div>
            </div>

            <!-- Total Cost -->
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Cost</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-3xl font-black text-gray-900 leading-none">₱{{ number_format($totalCost, 2) }}</h3>
                    <p class="text-xs font-bold text-blue-500 italic">Completed revenue</p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <form action="{{ route('admin.service-history.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative md:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search plate, owner, service..." 
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                    >
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-1">
                    <select name="status" onchange="this.form.submit()" 
                        class="block w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        <option value="all">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                    </select>
                </div>

                <!-- Vehicle/Customer Filter -->
                <div class="md:col-span-2">
                    <select name="vehicle" onchange="this.form.submit()" 
                        class="block w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        <option value="all">All Vehicles (Registered Customers)</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ request('vehicle') == $v->id ? 'selected' : '' }}>
                                {{ $v->owner_name }} ({{ $v->plate_number }} - {{ $v->make }} {{ $v->model }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Service Table -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle & Owner</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Type</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Mechanic</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Cost</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($services as $service)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 leading-none mb-1">{{ $service->service_date->format('M d, Y') }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $service->service_date->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 underline decoration-autocheck-red/0 group-hover:decoration-autocheck-red/100 transition-all">{{ $service->vehicle->make }} {{ $service->vehicle->model }}</span>
                                        <div class="flex items-center mt-1">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $service->vehicle->plate_number }}</span>
                                            <span class="mx-2 text-gray-200">|</span>
                                            <span class="text-[10px] font-bold text-autocheck-red uppercase tracking-widest">{{ $service->vehicle->owner_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="text-sm font-bold text-gray-700">{{ $service->service_type }}</span>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">{{ $service->mechanic_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="text-sm font-black text-gray-900">₱{{ number_format($service->cost, 2) }}</span>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] {{ 
                                        match($service->status) {
                                            'completed' => 'bg-green-50 text-green-600',
                                            'scheduled' => 'bg-blue-50 text-blue-600',
                                            'in progress' => 'bg-yellow-50 text-yellow-600',
                                            default => 'bg-gray-50 text-gray-600',
                                        }
                                    }}">
                                        {{ $service->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">No service records found</h3>
                                    <p class="text-xs font-bold text-gray-400 mt-1">Try adjusting your filters or search terms.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/50">
                    {{ $services->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
