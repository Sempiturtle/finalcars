<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Maintenance <span class="text-autocheck-red">Schedule</span></h1>
                <p class="text-gray-500 mt-1 font-medium">Monitor and manage your fleet's regular service appointments.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Calendar Card -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                    <div id="calendar" class="min-h-[600px]"></div>
                </div>
            </div>

            <!-- Schedule List -->
            <div class="space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                        Upcoming <span class="text-autocheck-red ml-1">Schedules</span>
                    </h2>
                    
                    <div class="space-y-6 overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
                        @foreach($schedules as $schedule)
                            <div class="group relative bg-gray-50 rounded-3xl p-6 transition-all hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 border border-transparent hover:border-gray-100">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest 
                                        {{ $schedule['status'] === 'Overdue' ? 'bg-red-100 text-red-600' : '' }}
                                        {{ $schedule['status'] === 'Scheduled' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $schedule['status'] === 'Unscheduled' ? 'bg-gray-100 text-gray-500' : '' }}">
                                        {{ $schedule['status'] }}
                                    </span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                    </button>
                                </div>

                                <h3 class="font-bold text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $schedule['description'] }}</h3>
                                <p class="text-sm text-gray-500 font-medium mb-4">{{ $schedule['make_model'] }} ({{ $schedule['plate_number'] }})</p>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-sm font-bold text-gray-900">
                                        <svg class="h-4 w-4 mr-2 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $schedule['next_service_date'] }}
                                    </div>
                                    <div class="text-[11px] font-black text-gray-400 uppercase tracking-tight flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-2"></span>
                                        @if($schedule['days_diff'] !== null)
                                            {{ $schedule['is_overdue'] ? "{$schedule['days_diff']} days overdue" : "In {$schedule['days_diff']} days" }}
                                        @else
                                            No date set
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($schedules->isEmpty())
                            <div class="text-center py-12">
                                <div class="bg-gray-50 w-16 h-16 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-bold text-sm">No maintenance scheduled</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: [
                    @foreach($schedules as $schedule)
                        @if($schedule['sort_date'])
                        {
                            title: '{{ $schedule["plate_number"] }}',
                            start: '{{ $schedule["sort_date"] }}',
                            color: '{{ $schedule["is_overdue"] ? "#F53003" : "#059669" }}',
                            extendedProps: {
                                description: '{{ $schedule["description"] }}'
                            }
                        },
                        @endif
                    @endforeach
                ],
                themeSystem: 'standard',
                height: 'auto',
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                }
            });
            calendar.render();
        });
    </script>

    <style>
        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 900 !important;
            color: #111827 !important;
        }
        .fc-button {
            background-color: white !important;
            border-color: #E5E7EB !important;
            color: #374151 !important;
            text-transform: capitalize !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding: 0.5rem 1rem !important;
            height: auto !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s !important;
        }
        .fc-button:hover {
            background-color: #F9FAFB !important;
            border-color: #D1D5DB !important;
        }
        .fc-button-active {
            background-color: #F53003 !important;
            border-color: #F53003 !important;
            color: white !important;
        }
        .fc-daygrid-event {
            border-radius: 0.5rem !important;
            padding: 2px 4px !important;
            font-weight: 800 !important;
            font-size: 0.75rem !important;
            border: none !important;
        }
        .fc-icon {
            font-size: 1rem !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }
    </style>
</x-admin-layout>
