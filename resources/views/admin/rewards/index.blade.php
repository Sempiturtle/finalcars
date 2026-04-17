<x-admin-layout>
    <div class="space-y-8" 
        x-data="{ 
            activeTab: 'catalog'
        }"
    >
        <!-- Header Section -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-50 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-autocheck-red/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-autocheck-red/5 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Loyalty <span class="text-autocheck-red">Command</span></h1>
                <p class="text-[13px] text-gray-500 font-bold mt-1">Design, manage, and track the AutoCheck rewards ecosystem.</p>
            </div>

            <div class="mt-6 md:mt-0 relative z-10 flex space-x-1.5 bg-gray-100 p-1.5 rounded-2xl border border-gray-200">
                <button 
                    @click="activeTab = 'catalog'"
                    :class="activeTab === 'catalog' ? 'bg-white text-gray-900 shadow-lg' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                >
                    Catalog
                </button>
                <button 
                    @click="activeTab = 'claims'"
                    :class="activeTab === 'claims' ? 'bg-white text-gray-900 shadow-lg' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                >
                    Claims History
                </button>
            </div>
        </div>

        <!-- Catalog Management -->
        <div x-show="activeTab === 'catalog'" x-transition class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-gray-900 flex items-center">
                    <span class="w-1.5 h-6 bg-autocheck-red rounded-full mr-3"></span>
                    Rewards Catalog
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rewards as $reward)
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 group relative">
                        <!-- Points Badge -->
                        <div class="absolute top-5 right-5">
                            <span class="px-3 py-1 bg-gray-900 text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                {{ $reward->points_cost }} PTS
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-autocheck-red group-hover:bg-autocheck-red group-hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c.533.483.874 1.136.951 1.836l.117 1.17a1 1 0 01-1 1H3a1 1 0 01-1-1l.117-1.17c.077-.7.418-1.353.951-1.836a9.481 9.481 0 012.339-1.555c1.458-.68 3.033-1.035 4.632-1.035 1.6 0 3.175.355 4.633 1.035a9.481 9.481 0 012.339 1.555zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                            </div>
                        </div>

                        <h3 class="text-lg font-black text-gray-900 mb-1.5">{{ $reward->name }}</h3>
                        <p class="text-gray-500 text-xs font-medium leading-relaxed line-clamp-2">{{ $reward->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Claims History -->
        <div x-show="activeTab === 'claims'" x-transition class="space-y-6">
            <h2 class="text-xl font-black text-gray-900 flex items-center">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3"></span>
                User Claim Monitoring
            </h2>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] italic border-b border-gray-50">
                            <th class="px-6 py-4">Redeemer</th>
                            <th class="px-6 py-4">Reward Name</th>
                            <th class="px-6 py-4 text-center">Cost (Pts)</th>
                            <th class="px-6 py-4 text-right">Date Claimed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($claims as $claim)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-[13px] font-black text-gray-900 tracking-tight">{{ $claim->user_name }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 lowercase">{{ $claim->user_email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-7 h-7 rounded bg-red-50 flex items-center justify-center text-autocheck-red mr-2.5">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                        </div>
                                        <span class="text-[13px] font-bold text-gray-700">{{ $claim->reward_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-gray-100 rounded-full text-[9px] font-black tracking-widest text-gray-600">
                                        {{ $claim->points_cost }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-[12px] text-gray-500 italic">
                                    {{ \Carbon\Carbon::parse($claim->claimed_at)->format('M d, Y - h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modals Removed since rewards are auto-managed -->
    </div>
</x-admin-layout>
