<x-customer-layout>
    <div class="max-w-7xl mx-auto space-y-12 pb-20" x-data="{ activeTab: 'info' }">
        <!-- Cinematic Header Section -->
        <div class="relative min-h-[300px] rounded-[4rem] overflow-hidden shadow-2xl bg-gray-900 group">
            <!-- Animated Background Mesh -->
            <div class="absolute inset-0 opacity-40">
                <div
                    class="absolute top-0 -left-20 w-96 h-96 bg-autocheck-red rounded-full mix-blend-screen filter blur-[80px] animate-pulse">
                </div>
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-red-600 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"
                    style="animation-delay: 2s"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-red-900/20 rounded-full filter blur-[120px]">
                </div>
            </div>

            <div
                class="relative z-10 p-12 md:p-16 flex flex-col md:flex-row md:items-center justify-between h-full gap-12">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] italic">
                        Customer Account
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter">
                        Your <span class="text-autocheck-red">Legacy</span>
                    </h1>
                    <p class="text-gray-400 font-medium text-lg max-w-md leading-relaxed italic">
                        Oversee your automotive journey, manage your fleet, and secure your profile settings.
                    </p>
                </div>

                <div
                    class="flex items-center space-x-6 bg-white/5 backdrop-blur-xl p-8 rounded-[3rem] border border-white/10 shadow-2xl">
                    <div class="relative">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-autocheck-red to-red-800 rounded-[2rem] flex items-center justify-center text-white text-4xl font-black shadow-2xl shadow-red-600/20">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Total
                            Points</p>
                        <p class="text-4xl font-black text-white tracking-tight">
                            {{ number_format($user->loyalty_points) }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <div class="h-1 w-20 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-autocheck-red w-3/4"></div>
                            </div>
                            <span
                                class="text-[8px] font-black text-autocheck-red uppercase tracking-widest italic"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
            <!-- Advanced Navigation -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white/80 backdrop-blur-xl p-6 rounded-[2.5rem] md:rounded-[3.5rem] border border-gray-100 shadow-xl lg:sticky lg:top-28 space-y-3">
                    <nav class="space-y-3">
                        <button @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? 'bg-gray-900 text-white shadow-2xl shadow-gray-400 scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900'"
                            class="w-full flex items-center px-8 py-5 rounded-[2rem] text-sm font-black transition-all duration-500 group">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center mr-4 group-hover:bg-white transition-colors"
                                :class="activeTab === 'info' ? 'bg-white/10' : ''">
                                <svg class="h-5 w-5"
                                    :class="activeTab === 'info' ? 'text-autocheck-red' : 'text-gray-400'" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Personal Info
                        </button>

                        <button @click="activeTab = 'vehicles'"
                            :class="activeTab === 'vehicles' ? 'bg-gray-900 text-white shadow-2xl shadow-gray-400 scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900'"
                            class="w-full flex items-center px-8 py-5 rounded-[2rem] text-sm font-black transition-all duration-500 group">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center mr-4 group-hover:bg-white transition-colors"
                                :class="activeTab === 'vehicles' ? 'bg-white/10' : ''">
                                <svg class="h-5 w-5"
                                    :class="activeTab === 'vehicles' ? 'text-autocheck-red' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            My Vehicles
                        </button>

                        <button @click="activeTab = 'security'"
                            :class="activeTab === 'security' ? 'bg-gray-900 text-white shadow-2xl shadow-gray-400 scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900'"
                            class="w-full flex items-center px-8 py-5 rounded-[2rem] text-sm font-black transition-all duration-500 group">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center mr-4 group-hover:bg-white transition-colors"
                                :class="activeTab === 'security' ? 'bg-white/10' : ''">
                                <svg class="h-5 w-5"
                                    :class="activeTab === 'security' ? 'text-autocheck-red' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            Security
                        </button>
                    </nav>

                    <div class="pt-6 border-t border-gray-100">
                        <div class="bg-red-50 p-6 rounded-[2rem] border border-red-100">
                            <p class="text-[10px] font-black text-autocheck-red uppercase tracking-widest mb-2 italic">
                                Quick Stat</p>
                            <p class="text-xs font-bold text-gray-600 italic">Member since
                                {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area Overhaul -->
            <div class="lg:col-span-9">
                <!-- Status Messages -->
                @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                    <div class="mb-8 bg-white p-6 rounded-[2.5rem] border-l-8 border-green-500 shadow-xl flex items-center"
                        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                        <div
                            class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-500 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <p class="font-black text-gray-900 tracking-tight italic">{{ session('message') }}</p>
                    </div>
                @endif

                <!-- Profile Info Section -->
                <div x-show="activeTab === 'info'"
                    x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-12"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div
                        class="bg-white p-6 md:p-12 rounded-[2.5rem] md:rounded-[4rem] border border-gray-100 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16"></div>

                        <div class="relative z-10 flex items-center justify-between mb-8 md:mb-16">
                            <div>
                                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Legacy <span
                                        class="text-autocheck-red">Information</span></h3>
                                <p class="text-gray-400 font-medium italic mt-2">Personalize your identity and contact
                                    preferences.</p>
                            </div>
                        </div>

                        <form action="{{ route('customer.profile.update') }}" method="POST"
                            class="space-y-6 md:space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-10">
                                <div class="space-y-3 group">
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-wider ml-4 block italic transition-colors group-focus-within:text-autocheck-red">Full
                                        Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/5 focus:border-autocheck-red transition-all duration-300">
                                    @error('name') <p
                                        class="text-[10px] text-autocheck-red mt-2 ml-4 font-black italic uppercase tracking-wider">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-3 group">
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-wider ml-4 block italic transition-colors group-focus-within:text-autocheck-red">Email
                                        Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/5 focus:border-autocheck-red transition-all duration-300">
                                    @error('email') <p
                                        class="text-[10px] text-autocheck-red mt-2 ml-4 font-black italic uppercase tracking-wider">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-3 group">
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-wider ml-4 block italic transition-colors group-focus-within:text-autocheck-red">Phone
                                        Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/5 focus:border-autocheck-red transition-all duration-300"
                                        placeholder="+63 XXX XXX XXXX">
                                    @error('phone') <p
                                        class="text-[10px] text-autocheck-red mt-2 ml-4 font-black italic uppercase tracking-wider">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-3 group lg:col-span-2">
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-wider ml-4 block italic transition-colors group-focus-within:text-autocheck-red">Physical
                                        Address</label>
                                    <textarea name="address" rows="3"
                                        class="w-full px-5 py-5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-autocheck-red/5 focus:border-autocheck-red transition-all duration-300 resize-none"
                                        placeholder="Street, City, State, ZIP">{{ old('address', $user->address) }}</textarea>
                                    @error('address') <p
                                        class="text-[10px] text-autocheck-red mt-2 ml-4 font-black italic uppercase tracking-wider">
                                    {{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit"
                                    class="px-12 py-5 bg-gray-900 text-white rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-autocheck-red hover:shadow-2xl hover:shadow-red-500/20 transition-all duration-500 italic">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Vehicles Fleet Overhaul -->
                <div x-show="activeTab === 'vehicles'"
                    x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-12"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-12">
                        <div class="bg-white p-12 rounded-[4rem] border border-gray-100 shadow-xl">
                            <div class="flex items-center justify-between mb-16">
                                <div>
                                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Your <span
                                            class="text-autocheck-red">Fleet</span></h3>
                                    <p class="text-gray-400 font-medium italic mt-2">Command and monitor your registered
                                        automotive assets.</p>
                                </div>
                                <div class="bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100 italic">
                                    <span
                                        class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ $vehicles->count() }}
                                        Assets Total</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                @forelse($vehicles as $vehicle)
                                    <div
                                        class="relative bg-gray-50/50 p-10 rounded-[3.5rem] border border-gray-50 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
                                        <div
                                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-autocheck-red/5 to-transparent rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-125 duration-700">
                                        </div>

                                        <div class="relative z-10 flex items-start justify-between mb-10">
                                            <div class="flex items-center space-x-6">
                                                <div
                                                    class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-autocheck-red italic font-black text-2xl shadow-xl border border-gray-50 transition-transform group-hover:rotate-6">
                                                    {{ substr($vehicle->make, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="text-xl font-black text-gray-900 tracking-tight">
                                                        {{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                                    <p
                                                        class="text-[10px] font-black text-autocheck-red uppercase tracking-[0.3em] mt-1">
                                                        {{ $vehicle->plate_number }}</p>
                                                </div>
                                            </div>
                                            <span
                                                class="px-5 py-2 bg-white text-[9px] font-black uppercase tracking-widest text-gray-400 rounded-full border border-gray-100 italic group-hover:text-autocheck-red group-hover:border-autocheck-red/20 transition-colors">
                                                {{ $vehicle->status }}
                                            </span>
                                        </div>

                                        <div class="relative z-10 space-y-6 pt-6 border-t border-gray-200/50">
                                            <div class="flex justify-between items-end">
                                                <div class="space-y-1">
                                                    <p
                                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">
                                                        Appointment Status</p>
                                                    <p class="text-sm font-black text-gray-900">
                                                        {{ $vehicle->next_service_date ? $vehicle->next_service_date->format('M d, Y') : 'Indefinite' }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p
                                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">
                                                        Countdown</p>
                                                    <p
                                                        class="text-sm font-black {{ ($vehicle->next_service_date && $vehicle->next_service_date->isPast()) ? 'text-autocheck-red' : 'text-green-600' }}">
                                                        {{ $vehicle->next_service_date ? $vehicle->next_service_date->diffForHumans() : 'Cleared' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-autocheck-red to-red-800 rounded-full transition-all duration-1000"
                                                    style="width: 100%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="md:col-span-2 py-32 bg-gray-50/50 rounded-[4rem] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center">
                                        <div
                                            class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-6">
                                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-black text-gray-900 tracking-tight">Fleet Empty</h4>
                                        <p class="text-gray-400 font-medium italic mt-2">No automotive assets detected in
                                            your registry.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Maintenance Stream Table -->
                        <div class="bg-white rounded-[4.5rem] border border-gray-100 shadow-2xl overflow-hidden">
                            <div class="p-12 md:p-16 border-b border-gray-50 flex items-center justify-between">
                                <div>
                                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Maintenance <span
                                            class="text-autocheck-red">Stream</span></h3>
                                    <p class="text-gray-400 font-medium italic mt-2 text-base">Historical record of
                                        latest executive service operations.</p>
                                </div>
                                <div class="hidden md:block">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center text-gray-300">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto px-8 pb-8">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em] italic border-b border-gray-100">
                                            <th class="px-8 py-8">Asset Identifier</th>
                                            <th class="px-8 py-8">Timstamp</th>
                                            <th class="px-8 py-8">Operation Type</th>
                                            <th class="px-8 py-8 text-right">Resource Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($recentServices as $log)
                                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                                <td class="px-8 py-10">
                                                    <p
                                                        class="text-base font-black text-gray-900 tracking-tight group-hover:text-autocheck-red transition-colors">
                                                        {{ $log->vehicle->make }} {{ $log->vehicle->model }}</p>
                                                    <p
                                                        class="text-[8px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">
                                                        {{ $log->vehicle->plate_number }}</p>
                                                </td>
                                                <td class="px-8 py-10">
                                                    <span
                                                        class="inline-flex items-center px-4 py-1.5 rounded-full bg-gray-100 text-[10px] font-black text-gray-500 italic">
                                                        {{ optional($log->service_date)->format('M d, Y') ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-8 py-10">
                                                    <p class="text-sm font-black text-gray-900 tracking-tight">
                                                        {{ $log->service_type }}</p>
                                                </td>
                                                <td
                                                    class="px-8 py-10 text-right font-black text-gray-900 text-lg tracking-tighter">
                                                    ₱{{ number_format($log->cost, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"
                                                    class="px-10 py-32 text-center text-gray-400 font-black italic uppercase tracking-widest text-sm">
                                                    NO LOGS DETECTED</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Interface Overhaul -->
                <div x-show="activeTab === 'security'"
                    x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-12"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div
                        class="bg-gray-900 p-8 md:p-12 xl:p-16 rounded-[3rem] md:rounded-[4.5rem] border border-white/5 shadow-2xl relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-96 h-96 bg-autocheck-red opacity-10 filter blur-[100px] -mr-48 -mt-48 transition-transform group-hover:scale-125 duration-1000">
                        </div>

                        <div class="relative z-10 mb-12 md:mb-16 space-y-4">
                            <h3 class="text-3xl md:text-4xl font-black text-white tracking-tighter italic">Security
                                <span class="text-autocheck-red">Cipher</span></h3>
                            <p class="text-gray-400 font-medium text-base md:text-lg leading-relaxed italic max-w-lg">
                                Protect your digital footprint. Establish a new encrypted credential to safeguard your
                                legacy assets.</p>
                        </div>

                        <form action="{{ route('customer.profile.password') }}" method="POST"
                            class="space-y-8 md:space-y-10 relative z-10">
                            @csrf
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
                                <div class="space-y-4">
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-wider ml-4 block italic">Current
                                        Password</label>
                                    <input type="password" name="current_password" required
                                        class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-xl text-white font-bold text-sm focus:bg-white/10 focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all duration-300 placeholder-gray-700">
                                    @error('current_password', 'updatePassword') <p
                                        class="text-[10px] text-autocheck-red mt-3 ml-4 font-black uppercase tracking-widest italic animate-pulse">
                                    {{ $message }}</p> @enderror
                                </div>
                                <div class="hidden xl:block"></div>
                                <div class="space-y-4">
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-wider ml-4 block italic">New
                                        Password</label>
                                    <input type="password" name="password" required
                                        class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-xl text-white font-bold text-sm focus:bg-white/10 focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all duration-300 placeholder-gray-700">
                                    @error('password', 'updatePassword') <p
                                        class="text-[10px] text-autocheck-red mt-3 ml-4 font-black uppercase tracking-widest italic">
                                    {{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-4">
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-wider ml-4 block italic">Verify
                                        Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-xl text-white font-bold text-sm focus:bg-white/10 focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all duration-300 placeholder-gray-700">
                                </div>
                            </div>

                            <div class="flex justify-start pt-8">
                                <button type="submit"
                                    class="group relative px-12 py-5 bg-autocheck-red rounded-full overflow-hidden transition-all duration-500 hover:scale-105 active:scale-95 shadow-2xl shadow-red-900/40">
                                    <div
                                        class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                    </div>
                                    <span
                                        class="relative z-10 text-[10px] font-black uppercase tracking-widest text-white group-hover:text-autocheck-red transition-colors duration-500 italic">Update
                                        Password</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>