<x-customer-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <div class="max-w-7xl mx-auto space-y-6 pb-20 animate-fade-in"
        x-data="{ 
            showReceipt: false, 
            currentReceipt: { name: '', date: '', code: '', cost: 0 },
            updateScrollLock() {
                const main = document.querySelector('main');
                if (this.showReceipt) main.style.overflow = 'hidden';
                else main.style.overflow = 'auto';
            },
            downloadPDF() {
                const element = document.getElementById('receipt-content');
                const opt = {
                    margin: 1,
                    filename: `AutoCheck_Receipt_${this.currentReceipt.code}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            }
        }"
        x-init="$watch('showReceipt', () => updateScrollLock());"
    >
        <!-- Compact Rewards Header -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="relative z-10 flex items-center space-x-4">
                <div class="w-12 h-12 bg-autocheck-red rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">Loyalty <span class="text-autocheck-red">Rewards</span></h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 italic">Redeem prestige promos.</p>
                </div>
            </div>

            <div class="mt-6 md:mt-0 flex items-center space-x-8">
                <div class="text-right">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Available Balance</p>
                    <div class="text-2xl font-black text-autocheck-red tabular-nums">{{ number_format($user->availablePoints()) }} <span class="text-[10px] uppercase">Pts</span></div>
                </div>
                <div class="w-px h-8 bg-gray-100 hidden md:block"></div>
                <div class="text-right hidden sm:block">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Life Earnings</p>
                    <p class="text-sm font-black text-gray-700 tabular-nums">{{ number_format($user->loyalty_points) }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm flex items-center animate-fade-in">
                <svg class="h-4 w-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-[10px] font-black text-gray-900 tracking-tight italic uppercase">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Available Rewards -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] italic flex items-center">
                        <span class="w-1 h-3 bg-autocheck-red rounded-full mr-2"></span>
                        Available Promos
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($rewards as $reward)
                        @php
                            $availableForThis = $rewardPoints[$reward->id] ?? 0;
                            $canClaim = $availableForThis >= $reward->points_cost;
                        @endphp
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm transition-all duration-300 flex flex-col group {{ !$canClaim ? 'opacity-70 grayscale' : 'hover:shadow-md' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 {{ $canClaim ? 'bg-gray-50 text-autocheck-red' : 'bg-gray-100 text-gray-400' }} rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5l7 7-7 7-7-7z"></path></svg>
                                </div>
                                <div class="text-right">
                                    <span class="{{ $canClaim ? 'bg-autocheck-red/10 text-autocheck-red' : 'bg-gray-100 text-gray-400' }} text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">
                                        {{ $reward->points_cost }} Pts
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight group-hover:text-autocheck-red transition-colors">{{ $reward->name }}</h3>
                            <p class="text-[10px] text-gray-400 font-bold mt-1 italic flex-grow">
                                @if($reward->serviceType)
                                    Specific to {{ $reward->serviceType->name }} usage.
                                @else
                                    Redeemable via total points.
                                @endif
                            </p>
                            
                            <div class="mt-4">
                                <form action="{{ route('customer.rewards.claim', $reward) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all {{ $canClaim ? 'bg-gray-900 text-white hover:bg-autocheck-red' : 'bg-gray-50 text-gray-300 cursor-not-allowed' }}" {{ !$canClaim ? 'disabled' : '' }}>
                                        {{ $canClaim ? 'Claim Now' : 'Insufficient Points' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Claimed History -->
            <div class="space-y-6">
                <div class="space-y-4">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] italic flex items-center">
                        <span class="w-1 h-3 bg-autocheck-red rounded-full mr-2"></span>
                        Active Code Vault
                    </h2>

                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm min-h-[200px]">
                        @forelse($claimedRewards as $claimed)
                            @php $code = strtoupper(substr(md5($claimed->id . $user->id . $claimed->pivot->claimed_at), 0, 10)); @endphp
                            <div class="p-3 bg-gray-50/50 rounded-lg border border-dashed border-gray-200 mb-2 group hover:border-autocheck-red transition-all cursor-pointer"
                                 @click="currentReceipt = { name: '{{ addslashes($claimed->name) }}', date: '{{ \Carbon\Carbon::parse($claimed->pivot->claimed_at)->format('M d, Y') }}', code: '{{ $code }}', cost: '{{ $claimed->points_cost }}' }; showReceipt = true;">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-black text-[11px] text-gray-900 truncate pr-2 uppercase">{{ $claimed->name }}</h4>
                                    <div class="px-2 py-0.5 bg-white border border-gray-100 rounded text-[8px] font-mono font-bold tracking-widest">#{{ substr($code, 0, 6) }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-32 opacity-30 italic text-[10px] font-bold">No active promos.</div>
                        @endforelse
                    </div>
                </div>

                <!-- How I Earned Points Breakdown -->
                <div class="space-y-4" x-data="{ isDismissed: false }" x-show="!isDismissed">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] italic flex items-center">
                            <span class="w-1 h-3 bg-autocheck-red rounded-full mr-2"></span>
                            Point Stream
                        </h2>
                        <button @click="isDismissed = true" class="text-[8px] font-black text-gray-300 uppercase hover:text-autocheck-red transition-colors italic">Dismiss</button>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        @if($pointsBreakdown->count() > 0)
                            <div class="divide-y divide-gray-50">
                                    @foreach($pointsBreakdown as $log)
                                    <div class="flex flex-col px-4 py-3 hover:bg-gray-50/50 transition-colors border-b last:border-0 border-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="min-w-0">
                                                <p class="text-[10px] font-black text-gray-900 truncate uppercase">{{ $log['service_type'] }}</p>
                                                <p class="text-[8px] font-bold text-gray-400 mt-0.5 italic">{{ $log['service_date'] }}</p>
                                            </div>
                                            <span class="flex-shrink-0 ml-3 text-[9px] font-black text-green-600">+{{ $log['points_earned'] }}</span>
                                        </div>
                                        @if(!empty($log['notes']))
                                            <div class="mt-2 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                                <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest mb-1 italic">Service Audit Trail</p>
                                                <p class="text-[9px] text-gray-700 font-medium leading-relaxed italic">"{{ $log['notes'] }}"</p>
                                                <div class="mt-1.5 flex items-center text-[7px] font-black text-gray-400 uppercase tracking-tighter">
                                                    <svg class="w-2.5 h-2.5 mr-1 text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4"></path></svg>
                                                    Verified by: {{ $log['completed_by'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                            </div>
                        @else
                            <div class="py-10 text-center opacity-30 italic text-[10px] font-bold">No data.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div x-show="showReceipt" class="fixed inset-0 z-[70] flex items-center justify-center p-4" x-cloak>
            <div @click="showReceipt = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-xl transition-all" x-show="showReceipt" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative z-10 overflow-hidden transform transition-all"
                 x-show="showReceipt"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                 x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            >
                <div id="receipt-content" class="p-8 bg-white text-center">
                    <div class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                    </div>
                    <h2 class="text-sm font-black text-gray-900 uppercase">AutoCheck <span class="text-autocheck-red">Rewards</span></h2>
                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Digital Voucher Token</p>

                    <div class="border-y-2 border-dashed border-gray-100 my-6 py-6 space-y-4">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="font-bold text-gray-400 uppercase">ID Token</span>
                            <span class="font-mono font-black text-gray-900" x-text="'#' + currentReceipt.code"></span>
                        </div>
                        <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                            <h3 class="text-xs font-black text-gray-900 uppercase leading-tight" x-text="currentReceipt.name"></h3>
                            <div class="mt-3 text-xl font-black text-autocheck-red" x-text="'-' + currentReceipt.cost + ' Pts'"></div>
                        </div>
                    </div>
                    <p class="text-[8px] font-bold text-gray-300 uppercase tracking-[0.3em]">VALID UNTIL REDEEMED</p>
                </div>

                <div class="p-4 bg-gray-50 flex space-x-2">
                    <button @click="showReceipt = false" class="flex-1 py-3 bg-white border border-gray-200 text-gray-400 font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">Close</button>
                    <button @click="downloadPDF()" class="flex-1 py-3 bg-gray-900 text-white font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-autocheck-red transition-all shadow-xl flex items-center justify-center">
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>
