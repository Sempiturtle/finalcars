<x-admin-layout>
    <div class="space-y-8" x-data="{ showAdjustModal: false, selectedUser: null }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Pointing <span class="text-autocheck-red">System</span></h1>
                <p class="text-gray-500 font-medium mt-1">Track customer loyalty, service visits, and engagement levels.</p>
            </div>
            <div class="flex items-center space-x-3">
                <form action="{{ route('admin.points.sync-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-gray-200 text-sm font-bold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Sync All Points
                    </button>
                </form>
            </div>
        </div>

        <!-- Metrics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Customers</p>
                <p class="text-2xl font-black text-gray-900">{{ count($customers) }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active (90d)</p>
                <p class="text-2xl font-black text-green-600">{{ $customers->where('status', 'Active')->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Loyalty Points</p>
                <p class="text-2xl font-black text-autocheck-red">{{ number_format($customers->sum('points')) }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Top Performer</p>
                <p class="text-lg font-black text-gray-900 truncate">{{ $customers->first()->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Customer List -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Rank</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Customer</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Activity Level</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Points</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                        @foreach($customers as $index => $customer)
                        <tbody x-data="{ showBreakdown: false }">
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black {{ $index < 3 ? 'text-autocheck-red' : 'text-gray-400' }}">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-xs font-black text-gray-400 uppercase">
                                            {{ substr($customer['name'], 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900">{{ $customer['name'] }}</p>
                                            <p class="text-[10px] font-bold text-gray-400">{{ $customer['vehicle_count'] }} Vehicles Registered</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ 
                                        match($customer['status']) {
                                            'Active' => 'bg-green-50 text-green-600',
                                            'Regular' => 'bg-blue-50 text-blue-600',
                                            default => 'bg-gray-100 text-gray-400',
                                        }
                                    }}">
                                        {{ $customer['status'] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-4 w-4 text-autocheck-red" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-sm font-black text-gray-900">{{ number_format($customer['points']) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        {{-- Points Breakdown toggle --}}
                                        <button @click="showBreakdown = !showBreakdown"
                                            class="p-2.5 bg-gray-50 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all"
                                            title="View Points Breakdown">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        </button>
                                        {{-- Adjust Points --}}
                                        <button
                                            @click="selectedUser = {{ json_encode($customer) }}; showAdjustModal = true"
                                            class="p-2.5 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-xl transition-all"
                                            title="Adjust Points">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Points Breakdown Row --}}
                            <tr x-show="showBreakdown" style="display: none;">
                                <td colspan="5" class="px-8 pb-6 bg-gray-50/30">
                                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Points Breakdown — {{ $customer['name'] }}</p>
                                        @if(count($customer['service_logs']) > 0)
                                            <div class="space-y-2">
                                                @foreach($customer['service_logs'] as $log)
                                                <div class="flex items-center justify-between bg-gray-50 rounded-2xl px-5 py-3 border border-gray-100">
                                                    <div>
                                                        <p class="text-sm font-black text-gray-900">{{ $log['service_type'] }}</p>
                                                        <p class="text-[10px] font-bold text-gray-400">{{ $log['service_date'] }} &mdash; ₱{{ number_format($log['cost'], 2) }}</p>
                                                    </div>
                                                    <div class="flex items-center space-x-4">
                                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase tracking-widest">+{{ $log['points_earned'] }} pts</span>
                                                        <form action="{{ route('admin.service-history.destroy', $log['id']) }}" method="POST" onsubmit="return confirm('Delete this point record? This will also remove the service log and recalculate the customer points.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-600 transition-colors">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-4 flex justify-end items-center border-t border-gray-100 pt-4">
                                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-3">Total Earned</span>
                                                <span class="text-xl font-black text-autocheck-red">{{ number_format($customer['points']) }} pts</span>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 font-bold text-center py-4">No completed services found for this customer.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                </table>
            </div>
        </div>

        <!-- Adjust Points Modal -->
        <div 
            x-show="showAdjustModal" 
            class="fixed inset-0 z-[60] overflow-y-auto" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showAdjustModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
                
                <div 
                    class="relative bg-white w-full max-w-md rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden"
                    x-show="showAdjustModal"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-95 translate-y-4"
                    x-transition:enter-end="scale-100 translate-y-0"
                >
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Adjust Points</h2>
                                <p class="text-gray-500 font-medium text-sm mt-1" x-text="'Managing points for ' + selectedUser?.name"></p>
                            </div>
                            <button @click="showAdjustModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form :action="'{{ route('admin.points.adjust', '') }}/' + selectedUser?.id" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Action</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label class="relative">
                                            <input type="radio" name="action" value="add" checked class="peer sr-only">
                                            <div class="px-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-xs font-bold text-center cursor-pointer peer-checked:bg-green-50 peer-checked:text-green-600 peer-checked:border-green-200 transition-all">Add</div>
                                        </label>
                                        <label class="relative">
                                            <input type="radio" name="action" value="subtract" class="peer sr-only">
                                            <div class="px-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-xs font-bold text-center cursor-pointer peer-checked:bg-red-50 peer-checked:text-autocheck-red peer-checked:border-red-200 transition-all">Subtract</div>
                                        </label>
                                        <label class="relative">
                                            <input type="radio" name="action" value="set" class="peer sr-only">
                                            <div class="px-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-xs font-bold text-center cursor-pointer peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-200 transition-all">Set Exact</div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Point Amount</label>
                                    <input type="number" name="points" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all" placeholder="Enter amount...">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-autocheck-red text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 active:scale-95 transform mt-4">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
