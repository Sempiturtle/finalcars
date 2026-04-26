<x-customer-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.vehicles.index') }}" class="p-2 bg-white border border-gray-100 rounded-xl text-gray-400 hover:text-autocheck-red transition-all shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $vehicle->plate_number }} • Maintenance Profile</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button onclick="document.getElementById('serviceModal').classList.remove('hidden')" class="px-6 py-2.5 bg-autocheck-red text-white text-[10px] font-black rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20 uppercase tracking-widest flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Service Log
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 rounded-2xl border border-green-100 flex items-center space-x-3 shadow-sm">
                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 text-[10px] font-bold uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Vehicle Details -->
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 border-2 border-white shadow-xl text-autocheck-red">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 leading-tight uppercase">{{ $vehicle->year }} {{ $vehicle->make }}</h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $vehicle->model }} • {{ $vehicle->color ?? 'Standard' }}</p>
                        
                        <div class="mt-8 grid grid-cols-2 gap-2">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $vehicle->calculated_status === 'due today' ? 'text-amber-600' : ($vehicle->calculated_status === 'overdue' ? 'text-red-600' : 'text-blue-600') }}">{{ $vehicle->calculated_status }}</span>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Next Service</p>
                                <span class="text-[10px] font-black uppercase tracking-widest text-yellow-600">{{ $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->format('M d') : 'PENDING' }}</span>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-gray-900 rounded-2xl border border-gray-800 shadow-xl">
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Plate Authentication</p>
                            <p class="text-lg font-black text-white tracking-[0.2em] italic">{{ $vehicle->plate_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Registration info -->
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center">
                        <svg class="h-3 w-3 mr-2 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Registration Details
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="font-black text-gray-400 uppercase tracking-widest">Registered Date</span>
                            <span class="font-bold text-gray-900 uppercase">{{ $vehicle->registration_date ? \Carbon\Carbon::parse($vehicle->registration_date)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="font-black text-gray-400 uppercase tracking-widest">Ownership</span>
                            <span class="font-bold text-gray-900 uppercase">Self-Account</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Service History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Service Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                             <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Services</p>
                            <p class="text-xl font-black text-gray-900">{{ $serviceHistory->count() }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                             <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Maintenance Investment</p>
                            <p class="text-xl font-black text-gray-900">₱{{ number_format($serviceHistory->sum('cost'), 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- History Table -->
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
                    <div class="p-8 border-b border-gray-50">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Service Records</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Service Type</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date Reported</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Cost</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($serviceHistory as $log)
                                    <tr class="group hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-5">
                                            <p class="text-xs font-black text-gray-900 uppercase">{{ $log->service_type }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5 tracking-widest italic">{{ $log->service_mode ?? 'Walk-in' }}</p>
                                        </td>
                                        <td class="px-8 py-5">
                                            <p class="text-[10px] font-bold text-gray-500 uppercase italic">{{ $log->service_date->format('M d, Y') }}</p>
                                        </td>
                                        <td class="px-8 py-5 text-right font-black text-xs text-autocheck-red">
                                            ₱{{ number_format($log->cost, 2) }}
                                        </td>
                                        <td class="px-8 py-5 flex justify-center">
                                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest 
                                                {{ $log->status === 'scheduled' && $log->service_date->isToday() ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                                                {{ $log->status === 'scheduled' && $log->service_date->isToday() ? 'due today' : $log->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-14 text-center">
                                            <p class="text-xs font-bold text-gray-400 italic">No service records found for this vehicle.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div id="serviceModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Log Maintenance</h3>
                <button onclick="document.getElementById('serviceModal').classList.add('hidden')" class="p-2 text-gray-400 hover:text-autocheck-red transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('customer.vehicles.log-service', $vehicle) }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Type</label>
                        <select name="service_type_id" id="service_type_id" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" onchange="updatePrice()">
                            <option value="" disabled selected>Select type...</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" data-price="{{ $type->base_cost }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Mode</label>
                        <select name="service_mode" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs">
                            <option value="Walk-in">Walk-in</option>
                            <option value="Towing">Towing</option>
                            <option value="Home Service">Home Service</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Effective Cost</label>
                        <div class="w-full px-5 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl text-[10px] font-black text-gray-400 italic" id="priceDisplay">
                            Select Type First
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Date</label>
                        <input type="date" name="service_date" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-bold text-xs" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-autocheck-red text-white text-[10px] font-black rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 uppercase tracking-[0.2em]">
                        Log Maintenance Service
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-4 leading-relaxed italic">
                        By logging, this will be scheduled for admin review and notification will be sent.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updatePrice() {
            const select = document.getElementById('service_type_id');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('priceDisplay').innerText = '₱' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('priceDisplay').classList.remove('text-gray-400');
            document.getElementById('priceDisplay').classList.add('text-autocheck-red');
        </div>
    </script>
</x-customer-layout>
