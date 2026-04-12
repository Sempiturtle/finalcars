<x-customer-layout>
    <div class="h-[calc(100vh-12rem)] bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col border border-gray-100" 
         x-data="customerChat()" 
         x-init="init()">
        
        <!-- Chat Header -->
        <div class="h-20 px-8 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center space-x-4">
                <div class="h-10 w-10 rounded-xl bg-autocheck-red flex items-center justify-center text-white font-bold">A</div>
                <div>
                    <h3 class="font-bold text-gray-900 leading-none">AutoCheck Admin</h3>
                    <span class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1 inline-block">Support Agent</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Response Time</span>
                    <span class="text-xs font-bold text-gray-900 line-height-tight">Typically Instant</span>
                </div>
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
                             class="max-w-[85%] sm:max-w-[70%] px-6 py-4 text-sm font-medium leading-relaxed">
                            <p x-text="msg.message"></p>
                        </div>
                        <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2 px-2" x-text="formatTime(msg.created_at)"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Chat Input -->
        <div class="p-4 md:p-6 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex space-x-2 md:space-x-4">
                <input type="text" x-model="newMessage" placeholder="Type your message to support..." 
                       class="flex-1 bg-gray-50 border-none rounded-2xl px-4 py-3 md:px-6 md:py-4 focus:ring-2 focus:ring-autocheck-red/20 transition-all font-medium text-sm">
                <button type="submit" 
                        :disabled="!newMessage.trim() || sending"
                        class="bg-autocheck-red text-white p-3 md:p-4 rounded-2xl shadow-lg shadow-red-500/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:scale-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>

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
