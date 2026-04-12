<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Attention <span class="text-autocheck-red">Required</span></h1>
                <p class="text-gray-500 font-medium mt-1">Vehicles with overdue maintenance schedules that require immediate action.</p>
            </div>
            @if($attentionRequired->isNotEmpty())
                <form action="{{ route('admin.attention-required.notify-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-10 py-4 bg-gray-950 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-gray-900 transition-all shadow-xl shadow-gray-200 group">
                        <svg class="h-4 w-4 mr-3 text-autocheck-red group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Notify All Owners
                    </button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="p-6 bg-green-50 border border-green-100 rounded-3xl flex items-center space-x-4 animate-in fade-in slide-in-from-top-4">
                <div class="w-10 h-10 bg-green-500 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-green-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-green-800 font-bold tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Overdue List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if($attentionRequired->isNotEmpty())
                @foreach($attentionRequired as $v)
                <div class="bg-white p-8 rounded-[3.5rem] border border-gray-100 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group relative overflow-hidden">
                    <!-- Overdue Badge -->
                    <div class="absolute top-8 right-8">
                        <span class="px-4 py-1.5 bg-red-50 text-autocheck-red text-[10px] font-black uppercase tracking-widest rounded-full border border-red-100 shadow-sm animate-pulse">
                            {{ $v['days_overdue'] }} Days Overdue
                        </span>
                    </div>

                    <div class="flex flex-col h-full">
                        <div class="flex items-center space-x-5 mb-8">
                            <div class="w-16 h-16 bg-red-50 rounded-[2rem] flex items-center justify-center text-autocheck-red group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-500 shadow-inner">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight leading-tight">{{ $v['make_model'] }}</h3>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mt-1">{{ $v['plate_number'] }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8 flex-1">
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Customer</span>
                                <span class="text-sm font-black text-gray-900">{{ $v['customer_name'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Email</span>
                                <span class="text-sm font-bold text-blue-600 underline">{{ $v['customer_email'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Phone</span>
                                <span class="text-sm font-black text-gray-900">{{ $v['customer_phone'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Due Since</span>
                                <span class="text-sm font-black text-autocheck-red italic">{{ $v['next_service_date'] }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-3">
                            <form action="{{ route('admin.notifications.send', $v['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-autocheck-red text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20 active:scale-[0.98] flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Send Email
                                </button>
                            </form>

                            @if($v['customer_phone'])
                            <form action="{{ route('admin.notifications.call', $v['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-slate-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-[0.98] flex items-center justify-center group">
                                    <svg class="w-4 h-4 mr-2 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    Call Now (AI)
                                </button>
                            </form>
                            @else
                            <button disabled class="w-full py-4 bg-gray-200 text-gray-400 text-xs font-black uppercase tracking-[0.2em] rounded-2xl cursor-not-allowed flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                No Phone Listed
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full py-24 text-center">
                <div class="w-24 h-24 bg-green-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-green-500 shadow-inner">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">Excellent! Your Fleet is on Track</h3>
                <p class="text-gray-500 font-medium">There are currently no vehicles requiring urgent maintenance attention.</p>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center mt-8 text-sm font-black text-autocheck-red hover:underline">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>
    </div>
</x-admin-layout>
