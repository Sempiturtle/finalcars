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

                                                {{-- Modal Header --}}
                                                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                            @if($log->action === 'created') bg-green-50 text-green-600
                                                            @elseif($log->action === 'updated') bg-blue-50 text-blue-600
                                                            @elseif($log->action === 'deleted') bg-red-50 text-red-600
                                                            @else bg-gray-50 text-gray-500 @endif">
                                                            {{ $log->action }}
                                                        </span>
                                                        <div>
                                                            <h3 class="text-sm font-black text-gray-900 tracking-tight">{{ class_basename($log->model_type) }} <span class="text-gray-300">#{{ $log->model_id }}</span></h3>
                                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $log->created_at->format('F j, Y — h:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    <button @click="open = false" class="text-gray-300 hover:text-gray-600 transition-colors p-1">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>

                                                {{-- Modal Body --}}
                                                <div class="max-h-[55vh] overflow-y-auto custom-scrollbar">
                                                    @php
                                                        // Helper: humanize snake_case keys
                                                        $label = fn($key) => ucwords(str_replace('_', ' ', $key));

                                                        // Helper: format a raw value for display
                                                        $fmt = function($val) {
                                                            if (is_null($val))    return '—';
                                                            if (is_bool($val))    return $val ? 'Yes' : 'No';
                                                            if (is_array($val))   return '[ ' . count($val) . ' item' . (count($val) !== 1 ? 's' : '') . ' ]';
                                                            if (is_numeric($val) && strlen((string)$val) > 4)
                                                                return $val; // keep IDs / costs as-is
                                                            // Detect ISO date strings
                                                            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $val)) {
                                                                try { return \Carbon\Carbon::parse($val)->format('F j, Y'); } catch(\Exception $e) {}
                                                            }
                                                            // Humanize known slugs
                                                            return ucwords(str_replace(['_', '-'], ' ', $val));
                                                        };
                                                    @endphp

                                                    {{-- UPDATED: side-by-side diff --}}
                                                    @if($log->action === 'updated' && ($log->old_values || $log->new_values))
                                                        <table class="w-full text-left">
                                                            <thead>
                                                                <tr class="bg-gray-50/70 border-b border-gray-100">
                                                                    <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest w-1/3">Field</th>
                                                                    <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest w-1/3">Before</th>
                                                                    <th class="px-5 py-3 text-[9px] font-black text-blue-400 uppercase tracking-widest w-1/3">After</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-50">
                                                                @foreach(array_keys((array)$log->new_values) as $key)
                                                                    <tr class="hover:bg-blue-50/20">
                                                                        <td class="px-5 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider">{{ $label($key) }}</td>
                                                                        <td class="px-5 py-3 text-[11px] font-bold text-gray-400 line-through italic">
                                                                            {{ $fmt(($log->old_values)[$key] ?? null) }}
                                                                        </td>
                                                                        <td class="px-5 py-3 text-[11px] font-black text-blue-700">
                                                                            {{ $fmt(($log->new_values)[$key] ?? null) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    {{-- CREATED: new values card --}}
                                                    @elseif($log->action === 'created' && $log->new_values)
                                                        <table class="w-full text-left">
                                                            <thead>
                                                                <tr class="bg-green-50/50 border-b border-gray-100">
                                                                    <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest w-1/3">Field</th>
                                                                    <th class="px-5 py-3 text-[9px] font-black text-green-500 uppercase tracking-widest">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-50">
                                                                @foreach((array)$log->new_values as $key => $val)
                                                                    <tr class="hover:bg-green-50/20">
                                                                        <td class="px-5 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider">{{ $label($key) }}</td>
                                                                        <td class="px-5 py-3 text-[11px] font-black text-green-700">
                                                                            @if(is_array($val))
                                                                                <span class="text-gray-400 italic text-[10px]">{{ count($val) }} item{{ count($val) !== 1 ? 's' : '' }}</span>
                                                                            @else
                                                                                {{ $fmt($val) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    {{-- DELETED: snapshot card --}}
                                                    @elseif($log->action === 'deleted' && $log->old_values)
                                                        <table class="w-full text-left">
                                                            <thead>
                                                                <tr class="bg-red-50/40 border-b border-gray-100">
                                                                    <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest w-1/3">Field</th>
                                                                    <th class="px-5 py-3 text-[9px] font-black text-red-400 uppercase tracking-widest">Removed Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-50">
                                                                @foreach((array)$log->old_values as $key => $val)
                                                                    <tr class="hover:bg-red-50/20">
                                                                        <td class="px-5 py-3 text-[10px] font-black text-gray-500 uppercase tracking-wider">{{ $label($key) }}</td>
                                                                        <td class="px-5 py-3 text-[11px] font-bold text-red-500 line-through italic">
                                                                            @if(is_array($val))
                                                                                <span class="no-underline not-italic text-gray-400 text-[10px]">{{ count($val) }} item{{ count($val) !== 1 ? 's' : '' }}</span>
                                                                            @else
                                                                                {{ $fmt($val) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    @else
                                                        <div class="p-10 text-center">
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No payload data recorded</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Footer: user agent --}}
                                                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                                    <p class="text-[9px] font-bold text-gray-300 truncate max-w-xs italic" title="{{ $log->user_agent }}">
                                                        {{ $log->user_agent ? Str::limit($log->user_agent, 60) : 'N/A' }}
                                                    </p>
                                                    <button @click="open = false" class="px-5 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-700 transition-all">Close</button>
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
