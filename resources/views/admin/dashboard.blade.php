<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 font-medium mt-1">Welcome back, Admin. Here's what's happening with the fleet today.</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900">124</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Customers</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Active</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900">86</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Vehicles</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 text-autocheck-red rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">2026</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900">2,450</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Total Services</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:border-autocheck-red/20 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Global</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900">4,120</h3>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Emails Sent</p>
            </div>
        </div>

        <!-- Maintenance Status & Highlights -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Status Overview -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Maintenance Status</h2>
                        <button class="text-sm font-bold text-autocheck-red hover:underline">View All</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100 text-center">
                            <span class="block text-3xl font-black text-blue-600 mb-1">12</span>
                            <span class="text-sm font-bold text-blue-600/70 uppercase tracking-widest">Upcoming</span>
                        </div>
                        <div class="p-6 bg-yellow-50/50 rounded-3xl border border-yellow-100 text-center">
                            <span class="block text-3xl font-black text-yellow-600 mb-1">05</span>
                            <span class="text-sm font-bold text-yellow-600/70 uppercase tracking-widest">Due Soon</span>
                        </div>
                        <div class="p-6 bg-red-50/50 rounded-3xl border border-red-100 text-center">
                            <span class="block text-3xl font-black text-autocheck-red mb-1">03</span>
                            <span class="text-sm font-bold text-autocheck-red/70 uppercase tracking-widest">Overdue</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Recent Services</h2>
                        <button class="text-sm font-bold text-autocheck-red hover:underline italic">Download Logs</button>
                    </div>
                    <div class="overflow-x-auto -mx-8 px-8">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                    <th class="py-4">Vehicle</th>
                                    <th class="py-4">Customer</th>
                                    <th class="py-4">Service Type</th>
                                    <th class="py-4">Date</th>
                                    <th class="py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr>
                                    <td class="py-5">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-xl"></div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">Toyota Vios</p>
                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">PLT-1234</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 text-sm font-bold text-gray-700">Juan Dela Cruz</td>
                                    <td class="py-5 text-sm font-medium text-gray-500 italic">PMS 10,000 KM</td>
                                    <td class="py-5 text-sm font-bold text-gray-700">Feb 02, 2026</td>
                                    <td class="py-5">
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-5">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-xl"></div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">Honda Civic</p>
                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">ABC-5678</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 text-sm font-bold text-gray-700">Maria Santos</td>
                                    <td class="py-5 text-sm font-medium text-gray-500 italic">Oil Change</td>
                                    <td class="py-5 text-sm font-bold text-gray-700">Jan 30, 2026</td>
                                    <td class="py-5">
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Vehicles Requiring Attention -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm h-fit">
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-8">Attention Required</h2>
                <div class="space-y-6">
                    <div class="p-4 rounded-3xl bg-red-50/30 border border-red-100 flex items-center space-x-4">
                        <div class="p-3 bg-white text-autocheck-red rounded-2xl shadow-sm">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Toyota Hilux (XYZ-999)</p>
                            <p class="text-xs font-bold text-autocheck-red uppercase tracking-widest">3 Days Overdue</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-3xl bg-yellow-50/30 border border-yellow-100 flex items-center space-x-4">
                        <div class="p-3 bg-white text-yellow-600 rounded-2xl shadow-sm">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Mitsubishi L300 (WXY-123)</p>
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Due Tomorrow</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-3xl bg-red-50/30 border border-red-100 flex items-center space-x-4">
                        <div class="p-3 bg-white text-autocheck-red rounded-2xl shadow-sm">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Ford Ranger (RNG-777)</p>
                            <p class="text-xs font-bold text-autocheck-red uppercase tracking-widest">1 Day Overdue</p>
                        </div>
                    </div>
                </div>
                
                <button class="w-full mt-8 py-4 bg-gray-950 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-gray-900 transition-colors shadow-xl shadow-gray-200">
                    Process All Alerts
                </button>
            </div>
        </div>
    </div>
</x-admin-layout>
