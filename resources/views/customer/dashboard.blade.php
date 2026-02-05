<x-customer-layout>
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="bg-white p-8 md:p-12 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full -mr-32 -mt-32 transition-transform group-hover:scale-110 duration-700"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Welcome, <span class="text-autocheck-red">{{ $user->name }}</span>!</h1>
                <p class="text-gray-500 font-medium mt-2 text-lg italic">View your vehicle maintenance information and service history.</p>
                
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-gray-50">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Email Address</p>
                        <p class="font-bold text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Phone Number</p>
                        <p class="font-bold text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Physical Address</p>
                        <p class="font-bold text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Vehicle Selector & Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Selector -->
                <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm">
                    <form action="{{ route('customer.dashboard') }}" method="GET" x-data x-ref="selectForm">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 block mb-3">Select Vehicle</label>
                        <div class="relative">
                            <select 
                                name="vehicle_id" 
                                @change="$refs.selectForm.submit()"
                                class="w-full pl-8 pr-12 py-5 bg-gray-50 border-transparent rounded-[2rem] text-sm font-black focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all appearance-none cursor-pointer"
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
                    <div class="bg-white p-10 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
                            <div class="flex items-center space-x-6">
                                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center text-autocheck-red border border-gray-100 italic font-black text-2xl">
                                    {{ substr($selectedVehicle->make, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-1">Selected Vehicle</h3>
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $selectedVehicle->make }} {{ $selectedVehicle->model }}</h2>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-6 py-2 bg-red-50 text-autocheck-red rounded-full text-[10px] font-black uppercase tracking-widest border border-red-100 italic">
                                    {{ $selectedVehicle->status }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10 pt-10 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Color</p>
                                <p class="text-sm font-bold text-gray-900">{{ $selectedVehicle->color ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Plate Number</p>
                                <p class="text-sm font-black text-gray-900">{{ $selectedVehicle->plate_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Services</p>
                                <p class="text-sm font-bold text-gray-900">{{ $selectedVehicleStats['total_services'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Next Maintenance</p>
                                <p class="text-sm font-black {{ ($selectedVehicle->next_service_date && $selectedVehicle->next_service_date->isPast()) ? 'text-autocheck-red' : 'text-green-600' }}">
                                    {{ $selectedVehicle->next_service_date ? $selectedVehicle->next_service_date->format('M d, Y') : 'Not Scheduled' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 rounded-[2rem] p-8 border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Next Maintenance Due</h4>
                                <span class="text-[10px] font-bold text-gray-400 italic">Expected arrival</span>
                            </div>
                            <p class="text-2xl font-black text-gray-900 tracking-tight">
                                {{ $selectedVehicle->next_service_date ? $selectedVehicle->next_service_date->diffForHumans() : 'No upcoming service' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service History Table -->
                    <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-10 border-b border-gray-50">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">Service History</h3>
                            <p class="text-gray-500 font-medium text-sm mt-1italic">Detailed record of all maintenance work performed.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Type</th>
                                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Mechanic</th>
                                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($serviceHistory as $log)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-10 py-6 text-sm font-bold text-gray-500 italic">{{ optional($log->service_date)->format('M d, Y') ?? 'N/A' }}</td>
                                            <td class="px-10 py-6">
                                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $log->service_type }}</p>
                                            </td>
                                            <td class="px-10 py-6 text-sm font-bold text-gray-500">{{ $log->mechanic_name ?? 'N/A' }}</td>
                                            <td class="px-10 py-6 text-right">
                                                <span class="text-lg font-black text-gray-900 tracking-tight">₱{{ number_format($log->cost, 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-10 py-20 text-center text-gray-400 font-bold italic">No service history found for this vehicle.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-20 rounded-[3.5rem] border border-gray-100 shadow-sm text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">No Vehicle Selected</h3>
                        <p class="text-gray-500 font-medium mt-2">Please select a vehicle to view its maintenance information.</p>
                    </div>
                @endif
            </div>

            <!-- Fleet Summary Sidebar -->
            <div class="space-y-8">
                <div class="bg-white p-10 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-autocheck-red/5 rounded-full -mr-16 -mt-16"></div>
                    
                    <h3 class="text-xl font-black text-gray-900 tracking-tight mb-8">Fleet Overview</h3>
                    
                    <div class="space-y-10">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Services</p>
                            <div class="flex items-baseline space-x-2">
                                <p class="text-4xl font-black text-gray-900">{{ number_format($fleetStats['total_services']) }}</p>
                                <span class="text-xs font-bold text-gray-400 italic">records</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Upcoming Services</p>
                            <div class="flex items-baseline space-x-2">
                                <p class="text-4xl font-black text-yellow-600">{{ number_format($fleetStats['upcoming_services']) }}</p>
                                <span class="text-xs font-bold text-gray-400 italic">pending</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Investment</p>
                            <div class="flex items-baseline space-x-1">
                                <p class="text-xs font-black text-gray-400">₱</p>
                                <p class="text-3xl font-black text-gray-900">{{ number_format($fleetStats['total_cost'], 2) }}</p>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 mt-1 italic tracking-wider">Lifetime maintenance cost</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-customer-layout>
