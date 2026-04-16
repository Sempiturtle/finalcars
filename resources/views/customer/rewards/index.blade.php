<x-customer-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <div class="space-y-8 animate-fade-in">
        <!-- Dashboard Header -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-autocheck-red/5 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-32 h-32 bg-autocheck-red/5 rounded-full"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 text-center md:text-left">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-autocheck-red rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-red-500/30">
                    <svg class="h-8 w-8 md:h-10 md:w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Loyalty <span class="text-autocheck-red">Rewards</span></h1>
                    <p class="text-gray-500 font-bold mt-1 text-sm md:text-base">Redeem your points for exclusive promos.</p>
                </div>
            </div>

            <div class="mt-8 md:mt-0 relative z-10 bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center md:text-right min-w-full md:min-w-[200px]">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Available Points</p>
                <div class="text-3xl md:text-4xl font-black text-autocheck-red tabular-nums">{{ number_format($user->availablePoints()) }}</div>
                <p class="text-[10px] font-bold text-gray-500 mt-1">Total Earned: {{ number_format($user->loyalty_points) }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-6 bg-green-50 rounded-3xl border border-green-100 flex items-center space-x-4 animate-bounce-subtle shadow-sm">
                <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-green-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-500/20">
                    <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-green-800 text-sm md:text-base font-bold tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="p-6 bg-red-50 rounded-3xl border border-red-100 flex items-center space-x-4 animate-shake shadow-sm">
                <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-autocheck-red rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                    <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-red-800 text-sm md:text-base font-bold tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
            x-data="{ 
                showReceipt: false, 
                currentReceipt: { name: '', date: '', code: '', cost: 0 },
                updateScrollLock() {
                    const main = document.querySelector('main');
                    if (this.showReceipt) {
                        main.style.overflow = 'hidden';
                    } else {
                        main.style.overflow = 'auto';
                    }
                },
                downloadPDF() {
                    const element = document.getElementById('receipt-content');
                    const opt = {
                        margin:       1,
                        filename:     `AutoCheck_Receipt_${this.currentReceipt.code}.pdf`,
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true },
                        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };
                    html2pdf().set(opt).from(element).save();
                }
            }"
            x-init="
                $watch('showReceipt', () => updateScrollLock());
            "
        >
            <!-- Available Rewards -->
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-xl font-black text-gray-900 flex items-center mb-4">
                    <span class="w-1.5 h-6 bg-autocheck-red rounded-full mr-3"></span>
                    Explore Promos
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($rewards as $reward)
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-autocheck-red group-hover:bg-autocheck-red group-hover:text-white transition-colors duration-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <span class="bg-autocheck-red/10 text-autocheck-red text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                                    {{ $reward->points_cost }} Pts
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-black text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $reward->name }}</h3>
                            <p class="text-gray-500 text-sm font-medium mt-2 flex-grow line-clamp-2">{{ $reward->description }}</p>
                            
                            <div class="mt-6">
                                <form action="{{ route('customer.rewards.claim', $reward) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-lg
                                                   {{ $user->availablePoints() >= $reward->points_cost 
                                                      ? 'bg-autocheck-red text-white hover:bg-red-700 shadow-red-500/20 active:scale-95' 
                                                      : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                                             {{ $user->availablePoints() < $reward->points_cost ? 'disabled' : '' }}>
                                        {{ $user->availablePoints() >= $reward->points_cost ? 'Claim Reward Now' : 'Insufficient Points' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Claimed History -->
            <div class="space-y-6">
                <h2 class="text-xl font-black text-gray-900 flex items-center mb-4">
                    <span class="w-1.5 h-6 bg-autocheck-red rounded-full mr-3"></span>
                    My Active Promos
                </h2>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl min-h-[400px]">
                    @forelse($claimedRewards as $claimed)
                        @php $code = strtoupper(substr(md5($claimed->id . $user->id . $claimed->pivot->claimed_at), 0, 10)); @endphp
                        <div class="relative p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 mb-4 group hover:border-autocheck-red transition-all cursor-pointer overflow-hidden"
                             @click="currentReceipt = { name: '{{ addslashes($claimed->name) }}', date: '{{ \Carbon\Carbon::parse($claimed->pivot->claimed_at)->format('M d, Y - h:i A') }}', code: '{{ $code }}', cost: '{{ $claimed->points_cost }}' }; showReceipt = true;">
                            <div class="absolute -top-4 -right-4 w-12 h-12 bg-autocheck-red flex items-center justify-center text-white transform rotate-12 shadow-md">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="font-black text-sm text-gray-900 pr-4">{{ $claimed->name }}</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Claimed on {{ \Carbon\Carbon::parse($claimed->pivot->claimed_at)->format('M d, Y') }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200 border-dashed flex items-center justify-between">
                                <span class="text-[10px] font-black text-autocheck-red uppercase tracking-widest">View Receipt</span>
                                <div class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-[10px] font-mono font-bold tracking-widest">
                                    #{{ substr($code, 0, 6) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-64 text-center opacity-40">
                            <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-sm font-bold text-gray-500">No claimed promos yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Receipt Modal -->
            <div x-show="showReceipt" class="fixed inset-0 z-[70] flex items-center justify-center p-4" x-cloak>
                <div @click="showReceipt = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-all" x-show="showReceipt" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
                
                <div class="bg-white w-full max-w-md rounded-[3rem] shadow-2xl relative z-10 overflow-hidden transform transition-all"
                     x-show="showReceipt"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="scale-95 opacity-0 translate-y-8"
                     x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                >
                    <div id="receipt-content" class="p-10 bg-white">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gray-900 text-white rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900">AutoCheck <span class="text-autocheck-red">Rewards</span></h2>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Official Digital Receipt</p>
                        </div>

                        <div class="border-y-2 border-dashed border-gray-100 py-8 space-y-6">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-gray-400 uppercase tracking-widest">Transaction ID</span>
                                <span class="font-mono font-black text-gray-900" x-text="'#' + currentReceipt.code"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-gray-400 uppercase tracking-widest">Customer</span>
                                <span class="font-black text-gray-900">{{ $user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-gray-400 uppercase tracking-widest">Date</span>
                                <span class="font-black text-gray-900" x-text="currentReceipt.date"></span>
                            </div>
                            
                            <div class="p-6 bg-red-50 rounded-3xl border border-red-100 mt-2">
                                <p class="text-[10px] font-black text-autocheck-red uppercase tracking-widest mb-1">Redeemed Item</p>
                                <h3 class="text-lg font-black text-gray-900 leading-tight" x-text="currentReceipt.name"></h3>
                                <div class="mt-4 flex items-center justify-between pt-4 border-t border-red-200/50">
                                    <span class="text-[10px] font-bold text-red-400 uppercase tracking-widest text-[8px]">Points Deducted</span>
                                    <span class="text-xl font-black text-autocheck-red" x-text="'-' + currentReceipt.cost"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <div class="mb-4 flex justify-center space-x-1 opacity-20">
                                @for($i=0; $i<15; $i++)
                                    <div class="w-1.5 h-1.5 bg-gray-900 rounded-full"></div>
                                @endfor
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">VALID UNTIL REDEEMED</p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 flex space-x-3">
                        <button @click="showReceipt = false" class="flex-1 py-4 bg-white border border-gray-200 text-gray-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all">Close</button>
                        <button @click="downloadPDF()" class="flex-1 py-4 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-800 transition-all shadow-xl flex items-center justify-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Print-only Styles -->
            <style>
                @media print {
                    body * { visibility: hidden; }
                    #receipt-content, #receipt-content * { visibility: visible; }
                    #receipt-content {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        padding: 40px;
                        background: white !important;
                        border: none !important;
                    }
                    .fixed { display: none !important; }
                }
            </style>
        </div>
    </div>
</x-customer-layout>
