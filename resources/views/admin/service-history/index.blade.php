<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Service <span class="text-autocheck-red">History</span></h1>
                <p class="text-[13px] text-gray-500 font-medium mt-0.5">Track and manage all vehicle maintenance records.</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Total Records -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-autocheck-red group-hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Records</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-2xl font-black text-gray-900 leading-none">{{ $totalRecords }}</h3>
                    <p class="text-[10px] font-bold text-gray-400">Total entries</p>
                </div>
            </div>

            <!-- Completed Services -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-green-500 group-hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Completed Services</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-2xl font-black text-gray-900 leading-none">{{ $completedServices }}</h3>
                    <p class="text-[10px] font-bold text-green-500">Successfully done</p>
                </div>
            </div>

            <!-- Total Cost -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Cost</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-2xl font-black text-gray-900 leading-none">₱{{ number_format($totalCost, 2) }}</h3>
                    <p class="text-[10px] font-bold text-blue-500 italic">Completed revenue</p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.service-history.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div class="relative md:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search records..." 
                        class="block w-full pl-9 pr-4 py-2 bg-gray-50 border-transparent rounded-xl text-[11px] font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                    >
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-1">
                    <select name="status" onchange="this.form.submit()" 
                        class="block w-full px-4 py-2 bg-gray-50 border-transparent rounded-xl text-[11px] font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        <option value="all">Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                    </select>
                </div>

                <!-- Vehicle/Customer Filter -->
                <div class="md:col-span-2">
                    <select name="vehicle" onchange="this.form.submit()" 
                        class="block w-full px-4 py-2 bg-gray-50 border-transparent rounded-xl text-[11px] font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        <option value="all">All Vehicles (Registered)</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ request('vehicle') == $v->id ? 'selected' : '' }}>
                                {{ $v->owner_name }} ({{ $v->plate_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Service Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle & Owner</th>
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Type</th>
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Cost</th>
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Customer Rating</th>
                            <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($services as $service)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-[13px] font-black text-gray-900 leading-none mb-0.5">{{ $service->service_date->format('F j, Y') }}</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $service->service_date->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-[13px] font-bold text-gray-900 underline decoration-autocheck-red/0 group-hover:decoration-autocheck-red/100 transition-all">{{ $service->vehicle->make }} {{ $service->vehicle->model }}</span>
                                        <div class="flex items-center mt-0.5">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $service->vehicle->plate_number }}</span>
                                            <span class="mx-1.5 text-gray-200">|</span>
                                            <span class="text-[9px] font-bold text-autocheck-red uppercase tracking-widest">{{ $service->vehicle->owner_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[13px] font-bold text-gray-700">{{ $service->service_type }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[13px] font-black text-gray-900">₱{{ number_format($service->cost, 2) }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($service->review)
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center space-x-0.5 mb-1">
                                                @for($i=1; $i<=5; $i++)
                                                    <svg class="h-3 w-3 {{ $i <= $service->review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endfor
                                            </div>
                                            @if($service->review->comment)
                                                <button @click="$dispatch('view-comment', { comment: '{{ addslashes($service->review->comment) }}', name: '{{ $service->vehicle->owner_name }}' })" 
                                                        class="text-[9px] font-bold text-blue-600 hover:underline uppercase tracking-widest">
                                                    View Comment
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-300 italic uppercase tracking-widest">No review</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ 
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
                                <td colspan="5" class="py-20 text-center">
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
                <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                    {{ $services->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- View Comment Modal -->
    <div x-data="{ isOpen: false, comment: '', name: '' }"
         x-show="isOpen"
         @view-comment.window="isOpen = true; comment = $event.detail.comment; name = $event.detail.name"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 md:p-0"
         x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isOpen = false"></div>
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden transform transition-all p-8"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <button @click="isOpen = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <h3 class="text-2xl font-black text-gray-900 tracking-tight" x-text="'Feedback from ' + name"></h3>
            
            <div class="mt-6 bg-gray-50 rounded-3xl p-6 border border-gray-100 italic text-gray-700 font-medium" x-text="comment"></div>
            
            <button @click="isOpen = false" class="mt-8 w-full bg-gray-900 text-white font-black py-4 rounded-full shadow-lg transition-all hover:bg-black uppercase tracking-widest text-xs">Close Feedback</button>
        </div>
    </div>
</x-admin-layout>
