<x-admin-layout>
    <div class="flex flex-col transition-all duration-500 overflow-hidden bg-white rounded-[2rem] shadow-xl border border-gray-100 h-[calc(100vh-6.5rem)]"
         x-data="chatSystem()" 
         x-init="init()">
        
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Sidebar: User List -->
            <div class="w-full md:w-80 border-r flex flex-col z-20 transition-all duration-500 border-gray-100 bg-gray-50/50"
                 :class="{ 'hidden md:flex': !showList && selectedUser }">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                            Messages
                        </h2>
                        <div class="flex items-center space-x-2">
                            <template x-if="selectedUser">
                                <button @click="showList = false" 
                                        class="md:hidden p-2 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="relative group">
                        <input type="text" x-model="search" placeholder="Search contacts..." 
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-2xl text-xs font-bold focus:ring-4 focus:ring-[#F53003]/10 focus:border-[#F53003] focus:bg-white transition-all placeholder-gray-400 outline-none">
                        <svg class="h-4 w-4 absolute left-4 top-3.5 text-gray-400 group-focus-within:text-autocheck-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-3 space-y-1 custom-scrollbar">
                    <!-- Active Conversations Header -->
                    <template x-if="!search">
                        <div class="px-3 py-2">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Conversations</span>
                        </div>
                    </template>

                    <!-- Search Section Header -->
                    <template x-if="search">
                        <div class="px-3 py-2 flex items-center justify-between">
                            <span class="text-[9px] font-black text-autocheck-red uppercase tracking-widest">Global Search</span>
                            <template x-if="isLoadingSearch">
                                <div class="w-3 h-3 border-2 border-autocheck-red border-t-transparent rounded-full animate-spin"></div>
                            </template>
                        </div>
                    </template>

                    <template x-for="user in displayUsers" :key="user.id">
                        <button @click="selectUser(user)" 
                                :class="selectedUser && selectedUser.id === user.id ? 'bg-white shadow-sm ring-1 ring-black/5' : 'hover:bg-white/60'"
                                class="w-full p-3 rounded-2xl transition-all duration-200 text-left group relative">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0">
                                    <div class="h-12 w-12 rounded-xl bg-autocheck-red flex items-center justify-center text-white font-black text-sm shadow-lg shadow-red-500/20 group-hover:scale-105 transition-transform"
                                         x-text="getInitials(user.name)">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 border-4 border-gray-50 rounded-full"
                                         :class="user.is_online ? 'bg-green-500' : 'bg-gray-300'"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3 class="font-bold truncate text-xs text-gray-900 tracking-tight" x-text="user.name"></h3>
                                        <span class="text-[9px] font-black text-gray-400 uppercase" x-text="user.last_message_time || formatTime(new Date())"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] text-gray-500 truncate font-medium" x-text="user.email"></p>
                                        <template x-if="user.unread_count > 0">
                                            <span class="bg-autocheck-red text-white text-[9px] font-black px-1.5 py-0.5 rounded-lg" x-text="user.unread_count"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </template>

                    <template x-if="search && displayUsers.length === 0 && !isLoadingSearch">
                        <div class="p-8 text-center">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-relaxed">No customers found matching "<span x-text="search"></span>"</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col bg-white"
                 :class="(!selectedUser || showList) ? 'hidden md:flex' : 'flex'">
                <template x-if="!selectedUser">
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-gray-50/30">
                        <div class="w-24 h-24 rounded-3xl bg-white shadow-2xl flex items-center justify-center mb-8 relative border border-gray-100">
                            <div class="absolute inset-0 bg-autocheck-red rounded-3xl blur-[30px] opacity-10"></div>
                            <img src="{{ asset('images/logo.png') }}" class="h-12 w-12 relative z-10 object-cover rounded-full border-2 border-autocheck-red" alt="AutoCheck Logo">
                        </div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Chat Center</h3>
                        <p class="mt-3 max-w-xs font-black text-[10px] text-gray-400 uppercase tracking-[0.2em] leading-relaxed">Select a conversation to start messaging with your customers</p>
                    </div>
                </template>

                <template x-if="selectedUser">
                    <div class="flex flex-col h-full">
                        <!-- Chat Header -->
                        <div class="h-20 px-8 border-b border-gray-100 flex items-center justify-between bg-white/80 backdrop-blur-xl z-10">
                            <div class="flex items-center space-x-4">
                                <button @click="showList = true" class="md:hidden p-2 -ml-2 text-autocheck-red hover:bg-red-50 rounded-xl transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <div class="relative">
                                    <div class="h-10 w-10 rounded-xl bg-autocheck-red flex items-center justify-center text-white font-black text-xs shadow-lg shadow-red-500/20" x-text="getInitials(selectedUser.name)"></div>
                                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 border-2 border-white rounded-full"
                                         :class="selectedUser.is_online ? 'bg-green-500' : 'bg-gray-300'"></div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-black text-sm text-gray-900 tracking-tight leading-none truncate uppercase" x-text="selectedUser.name"></h3>
                                    <div class="flex items-center mt-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full mr-2"
                                             :class="selectedUser.is_online ? 'bg-green-500 animate-pulse' : 'bg-gray-400'"></div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                           x-text="selectedUser.is_online ? 'Active Now' : 'Offline'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat History -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/30 custom-scrollbar scroll-smooth" id="chat-history">
                            <template x-for="(msg, index) in messages" :key="msg.id">
                                <div class="reveal-message">
                                    <!-- Date Separator -->
                                    <template x-if="shouldShowDate(msg, index)">
                                        <div class="flex justify-center my-8">
                                            <span class="px-4 py-1 bg-white rounded-full shadow-sm border border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest" x-text="formatDate(msg.created_at)"></span>
                                        </div>
                                    </template>

                                    <div class="flex flex-col group/msg" :class="msg.sender_id === {{ Auth::id() }} ? 'items-end' : 'items-start'">
                                        <div class="flex items-end space-x-2" :class="msg.sender_id === {{ Auth::id() }} ? 'flex-row-reverse space-x-reverse' : ''">
                                            <div :class="msg.sender_id === {{ Auth::id() }} 
                                                 ? 'bg-autocheck-red text-white rounded-2xl rounded-tr-sm shadow-lg shadow-red-500/20' 
                                                 : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm'"
                                                 class="w-fit max-w-[85%] md:max-w-[70%] px-4 py-3 text-xs font-bold leading-relaxed transition-all">
                                                <p x-text="msg.message" class="break-words whitespace-pre-wrap"></p>
                                            </div>
                                        </div>
                                        <span class="text-[9px] font-black text-gray-400 uppercase mt-1 px-1 opacity-0 group-hover/msg:opacity-100 transition-all tracking-tighter" 
                                              x-text="formatTime(msg.created_at)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-6 bg-white border-t border-gray-100">
                            <form @submit.prevent="sendMessage" class="flex items-center space-x-3 max-w-4xl mx-auto">
                                <div class="flex-1 relative">
                                    <input type="text" x-model="newMessage" placeholder="Type your message here..." 
                                           class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 focus:ring-4 focus:ring-autocheck-red/10 focus:border-autocheck-red focus:bg-white text-xs font-bold transition-all placeholder-gray-400 shadow-inner">
                                </div>
                                <button type="submit" 
                                        :disabled="!newMessage.trim()"
                                        class="h-12 w-12 flex items-center justify-center bg-autocheck-red text-white rounded-2xl shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all active:scale-95 disabled:opacity-20 disabled:grayscale">
                                    <svg class="h-5 w-5 rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #F53003;
        }
        .reveal-message {
            animation: slideUp 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function chatSystem() {
            return {
                customers: @json($customers),
                search: '',
                searchResults: [],
                isLoadingSearch: false,
                selectedUser: null,
                showList: true,
                messages: [],
                newMessage: '',
                pollingTimer: null,

                init() {
                    this.$watch('search', (value) => {
                        this.performSearch();
                    });

                    // Smart Polling: Only poll when tab is active and user is selected
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.stopPolling();
                        } else {
                            if (this.selectedUser) {
                                this.fetchMessages(); // Immediate catch-up
                                this.startPolling();
                            }
                        }
                    });
                },

                startPolling() {
                    if (this.pollingTimer) clearInterval(this.pollingTimer);
                    this.pollingTimer = setInterval(() => this.fetchMessages(true), 4000);
                },

                stopPolling() {
                    if (this.pollingTimer) {
                        clearInterval(this.pollingTimer);
                        this.pollingTimer = null;
                    }
                },

                async performSearch() {
                    if (!this.search || this.search.length < 1) {
                        this.searchResults = [];
                        return;
                    }
                    this.isLoadingSearch = true;
                    try {
                        const res = await fetch(`/admin/chat/search?q=${encodeURIComponent(this.search)}`);
                        this.searchResults = await res.json();
                    } catch (e) {
                        console.error('Search error:', e);
                    } finally {
                        this.isLoadingSearch = false;
                    }
                },

                get displayUsers() {
                    if (!this.search) return this.customers;
                    return this.searchResults;
                },

                selectUser(user) {
                    // Add to customers list if not already there (so it stays in the sidebar after selection)
                    if (!this.customers.find(c => c.id === user.id)) {
                        this.customers.unshift(user);
                    }
                    
                    this.selectedUser = user;
                    this.showList = false;
                    user.unread_count = 0;
                    this.search = ''; // Clear search after selection
                    this.fetchMessages();
                    this.startPolling();
                },

                fetchMessages(silent = false) {
                    if (!this.selectedUser) return;

                    fetch(`/admin/chat/${this.selectedUser.id}`)
                        .then(res => res.json())
                        .then(data => {
                            const oldLength = this.messages.length;
                            this.messages = data.messages;
                            
                            // Update selected user's status
                            if (data.user) {
                                this.selectedUser.is_online = data.user.is_online;
                                this.selectedUser.last_seen_at = data.user.last_seen_at;
                                
                                // Update in customers list too
                                const customer = this.customers.find(c => c.id === data.user.id);
                                if (customer) {
                                    customer.is_online = data.user.is_online;
                                }
                            }
                            
                            if (this.messages.length > oldLength) {
                                this.scrollToBottom();
                            }
                        })
                        .catch(err => console.error(err));
                },

                sendMessage() {
                    const text = this.newMessage.trim();
                    if (!text || !this.selectedUser) return;

                    // Clear input immediately so user can type the next one
                    this.newMessage = '';

                    // Optimistic update: show message in UI immediately
                    const tempId = 'temp-' + Date.now();
                    const tempMsg = {
                        id: tempId,
                        message: text,
                        sender_id: {{ Auth::id() }},
                        created_at: new Date().toISOString(),
                        is_pending: true
                    };
                    this.messages.push(tempMsg);
                    this.scrollToBottom();

                    const body = {
                        message: text,
                        receiver_id: this.selectedUser.id
                    };

                    fetch('/admin/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(body)
                    })
                    .then(res => res.json())
                    .then(msg => {
                        // Replace the pending message with the confirmed one from server
                        const index = this.messages.findIndex(m => m.id === tempId);
                        if (index !== -1) {
                            this.messages[index] = msg;
                        }
                    })
                    .catch(err => {
                        console.error('Failed to send:', err);
                        // Mark as failed in UI if desired
                    });
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const el = document.getElementById('chat-history');
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                },

                formatTime(dateStr) {
                    const date = new Date(dateStr);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                formatDate(dateStr) {
                    const date = new Date(dateStr);
                    const now = new Date();
                    if (date.toDateString() === now.toDateString()) return 'Today';
                    return date.toLocaleDateString(undefined, { weekday: 'long', month: 'short', day: 'numeric' });
                },

                shouldShowDate(msg, index) {
                    if (index === 0) return true;
                    const prevMsg = this.messages[index - 1];
                    const currDate = new Date(msg.created_at).toDateString();
                    const prevDate = new Date(prevMsg.created_at).toDateString();
                    return currDate !== prevDate;
                },

                getInitials(name) {
                    if (!name) return '';
                    const parts = name.split(' ');
                    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
                    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
                }
            }
        }
    </script>
</x-admin-layout>
