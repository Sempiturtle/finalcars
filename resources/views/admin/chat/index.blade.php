<x-admin-layout>
    <div class="h-[calc(100vh-8rem)] md:h-[calc(100vh-10rem)] bg-[#0F172A] mt-0 md:-mt-10 mx-0 md:-mx-6 px-4 md:px-6 py-4 md:py-10 flex flex-col space-y-4 md:space-y-6 overflow-hidden">
        
        <div class="flex-1 flex glass rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl relative" 
             x-data="chatSystem()" 
             x-init="init()">
            
            <!-- Sidebar: User List -->
            <div class="w-full md:w-96 border-r border-white/5 flex flex-col bg-white/5 backdrop-blur-2xl z-20"
                 :class="{ 'hidden md:flex': !showList && selectedUser }">
                <div class="p-6 md:p-8 border-b border-white/5">
                    <h2 class="text-lg md:text-xl font-black text-white flex items-center uppercase tracking-tighter">
                        <span class="w-1.5 md:w-2 h-6 md:h-8 bg-autocheck-red rounded-full mr-3 md:mr-4 shadow-lg shadow-red-500/50"></span>
                        Communications
                    </h2>
                    <div class="mt-4 md:mt-6 relative group">
                        <input type="text" x-model="search" placeholder="Search..." 
                               class="w-full pl-10 md:pl-12 pr-4 py-3 md:py-4 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl text-sm text-white focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all placeholder-gray-500 group-hover:bg-white/10">
                        <svg class="h-4 w-4 md:h-5 md:w-5 absolute left-4 top-3.5 md:top-4 text-gray-500 group-hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                    <template x-for="user in filteredCustomers" :key="user.id">
                        <button @click="selectUser(user)" 
                                :class="selectedUser && selectedUser.id === user.id ? 'bg-white/10 border-white/20 shadow-2xl ring-1 ring-white/10' : 'hover:bg-white/5 border-transparent opacity-70 hover:opacity-100'"
                                class="w-full p-5 rounded-[2rem] border transition-all duration-300 text-left group relative overflow-hidden">
                            <div class="flex items-center space-x-4 relative z-10">
                                <div class="relative">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-autocheck-red to-red-700 flex items-center justify-center text-white font-black text-xl shadow-xl shadow-red-500/20 group-hover:scale-110 transition-transform"
                                         x-text="user.name.charAt(0)">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#0F172A] rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="font-black text-white truncate text-sm uppercase tracking-tight" x-text="user.name"></h3>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-autocheck-red/80" x-text="user.role"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-500 truncate" x-text="user.email"></p>
                                        <template x-if="user.unread_count > 0">
                                            <span class="bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded-lg animate-bounce" x-text="user.unread_count"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-r from-autocheck-red/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col bg-white/2 backdrop-blur-3xl"
                 :class="{ 'hidden md:flex': showList && selectedUser, 'flex': !showList && selectedUser }">
                <template x-if="!selectedUser">
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                        <div class="w-32 h-32 bg-white/5 rounded-full flex items-center justify-center mb-8 relative">
                            <div class="absolute inset-0 bg-autocheck-red rounded-full blur-[50px] opacity-20"></div>
                            <svg class="h-16 w-16 text-gray-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-white uppercase tracking-tighter">Secure Comms</h3>
                        <p class="mt-4 text-gray-500 max-w-sm font-medium leading-relaxed uppercase text-[10px] tracking-[0.3em]">Select a terminal session from the sidebar to engage with clients.</p>
                    </div>
                </template>

                <template x-if="selectedUser">
                    <div class="flex flex-col h-full">
                        <!-- Chat Header -->
                        <div class="h-20 md:h-24 px-6 md:px-10 border-b border-white/5 flex items-center justify-between bg-white/5 backdrop-blur-xl shrink-0 z-10">
                            <div class="flex items-center space-x-4 md:space-x-6">
                                <button @click="showList = true" class="md:hidden p-2.5 -ml-2 text-gray-500 hover:text-white bg-white/5 rounded-xl transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <div class="relative">
                                    <div class="h-10 w-10 md:h-12 md:w-12 rounded-xl md:rounded-2xl bg-autocheck-red flex items-center justify-center text-white font-black text-lg md:text-xl shadow-lg shadow-red-500/20" x-text="selectedUser.name.charAt(0)"></div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-green-500 border-2 md:border-4 border-[#0F172A] rounded-full"></div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-black text-white text-base md:text-lg tracking-tight leading-none uppercase truncate" x-text="selectedUser.name"></h3>
                                    <div class="flex items-center mt-1.5 md:mt-2">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-ping"></span>
                                        <span class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Session</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button class="p-3 bg-white/5 rounded-2xl text-gray-500 hover:text-white hover:bg-white/10 transition-all group">
                                    <svg class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Chat History -->
                        <div class="flex-1 overflow-y-auto p-6 md:p-10 space-y-8 bg-white/[0.01] custom-scrollbar scroll-smooth" id="chat-history">
                            <template x-for="(msg, index) in messages" :key="msg.id">
                                <div class="reveal-message">
                                    <!-- Date Separator -->
                                    <template x-if="shouldShowDate(msg, index)">
                                        <div class="flex justify-center my-12">
                                            <span class="px-6 py-2 bg-white/5 border border-white/10 text-gray-400 text-[9px] font-black uppercase tracking-[0.4em] rounded-full backdrop-blur-md" x-text="formatDate(msg.created_at)"></span>
                                        </div>
                                    </template>

                                    <div class="flex flex-col" :class="msg.sender_id === {{ Auth::id() }} ? 'items-end' : 'items-start'">
                                        <div class="flex items-end space-x-3" :class="msg.sender_id === {{ Auth::id() }} ? 'flex-row-reverse space-x-reverse' : ''">
                                            <div :class="msg.sender_id === {{ Auth::id() }} 
                                                 ? 'bg-gradient-to-br from-autocheck-red to-red-700 text-white rounded-2xl rounded-tr-none shadow-xl shadow-red-500/10' 
                                                 : 'bg-white/5 backdrop-blur-xl text-gray-200 rounded-2xl rounded-tl-none border border-white/10 shadow-lg'"
                                                 class="w-fit max-w-[90%] md:max-w-[70%] px-4 md:px-5 py-2.5 md:py-3 text-sm font-medium leading-relaxed group relative transition-all hover:scale-[1.01]">
                                                <p x-text="msg.message" class="relative z-10 break-words whitespace-pre-wrap text-left"></p>
                                                <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                            </div>
                                        </div>
                                        <span class="text-[8px] md:text-[9px] font-black text-gray-500 uppercase tracking-widest mt-1.5 md:mt-2 px-1 italic opacity-60" x-text="formatTime(msg.created_at)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 md:p-8 bg-white/5 border-t border-white/5 backdrop-blur-2xl">
                            <form @submit.prevent="sendMessage" class="flex space-x-3 md:space-x-4 max-w-5xl mx-auto">
                                <div class="flex-1 relative group">
                                    <input type="text" x-model="newMessage" placeholder="Type response..." 
                                           class="w-full bg-white/5 border border-white/10 rounded-[1.5rem] md:rounded-[2rem] px-5 md:px-8 py-4 md:py-5 focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all font-medium text-sm text-white placeholder-gray-500 group-hover:bg-white/10">
                                </div>
                                <button type="submit" 
                                        :disabled="!newMessage.trim()"
                                        class="bg-autocheck-red text-white px-6 md:px-10 rounded-[1.5rem] md:rounded-[2rem] shadow-2xl shadow-red-500/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:scale-100 flex items-center justify-center group">
                                    <svg class="h-6 w-6 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <style>
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
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
                }
            }
        }
    </script>
</x-admin-layout>
