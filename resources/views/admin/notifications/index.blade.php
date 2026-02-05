<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Email Notification System</h1>
                <p class="text-gray-500 font-medium mt-1">Automated email reminders for maintenance schedules.</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm flex items-center space-x-6">
                <div class="w-16 h-16 bg-blue-50 rounded-[2rem] flex items-center justify-center shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Email Sent</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_sent']) }}</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm flex items-center space-x-6">
                <div class="w-16 h-16 bg-green-50 rounded-[2rem] flex items-center justify-center shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Successfully Delivered</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['successfully_delivered']) }}</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm flex items-center space-x-6">
                <div class="w-16 h-16 bg-red-50 rounded-[2rem] flex items-center justify-center shrink-0">
                    <svg class="h-8 w-8 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Failed/Pending</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($stats['failed_pending']) }}</p>
                </div>
            </div>
        </div>

        <!-- Vehicles Requiring Attention -->
        <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Vehicles Requiring Email Notifications</h2>
                    <p class="text-gray-500 font-medium text-sm mt-1">Customers with overdue or upcoming maintenance schedules.</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center">
                    <span class="text-sm font-black text-gray-400">{{ $vehiclesRequiringAttention->count() }}</span>
                </div>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($vehiclesRequiringAttention as $v)
                    <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-6">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border border-gray-100 {{ $v['is_overdue'] ? 'bg-red-50 text-autocheck-red' : 'bg-yellow-50 text-yellow-600' }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-1">
                                    <h4 class="text-lg font-black text-gray-900 tracking-tight">{{ $v['type'] }} {{ $v['plate_number'] }}</h4>
                                    <span class="px-3 py-1 bg-white border border-gray-100 rounded-lg text-[10px] font-black uppercase text-gray-400 tracking-widest italic">{{ $v['plate_number'] }}</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                                    <p class="text-sm text-gray-500 font-bold">Customer: <span class="text-gray-900">{{ $v['customer_name'] }}</span></p>
                                    <p class="text-sm text-gray-500 font-bold">Email: <span class="text-blue-600 underline">{{ $v['customer_email'] }}</span></p>
                                    <p class="text-sm text-gray-500 font-bold">Vehicle: <span class="text-gray-900 uppercase">{{ $v['vehicle_desc'] }}</span></p>
                                    <p class="text-sm text-gray-500 font-bold">Next Service: <span class="{{ $v['is_overdue'] ? 'text-autocheck-red' : 'text-gray-900' }}">{{ $v['next_service'] }}</span></p>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.notifications.send', $v['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-8 py-3.5 bg-autocheck-red text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Send Email
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <p class="text-gray-400 font-bold italic tracking-wide">No vehicles currently require notification.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Email Logs Section -->
        <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Email Logs</h2>
                    <p class="text-gray-500 font-medium text-sm mt-1">Detailed history of all notification attempts.</p>
                </div>
                <div class="flex gap-2 p-1 bg-gray-50 rounded-2xl shrink-0">
                    @foreach(['All', 'Before Due', 'On Due', 'Over Due'] as $filter)
                        <a href="{{ route('admin.notifications.index', ['type' => $filter]) }}" 
                           class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ (request('type', 'All') == $filter) ? 'bg-white text-autocheck-red shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                            {{ $filter }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date Sent</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Customer Email</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Type</th>
                            <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($emailLogs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-10 py-6 text-sm font-bold text-gray-700 italic border-r border-gray-50">{{ $log->sent_at?->format('M d, Y h:i A') ?? 'Pending' }}</td>
                                <td class="px-10 py-6">
                                    <p class="text-sm font-black text-gray-900 tracking-tight">{{ $log->vehicle->make }} {{ $log->vehicle->model }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $log->vehicle->plate_number }}</p>
                                </td>
                                <td class="px-10 py-6 text-sm font-bold text-blue-600 underline">{{ $log->recipient_email }}</td>
                                <td class="px-10 py-6">
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg bg-gray-100 text-gray-600 border border-gray-100">
                                        {{ str_replace('_', ' ', $log->notification_type) }}
                                    </span>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-1.5 rounded-full mr-2 {{ $log->status == 'delivered' ? 'bg-green-500' : ($log->status == 'failed' ? 'bg-autocheck-red' : 'bg-yellow-500') }}"></div>
                                        <span class="text-[10px] font-black uppercase tracking-[0.15em] {{ $log->status == 'delivered' ? 'text-green-600' : ($log->status == 'failed' ? 'text-autocheck-red' : 'text-yellow-600') }}">
                                            {{ $log->status }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-20 text-center text-gray-400 font-bold italic">No email logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-10 py-6 bg-gray-50/30">
                {{ $emailLogs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
