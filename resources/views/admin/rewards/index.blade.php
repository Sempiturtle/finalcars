<x-admin-layout>
    <div class="space-y-8" 
        x-data="{ 
            activeTab: 'catalog',
            showAddModal: false, 
            showEditModal: false,
            editReward: { id: null, name: '', description: '', points_cost: 0 },
            updateScrollLock() {
                const main = document.querySelector('main');
                if (this.showAddModal || this.showEditModal) {
                    main.style.overflow = 'hidden';
                } else {
                    main.style.overflow = 'auto';
                }
            }
        }"
        x-init="
            $watch('showAddModal', () => updateScrollLock());
            $watch('showEditModal', () => updateScrollLock());
        "
    >
        <!-- Header Section -->
        <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-gray-50 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-autocheck-red/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-64 h-64 bg-autocheck-red/5 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Loyalty <span class="text-autocheck-red">Command</span></h1>
                <p class="text-gray-500 font-bold mt-2">Design, manage, and track the AutoCheck rewards ecosystem.</p>
            </div>

            <div class="mt-8 md:mt-0 relative z-10 flex space-x-2 bg-gray-100 p-2 rounded-3xl border border-gray-200">
                <button 
                    @click="activeTab = 'catalog'"
                    :class="activeTab === 'catalog' ? 'bg-white text-gray-900 shadow-lg' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all"
                >
                    Catalog
                </button>
                <button 
                    @click="activeTab = 'claims'"
                    :class="activeTab === 'claims' ? 'bg-white text-gray-900 shadow-lg' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all"
                >
                    Claims History
                </button>
            </div>
        </div>

        <!-- Catalog Management -->
        <div x-show="activeTab === 'catalog'" x-transition class="space-y-8">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-gray-900 flex items-center">
                    <span class="w-2 h-8 bg-autocheck-red rounded-full mr-4"></span>
                    Rewards Catalog
                </h2>
                <button @click="showAddModal = true" class="px-8 py-4 bg-autocheck-red text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create Reward</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rewards as $reward)
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative">
                        <!-- Points Badge -->
                        <div class="absolute top-8 right-8">
                            <span class="px-4 py-1.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                {{ $reward->points_cost }} PTS
                            </span>
                        </div>

                        <div class="mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-autocheck-red group-hover:bg-autocheck-red group-hover:text-white transition-colors">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c.533.483.874 1.136.951 1.836l.117 1.17a1 1 0 01-1 1H3a1 1 0 01-1-1l.117-1.17c.077-.7.418-1.353.951-1.836a9.481 9.481 0 012.339-1.555c1.458-.68 3.033-1.035 4.632-1.035 1.6 0 3.175.355 4.633 1.035a9.481 9.481 0 012.339 1.555zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                            </div>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 mb-2">{{ $reward->name }}</h3>
                        <p class="text-gray-500 text-sm font-medium mb-8 leading-relaxed line-clamp-2">{{ $reward->description }}</p>

                        <div class="flex items-center space-x-3 mt-auto">
                            <button 
                                @click="
                                    editReward = { 
                                        id: {{ $reward->id }}, 
                                        name: '{{ addslashes($reward->name) }}', 
                                        description: '{{ addslashes($reward->description) }}', 
                                        points_cost: {{ $reward->points_cost }} 
                                    }; 
                                    showEditModal = true;
                                "
                                class="flex-1 py-4 bg-gray-50 text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-900 hover:text-white transition-all"
                            >
                                Edit Info
                            </button>
                            
                            <form action="{{ route('admin.rewards.toggle', $reward) }}" method="POST" class="w-1/4">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-4 text-center rounded-2xl border-2 transition-all {{ $reward->is_active ? 'border-green-100 bg-green-50 text-green-600' : 'border-gray-100 bg-gray-50 text-gray-400' }}" title="{{ $reward->is_active ? 'Deactivate' : 'Activate' }}">
                                    <svg class="h-4 w-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>

                            <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" class="w-1/4" onsubmit="return confirm('Delete this reward permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-white border-2 border-red-50 text-red-400 rounded-2xl hover:bg-red-50 transition-all">
                                    <svg class="h-4 w-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Claims History -->
        <div x-show="activeTab === 'claims'" x-transition class="space-y-8">
            <h2 class="text-2xl font-black text-gray-900 flex items-center">
                <span class="w-2 h-8 bg-blue-600 rounded-full mr-4"></span>
                User Claim Monitoring
            </h2>

            <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] italic border-b border-gray-50">
                            <th class="px-8 py-8">Redeemer</th>
                            <th class="px-8 py-8">Reward Name</th>
                            <th class="px-8 py-8 text-center">Cost (Pts)</th>
                            <th class="px-8 py-8 text-right">Date Claimed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($claims as $claim)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <div>
                                        <p class="text-sm font-black text-gray-900 tracking-tight">{{ $claim->user_name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 lowercase">{{ $claim->user_email }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-autocheck-red mr-3">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">{{ $claim->reward_name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black tracking-widest text-gray-600">
                                        {{ $claim->points_cost }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-sm text-gray-500 italic">
                                    {{ \Carbon\Carbon::parse($claim->claimed_at)->format('M d, Y - h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Reward Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showAddModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showAddModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showAddModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-10">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-black text-gray-900">New <span class="text-autocheck-red">Reward</span></h2>
                            <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <form action="{{ route('admin.rewards.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reward Name</label>
                                <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="e.g. 15% OFF Oil Change">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Points Required</label>
                                <input type="number" name="points_cost" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Detailed Description</label>
                                <textarea name="description" rows="3" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none" placeholder="Provide details about this promo..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-5 bg-autocheck-red text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/10">Publish to Customers</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Reward Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showEditModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showEditModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showEditModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-10">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-black text-gray-900">Modify <span class="text-autocheck-red">Reward</span></h2>
                            <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <form :action="'/admin/rewards/' + editReward.id" method="POST" class="space-y-6">
                            @csrf @method('PUT')
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reward Name</label>
                                <input type="text" name="name" x-model="editReward.name" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Points Required</label>
                                <input type="number" name="points_cost" x-model="editReward.points_cost" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Detailed Description</label>
                                <textarea name="description" x-model="editReward.description" rows="3" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full py-5 bg-autocheck-red text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/10">Finalize Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
