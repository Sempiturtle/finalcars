<x-customer-layout>
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Maintenance <span class="text-autocheck-red">Timeline</span></h1>
            <p class="text-gray-500 font-medium mt-1">Track when your vehicles are due for their next service.</p>
        </div>

        @if($timeline['overdue']->count() > 0)
            <div class="bg-red-50 p-6 rounded-[2.5rem] border border-red-100 flex items-center space-x-4 animate-pulse shadow-sm">
                <div class="flex-shrink-0 w-12 h-12 bg-autocheck-red rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h4 class="text-autocheck-red font-black text-sm uppercase tracking-widest">Attention Required</h4>
                    <p class="text-red-700 text-xs font-bold">{{ $timeline['overdue']->count() }} of your vehicles are overdue for maintenance. Please schedule a visit today.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Today & Soon -->
            <div class="md:col-span-1 space-y-6">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest px-2">Due Today & Soon</h3>
                <div class="space-y-4">
                    @forelse($timeline['today']->merge($timeline['this_week']) as $vehicle)
                        <div class="bg-white p-6 rounded-[2rem] border-2 border-orange-100 shadow-xl shadow-orange-500/10 group">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    {{ $vehicle->next_service_date->isToday() ? 'Today' : 'Within 7 Days' }}
                                </span>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $vehicle->plate_number }}</span>
                            </div>
                            <h4 class="text-lg font-black text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-2 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">{{ $vehicle->next_service_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 border-2 border-dashed border-gray-100 rounded-[2.5rem] flex flex-col items-center justify-center text-gray-300">
                           <svg class="h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                           <span class="text-[10px] font-black uppercase tracking-widest">No Urgent Tasks</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- This Month & Future -->
            <div class="md:col-span-3">
                 <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 px-2">Upcoming Schedule</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($timeline['this_month']->merge($timeline['future']) as $vehicle)
                        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-all group border-l-8 {{ $vehicle->next_service_date->diffInDays(now()) <= 30 ? 'border-blue-500' : 'border-gray-300' }}">
                            <div class="flex flex-col space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-black text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 px-3 py-1 rounded-full border border-gray-100">{{ $vehicle->plate_number }}</span>
                                </div>
                                <div class="flex items-center space-x-6">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Next Service</span>
                                            <span class="text-xs font-black text-gray-900">{{ $vehicle->next_service_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="h-8 w-px bg-gray-100"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Days Remaining</span>
                                        <span class="text-xs font-black {{ $vehicle->next_service_date->diffInDays(now()) <= 30 ? 'text-blue-600' : 'text-gray-900' }}">
                                            {{ $vehicle->next_service_date->diffInDays(now()) }} Days
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                 </div>

                 @if($timeline['unscheduled']->count() > 0)
                    <div class="mt-10 p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Unscheduled Maintenance</h3>
                            <span class="text-[10px] font-black text-gray-400 bg-white px-3 py-1 rounded-full">{{ $timeline['unscheduled']->count() }} Items</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($timeline['unscheduled'] as $vehicle)
                                <div class="bg-white p-4 rounded-3xl border border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-lg font-black text-gray-300">{{ substr($vehicle->make, 0, 1) }}</div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                            <p class="text-[10px] font-bold text-gray-400">{{ $vehicle->plate_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-customer-layout>
