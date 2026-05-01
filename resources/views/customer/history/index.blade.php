<x-customer-layout>
    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-4 animate-fade-in">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-autocheck-red/5 rounded-full"></div>
            
            <div class="relative z-10 flex items-center space-x-4">
                <div class="w-10 h-10 bg-autocheck-red rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">Vehicle <span class="text-autocheck-red">History</span></h1>
                    <p class="text-[11px] text-gray-500 font-bold mt-0.5">Track every service and maintenance log.</p>
                </div>
            </div>
        </div>

        @forelse($vehicles as $vehicle)
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden" x-data="{ open: true }">
                <!-- Vehicle Header -->
                <div @click="open = !open" class="p-4 flex flex-col md:flex-row items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($vehicle->make, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-base font-black text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h2>
                            <p class="text-[9px] font-black text-autocheck-red uppercase tracking-widest">{{ $vehicle->plate_number }}</p>
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center space-x-6">
                        <div class="text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Services</p>
                            <p class="text-sm font-black text-gray-900">{{ $vehicle->serviceLogs->count() }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Total Cost</p>
                            <p class="text-sm font-black text-gray-900">₱{{ number_format($vehicle->serviceLogs->sum('cost'), 2) }}</p>
                        </div>
                        <div class="p-1.5 rounded-lg bg-gray-100 text-gray-400 group-hover:text-gray-600 transition-transform duration-300" :class="{ 'rotate-180': open }">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

        <!-- History Table & Mobile Cards -->
                <div x-show="open" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4">
                    <div class="px-4 md:px-6 pb-4">
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b border-gray-100">
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Type</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mechanic</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Points</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Cost</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Review</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($vehicle->serviceLogs as $log)
                                        <tr class="group hover:bg-gray-50/50 transition-colors">
                                            <td class="py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->service_date)->format('M d, Y') }}</span>
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ \Carbon\Carbon::parse($log->service_date)->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center space-x-2">
                                                    <span class="w-2 h-2 rounded-full bg-autocheck-red"></span>
                                                    <span class="text-sm font-bold text-gray-700">{{ $log->service_type }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-sm font-bold text-gray-600 italic">{{ $log->mechanic_name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-4 text-center">
                                                @if($log->points_earned > 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-600 uppercase tracking-widest">
                                                        +{{ $log->points_earned }} pts
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-300 font-bold">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-right">
                                                <span class="text-sm font-black text-gray-900">₱{{ number_format($log->cost, 2) }}</span>
                                            </td>
                                            <td class="py-4 text-right">
                                                @if($log->review)
                                                    <div class="flex flex-col items-end">
                                                        <div class="flex items-center space-x-0.5 mb-1">
                                                            @for($i=1; $i<=5; $i++)
                                                                <svg class="h-3 w-3 {{ $i <= $log->review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Review Sent</span>
                                                        @if($log->review->admin_reply)
                                                            <div class="mt-2 text-right">
                                                                <span class="inline-block px-3 py-1 bg-gray-900 text-white text-[8px] font-black uppercase tracking-widest rounded-lg italic">Admin Responded</span>
                                                                <p class="text-[10px] text-gray-500 font-bold italic mt-1 max-w-[150px] leading-tight">"{{ $log->review->admin_reply }}"</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <button @click="$dispatch('open-review-modal', { logId: {{ $log->id }}, serviceType: '{{ $log->service_type }}' })" 
                                                            class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-full hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20 uppercase tracking-widest">
                                                        Rate Service
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden space-y-4">
                            @forelse($vehicle->serviceLogs as $log)
                                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 w-1.5 h-full bg-autocheck-red rounded-r-full"></div>
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Date</p>
                                            <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->service_date)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cost</p>
                                            <p class="text-sm font-black text-autocheck-red">₱{{ number_format($log->cost, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Type</p>
                                            <p class="text-sm font-bold text-gray-700">{{ $log->service_type }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mechanic</p>
                                            <p class="text-sm font-bold text-gray-600 italic">{{ $log->mechanic_name ?? 'N/A' }}</p>
                                        </div>
                                        @if($log->points_earned > 0)
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-200 border-dashed">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Points Earned</p>
                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase tracking-widest">+{{ $log->points_earned }} pts</span>
                                        </div>
                                        @endif
                                        @if($log->review && $log->review->admin_reply)
                                            <div class="pt-3 border-t border-gray-200 border-dashed">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-[10px] font-black text-autocheck-red uppercase tracking-widest">Admin Response</p>
                                                    <span class="text-[8px] text-gray-400 font-bold italic">{{ $log->review->replied_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                                    <p class="text-[11px] font-bold text-gray-600 italic">"{{ $log->review->admin_reply }}"</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-sm font-bold text-gray-500">No service history found.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-16 shadow-xl border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">No Vehicles Registered</h3>
                <p class="mt-2 text-gray-500 max-w-sm">It looks like you don't have any vehicles in our system yet. Contact AutoCheck to register your fleet.</p>
            </div>
        @endforelse
    </div>

    <!-- Review Modal [Senior Product Architect Choice: Premium Modal] -->
    <div x-data="{ 
            isOpen: false, 
            logId: null, 
            serviceType: '', 
            rating: 5, 
            comment: '',
            submitting: false,
            success: false
         }"
         x-show="isOpen"
         @open-review-modal.window="isOpen = true; logId = $event.detail.logId; serviceType = $event.detail.serviceType; success = false; rating = 5; comment = ''"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 md:p-0"
         x-cloak>
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="isOpen = false"></div>

        <!-- Modal Card -->
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden transform transition-all"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="p-8">
                <div x-show="!success">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <button @click="isOpen = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Rate your <span class="text-blue-600" x-text="serviceType"></span></h3>
                    <p class="text-gray-500 font-bold mt-2 text-sm uppercase tracking-wide">How was your experience today?</p>

                    <div class="mt-8 flex justify-center space-x-2">
                        <template x-for="i in 5">
                            <button @click="rating = i" class="group focus:outline-none transition-transform hover:scale-110 active:scale-95">
                                <svg class="h-10 w-10 transition-colors" 
                                     :class="i <= rating ? 'text-yellow-400 fill-current' : 'text-gray-200'"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                    </div>

                    <div class="mt-8">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Share more details (Optional)</label>
                        <textarea x-model="comment" 
                                  placeholder="What did you like or what could be better?"
                                  class="w-full bg-gray-50 border-none rounded-3xl p-5 text-sm font-medium focus:ring-4 focus:ring-blue-100 transition-all min-h-[120px]"></textarea>
                    </div>

                    <div class="mt-8">
                        <button @click="submitReview" 
                                :disabled="submitting"
                                class="w-full bg-blue-600 text-white font-black py-4 rounded-full shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all disabled:opacity-50 flex items-center justify-center space-x-2">
                            <template x-if="!submitting"><span>Submit Review</span></template>
                            <template x-if="submitting">
                                <div class="flex items-center space-x-2">
                                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Sending...</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </div>

                <div x-show="success" class="text-center py-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-green-600 mx-auto mb-6">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Review Submitted!</h3>
                    <p class="text-gray-500 font-bold mt-2">Thank you for helping us improve our service.</p>
                    <button @click="isOpen = false; window.location.reload();" class="mt-8 text-blue-600 font-black text-sm uppercase tracking-widest hover:underline">Close</button>
                </div>
            </div>
        </div>

        <script>
            function submitReview() {
                this.submitting = true;
                
                fetch('{{ route('customer.reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        service_log_id: this.logId,
                        rating: this.rating,
                        comment: this.comment
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.submitting = false;
                    this.success = true;
                })
                .catch(err => {
                    console.error(err);
                    this.submitting = false;
                    alert('Something went wrong. Please try again.');
                });
            }
        </script>
    </div>
</x-customer-layout>
