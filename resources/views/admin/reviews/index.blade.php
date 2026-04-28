<x-admin-layout>
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic">Customer <span class="text-autocheck-red">Feedback</span></h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manage and respond to service experience reports.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm flex items-center animate-fade-in">
                <svg class="h-4 w-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-[10px] font-black text-gray-900 tracking-tight italic uppercase">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 gap-6">
            @forelse($reviews as $review)
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row gap-8">
                            <!-- Left Side: User & Vehicle Info -->
                            <div class="w-full lg:w-1/3 space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-autocheck-red font-black text-xl border border-gray-100">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $review->user->name }}</h4>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic">{{ $review->created_at->format('M d, Y • h:i A') }}</p>
                                    </div>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest italic">Asset Verified</span>
                                        <span class="text-[10px] font-black text-autocheck-red uppercase">{{ $review->vehicle->plate_number }}</span>
                                    </div>
                                    <p class="text-xs font-black text-gray-900 uppercase">{{ $review->vehicle->make }} {{ $review->vehicle->model }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold italic mt-1">{{ $review->serviceLog->service_type ?? 'General Service' }}</p>
                                </div>

                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-[10px] font-black text-gray-400 uppercase italic">{{ $review->rating }}/5 Score</span>
                                </div>
                            </div>

                            <!-- Right Side: Content & Actions -->
                            <div class="flex-1 space-y-6">
                                <div>
                                    <span class="text-[8px] font-black text-autocheck-red uppercase tracking-[0.3em] mb-2 block italic">Transmission Received</span>
                                    <p class="text-sm font-bold text-gray-700 leading-relaxed italic">"{{ $review->comment }}"</p>
                                </div>

                                @if($review->admin_reply)
                                    <div class="p-6 bg-gray-900 rounded-3xl relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-autocheck-red/10 rounded-full blur-3xl"></div>
                                        <div class="relative z-10">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-[8px] font-black text-autocheck-red uppercase tracking-[0.2em] italic">Official Response</span>
                                                <span class="text-[8px] text-gray-500 font-bold uppercase italic">{{ $review->replied_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-300 font-medium leading-relaxed italic">"{{ $review->admin_reply }}"</p>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('admin.reviews.reply', $review) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="relative">
                                            <textarea name="admin_reply" rows="3" placeholder="Draft your response to build customer loyalty..." class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/5 focus:border-autocheck-red transition-all resize-none italic"></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-autocheck-red transition-all shadow-lg italic">Post Official Response</button>
                                        </div>
                                    </form>
                                @endif
                            </div>

                            <!-- Visibility Controls -->
                            <div class="flex flex-row lg:flex-col justify-between items-center lg:items-stretch border-t lg:border-t-0 lg:border-l border-gray-100 pt-6 lg:pt-0 lg:pl-6 gap-4">
                                <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 {{ $review->is_public ? 'text-green-500 bg-green-50' : 'text-gray-400 bg-gray-50' }} rounded-2xl transition-all hover:scale-110 shadow-sm border border-transparent hover:border-gray-100" title="Toggle Public Visibility">
                                        @if($review->is_public)
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @else
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                        @endif
                                    </button>
                                </form>
                                <span class="text-[9px] font-black {{ $review->is_public ? 'text-green-500' : 'text-gray-400' }} uppercase tracking-widest text-center italic bg-gray-50 px-3 py-1 rounded-full">{{ $review->is_public ? 'Live' : 'Hidden' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-12 border border-gray-100 shadow-sm text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">No transmissions detected</h3>
                    <p class="text-[10px] text-gray-400 font-bold italic mt-2">Customer feedback will appear here once service logs are verified and reviewed.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    </style>
</x-admin-layout>
