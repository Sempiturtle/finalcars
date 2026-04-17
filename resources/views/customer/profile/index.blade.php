<x-customer-layout>
    <div class="max-w-6xl mx-auto space-y-6 pb-20" x-data="{ activeTab: 'info' }">
        <!-- Compact Header Section -->
        <div class="relative min-h-[140px] rounded-2xl overflow-hidden shadow-lg bg-gray-900 group">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-0 -left-20 w-80 h-80 bg-autocheck-red rounded-full mix-blend-screen filter blur-[60px] animate-pulse"></div>
                <div class="absolute bottom-0 right-0 w-60 h-60 bg-red-600 rounded-full mix-blend-screen filter blur-[80px] animate-pulse" style="animation-delay: 2s"></div>
            </div>

            <div class="relative z-10 p-6 flex flex-col md:flex-row md:items-center justify-between h-full gap-6">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tighter uppercase mb-1">
                        Account <span class="text-autocheck-red">Settings</span>
                    </h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>

                <div class="flex items-center space-x-4 bg-white/5 backdrop-blur-xl p-4 rounded-xl border border-white/10 shadow-xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-autocheck-red to-red-800 rounded-lg flex items-center justify-center text-white text-xl font-black shadow-lg shadow-red-600/20">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-0.5 italic">Total Points</p>
                        <p class="text-xl font-black text-white tracking-tight">{{ number_format($user->loyalty_points) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Compact Navigation -->
            <div class="lg:col-span-3">
                <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm lg:sticky lg:top-28 space-y-1">
                    <nav class="space-y-1">
                        @foreach([
                            ['id' => 'info', 'label' => 'Personal Info', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            ['id' => 'vehicles', 'label' => 'Garage Fleet', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                            ['id' => 'security', 'label' => 'Security', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z']
                        ] as $tab)
                        <button @click="activeTab = '{{ $tab['id'] }}'"
                            :class="activeTab === '{{ $tab['id'] }}' ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900'"
                            class="w-full flex items-center px-4 py-3 rounded-lg text-[11px] font-black transition-all duration-300 group">
                            <div class="w-7 h-7 rounded-md bg-gray-50 flex items-center justify-center mr-3 group-hover:bg-white transition-colors"
                                :class="activeTab === '{{ $tab['id'] }}' ? 'bg-white/10' : ''">
                                <svg class="h-4 w-4" :class="activeTab === '{{ $tab['id'] }}' ? 'text-autocheck-red' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"></path>
                                </svg>
                            </div>
                            {{ $tab['label'] }}
                        </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Compact Content Area -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Status Messages -->
                @if (session('status'))
                    <div class="bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm flex items-center animate-fade-in">
                        <svg class="h-4 w-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <p class="text-xs font-black text-gray-900 tracking-tight italic">{{ session('message') }}</p>
                    </div>
                @endif

                <!-- Profile Info Section -->
                <div x-show="activeTab === 'info'" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Profile <span class="text-autocheck-red">Information</span></h3>
                            <p class="text-[10px] text-gray-400 font-bold italic mt-1">Manage your identity and contact preferences.</p>
                        </div>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div class="space-y-1.5 min-w-0">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 italic">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>

                        <div class="space-y-1.5 min-w-0">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 italic">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>

                        <div class="space-y-1.5 min-w-0">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 italic">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all">
                        </div>

                        <div class="space-y-1.5 md:col-span-2 min-w-0">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 italic">Physical Address</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/10 focus:border-autocheck-red transition-all resize-none">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" class="px-8 py-3 bg-gray-900 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-autocheck-red transition-all italic shadow-lg">Save Changes</button>
                        </div>
                    </form>
                </div>

                <!-- Garage Fleet Section -->
                <div x-show="activeTab === 'vehicles'" class="space-y-6 animate-fade-in">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Active <span class="text-autocheck-red">Fleet</span></h3>
                            <span class="text-[9px] font-black bg-gray-50 px-3 py-1 rounded-full text-gray-400 border border-gray-100 uppercase italic">{{ $vehicles->count() }} Assets</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($vehicles as $vehicle)
                                <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 group transition-all hover:bg-white hover:shadow-lg">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-autocheck-red font-black text-sm shadow-sm border border-gray-50">{{ substr($vehicle->make, 0, 1) }}</div>
                                        <div>
                                            <h4 class="text-sm font-black text-gray-900 tracking-tight">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                            <p class="text-[8px] font-black text-autocheck-red uppercase tracking-widest">{{ $vehicle->plate_number }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2 pt-4 border-t border-gray-200/50">
                                        <div class="flex justify-between items-end text-[9px] font-black uppercase italic text-gray-400">
                                            <span>Next Service</span>
                                            <span class="text-gray-900">{{ $vehicle->next_service_date ? $vehicle->next_service_date->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="h-1 w-full bg-gray-200 rounded-full"><div class="h-full bg-autocheck-red rounded-full" style="width: 100%"></div></div>
                                    </div>
                                </div>
                            @empty
                                <div class="md:col-span-2 py-12 text-center opacity-40 italic font-bold text-xs">No vehicles registered.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Maintenance Log -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-black text-gray-900">Recent Service History</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[10px]">
                                <thead>
                                    <tr class="font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="px-6 py-4 italic">Asset</th>
                                        <th class="px-6 py-4 italic">Date</th>
                                        <th class="px-6 py-4 italic">Type</th>
                                        <th class="px-6 py-4 text-right italic">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($recentServices as $log)
                                    <tr class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-6 py-3 font-black text-gray-900">{{ $log->vehicle->make }}</td>
                                        <td class="px-6 py-3 text-gray-500 font-bold uppercase tracking-tighter">{{ optional($log->service_date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-3 font-black text-gray-900">{{ $log->service_type }}</td>
                                        <td class="px-6 py-3 text-right font-black text-autocheck-red">₱{{ number_format($log->cost, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Security Interface -->
                <div x-show="activeTab === 'security'" class="bg-gray-900 p-6 rounded-2xl border border-white/5 shadow-xl space-y-8 animate-fade-in relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-autocheck-red opacity-10 filter blur-[60px] -mr-24 -mt-24"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-lg font-black text-white tracking-tight italic">Security <span class="text-autocheck-red">Cipher</span></h3>
                        <p class="text-[10px] text-gray-400 font-bold italic mt-1">Safeguard your account with a fresh credential.</p>
                    </div>

                    <form action="{{ route('customer.profile.password') }}" method="POST" class="relative z-10 space-y-4 max-w-lg">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1 italic">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white font-bold text-xs focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1 italic">New Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white font-bold text-xs focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1 italic">Verify Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white font-bold text-xs focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                        </div>
                        <div class="pt-4 flex justify-start">
                            <button type="submit" class="px-8 py-3 bg-autocheck-red text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-autocheck-red transition-all italic shadow-xl">Update Key</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    </style>
</x-customer-layout>