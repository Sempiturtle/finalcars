<x-admin-layout>
    <div class="space-y-4 animate-fade-in pb-10">
        <!-- Compact Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">Scheduling <span class="text-autocheck-red italic">& Capacity</span></h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 italic">Manage shop workload, rest days, and service weights.</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="px-4 py-2 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-autocheck-red">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Capacity</p>
                        <p class="text-sm font-black text-gray-900 leading-none">{{ $maxSlots }} <span class="text-[8px] text-gray-400 uppercase">Slots</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Sidebar: Constraints & Weights -->
            <div class="lg:col-span-1 space-y-4">
                <!-- Global Constraints -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-20 h-20 bg-autocheck-red/5 rounded-full"></div>
                    
                    <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center relative z-10">
                        <span class="w-6 h-6 rounded-md bg-red-50 text-autocheck-red flex items-center justify-center mr-2 text-[10px]">01</span>
                        Constraints
                    </h3>

                    <form action="{{ route('admin.scheduling.update-settings') }}" method="POST" class="space-y-4 relative z-10">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Max Slots/Day</label>
                            <input type="number" name="max_slots_per_day" value="{{ $maxSlots }}" min="1" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border-transparent rounded-xl text-xs font-black focus:bg-white focus:ring-2 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Rest Days</label>
                            <div class="grid grid-cols-2 gap-1.5">
                                @php
                                    $days = [
                                        0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                                        4 => 'Thu', 5 => 'Fri', 6 => 'Sat'
                                    ];
                                @endphp
                                @foreach($days as $val => $name)
                                    <label class="flex items-center p-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors group">
                                        <input type="checkbox" name="rest_days[]" value="{{ $val }}" 
                                               {{ in_array($val, $restDays) ? 'checked' : '' }}
                                               class="w-3 h-3 rounded border-gray-300 text-autocheck-red focus:ring-autocheck-red">
                                        <span class="ml-2 text-[9px] font-black uppercase tracking-tighter text-gray-500 group-hover:text-gray-900">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-black transition-all">
                            Update
                        </button>
                    </form>
                </div>

                <!-- Service Weights -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center mr-2 text-[10px]">02</span>
                        Weights
                    </h3>

                    <form action="{{ route('admin.scheduling.update-weights') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="max-h-[250px] overflow-y-auto pr-1 custom-scrollbar space-y-2">
                            @foreach($serviceTypes as $type)
                                <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl border border-transparent hover:bg-white hover:border-gray-100 transition-all">
                                    <div class="truncate mr-2">
                                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-tighter truncate">{{ $type->name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-1 shrink-0">
                                        <input type="number" name="weights[{{ $type->id }}]" value="{{ $type->required_slots }}" min="1"
                                               class="w-10 px-1 py-1 bg-white border border-gray-100 rounded-md text-[10px] font-black text-center focus:ring-1 focus:ring-blue-500 transition-all">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all mt-2">
                            Save Weights
                        </button>
                    </form>
                </div>
            </div>

            <!-- Calendar View Card -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-autocheck-red border border-gray-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $startDate->format('F Y') }}</h3>
                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Workload Monitor</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            @php
                                $prev = $startDate->copy()->subMonth();
                                $next = $startDate->copy()->addMonth();
                            @endphp
                            <a href="?month={{ $prev->month }}&year={{ $prev->year }}" class="p-2 bg-white border border-gray-100 rounded-lg text-gray-400 hover:text-autocheck-red transition-all shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </a>
                            <a href="?month={{ $next->month }}&year={{ $next->year }}" class="p-2 bg-white border border-gray-100 rounded-lg text-gray-400 hover:text-autocheck-red transition-all shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div class="p-4 flex-1">
                        <div class="grid grid-cols-7 gap-px bg-gray-100 rounded-2xl overflow-hidden border border-gray-100">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="bg-gray-50 py-2 text-center">
                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">{{ $day }}</span>
                                </div>
                            @endforeach

                            @php
                                $currentDate = $startDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                $monthEnd = $startDate->copy()->endOfMonth();
                                $calendarEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            @endphp

                            @while($currentDate <= $calendarEnd)
                                @php
                                    $dateKey = $currentDate->toDateString();
                                    $used = $dailyUsage[$dateKey] ?? 0;
                                    $percent = min(100, ($used / $maxSlots) * 100);
                                    $isToday = $currentDate->isToday();
                                    $isCurrentMonth = $currentDate->month == $startDate->month;
                                    $isRestDay = in_array($currentDate->dayOfWeek, $restDays);
                                @endphp
                                
                                <div class="bg-white min-h-[70px] p-2 relative group transition-colors hover:bg-gray-50/20 {{ !$isCurrentMonth ? 'opacity-30' : '' }}">
                                    <span class="text-[10px] font-black {{ $isToday ? 'bg-autocheck-red text-white w-5 h-5 rounded-md flex items-center justify-center' : 'text-gray-900' }}">
                                        {{ $currentDate->day }}
                                    </span>

                                    <div class="mt-2 space-y-1">
                                        @if($isRestDay)
                                            <div class="px-1.5 py-0.5 bg-gray-50 rounded text-center">
                                                <span class="text-[7px] font-black text-gray-300 uppercase tracking-tighter">OFF</span>
                                            </div>
                                        @elseif($used > 0)
                                            <div class="space-y-0.5">
                                                <div class="flex justify-between items-center text-[7px] font-black uppercase tracking-tighter">
                                                    <span class="{{ $percent >= 100 ? 'text-red-600' : 'text-gray-500' }}">
                                                        {{ $used }}/{{ $maxSlots }}
                                                    </span>
                                                </div>
                                                <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                                                    <div class="h-full transition-all duration-500 {{ $percent >= 100 ? 'bg-red-500' : ($percent >= 70 ? 'bg-orange-400' : 'bg-green-500') }}" 
                                                         style="width: {{ $percent }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if($isCurrentMonth && !$isRestDay && $used < $maxSlots)
                                        <div class="absolute bottom-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-400/50"></div>
                                        </div>
                                    @endif
                                </div>
                                @php $currentDate->addDay(); @endphp
                            @endwhile
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
