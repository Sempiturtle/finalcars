<x-admin-layout>
    <div class="space-y-8" 
        x-data="{ 
            showAddModal: {{ $errors->any() ? 'true' : 'false' }}, 
            showEditModal: false, 
            editUser: { id: null, name: '', email: '', username: '', phone: '+63', role: '', address: '' },
            showToast: {{ session('success') || session('error') ? 'true' : 'false' }},
            updateScrollLock() {
                const main = document.querySelector('main');
                if (this.showAddModal || this.showEditModal) {
                    main.style.overflow = 'hidden';
                    main.style.paddingRight = '0px'; 
                } else {
                    main.style.overflow = 'auto';
                    main.style.paddingRight = '0px';
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
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Admin <span class="text-autocheck-red">Management</span></h1>
                <p class="text-[13px] text-gray-500 font-bold mt-0.5">Manage system administrator accounts.</p>
            </div>
            <button @click="showAddModal = true" class="px-6 py-3 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 flex items-center space-x-2 text-[11px] uppercase tracking-widest">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Admin</span>
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="p-2.5 bg-gray-50 rounded-xl text-gray-400 w-fit mb-3"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalUsers }}</h3>
                <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Total Users</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="p-2.5 bg-red-50 rounded-xl text-autocheck-red w-fit mb-3"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalAdmins }}</h3>
                <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Administrators</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 w-fit mb-3"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalCustomers }}</h3>
                <p class="text-gray-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Customers</p>
            </div>
        </div>

        <!-- User Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] italic border-b border-gray-50">
                            <th class="px-6 py-4">Identity</th>
                            <th class="px-6 py-4">Access Role</th>
                            <th class="px-6 py-4">Contact Information</th>
                            <th class="px-6 py-4 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white font-black text-base group-hover:bg-autocheck-red transition-colors">{{ substr($user->name, 0, 1) }}</div>
                                        <div>
                                            <p class="text-[13px] font-black text-gray-900 tracking-tight">{{ $user->name }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 lowercase">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">@ {{ $user->role }}</span>
                                </td>
                                <td class="px-6 py-4 text-[13px] font-bold text-gray-500">{{ $user->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <button @click="editUser = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', username: '{{ $user->username }}', phone: '{{ $user->phone }}', role: '{{ $user->role }}', address: '{{ addslashes($user->address) }}' }; showEditModal = true;" class="p-2 bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white rounded-lg transition-all"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Archive this user? This action is permanent.')" class="inline">@csrf @method('DELETE') <button type="submit" class="p-2 bg-gray-50 text-red-400 hover:bg-red-100 hover:text-red-700 rounded-lg transition-all"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages()) <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-50">{{ $users->links() }}</div> @endif
        </div>

        <!-- Add User Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showAddModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showAddModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showAddModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8"><h2 class="text-2xl font-black text-gray-900">New <span class="text-autocheck-red">Admin</span></h2><button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Name</label><input type="text" name="name" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Username</label><input type="text" name="username" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label><input type="email" name="email" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone</label><input type="text" name="phone" value="+63" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <input type="hidden" name="role" value="admin">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label><input type="password" name="password" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="col-span-2 space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Address</label><textarea name="address" rows="2" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none"></textarea></div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all mt-4 tracking-widest shadow-xl shadow-red-500/10">AUTHENTICATE REGISTRATION</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[60]" x-cloak>
            <div @click="showEditModal = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-2xl transition-opacity duration-300" x-show="showEditModal" x-transition:enter="ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" x-show="showEditModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8"><h2 class="text-2xl font-black text-gray-900">Modify <span class="text-autocheck-red" x-text="editUser.name"></span></h2><button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                        <form :action="'/admin/users/' + editUser.id" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label><input type="text" name="name" x-model="editUser.name" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Username</label><input type="text" name="username" x-model="editUser.username" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label><input type="email" name="email" x-model="editUser.email" required class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone</label><input type="text" name="phone" x-model="editUser.phone" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <input type="hidden" name="role" value="admin">
                                <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">New Password (Empty to keep)</label><input type="password" name="password" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></div>
                                <div class="col-span-2 space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Physical Address</label><textarea name="address" x-model="editUser.address" rows="2" class="w-full px-5 py-3.5 bg-gray-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none"></textarea></div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-xl hover:bg-red-700 transition-all mt-4 tracking-widest shadow-xl shadow-red-500/10">FINALIZE MODIFICATIONS</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
