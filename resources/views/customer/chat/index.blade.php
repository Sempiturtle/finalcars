<x-customer-layout>
    <div class="fixed inset-0 z-[100] flex flex-col transition-colors duration-500 overflow-hidden"
         :class="darkMode ? 'bg-[#18191A]' : 'bg-[#F0F2F5]'"
         x-data="customerChat()" 
         x-init="init()">
        
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <!-- Chat Header -->
            <div class="h-20 md:h-24 px-6 md:px-10 border-b flex items-center justify-between backdrop-blur-xl shrink-0 z-10 transition-colors duration-500"
                 :class="darkMode ? 'border-white/5 bg-[#18191A]/80' : 'border-black/5 bg-white/80'">
                <div class="flex items-center space-x-4 md:space-x-5">
                    <div class="flex items-center space-x-2 mr-2">
                        <a href="{{ route('customer.dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors" :class="darkMode ? 'text-gray-400 hover:bg-white/10' : 'text-gray-600'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                    </div>
                    <div class="relative">
                        <div class="h-10 w-10 md:h-12 md:w-12 rounded-full bg-autocheck-red flex items-center justify-center text-white font-black text-sm md:text-base shadow-lg shadow-red-500/20 animate-pulse-slow">AS</div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 md:w-4 md:h-4 border-2 rounded-full transition-colors duration-500"
                             :class="[darkMode ? 'border-[#18191A]' : 'border-white', adminStatus?.is_online ? 'bg-green-500' : 'bg-gray-400']"></div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base md:text-lg tracking-tight leading-none"
                            :class="darkMode ? 'text-white' : 'text-gray-900'">AutoCheck Support</h3>
                        <p class="text-[11px] font-medium text-gray-500 mt-1" x-text="adminStatus?.is_online ? 'Active now' : 'Offline'"></p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 md:space-x-6 text-right">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode" 
                            class="p-2.5 rounded-full transition-all duration-300"
                            :class="darkMode ? 'bg-white/5 text-yellow-400 hover:bg-white/10' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364-6.364l-.707.707M6.364 17.636l-.707.707M6.364 6.364l.707-.707m11.272 11.272l.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                    <div class="h-10 w-[1px] hidden md:block" :class="darkMode ? 'bg-white/10' : 'bg-black/5'"></div>
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
                                    <div class="h-7 w-7 rounded-full bg-autocheck-red flex-shrink-0 mb-1 overflow-hidden flex items-center justify-center text-[8px] text-white font-bold">AS</div>
                                </template>

                                <div :class="msg.sender_id === {{ Auth::id() }} 
                                     ? 'bg-autocheck-red text-white rounded-[1.25rem] rounded-tr-[0.25rem] rounded-br-[0.25rem]' 
                                     : (darkMode ? 'bg-[#3E4042] text-white' : 'bg-[#E4E6EB] text-gray-900') + ' rounded-[1.25rem] rounded-tl-[0.25rem] rounded-bl-[0.25rem]'"
                                     class="w-fit min-w-[50px] max-w-[85%] md:max-w-[70%] px-4 py-2 text-[14px] md:text-[15px] font-normal leading-snug transition-all">
                                    <p x-text="msg.message" class="break-words whitespace-pre-wrap text-left"></p>
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
                            :disabled="!newMessage.trim() || sending"
                            class="text-autocheck-red disabled:opacity-30 p-2 hover:bg-gray-100 rounded-full transition-all">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
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
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(0.98); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
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
        function customerChat() {
            return {
                messages: [],
                newMessage: '',
                sending: false,
                pollingTimer: null,
                darkMode: true,
                adminStatus: null,

                init() {
                    this.fetchMessages();
                    
                    // Smart Polling: Only poll when tab is active
                    this.startPolling();
                    
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.stopPolling();
                        } else {
                            this.fetchMessages(); // Immediate catch-up
                            this.startPolling();
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

                fetchMessages(silent = false) {
                    fetch('/customer/chat/fetch')
                        .then(res => res.json())
                        .then(data => {
                            const oldLength = this.messages.length;
                            this.messages = data;
                            
                            if (this.messages.length > oldLength) {
                                this.scrollToBottom();
                            }
                        })
                        .catch(err => console.error(err));

                    // Also fetch admin status
                    fetch('/chat/status')
                        .then(res => res.json())
                        .then(data => {
                            this.adminStatus = data;
                        })
                        .catch(err => console.error(err));
                },

                sendMessage() {
                    if (!this.newMessage.trim() || this.sending) return;

                    this.sending = true;
                    const body = {
                        message: this.newMessage
                    };

                    fetch('/customer/chat/send', {
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
                        this.sending = false;
                        this.scrollToBottom();
                    })
                    .catch(err => {
                        console.error(err);
                        this.sending = false;
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
</x-customer-layout>
