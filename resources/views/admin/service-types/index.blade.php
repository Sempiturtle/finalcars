<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Manage Service <span class="text-autocheck-red">Types</span></h1>
                <p class="text-gray-500 font-medium mt-1">Configure available service options for the vehicle fleet.</p>
            </div>
            
            <button 
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'create-service-type')"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-lg shadow-red-500/30"
            >
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Service Type
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center shadow-sm">
                <svg class="h-5 w-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-autocheck-red px-6 py-4 rounded-2xl flex flex-col shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 mr-3 text-autocheck-red" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm font-bold">Please check the errors below.</span>
                </div>
                <ul class="list-disc ml-8 text-xs font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Service Name</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Description</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Session Cost</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Earn Points</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Promo Cost</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($serviceTypes as $type)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 px-8">
                                    <span class="text-sm font-black text-gray-900 group-hover:text-autocheck-red transition-colors">{{ $type->name }}</span>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="text-sm font-medium text-gray-500">{{ $type->description ?: '—' }}</span>
                                <td class="py-6 px-8 text-center bg-gray-50/10">
                                    <span class="text-xs font-black text-gray-700">₱{{ number_format($type->base_cost, 2) }}</span>
                                </td>
                                <td class="py-6 px-8 text-center bg-green-50/20">
                                    <span class="text-xs font-black text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase tracking-widest">+{{ $type->points_awarded }} PTS</span>
                                </td>
                                <td class="py-6 px-8 text-center bg-autocheck-red/5">
                                    <span class="text-xs font-black text-autocheck-red bg-red-50 px-3 py-1 rounded-full uppercase tracking-widest">{{ $type->promo_points_cost }} PTS</span>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Edit Button -->
                                        <button 
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-service-type-{{ $type->id }}')"
                                            class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all"
                                            title="Edit"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <button 
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-service-type-{{ $type->id }}')"
                                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                            title="Delete"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-16 text-center text-gray-400 font-medium">
                                    No service types found. Create one to get started!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-service-type" focusable>
        <form method="post" action="{{ route('admin.service-types.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-black text-gray-900 mb-6">Create New Service Type</h2>
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Service Name</label>
                    <input type="text" id="name" name="name" required
                        class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                        placeholder="e.g. Battery Replacement" value="{{ old('name') }}">
                </div>
                <div>
                    <label for="description" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3"
                        class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                        placeholder="Detail about the service...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="base_cost" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Base Cost (₱)</label>
                    <input type="number" min="0" step="0.01" id="base_cost" name="base_cost" required
                        class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                        placeholder="e.g. 1500.00" value="{{ old('base_cost', 0.00) }}">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="points_awarded" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Earning Points</label>
                        <input type="number" min="0" id="points_awarded" name="points_awarded" required
                            class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. 50" value="{{ old('points_awarded', 50) }}">
                    </div>
                    <div>
                        <label for="promo_points_cost" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Redeem Promo Cost</label>
                        <input type="number" min="0" id="promo_points_cost" name="promo_points_cost" required
                            class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            placeholder="e.g. 300" value="{{ old('promo_points_cost', 300) }}">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 border border-gray-200 text-sm font-bold rounded-2xl text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                <button type="submit" class="px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-green-500 hover:bg-green-600 transition-all shadow-lg shadow-green-500/30">Save Type</button>
            </div>
        </form>
    </x-modal>

    <!-- Modals per type -->
    @foreach($serviceTypes as $type)
        <!-- Edit Modal -->
        <x-modal name="edit-service-type-{{ $type->id }}" focusable>
            <form method="post" action="{{ route('admin.service-types.update', $type) }}" class="p-8">
                @csrf
                @method('PUT')
                <h2 class="text-xl font-black text-gray-900 mb-6">Edit Service Type</h2>
                <div class="space-y-6">
                    <div>
                        <label for="edit_name_{{ $type->id }}" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Service Name</label>
                        <input type="text" id="edit_name_{{ $type->id }}" name="name" required
                            class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            value="{{ old('name', $type->name) }}">
                    </div>
                    <div>
                        <label for="edit_desc_{{ $type->id }}" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Description (Optional)</label>
                        <textarea id="edit_desc_{{ $type->id }}" name="description" rows="3"
                            class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all">{{ old('description', $type->description) }}</textarea>
                    </div>
                    <div>
                        <label for="edit_base_cost_{{ $type->id }}" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Base Cost (₱)</label>
                        <input type="number" min="0" step="0.01" id="edit_base_cost_{{ $type->id }}" name="base_cost" required
                            class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                            value="{{ old('base_cost', $type->base_cost) }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_points_{{ $type->id }}" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Earning Points</label>
                            <input type="number" min="0" id="edit_points_{{ $type->id }}" name="points_awarded" required
                                class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                                value="{{ old('points_awarded', $type->points_awarded) }}">
                        </div>
                        <div>
                            <label for="edit_promo_cost_{{ $type->id }}" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Redeem Promo Cost</label>
                            <input type="number" min="0" id="edit_promo_cost_{{ $type->id }}" name="promo_points_cost" required
                                class="block w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                                value="{{ old('promo_points_cost', $type->promo_points_cost) }}">
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 border border-gray-200 text-sm font-bold rounded-2xl text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-blue-500 hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/30">Update Type</button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Modal -->
        <x-modal name="delete-service-type-{{ $type->id }}" focusable>
            <form method="post" action="{{ route('admin.service-types.destroy', $type) }}" class="p-8">
                @csrf
                @method('DELETE')
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <svg class="h-6 w-6 text-autocheck-red" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900">Delete Service Type</h2>
                        <p class="text-sm font-medium text-gray-500">Are you sure you want to delete "{{ $type->name }}"?</p>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 border border-gray-200 text-sm font-bold rounded-2xl text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-lg shadow-red-500/30">Confirm Delete</button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-admin-layout>
