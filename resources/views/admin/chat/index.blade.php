<x-admin-layout>
    <div class="h-[calc(100vh-12rem)] bg-white rounded-3xl shadow-xl overflow-hidden flex border border-gray-100" 
         x-data="chatSystem()" 
         x-init="init()">
        
        <!-- Sidebar: User List -->
        <div class="w-80 border-r border-gray-100 flex flex-col bg-gray-50/30">
            <div class="p-6 border-b border-gray-100 bg-white">
                <h2 class="text-xl font-black text-gray-900 flex items-center">
                    <span class="w-2 h-8 bg-autocheck-red rounded-full mr-3"></span>
                    Messages
                </h2>
                <div class="mt-4 relative">
                    <input type="text" x-model="search" placeholder="Search customers..." 
                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-autocheck-red/20 transition-all">
                    <svg class="h-4 w-4 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <template x-for="user in filteredCustomers" :key="user.id">
                    <button @click="selectUser(user)" 
                            :class="selectedUser && selectedUser.id === user.id ? 'bg-white shadow-lg border-gray-100 ring-1 ring-gray-100' : 'hover:bg-white/50 border-transparent'"
                            class="w-full p-4 rounded-2xl border transition-all duration-200 text-left group">
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-2xl bg-autocheck-red flex items-center justify-center text-white font-black text-lg shadow-lg shadow-red-500/10 group-hover:scale-105 transition-transform"
                                 x-text="user.name.charAt(0)">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-gray-900 truncate text-sm" x-text="user.name"></h3>
                                    <div class="flex items-center space-x-1">
                                        <template x-if="user.unread_count > 0">
                                            <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full" x-text="user.unread_count"></span>
                                        </template>
                                        <span class="text-[10px] font-black uppercase tracking-tighter text-autocheck-red/60" x-text="user.role"></span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-1" x-text="user.email"></p>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col bg-white">
            <template x-if="!selectedUser">
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">No Chat Selected</h3>
                    <p class="mt-2 text-gray-500 max-w-xs">Select a customer from the left sidebar to start or continue a conversation.</p>
                </div>
            </template>

            <template x-if="selectedUser">
                <div class="flex flex-col h-full">
                    <!-- Chat Header -->
                    <div class="h-20 px-8 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                        <div class="flex items-center space-x-4">
                            <div class="h-10 w-10 rounded-xl bg-autocheck-red flex items-center justify-center text-white font-bold" x-text="selectedUser.name.charAt(0)"></div>
                            <div>
                                <h3 class="font-bold text-gray-900 leading-none" x-text="selectedUser.name"></h3>
                                <span class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1 inline-block">Online History</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Chat History -->
                    <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-gray-50/20" id="chat-history">
                        <template x-for="(msg, index) in messages" :key="msg.id">
                            <div>
                                <!-- Date Separator -->
                                <template x-if="shouldShowDate(msg, index)">
                                    <div class="flex justify-center my-8">
                                        <span class="px-4 py-1.5 bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-full" x-text="formatDate(msg.created_at)"></span>
                                    </div>
                                </template>

                                <div class="flex flex-col" :class="msg.sender_id === {{ Auth::id() }} ? 'items-end' : 'items-start'">
                                    <div :class="msg.sender_id === {{ Auth::id() }} ? 'bg-autocheck-red text-white rounded-t-3xl rounded-bl-3xl shadow-lg shadow-red-500/10' : 'bg-white text-gray-800 rounded-t-3xl rounded-br-3xl shadow-sm border border-gray-100'"
                                         class="max-w-[70%] px-6 py-4 text-sm font-medium leading-relaxed">
                                        <p x-text="msg.message"></p>
                                    </div>
                                    <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2 px-2" x-text="formatTime(msg.created_at)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-6 bg-white border-t border-gray-100">
                        <form @submit.prevent="sendMessage" class="flex space-x-4">
                            <input type="text" x-model="newMessage" placeholder="Type your message..." 
                                   class="flex-1 bg-gray-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-autocheck-red/20 transition-all font-medium text-sm">
                            <button type="submit" 
                                    :disabled="!newMessage.trim()"
                                    class="bg-autocheck-red text-white p-4 rounded-2xl shadow-lg shadow-red-500/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:scale-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function chatSystem() {
            return {
                customers: @json($customers),
                search: '',
                selectedUser: null,
                messages: [],
                newMessage: '',
                pollingTimer: null,

                init() {
                    // No initial actions
                },

                get filteredCustomers() {
                    if (!this.search) return this.customers;
                    return this.customers.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()) || c.email.toLowerCase().includes(this.search.toLowerCase()));
                },

                selectUser(user) {
                    this.selectedUser = user;
                    user.unread_count = 0; // Visual immediate clear
                    this.fetchMessages();
                    
                    // Stop previous polling and start new one
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
