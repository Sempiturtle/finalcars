<x-customer-layout>
    <div class="h-[calc(100vh-10rem)] bg-[#0F172A] -mt-10 -mx-6 px-6 py-10 flex flex-col space-y-6 overflow-hidden">
        
        <!-- Premium Chat Container -->
        <div class="flex-1 flex flex-col glass rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl relative" 
             x-data="customerChat()" 
             x-init="init()">
            
            <!-- Chat Header -->
            <div class="h-24 px-10 border-b border-white/5 flex items-center justify-between bg-white/5 backdrop-blur-xl shrink-0 z-10">
                <div class="flex items-center space-x-5">
                    <div class="relative">
                        <div class="h-12 w-12 rounded-2xl bg-autocheck-red flex items-center justify-center text-white font-black text-xl shadow-lg shadow-red-500/20 animate-pulse-slow">A</div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#0F172A] rounded-full"></div>
                    </div>
                    <div>
                        <h3 class="font-black text-white text-lg tracking-tight leading-none uppercase">AutoCheck Support</h3>
                        <div class="flex items-center mt-2">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-ping"></span>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Live Agent Online</span>
                        </div>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-6 text-right">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] mb-1">Response Time</span>
                        <span class="text-xs font-black text-white uppercase italic">Typically Instant</span>
                    </div>
                    <div class="h-10 w-[1px] bg-white/10"></div>
                    <button class="p-3 bg-white/5 rounded-2xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Chat History -->
            <div class="flex-1 overflow-y-auto p-6 md:p-10 space-y-8 custom-scrollbar scroll-smooth" id="chat-history">
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
                                <!-- Message Bubble -->
                                <div :class="msg.sender_id === {{ Auth::id() }} 
                                     ? 'bg-gradient-to-br from-autocheck-red to-red-700 text-white rounded-2xl rounded-tr-none shadow-xl shadow-red-500/10' 
                                     : 'bg-white/5 backdrop-blur-xl text-gray-200 rounded-2xl rounded-tl-none border border-white/10 shadow-lg'"
                                     class="w-fit max-w-[85%] sm:max-w-[70%] px-5 py-3 text-sm font-medium leading-relaxed group relative transition-all hover:scale-[1.01]">
                                    <p x-text="msg.message" class="relative z-10 break-words whitespace-pre-wrap text-left"></p>
                                    <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                </div>
                            </div>
                            <!-- Timestamp -->
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest mt-2 px-1 italic opacity-60" x-text="formatTime(msg.created_at)"></span>
                        </div>
                    </div>
                </template>
                
                <!-- Typing Indicator (Simulated/Placeholder) -->
                <div x-show="false" class="flex items-center space-x-2 text-gray-500 text-[10px] font-black uppercase tracking-widest px-4">
                    <span class="animate-bounce">●</span>
                    <span class="animate-bounce [animation-delay:0.2s]">●</span>
                    <span class="animate-bounce [animation-delay:0.4s]">●</span>
                    <span class="ml-2">Agent is typing</span>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-6 md:p-8 bg-white/5 border-t border-white/5 backdrop-blur-2xl">
                <form @submit.prevent="sendMessage" class="flex space-x-4 max-w-6xl mx-auto">
                    <div class="flex-1 relative group">
                        <input type="text" x-model="newMessage" placeholder="Type your message to support..." 
                               class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 focus:ring-4 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all font-medium text-sm text-white placeholder-gray-500 group-hover:bg-white/10">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center space-x-3 text-gray-500">
                            <button type="button" class="hover:text-white transition-colors" title="Attach Files">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" 
                            :disabled="!newMessage.trim() || sending"
                            class="bg-autocheck-red text-white px-10 rounded-[2rem] shadow-2xl shadow-red-500/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:scale-100 flex items-center justify-center group">
                        <span class="mr-3 text-xs font-black uppercase tracking-widest hidden sm:block">Send Message</span>
                        <svg class="h-5 w-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
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

                init() {
                    this.fetchMessages();
                    this.pollingTimer = setInterval(() => this.fetchMessages(true), 3000);
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
                }
            }
        }
    </script>
</x-customer-layout>
