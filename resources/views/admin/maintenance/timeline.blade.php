<x-admin-layout>
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Timeline <span class="text-autocheck-red">Monitoring</span></h1>
            <p class="text-gray-500 font-medium mt-1">Easily monitor upcoming maintenance across the entire fleet.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            @php
                $sections = [
                    'overdue' => ['title' => 'Overdue', 'color' => 'red', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'bg' => 'bg-red-50', 'text' => 'text-autocheck-red'],
                    'today' => ['title' => 'Due Today', 'color' => 'orange', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                    'this_week' => ['title' => 'Next 7 Days', 'color' => 'yellow', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                    'this_month' => ['title' => 'Next 30 Days', 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                    'future' => ['title' => 'Future', 'color' => 'gray', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'],
                ];
            @endphp

            @foreach($sections as $key => $info)
                <div class="flex flex-col space-y-4">
                    <div class="flex items-center space-x-3 px-2">
                        <div class="p-2 {{ $info['bg'] }} {{ $info['text'] }} rounded-xl">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path></svg>
                        </div>
                        <h2 class="font-black text-sm uppercase tracking-widest text-gray-400">{{ $info['title'] }}</h2>
                        <span class="ml-auto bg-gray-100 text-gray-500 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $timeline[$key]->count() }}</span>
                    </div>

                    <div class="space-y-3 min-h-[200px]">
                        @forelse($timeline[$key] as $vehicle)
                            <div class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full {{ str_replace('text', 'bg', $info['text']) }}"></div>
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $vehicle->plate_number }}</span>
                                        <span class="text-[10px] font-bold {{ $info['text'] }}">{{ $vehicle->next_service_date->format('M d') }}</span>
                                    </div>
                                    <p class="text-sm font-black text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-5 h-5 rounded-lg bg-gray-50 flex items-center justify-center">
                                            <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-500">{{ $vehicle->owner_name ?? ($vehicle->owner ? $vehicle->owner->name : 'N/A') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="absolute inset-0 z-10"></a>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-100 rounded-[2rem] text-gray-300">
                                <svg class="h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">Clear</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        @if($timeline['unscheduled']->count() > 0)
            <div class="bg-gray-100 p-8 rounded-[2.5rem]">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Unscheduled Vehicles ({{ $timeline['unscheduled']->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($timeline['unscheduled'] as $vehicle)
                        <div class="bg-white p-4 rounded-2xl border border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-[10px] font-black text-gray-400">{{ substr($vehicle->make, 0, 1) }}</div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                    <p class="text-[9px] font-bold text-gray-400">{{ $vehicle->plate_number }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-[9px] font-black text-autocheck-red p-2 hover:bg-red-50 rounded-lg">Schedule</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
