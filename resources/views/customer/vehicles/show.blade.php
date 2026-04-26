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
                                <span class="text-[10px] font-black uppercase tracking-widest text-yellow-600">{{ $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->format('F j, Y') : 'PENDING' }}</span>
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
                            <span class="font-bold text-gray-900 uppercase">{{ $vehicle->registration_date ? \Carbon\Carbon::parse($vehicle->registration_date)->format('F j, Y') : 'N/A' }}</span>
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

    <!-- Add Service Log Modal -->
    <div id="serviceModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-fade-in-up" x-data="calendarPicker()">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-autocheck-red border border-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Log Maintenance</h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Schedule a new service record</p>
                    </div>
                </div>
                <button onclick="document.getElementById('serviceModal').classList.add('hidden')" class="p-2 text-gray-400 hover:text-autocheck-red transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('customer.vehicles.log-service', $vehicle) }}" method="POST" class="p-8 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl mb-2">
                        <div class="flex items-center mb-2">
                            <svg class="h-4 w-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            <span class="text-[9px] font-black text-red-700 uppercase tracking-widest">Appointment Error</span>
                        </div>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-[9px] font-bold text-red-600 uppercase tracking-tighter italic">✕ {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Type</label>
                        <select name="service_type_id" id="service_type_id" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-xs" onchange="updatePrice()">
                            <option value="" disabled selected>Select type...</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" data-price="{{ $type->base_cost }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Mode</label>
                        <select name="service_mode" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-xs">
                            <option value="Walk-in">Walk-in</option>
                            <option value="Towing">Towing</option>
                            <option value="Home Service">Home Service</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Effective Cost</label>
                        <div class="w-full px-5 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl text-[11px] font-black text-gray-400 italic" id="priceDisplay">
                            Select Type First
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Service Date</label>
                        <input type="hidden" name="service_date" :value="selectedDate">
                        <button type="button" @click="showCalendar = true" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-between group hover:border-autocheck-red transition-all">
                            <span class="text-xs font-black text-gray-900" x-text="selectedDate ? formatDate(selectedDate) : 'Pick a date...'"></span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-autocheck-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Additional Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-autocheck-red transition-all outline-none font-bold text-xs" placeholder="Describe any specific issues..."></textarea>
                </div>

                <button type="submit" :disabled="!selectedDate" class="w-full py-5 bg-gray-900 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-autocheck-red transition-all shadow-xl shadow-black/10 disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirm Service Log
                </button>
            </form>

            <!-- Interactive Calendar Overlay (Nested Modal) -->
            <div x-show="showCalendar" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak x-transition>
                <div class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden flex flex-col md:flex-row" @click.away="showCalendar = false">
                    <!-- Left Sidebar: Info & Legend (Compact) -->
                    <div class="w-full md:w-64 bg-gray-50/80 p-8 flex flex-col justify-between border-b md:border-b-0 md:border-r border-gray-100">
                        <div class="space-y-8">
                            <div>
                                <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em] mb-2">Service Scheduler</h3>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed">Select a date. We track real-time shop capacity for you.</p>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Guide</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3 group">
                                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-sm group-hover:scale-110 transition-transform"></div>
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Available</span>
                                    </div>
                                    <div class="flex items-center space-x-3 group">
                                        <div class="w-2.5 h-2.5 rounded-full bg-orange-400 shadow-sm group-hover:scale-110 transition-transform"></div>
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Filling Up</span>
                                    </div>
                                    <div class="flex items-center space-x-3 group">
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm group-hover:scale-110 transition-transform"></div>
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Almost Full</span>
                                    </div>
                                    <div class="flex items-center space-x-3 group opacity-50">
                                        <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Closed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-200/50">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest italic">Live capacity tracking active.</p>
                        </div>
                    </div>

                    <!-- Right Section: Calendar Grid (Efficient) -->
                    <div class="flex-1 p-8 bg-white relative">
                        <!-- Top Nav (Integrated) -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-4">
                                <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-50 rounded-xl text-gray-400 hover:text-autocheck-red transition-all">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <span class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em] min-w-[120px] text-center" x-text="monthName + ' ' + year"></span>
                                <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-50 rounded-xl text-gray-400 hover:text-autocheck-red transition-all">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>

                            <button type="button" @click="showCalendar = false" class="p-2 text-gray-300 hover:text-autocheck-red transition-all">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-2">
                            <template x-for="dayName in ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']">
                                <div class="text-center py-1">
                                    <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest" x-text="dayName"></span>
                                </div>
                            </template>

                            <template x-for="blank in blanks">
                                <div class="aspect-square"></div>
                            </template>

                            <template x-for="day in daysInMonth">
                                <button type="button" 
                                    @click="if(day.available) { selectedDate = day.date; showCalendar = false; }"
                                    :disabled="!day.available"
                                    class="aspect-square flex flex-col items-center justify-between p-3 rounded-[1.5rem] transition-all relative border group"
                                    :class="{
                                        'bg-autocheck-red text-white border-autocheck-red shadow-xl shadow-red-500/30 transform scale-105 z-10': selectedDate === day.date,
                                        'bg-white border-gray-50 hover:border-autocheck-red hover:shadow-md': selectedDate !== day.date && day.available,
                                        'bg-gray-50 border-transparent opacity-40 cursor-not-allowed': !day.available && !day.is_rest_day,
                                        'bg-gray-100/50 border-transparent text-gray-300': day.is_rest_day,
                                        'ring-1 ring-autocheck-red ring-offset-2': day.date === new Date().toISOString().split('T')[0] && selectedDate !== day.date
                                    }">
                                    
                                    <span class="text-[11px] font-black" x-text="day.day"></span>

                                    <div class="w-full space-y-0.5">
                                        <template x-if="day.is_rest_day">
                                            <span class="text-[7px] font-black text-gray-300 uppercase tracking-tighter block text-center">OFF</span>
                                        </template>
                                        <template x-if="!day.is_rest_day && day.available">
                                            <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full transition-all duration-700" 
                                                     :class="{
                                                        'bg-white/60': selectedDate === day.date,
                                                        'bg-green-500': selectedDate !== day.date && day.percent < 50,
                                                        'bg-orange-400': selectedDate !== day.date && day.percent >= 50 && day.percent < 80,
                                                        'bg-red-500': selectedDate !== day.date && day.percent >= 80
                                                     }"
                                                     :style="'width: ' + Math.max(20, day.percent) + '%'"></div>
                                            </div>
                                        </template>
                                        <template x-if="!day.is_rest_day && !day.available && !day.is_past">
                                            <span class="text-[7px] font-black text-red-400 uppercase tracking-tighter block text-center">FULL</span>
                                        </template>
                                    </div>
                                </button>
                            </template>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] italic">Click a date to schedule</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePrice() {
            const select = document.getElementById('service_type_id');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.getAttribute('data-price')) return;
            
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('priceDisplay').innerText = '₱' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('priceDisplay').classList.remove('text-gray-400');
            document.getElementById('priceDisplay').classList.add('text-autocheck-red');
        }

        function calendarPicker() {
            return {
                showCalendar: false,
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                daysInMonth: [],
                blanks: [],
                selectedDate: '{{ old('service_date') }}',
                restDays: {{ json_encode(array_map('intval', \App\Models\Setting::get('rest_days', [0]))) }},
                monthName: '',

                init() {
                    this.renderCalendar();
                },

                async renderCalendar() {
                    const firstDay = new Date(this.year, this.month, 1);
                    this.monthName = firstDay.toLocaleString('default', { month: 'long' });
                    this.blanks = Array.from({ length: firstDay.getDay() }, (_, i) => i);
                    
                    const lastDay = new Date(this.year, this.month + 1, 0).getDate();
                    const today = new Date().setHours(0,0,0,0);

                    // Initialize days with default state so they show up immediately
                    this.daysInMonth = Array.from({ length: lastDay }, (_, i) => {
                        const d = i + 1;
                        const dateStr = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        const tempDate = new Date(this.year, this.month, d);
                        const isRestDay = this.restDays.includes(tempDate.getDay());
                        const isPast = tempDate.getTime() < today;
                        
                        return {
                            day: d,
                            date: dateStr,
                            available: !isRestDay, // Allow past dates for testing overdue reports
                            is_rest_day: isRestDay,
                            percent: 0,
                            is_past: isPast
                        };
                    });
                    
                    try {
                        const response = await fetch(`{{ route('customer.vehicles.month-availability') }}?month=${this.month + 1}&year=${this.year}`);
                        const availability = await response.json();
                        
                        // Update availability info
                        this.daysInMonth = this.daysInMonth.map(day => {
                            const data = availability[day.date] || {};
                            return {
                                ...day,
                                // If it's in the past, it's available (for testing overdue). 
                                // If future, respect shop availability.
                                available: day.is_past ? !day.is_rest_day : (data.available && !day.is_rest_day),
                                percent: data.slots_percent || 0
                            };
                        });
                    } catch (e) {
                        console.error('Failed to fetch availability', e);
                    }
                },

                formatDate(dateStr) {
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                },

                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                    this.renderCalendar();
                },

                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                    this.renderCalendar();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if($errors->any())
                document.getElementById('serviceModal').classList.remove('hidden');
            @endif
        });
    </script>
</x-customer-layout>
