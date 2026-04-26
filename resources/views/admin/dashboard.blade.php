<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">Dashboard <span class="text-autocheck-red italic">Overview</span></h1>
                <p class="text-[11px] text-gray-400 font-bold mt-0.5 uppercase tracking-widest italic">Real-timesnapshots of your fleet.</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm flex items-center space-x-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Live Updates</span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-lg font-black text-gray-900 leading-tight">{{ number_format($stats['total_customers']) }}</h3>
                <p class="text-[9px] font-black text-gray-400 mt-0.5 uppercase tracking-widest">Customers</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-lg font-black text-gray-900 leading-tight">{{ number_format($stats['total_vehicles']) }}</h3>
                <p class="text-[9px] font-black text-gray-400 mt-0.5 uppercase tracking-widest">Vehicles</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-2 bg-red-50 text-autocheck-red rounded-lg group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <h3 class="text-lg font-black text-gray-900 leading-tight">{{ number_format($stats['total_services']) }}</h3>
                <p class="text-[9px] font-black text-gray-400 mt-0.5 uppercase tracking-widest">Services</p>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <h3 class="text-lg font-black text-gray-900 leading-tight">{{ number_format($stats['emails_sent']) }}</h3>
                <p class="text-[9px] font-black text-gray-400 mt-0.5 uppercase tracking-widest">Alerts Sent</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Maintenance Overview & Chart -->
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">Maintenance <span class="text-autocheck-red">Status</span></h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="space-y-3">
                            <div class="p-3.5 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-blue-600/70 uppercase tracking-widest mb-0.5">Upcoming</span>
                                    <span class="text-xl font-black text-blue-600 leading-none">{{ $maintenanceOverview['upcoming'] }}</span>
                                </div>
                                <svg class="h-5 w-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="p-3.5 bg-yellow-50/50 rounded-2xl border border-yellow-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-yellow-600/70 uppercase tracking-widest mb-0.5">Due Soon</span>
                                    <span class="text-xl font-black text-yellow-600 leading-none">{{ $maintenanceOverview['due_soon'] }}</span>
                                </div>
                                <svg class="h-5 w-5 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="p-3.5 bg-red-50/50 rounded-2xl border border-red-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-autocheck-red/70 uppercase tracking-widest mb-0.5">Overdue</span>
                                    <div class="flex items-baseline space-x-2">
                                        <span class="text-xl font-black text-autocheck-red leading-none">{{ $maintenanceOverview['overdue'] }}</span>
                                        @if($maintenanceOverview['critical_overdue'] > 0)
                                            <span class="text-[8px] font-black bg-red-600 text-white px-1.5 py-0.5 rounded-md animate-pulse">CRITICAL</span>
                                        @endif
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </div>
                        <div class="relative">
                            <div id="maintenanceChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Services -->
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">Recent <span class="text-autocheck-red">Services</span></h2>
                        <a href="{{ route('admin.maintenance.index') }}" class="text-[10px] font-black text-autocheck-red hover:underline italic uppercase tracking-widest">Manage All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                    <th class="py-2">Vehicle</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Date</th>
                                    <th class="py-2 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentServices as $service)
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 font-bold text-[10px]">
                                                    {{ substr($service['plate_number'], 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-900 leading-none">{{ $service['vehicle'] }}</p>
                                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $service['plate_number'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-[10px] font-bold text-gray-500 italic">{{ $service['service_type'] }}</td>
                                        <td class="py-3 text-[10px] font-black text-gray-700">{{ $service['date'] }}</td>
                                        <td class="py-3 text-right">
                                            <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-widest rounded-full">{{ $service['status'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-400 text-[10px] font-bold uppercase tracking-widest">No Activity</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="space-y-5">
                <!-- Quick Tracking -->
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-20 h-20 bg-autocheck-red/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-2 relative z-10">Quick <span class="text-autocheck-red">Tracking</span></h2>
                    <p class="text-[10px] font-bold text-gray-400 mb-4 relative z-10 italic">Monitor vehicle records.</p>
                    
                    <form action="{{ route('admin.reports.index') }}" method="GET" class="relative z-10 space-y-3">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Enter Plate Number..." 
                                class="w-full pl-5 pr-10 py-3 bg-gray-50 border-transparent rounded-xl text-[10px] font-black uppercase tracking-widest focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-autocheck-red text-white rounded-xl font-black text-[9px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                            Track Now
                        </button>
                    </form>
                </div>

                <!-- Attention Required -->
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm relative overflow-hidden group/attn">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">Attention <span class="text-autocheck-red">Required</span></h2>
                        <a href="{{ route('admin.attention-required') }}" class="text-[9px] font-black text-autocheck-red uppercase tracking-widest hover:underline italic">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($attentionRequired as $vehicle)
                            <a href="{{ route('admin.attention-required') }}" class="block p-3 rounded-2xl bg-red-50/30 border border-red-100 flex items-center space-x-3 group hover:bg-red-50 transition-colors">
                                <div class="p-2 bg-white text-autocheck-red rounded-xl shadow-sm group-hover:bg-autocheck-red group-hover:text-white transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900 line-clamp-1">{{ $vehicle['make_model'] }}</p>
                                    <div class="flex items-center mt-0.5">
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mr-2">{{ $vehicle['plate_number'] }}</span>
                                        <span class="text-[8px] font-black text-autocheck-red uppercase tracking-widest bg-red-100 px-1.5 py-0.5 rounded-full">{{ $vehicle['days_overdue'] }}d</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1.5 ml-auto">
                                    @if($vehicle['phone'])
                                    <form action="{{ route('admin.notifications.call', $vehicle['id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 bg-indigo-600 text-white rounded-lg hover:bg-slate-900 transition-colors shadow-sm" title="Call Now (AI)">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <div class="p-1.5 bg-red-50 text-autocheck-red rounded-lg">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6">
                                <div class="bg-green-50 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">All clear</p>
                            </div>
                        @endforelse
                    </div>

                    @if($attentionRequired->isNotEmpty())
                        <a href="{{ route('admin.attention-required') }}" class="block w-full mt-4 py-3 bg-gray-950 text-white rounded-xl font-black text-center text-[9px] uppercase tracking-widest hover:bg-gray-900 transition-colors shadow-lg">
                            Notify All Owners
                        </a>
                    @endif
                </div>

                <!-- Quick Actions / Live Stream Placeholder -->
                <div class="bg-autocheck-red rounded-2xl p-5 text-white shadow-xl shadow-red-500/20">
                    <h3 class="text-lg font-black uppercase tracking-tight mb-4">System Alerts</h3>
                    <p class="text-sm font-medium text-white/80 leading-relaxed mb-6">
                        Automated reminders for <span class="font-bold text-white">{{ $maintenanceOverview['due_soon'] }} vehicles</span> will be sent at 8:00 AM (Manila Time).
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center text-xs font-bold bg-white/10 p-3 rounded-2xl">
                            <span class="w-1.5 h-1.5 rounded-full bg-white mr-3"></span>
                            Email: Queue Processing
                        </div>
                        <div class="flex items-center text-xs font-bold bg-white/10 p-3 rounded-2xl">
                            <span class="w-1.5 h-1.5 rounded-full bg-white mr-3"></span>
                            Database: Optimized
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Support -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: @json($chartData['series']),
                chart: {
                    type: 'donut',
                    height: 220,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    }
                },
                labels: @json($chartData['labels']),
                colors: ['#3B82F6', '#EAB308', '#F53003'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    fontWeight: '900',
                                    color: '#9CA3AF',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: '900',
                                    color: '#111827',
                                    offsetY: 10,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Vehicles',
                                    color: '#9CA3AF',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    width: 0
                }
            };

            var chart = new ApexCharts(document.querySelector("#maintenanceChart"), options);
            chart.render();
        });
    </script>
</x-admin-layout>
