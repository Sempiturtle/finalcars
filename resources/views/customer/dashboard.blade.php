<x-customer-layout>
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-48 h-48 bg-red-50 rounded-full -mr-24 -mt-24 transition-transform group-hover:scale-110 duration-700"></div>
            <div class="relative z-10">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Welcome, <span class="text-autocheck-red">{{ $user->name }}</span>!</h1>
                <p class="text-[13px] text-gray-500 font-medium mt-0.5 italic">View your vehicle maintenance information and service history.</p>
                
                <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4 pt-5 border-t border-gray-50">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Email Address</p>
                        <p class="text-[12px] font-bold text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Phone Number</p>
                        <p class="text-[12px] font-bold text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Physical Address</p>
                        <p class="text-[12px] font-bold text-gray-900 line-clamp-1">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Vehicle Selector & Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Selector -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form action="{{ route('customer.dashboard') }}" method="GET" x-data x-ref="selectForm">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 block mb-2">Select Vehicle</label>
                        <div class="relative">
                            <select 
                                name="vehicle_id" 
                                @change="$refs.selectForm.submit()"
                                class="w-full pl-6 pr-10 py-3.5 bg-gray-50 border-transparent rounded-xl text-xs font-black focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all appearance-none cursor-pointer"
                            >
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ $selectedVehicle && $selectedVehicle->id == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                    </option>
                                @endforeach
                                @if($vehicles->isEmpty())
                                    <option disabled>No vehicles registered</option>
                                @endif
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </form>
                </div>

                @if($selectedVehicle)
                    <!-- Vehicle Details Card -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                            <div class="flex items-center space-x-5">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-autocheck-red border border-gray-100 italic font-black text-xl">
                                    {{ substr($selectedVehicle->make, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-0.5">Selected Vehicle</h3>
                                    <h2 class="text-xl font-black text-gray-900 tracking-tight">{{ $selectedVehicle->make }} {{ $selectedVehicle->model }}</h2>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-4 py-1.5 bg-red-50 text-autocheck-red rounded-full text-[9px] font-black uppercase tracking-widest border border-red-100 italic">
                                    {{ $selectedVehicle->status }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 pt-8 border-t border-gray-50">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Color</p>
                                <p class="text-[13px] font-bold text-gray-900">{{ $selectedVehicle->color ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Plate Number</p>
                                <p class="text-[13px] font-black text-gray-900">{{ $selectedVehicle->plate_number }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Services</p>
                                <p class="text-[13px] font-bold text-gray-900">{{ $selectedVehicleStats['total_services'] }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Next Maintenance</p>
                                <p class="text-[13px] font-black {{ ($selectedVehicle->next_service_date && $selectedVehicle->next_service_date->isPast()) ? 'text-autocheck-red' : 'text-green-600' }}">
                                    {{ $selectedVehicle->next_service_date ? $selectedVehicle->next_service_date->format('M d, Y') : 'Not Scheduled' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Next Maintenance Due</h4>
                                <span class="text-[9px] font-bold text-gray-400 italic">Expected arrival</span>
                            </div>
                            <p class="text-xl font-black text-gray-900 tracking-tight">
                                {{ $selectedVehicle->next_service_date ? $selectedVehicle->next_service_date->diffForHumans() : 'No upcoming service' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service History Table -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Service History</h3>
                            <p class="text-gray-500 font-medium text-[11px] mt-0.5 italic">Detailed record of all maintenance work performed.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Type</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($serviceHistory as $log)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4 text-[12px] font-bold text-gray-500 italic">{{ optional($log->service_date)->format('M d, Y') ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">
                                                <p class="text-[12px] font-black text-gray-900 tracking-tight">{{ $log->service_type }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="text-base font-black text-gray-900 tracking-tight">₱{{ number_format($log->cost, 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold italic text-[11px]">No service history found for this vehicle.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden relative">
                        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-autocheck-red/5 rounded-full blur-3xl"></div>
                        
                        <div class="p-12 md:p-20 relative z-10 text-center">
                            <div class="w-24 h-24 bg-red-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 text-autocheck-red shadow-inner">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            
                            <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-4 uppercase">Welcome to <span class="text-autocheck-red italic">AutoCheck</span></h2>
                            <p class="text-xl text-gray-500 font-medium mb-12 max-w-lg mx-auto leading-relaxed">
                                Get started by registering your vehicle to track maintenance, earn loyalty rewards, and receive smart service alerts.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 text-left">
                                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                                    <h4 class="text-xs font-black text-autocheck-red uppercase tracking-widest mb-2 italic">Phase 01</h4>
                                    <p class="text-sm font-bold text-gray-900 mb-1">Register Fleet</p>
                                    <p class="text-[10px] text-gray-500 font-medium line-clamp-2 uppercase">Add your vehicles to the digital garage.</p>
                                </div>
                                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                                    <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-2 italic">Phase 02</h4>
                                    <p class="text-sm font-bold text-gray-900 mb-1">Automated Tracking</p>
                                    <p class="text-[10px] text-gray-500 font-medium line-clamp-2 uppercase">Monitor service schedules in real-time.</p>
                                </div>
                                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                                    <h4 class="text-xs font-black text-green-600 uppercase tracking-widest mb-2 italic">Phase 03</h4>
                                    <p class="text-sm font-bold text-gray-900 mb-1">Earn Rewards</p>
                                    <p class="text-[10px] text-gray-500 font-medium line-clamp-2 uppercase">Collect points for every maintenance work.</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('customer.vehicles.create') }}" class="inline-flex items-center px-12 py-5 bg-autocheck-red text-white text-sm font-black rounded-2xl hover:bg-red-700 transition-all shadow-2xl shadow-red-500/30 uppercase tracking-widest active:scale-95 group">
                                Register Your First Vehicle
                                <svg class="ml-3 h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                        
                        <!-- Mini Branding -->
                        <div class="px-12 py-6 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">SECURE AUTOMOTIVE DATA TRACKING v2.0</span>
                            <div class="flex space-x-2">
                                <div class="h-1 w-8 bg-autocheck-red rounded-full"></div>
                                <div class="h-1 w-4 bg-gray-200 rounded-full"></div>
                                <div class="h-1 w-4 bg-gray-200 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>            <!-- Fleet Summary Sidebar -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-autocheck-red/5 rounded-full -mr-12 -mt-12"></div>
                    
                    <h3 class="text-lg font-black text-gray-900 tracking-tight mb-6">Fleet Overview</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Services</p>
                            <div class="flex items-baseline space-x-2">
                                <p class="text-3xl font-black text-gray-900">{{ number_format($fleetStats['total_services']) }}</p>
                                <span class="text-[10px] font-bold text-gray-400 italic">records</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Upcoming Services</p>
                            <div class="flex items-baseline space-x-2">
                                <p class="text-3xl font-black text-yellow-600">{{ number_format($fleetStats['upcoming_services']) }}</p>
                                <span class="text-[10px] font-bold text-gray-400 italic">pending</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Investment</p>
                            <div class="flex items-baseline space-x-1">
                                <p class="text-[10px] font-black text-gray-400">₱</p>
                                <p class="text-2xl font-black text-gray-900">{{ number_format($fleetStats['total_cost'], 2) }}</p>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 mt-0.5 italic tracking-wider">Lifetime maintenance</p>
                        </div>
                    </div>
                </div>
            </div>div>
        </div>
    </div>
</x-customer-layout>
