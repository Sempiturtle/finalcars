<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">System <span class="text-autocheck-red italic">Audit Logs</span></h1>
                <p class="text-[11px] text-gray-400 font-bold mt-0.5 uppercase tracking-widest italic">Complete traceability of all system actions.</p>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50 bg-gray-50/50">
                            <th class="px-6 py-3">Timestamp</th>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Action</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">IP Address</th>
                            <th class="px-6 py-3 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <span class="text-[11px] font-black text-gray-900">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span class="block text-[9px] font-bold text-gray-400">{{ $log->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 font-bold text-[10px] uppercase">
                                            {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">{{ $log->user ? $log->user->name : 'System' }}</p>
                                            <p class="text-[9px] text-gray-400 uppercase font-black">{{ $log->user ? $log->user->role : 'Automated' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest
                                        @if($log->action === 'created') bg-green-50 text-green-600
                                        @elseif($log->action === 'updated') bg-blue-50 text-blue-600
                                        @elseif($log->action === 'deleted') bg-red-50 text-red-600
                                        @else bg-gray-50 text-gray-600 @endif">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-[11px] font-bold text-gray-600">{{ $log->description }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium italic">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-[10px] font-mono text-gray-400">{{ $log->ip_address ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-3 text-right" x-data="{ open: false }">
                                    <button @click="open = true" class="text-[10px] font-black text-autocheck-red hover:underline uppercase tracking-widest italic">
                                        View Data
                                    </button>

                                    <!-- Data Modal -->
                                    <template x-if="open">
                                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" @click.self="open = false">
                                            <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl border border-gray-100 overflow-hidden animate-fade-in-up">
                                                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                                                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Audit Payload Details</h3>
                                                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                                    <div class="grid grid-cols-2 gap-6">
                                                        <div>
                                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Before Change</h4>
                                                            <div class="bg-gray-50 rounded-xl p-4 overflow-x-auto">
                                                                <pre class="text-[10px] font-mono text-gray-600">@json($log->old_values, JSON_PRETTY_PRINT)</pre>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">After Change</h4>
                                                            <div class="bg-blue-50/30 rounded-xl p-4 overflow-x-auto">
                                                                <pre class="text-[10px] font-mono text-blue-600">@json($log->new_values, JSON_PRETTY_PRINT)</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pt-4 border-t border-gray-50">
                                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">User Agent</p>
                                                        <p class="text-[10px] text-gray-500 italic">{{ $log->user_agent }}</p>
                                                    </div>
                                                </div>
                                                <div class="p-4 bg-gray-50 text-right">
                                                    <button @click="open = false" class="px-6 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="bg-gray-50 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">No audit logs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
