<x-admin-layout>
    <div class="space-y-8" x-data="{ reportType: '{{ $reportType }}' }">
        <!-- Header & Filters -->
        <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Reports & Analytics</h1>
                    <p class="text-gray-500 font-medium mt-1 text-sm italic">Comprehensive insights into your fleet and service performance.</p>
                </div>
                
                <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Report Type</label>
                        <select name="report_type" 
                            class="block w-64 px-6 py-3.5 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                            @foreach(['Summary Report', 'Vehicles Due Report', 'Overdue Vehicles', 'Completed Services'] as $type)
                                <option value="{{ $type }}" {{ $reportType == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Date Range</label>
                        <div class="flex items-center bg-gray-50 rounded-2xl border border-transparent focus-within:border-autocheck-red focus-within:bg-white transition-all">
                            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                                class="bg-transparent border-none px-4 py-3.5 text-sm font-bold focus:ring-0 w-40">
                            <span class="text-gray-300 font-bold">—</span>
                            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                                class="bg-transparent border-none px-4 py-3.5 text-sm font-bold focus:ring-0 w-40">
                        </div>
                    </div>

                    <button type="submit" class="px-8 py-3.5 bg-autocheck-red text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                        Generate
                    </button>
                </form>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total Customers -->
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm group hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black text-green-500 uppercase tracking-widest bg-green-50 px-3 py-1 rounded-lg">Real-time</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Customers</p>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalCustomers) }}</p>
                    <span class="text-xs font-bold text-gray-400 italic">total users</span>
                </div>
            </div>

            <!-- Total Vehicles -->
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm group hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-autocheck-red group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="text-[10px] font-black text-autocheck-red uppercase tracking-widest bg-red-50 px-3 py-1 rounded-lg">Fleet</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Vehicles</p>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalVehicles) }}</p>
                    <span class="text-xs font-bold text-gray-400 italic">registered</span>
                </div>
            </div>

            <!-- Services This Month (Smooth Gradient Idea) -->
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm group hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex -space-x-2">
                        <div class="w-6 h-6 rounded-full border-2 border-white bg-orange-200"></div>
                        <div class="w-6 h-6 rounded-full border-2 border-white bg-orange-300"></div>
                        <div class="w-6 h-6 rounded-full border-2 border-white bg-orange-400"></div>
                    </div>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Services (Monthly)</p>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-black text-gray-900">{{ number_format($servicesThisMonth) }}</p>
                    <span class="text-xs font-bold text-gray-400 italic">completed</span>
                </div>
            </div>

            <!-- Total Cost (Premium Gold/Currency aesthetic) -->
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm group hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-black text-yellow-600">PHP</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Cost (Monthly)</p>
                <p class="text-3xl font-black text-gray-900">₱{{ number_format($totalCostThisMonth, 2) }}</p>
            </div>
        </div>

        <!-- System Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vehicle Statistics -->
            <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-10 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">System Summary</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Vehicles Statistics</p>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-2xl border border-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="p-10 space-y-8">
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Total Vehicles:</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-gray-900">{{ $totalVehicles }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Vehicles Due Soon:</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-yellow-600">{{ $dueSoonCount }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Overdue Vehicles:</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-autocheck-red">{{ $overdueCount }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Statistics -->
            <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-10 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">System Summary</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Services Statistics</p>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-2xl border border-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="p-10 space-y-8">
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Total Services:</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-gray-900">{{ $totalServicesAllTime }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Total Cost (All-time):</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-gray-900">₱{{ number_format($totalCostAllTime, 2) }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between group">
                        <span class="text-sm font-bold text-gray-500 group-hover:text-gray-900 transition-colors">Avg Cost/Service:</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black text-gray-900">₱{{ number_format($avgCostPerService, 2) }}</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden mb-12">
            <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Recent Activity</h2>
                    <p class="text-gray-500 font-medium text-sm mt-1">Log of the most recent service updates within the system.</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center">
                    <span class="text-xs font-black text-gray-400">NEW</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Type</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentActivity as $activity)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-10 py-6 text-sm font-bold text-gray-500 italic">{{ optional($activity->service_date)->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="px-10 py-6">
                                    <p class="text-sm font-black text-gray-900 tracking-tight">{{ $activity->vehicle->make }} {{ $activity->vehicle->model }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic tracking-wider">{{ $activity->vehicle->plate_number }}</p>
                                </td>
                                <td class="px-10 py-6">
                                    <p class="text-sm font-bold text-gray-700 max-w-md truncate">{{ $activity->service_type }}</p>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <span class="text-lg font-black text-gray-900 tracking-tight">₱{{ number_format($activity->cost, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-10 py-20 text-center text-gray-400 font-bold italic">No recent activity found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-10 py-8 bg-gray-50/30 border-t border-gray-50 text-center">
                <a href="{{ route('admin.service-history.index') }}" class="text-xs font-black text-autocheck-red uppercase tracking-widest hover:underline">
                    View Full Service History
                    <svg class="h-3 w-3 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
