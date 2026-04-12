<x-customer-layout>
    <div class="space-y-8 animate-fade-in">
        <!-- Header Section -->
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-autocheck-red/5 rounded-full"></div>
            
            <div class="relative z-10 flex items-center space-x-6">
                <div class="w-16 h-16 bg-autocheck-red rounded-2xl flex items-center justify-center text-white shadow-xl shadow-red-500/20">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Vehicle <span class="text-autocheck-red">History</span></h1>
                    <p class="text-gray-500 font-bold mt-1">Track every service and maintenance log for your cars.</p>
                </div>
            </div>
        </div>

        @forelse($vehicles as $vehicle)
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" x-data="{ open: true }">
                <!-- Vehicle Header -->
                <div @click="open = !open" class="p-8 flex flex-col md:flex-row items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center text-white font-bold">
                            {{ substr($vehicle->make, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h2>
                            <p class="text-xs font-black text-autocheck-red uppercase tracking-widest">{{ $vehicle->plate_number }}</p>
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center space-x-8">
                        <div class="text-center">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Services</p>
                            <p class="text-lg font-black text-gray-900">{{ $vehicle->serviceLogs->count() }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Investment</p>
                            <p class="text-lg font-black text-gray-900">₱{{ number_format($vehicle->serviceLogs->sum('cost'), 2) }}</p>
                        </div>
                        <div class="p-2 rounded-lg bg-gray-100 text-gray-400 group-hover:text-gray-600 transition-transform duration-300" :class="{ 'rotate-180': open }">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

        <!-- History Table & Mobile Cards -->
                <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4">
                    <div class="px-4 md:px-8 pb-8">
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b border-gray-100">
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Type</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mechanic</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($vehicle->serviceLogs as $log)
                                        <tr class="group hover:bg-gray-50/50 transition-colors">
                                            <td class="py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->service_date)->format('M d, Y') }}</span>
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ \Carbon\Carbon::parse($log->service_date)->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center space-x-2">
                                                    <span class="w-2 h-2 rounded-full bg-autocheck-red"></span>
                                                    <span class="text-sm font-bold text-gray-700">{{ $log->service_type }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-sm font-bold text-gray-600 italic">{{ $log->mechanic_name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <span class="text-sm font-black text-gray-900">₱{{ number_format($log->cost, 2) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden space-y-4">
                            @forelse($vehicle->serviceLogs as $log)
                                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-1.5 h-full bg-autocheck-red rounded-r-full"></div>
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Date</p>
                                            <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->service_date)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cost</p>
                                            <p class="text-sm font-black text-autocheck-red">₱{{ number_format($log->cost, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Type</p>
                                            <p class="text-sm font-bold text-gray-700">{{ $log->service_type }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mechanic</p>
                                            <p class="text-sm font-bold text-gray-600 italic">{{ $log->mechanic_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-sm font-bold text-gray-500">No service history found.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-16 shadow-xl border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">No Vehicles Registered</h3>
                <p class="mt-2 text-gray-500 max-w-sm">It looks like you don't have any vehicles in our system yet. Contact AutoCheck to register your fleet.</p>
            </div>
        @endforelse
    </div>
</x-customer-layout>
