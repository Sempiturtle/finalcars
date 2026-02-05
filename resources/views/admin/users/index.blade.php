<x-admin-layout>
    <div class="space-y-8" x-data="{ showAddModal: {{ $errors->any() ? 'true' : 'false' }}, showEditModal: false, editUserId: null, showToast: {{ session('success') || session('error') ? 'true' : 'false' }} }">
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
            class="fixed top-4 right-4 z-[70] max-w-md"
        >
            @if(session('success'))
                <div class="bg-white border-l-4 border-green-500 rounded-2xl shadow-2xl p-4 flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">Success!</p>
                        <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
                    </div>
                    <button @click="showToast = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-white border-l-4 border-red-500 rounded-2xl shadow-2xl p-4 flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">Error!</p>
                        <p class="text-sm text-gray-600 mt-1">{{ session('error') }}</p>
                    </div>
                    <button @click="showToast = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif>
        </div>

        <!-- Validation Errors (Keep for form validation) -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl">
                <div class="flex items-start">
                    <svg class="h-5 w-5 mr-3 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">User Management</h1>
                <p class="text-gray-500 font-medium mt-1">Manage system administrators, staff members, and customers.</p>
            </div>
            <button @click="showAddModal = true" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-lg shadow-red-500/30">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Add New User
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Total Users -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-50 rounded-2xl text-gray-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $totalUsers }}</h3>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest mt-1">Total Users</p>
            </div>

            <!-- Administrators -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 rounded-2xl text-autocheck-red">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $administrators }}</h3>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest mt-1">Administrators</p>
            </div>

            <!-- Staff Members -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $staffMembers }}</h3>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest mt-1">Staff Members</p>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">User</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Username</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Role</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Address</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-sm font-black text-gray-400 group-hover:bg-white transition-colors border border-transparent group-hover:border-gray-100 uppercase">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 tracking-tight">{{ $user->name }}</p>
                                            <p class="text-xs font-bold text-gray-400">{{ $user->email }} @if($user->phone) • {{ $user->phone }} @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-bold text-gray-600">@ {{ $user->username ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] {{ 
                                        match($user->role) {
                                            'admin' => 'bg-red-50 text-autocheck-red',
                                            'staff' => 'bg-blue-50 text-blue-600',
                                            default => 'bg-gray-50 text-gray-600',
                                        }
                                    }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-bold text-gray-500 max-w-xs truncate">{{ $user->address ?? 'N/A' }}</p>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity justify-end">
                                        <button @click="editUserId = {{ $user->id }}; showEditModal = true" class="p-2.5 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Add User Modal -->
        <div 
            x-show="showAddModal" 
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
                <div @click="showAddModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
                
                <div 
                    class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden"
                    x-show="showAddModal"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-95 translate-y-4"
                    x-transition:enter-end="scale-100 translate-y-0"
                >
                    <div class="p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Create New User</h2>
                                <p class="text-gray-500 font-medium text-sm mt-1">Assign roles and set up access credentials.</p>
                            </div>
                            <button @click="showAddModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Full Name -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('name') border-red-500 @enderror" placeholder="John Doe">
                                    @error('name')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Username -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Username</label>
                                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('username') border-red-500 @enderror" placeholder="johndoe">
                                    @error('username')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('email') border-red-500 @enderror" placeholder="john@example.com">
                                    @error('email')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('phone') border-red-500 @enderror" placeholder="09123456789">
                                    @error('phone')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Access Role</label>
                                    <select name="role" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('role') border-red-500 @enderror">
                                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>User (Customer)</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff Member</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                    @error('role')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Initial Password</label>
                                    <input type="password" name="password" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('password') border-red-500 @enderror" placeholder="••••••••">
                                    @error('password')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Physical Address</label>
                                    <textarea name="address" rows="3" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all @error('address') border-red-500 @enderror" placeholder="Enter full address...">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="text-red-600 text-xs font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/30 uppercase tracking-[0.2em] text-xs">
                                    Create User Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div 
            x-show="showEditModal" 
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
                <div @click="showEditModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
                
                @foreach($users as $user)
                <div 
                    x-show="editUserId === {{ $user->id }}"
                    class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-95 translate-y-4"
                    x-transition:enter-end="scale-100 translate-y-0"
                >
                    <div class="p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Edit User</h2>
                                <p class="text-gray-500 font-medium text-sm mt-1">Update user information and access level.</p>
                            </div>
                            <button @click="showEditModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Full Name -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                </div>

                                <!-- Username -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Username</label>
                                    <input type="text" name="username" value="{{ $user->username }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                </div>

                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                                    <input type="text" name="phone" value="{{ $user->phone }}" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                </div>

                                <!-- Role -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Access Role</label>
                                    <select name="role" required class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">
                                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>User (Customer)</option>
                                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff Member</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                </div>

                                <!-- Password (Optional) -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">New Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all" placeholder="••••••••">
                                </div>

                                <!-- Address -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Physical Address</label>
                                    <textarea name="address" rows="3" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all" placeholder="Enter full address...">{{ $user->address }}</textarea>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-autocheck-red text-white font-black rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/30 uppercase tracking-[0.2em] text-xs">
                                    Update User Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
