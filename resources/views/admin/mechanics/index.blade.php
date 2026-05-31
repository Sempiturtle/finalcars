<x-admin-layout>
    <div class="space-y-8" 
        x-data="{ 
            showAddModal: {{ $errors->any() ? 'true' : 'false' }}, 
            showEditModal: false, 
            editMechanic: { id: null, name: '', specialty: '', phone: '' },
            showToast: {{ session('success') || session('error') ? 'true' : 'false' }},
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
        <!-- Toast Notification -->
        <div 
            x-show="showToast"
            x-init="if (showToast) setTimeout(() => showToast = false, 5000)"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-[-100%] opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-[-100%] opacity-0"
            class="fixed top-4 right-4 z-[80] max-w-md"
        >
            @if(session('success'))
                <div class="bg-white border-l-4 border-green-500 rounded-2xl shadow-2xl p-4 flex items-start space-x-3">
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div class="flex-1"><p class="text-sm font-bold text-gray-900">Success!</p><p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p></div>
                    <button @click="showToast = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-white border-l-4 border-red-500 rounded-2xl shadow-2xl p-4 flex items-start space-x-3">
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div class="flex-1"><p class="text-sm font-bold text-gray-900">Error!</p><p class="text-sm text-gray-600 mt-1">{{ session('error') }}</p></div>
                    <button @click="showToast = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-xl border border-gray-100">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Mechanic <span class="text-autocheck-red">Management</span></h1>
                <p class="text-[13px] text-gray-500 font-bold mt-0.5">Manage workshop mechanics, specialties, and real-time availability tracking.</p>
            </div>
            <button @click="showAddModal = true" class="px-6 py-3 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 flex items-center space-x-2 text-[11px] uppercase tracking-widest">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Mechanic</span>
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalMechanics }}</h3>
                    <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Total Mechanics</p>
                </div>
                <div class="p-3 bg-red-50 rounded-2xl text-autocheck-red"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg></div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $mechanics->filter(fn($m) => $m->isAvailable())->count() }}</h3>
                    <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Available Now</p>
                </div>
                <div class="p-3 bg-green-50 rounded-2xl text-green-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $mechanics->filter(fn($m) => !$m->isAvailable())->count() }}</h3>
                    <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Active on Jobs</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-2xl text-blue-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
            </div>
        </div>

        <!-- Mechanics Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] italic border-b border-gray-50">
                            <th class="px-6 py-4">Identity</th>
                            <th class="px-6 py-4">Specialty</th>
                            <th class="px-6 py-4">Contact Phone</th>
                            <th class="px-6 py-4">Availability</th>
                            <th class="px-6 py-4 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mechanics as $mechanic)
                            @php
                                $isAvail = $mechanic->isAvailable();
                                $activeVeh = $mechanic->currentActiveVehicle();
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white font-black text-base group-hover:bg-autocheck-red transition-colors">{{ substr($mechanic->name, 0, 1) }}</div>
                                        <div>
                                            <p class="text-[13px] font-black text-gray-900 tracking-tight">{{ $mechanic->name }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">ID: {{ str_pad($mechanic->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-100 text-gray-600">
                                        {{ $mechanic->specialty ?? 'Generalist' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[13px] font-bold text-gray-500">
                                    {{ $mechanic->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $inProgress = $mechanic->inProgressCount();
                                        $scheduled = $mechanic->scheduledCount();
                                    @endphp
                                    @if($inProgress >= 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-red-50 text-autocheck-red cursor-help" 
                                              title="Active on plate: {{ $activeVeh ? $activeVeh->plate_number : 'N/A' }}">
                                            🔴 Active ({{ $activeVeh ? $activeVeh->plate_number : 'N/A' }})
                                        </span>
                                    @elseif($scheduled >= 5)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-red-50 text-autocheck-red" 
                                              title="Fully booked with {{ $scheduled }} scheduled vehicles.">
                                            🔴 Fully Booked ({{ $scheduled }} Scheduled)
                                        </span>
                                    @elseif($scheduled > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-green-50 text-green-700"
                                              title="Has {{ $scheduled }} scheduled vehicles.">
                                            🟢 Available ({{ $scheduled }} Scheduled)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-green-50 text-green-700">
                                            🟢 Available
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <button @click="editMechanic = { id: '{{ $mechanic->id }}', name: '{{ addslashes($mechanic->name) }}', specialty: '{{ addslashes($mechanic->specialty) }}', phone: '{{ $mechanic->phone }}' }; showEditModal = true;" class="p-2 bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white rounded-lg transition-all"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <form action="{{ route('admin.mechanics.destroy', $mechanic) }}" method="POST" class="inline" id="del-mech-{{ $mechanic->id }}">
                                            @csrf @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="confirmDelete(this.closest('form'), 'This will permanently remove this mechanic from the database.', 'Delete Mechanic', 'Yes, Delete')"
                                                class="p-2 bg-gray-50 text-red-400 hover:bg-red-100 hover:text-red-700 rounded-lg transition-all"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($mechanics->hasPages()) <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-50">{{ $mechanics->links() }}</div> @endif
        </div>

        <!-- Add Mechanic Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showAddModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showAddModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showAddModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8"><h2 class="text-2xl font-black text-gray-900">New <span class="text-autocheck-red">Mechanic</span></h2><button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                        <form action="{{ route('admin.mechanics.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 gap-4">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label><input type="text" name="name" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="e.g. Master Tech - Dave"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Specialty</label><input type="text" name="specialty" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="e.g. Engine & Transmission"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number</label><input type="text" name="phone" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="e.g. +639123456789"></div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all mt-4 tracking-widest shadow-xl shadow-red-500/10">REGISTER MECHANIC</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mechanic Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showEditModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showEditModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showEditModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8"><h2 class="text-2xl font-black text-gray-900">Modify <span class="text-autocheck-red" x-text="editMechanic.name"></span></h2><button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                        <form :action="'/admin/mechanics/' + editMechanic.id" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 gap-4">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label><input type="text" name="name" x-model="editMechanic.name" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Specialty</label><input type="text" name="specialty" x-model="editMechanic.specialty" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number</label><input type="text" name="phone" x-model="editMechanic.phone" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all mt-4 tracking-widest shadow-xl shadow-red-500/10">FINALIZE MODIFICATIONS</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
