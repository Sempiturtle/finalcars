<x-admin-layout>
    <div class="fixed inset-0 z-[100] flex flex-col transition-colors duration-500 overflow-hidden"
         :class="darkMode ? 'bg-[#18191A]' : 'bg-[#F0F2F5]'"
         x-data="chatSystem()" 
         x-init="init()">
        
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Sidebar: User List -->
            <div class="w-full md:w-96 border-r flex flex-col z-20 transition-colors duration-500"
                 :class="[
                    darkMode ? 'border-white/5 bg-[#242526]' : 'border-gray-200 bg-white',
                    { 'hidden md:flex': !showList && selectedUser }
                 ]">
                <div class="p-4 md:p-6 border-b" :class="darkMode ? 'border-white/5' : 'border-gray-100'">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-black transition-colors"
                            :class="darkMode ? 'text-white' : 'text-gray-900'">
                            Chats
                        </h2>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full transition-colors" :class="darkMode ? 'text-gray-400 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-100'">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="relative group">
                        <input type="text" x-model="search" placeholder="Search Chat Center" 
                               :class="darkMode ? 'bg-white/5 border-white/10 text-white placeholder-gray-500 group-hover:bg-white/10' : 'bg-gray-100 border-transparent text-gray-900 placeholder-gray-400 group-hover:bg-gray-200'"
                               class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-4 border rounded-full text-sm focus:ring-4 focus:ring-blue-500 transition-all">
                        <svg class="h-4 w-4 md:h-5 md:w-5 absolute left-4 top-3.5 md:top-4 text-gray-500 group-hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">
                    <template x-for="user in filteredCustomers" :key="user.id">
                        <button @click="selectUser(user)" 
                                :class="[
                                    selectedUser && selectedUser.id === user.id 
                                        ? (darkMode ? 'bg-white/10 border-white/20' : 'bg-gray-100 border-transparent shadow-sm') 
                                        : (darkMode ? 'hover:bg-white/5 border-transparent opacity-80' : 'hover:bg-gray-50 border-transparent')
                                ]"
                                class="w-full p-4 rounded-2xl border transition-all duration-200 text-left group relative overflow-hidden">
                            <div class="flex items-center space-x-4 relative z-10">
                                <div class="relative">
                                    <div class="h-14 w-14 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow-lg group-hover:scale-105 transition-transform"
                                         x-text="getInitials(user.name)">
                                    </div>
                                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 border-4 rounded-full transition-colors duration-500"
                                         :class="darkMode ? 'border-[#242526]' : 'border-white'"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3 class="font-bold truncate text-sm tracking-tight transition-colors" 
                                            :class="darkMode ? 'text-white' : 'text-gray-900'"
                                            x-text="user.name"></h3>
                                        <span class="text-[10px] font-medium transition-colors" 
                                              :class="darkMode ? 'text-gray-400' : 'text-gray-500'"
                                              x-text="formatTime(new Date())"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs transition-colors truncate" 
                                           :class="darkMode ? 'text-gray-500' : 'text-gray-600'"
                                           x-text="user.email"></p>
                                        <template x-if="user.unread_count > 0">
                                            <span class="bg-blue-600 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full" x-text="user.unread_count"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col transition-colors duration-500"
                 :class="[
                    darkMode ? 'bg-[#18191A]' : 'bg-white',
                    { 'hidden md:flex': showList && selectedUser, 'flex': !showList && selectedUser }
                 ]">
                <template x-if="!selectedUser">
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center mb-8 relative"
                             :class="darkMode ? 'bg-white/5' : 'bg-gray-100'">
                            <div class="absolute inset-0 bg-autocheck-red rounded-full blur-[50px] opacity-20"></div>
                            <img src="{{ asset('images/logo.png') }}" class="h-16 w-16 relative z-10 object-cover rounded-full" alt="AutoCheck Logo">
                        </div>
                        <h3 class="text-3xl font-black uppercase tracking-tighter"
                            :class="darkMode ? 'text-white' : 'text-gray-900'">Autocheck Chat Center</h3>
                        <p class="mt-4 max-w-sm font-medium leading-relaxed uppercase text-[10px] tracking-[0.3em]"
                           :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Select a conversation to start messaging</p>
                    </div>
                </template>

                <template x-if="selectedUser">
                    <div class="flex flex-col h-full">
                        <!-- Chat Header -->
                        <div class="h-20 md:h-24 px-6 md:px-10 border-b flex items-center justify-between backdrop-blur-xl shrink-0 z-10 transition-colors duration-500"
                             :class="darkMode ? 'border-white/5 bg-[#18191A]/80' : 'border-black/5 bg-white/80'">
                            <div class="flex items-center space-x-4 md:space-x-4">
                                <button @click="showList = true" class="md:hidden p-2 -ml-2 text-blue-500 hover:bg-gray-100 rounded-full transition-all">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <div class="relative">
                                    <div class="h-10 w-10 md:h-12 md:w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-sm md:text-base shadow-lg" x-text="getInitials(selectedUser.name)"></div>
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 md:w-4 md:h-4 bg-green-500 border-2 rounded-full transition-colors duration-500"
                                         :class="darkMode ? 'border-[#242526]' : 'border-white'"></div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-base md:text-lg tracking-tight leading-none truncate" 
                                        :class="darkMode ? 'text-white' : 'text-gray-900'"
                                        x-text="selectedUser.name"></h3>
                                    <p class="text-[11px] font-medium text-gray-500 mt-1">Active now</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 md:space-x-4">
                                <!-- Theme Toggle -->
                                <button @click="darkMode = !darkMode" 
                                        class="p-2.5 rounded-full transition-all duration-300"
                                        :class="darkMode ? 'bg-white/5 text-yellow-400 hover:bg-white/10' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                    <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364-6.364l-.707.707M6.364 17.636l-.707.707M6.364 6.364l.707-.707m11.272 11.272l.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Chat History -->
                        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-1 bg-transparent custom-scrollbar scroll-smooth" id="chat-history">
                            <template x-for="(msg, index) in messages" :key="msg.id">
                                <div class="reveal-message">
                                    <!-- Date Separator -->
                                    <template x-if="shouldShowDate(msg, index)">
                                        <div class="flex justify-center my-8">
                                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wide" x-text="formatDate(msg.created_at)"></span>
                                        </div>
                                    </template>

                                    <div class="flex flex-col group/msg" :class="msg.sender_id === {{ Auth::id() }} ? 'items-end' : 'items-start'">
                                        <div class="flex items-end space-x-2" :class="msg.sender_id === {{ Auth::id() }} ? 'flex-row-reverse space-x-reverse' : ''">
                                            <!-- Avatar for received messages -->
                                            <template x-if="msg.sender_id !== {{ Auth::id() }}">
                                                <div class="h-7 w-7 rounded-full bg-gray-300 flex-shrink-0 mb-1 overflow-hidden">
                                                    <div class="w-full h-full bg-blue-600 flex items-center justify-center text-[8px] text-white font-bold" x-text="getInitials(selectedUser.name)"></div>
                                                </div>
                                            </template>

                                            <div :class="msg.sender_id === {{ Auth::id() }} 
                                                 ? 'bg-[#0084FF] text-white rounded-[1.25rem] rounded-tr-[0.25rem] rounded-br-[0.25rem]' 
                                                 : (darkMode ? 'bg-[#3E4042] text-white' : 'bg-[#E4E6EB] text-gray-900') + ' rounded-[1.25rem] rounded-tl-[0.25rem] rounded-bl-[0.25rem]'"
                                                 class="w-fit max-w-[85%] md:max-w-[65%] px-4 py-2 text-[14px] md:text-[15px] font-normal leading-snug transition-all">
                                                <p x-text="msg.message" class="break-words whitespace-pre-wrap"></p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] mt-1 px-10 opacity-0 group-hover/msg:opacity-100 transition-all" 
                                              :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                                              x-text="formatTime(msg.created_at)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 md:p-6 transition-colors duration-500"
                             :class="darkMode ? 'bg-[#18191A] border-t border-white/5' : 'bg-white border-t border-black/5'">
                            <form @submit.prevent="sendMessage" class="flex items-center space-x-3 max-w-5xl mx-auto">
                                <div class="flex-1 relative">
                                    <input type="text" x-model="newMessage" placeholder="Aa" 
                                           :class="darkMode ? 'bg-[#3E4042] text-white' : 'bg-gray-100 text-gray-900'"
                                           class="w-full border-none rounded-full px-5 py-2.5 focus:ring-0 text-sm font-normal">
                                </div>
                                <button type="submit" 
                                        :disabled="!newMessage.trim()"
                                        class="text-blue-500 disabled:opacity-30 p-2 hover:bg-gray-100 rounded-full transition-all">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
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
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
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
                selectedUser: null,
                showList: true,
                messages: [],
                newMessage: '',
                pollingTimer: null,
                darkMode: true,

                init() {
                    // Initial setup if needed
                },

                get filteredCustomers() {
                    if (!this.search) return this.customers;
                    return this.customers.filter(c => 
                        c.name.toLowerCase().includes(this.search.toLowerCase()) || 
                        c.email.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                selectUser(user) {
                    this.selectedUser = user;
                    this.showList = false;
                    user.unread_count = 0;
                    this.fetchMessages();
                    
                    if (this.pollingTimer) clearInterval(this.pollingTimer);
                    this.pollingTimer = setInterval(() => this.fetchMessages(true), 3000);
                },

                fetchMessages(silent = false) {
                    if (!this.selectedUser) return;

                    fetch(`/admin/chat/${this.selectedUser.id}`)
                        .then(res => res.json())
                        .then(data => {
                            const oldLength = this.messages.length;
                            this.messages = data.messages;
                            
                            if (this.messages.length > oldLength) {
                                this.scrollToBottom();
                            }
                        })
                        .catch(err => console.error(err));
                },

                sendMessage() {
                    if (!this.newMessage.trim() || !this.selectedUser) return;

                    const body = {
                        message: this.newMessage,
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
                        this.messages.push(msg);
                        this.newMessage = '';
                        this.scrollToBottom();
                    })
                    .catch(err => console.error(err));
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
