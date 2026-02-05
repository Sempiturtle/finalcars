<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard <span class="text-autocheck-red">Overview</span></h1>
                <p class="text-gray-500 font-medium mt-1">Real-time snapshots of your fleet and customer activity.</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-white px-6 py-3 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Live Updates</span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_customers']) }}</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Total Customers</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_vehicles']) }}</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Active Vehicles</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 text-autocheck-red rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_services']) }}</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Total Services</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['emails_sent']) }}</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Emails Sent</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Maintenance Overview & Chart -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Maintenance <span class="text-autocheck-red">Status</span></h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-sm font-bold text-blue-600/70 uppercase tracking-widest mb-1">Upcoming</span>
                                    <span class="text-3xl font-black text-blue-600">{{ $maintenanceOverview['upcoming'] }}</span>
                                </div>
                                <svg class="h-8 w-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="p-6 bg-yellow-50/50 rounded-3xl border border-yellow-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-sm font-bold text-yellow-600/70 uppercase tracking-widest mb-1">Due Soon</span>
                                    <span class="text-3xl font-black text-yellow-600">{{ $maintenanceOverview['due_soon'] }}</span>
                                </div>
                                <svg class="h-8 w-8 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="p-6 bg-red-50/50 rounded-3xl border border-red-100 flex items-center justify-between">
                                <div>
                                    <span class="block text-sm font-bold text-autocheck-red/70 uppercase tracking-widest mb-1">Overdue</span>
                                    <span class="text-3xl font-black text-autocheck-red">{{ $maintenanceOverview['overdue'] }}</span>
                                </div>
                                <svg class="h-8 w-8 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </div>
                        <div class="relative">
                            <div id="maintenanceChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Services -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Recent <span class="text-autocheck-red">Services</span></h2>
                        <a href="{{ route('admin.maintenance.index') }}" class="text-sm font-bold text-autocheck-red hover:underline italic">Manage All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                    <th class="py-4">Vehicle</th>
                                    <th class="py-4">Service Type</th>
                                    <th class="py-4">Date</th>
                                    <th class="py-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentServices as $service)
                                    <tr>
                                        <td class="py-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 font-bold text-xs">
                                                    {{ substr($service['plate_number'], 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ $service['vehicle'] }}</p>
                                                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">{{ $service['plate_number'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-5 text-sm font-medium text-gray-500 italic">{{ $service['service_type'] }}</td>
                                        <td class="py-5 text-sm font-bold text-gray-700">{{ $service['date'] }}</td>
                                        <td class="py-5 text-right">
                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-widest rounded-full">{{ $service['status'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10 text-center text-gray-400 text-sm font-medium">No recent services recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="space-y-8">
                <!-- Attention Required -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-8">Attention <span class="text-autocheck-red">Required</span></h2>
                    <div class="space-y-4">
                        @forelse($attentionRequired as $vehicle)
                            <div class="p-4 rounded-3xl bg-red-50/30 border border-red-100 flex items-center space-x-4 group hover:bg-red-50 transition-colors">
                                <div class="p-3 bg-white text-autocheck-red rounded-2xl shadow-sm group-hover:bg-autocheck-red group-hover:text-white transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $vehicle['make_model'] }}</p>
                                    <div class="flex items-center mt-0.5">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-2">{{ $vehicle['plate_number'] }}</span>
                                        <span class="text-[10px] font-black text-autocheck-red uppercase tracking-widest bg-red-100 px-2 py-0.5 rounded-full">{{ $vehicle['days_overdue'] }} Days Overdue</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <div class="bg-green-50 w-16 h-16 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-gray-500 font-bold text-sm text-balance">All clear! No vehicles require immediate attention.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($attentionRequired->isNotEmpty())
                        <button class="w-full mt-8 py-4 bg-gray-950 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors shadow-xl shadow-gray-200">
                            Notify All Owners
                        </button>
                    @endif
                </div>

                <!-- Quick Actions / Live Stream Placeholder -->
                <div class="bg-autocheck-red rounded-[2.5rem] p-8 text-white shadow-xl shadow-red-500/20">
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
                    height: 280,
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
