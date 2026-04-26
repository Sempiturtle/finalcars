<!-- Quick Verify Modal -->
<div x-show="showVerifyModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-cloak>
    
    <div @click.away="showVerifyModal = false" 
         class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl transform transition-all border border-gray-100"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 translate-y-4"
         x-transition:enter-end="scale-100 translate-y-0">
        
        <form :action="`/admin/vehicles/${currentVehicleId}/quick-verify`" method="POST">
            @csrf
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Verify <span class="text-autocheck-red italic" x-text="currentPlate"></span></h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Select completed services for verification.</p>
                    </div>
                    <button type="button" @click="showVerifyModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Services List -->
                <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar pr-2 mb-6">
                    <template x-for="service in pendingServices" :key="service.original_index">
                        <label class="flex items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent transition-all cursor-pointer hover:border-green-100 group"
                               :class="{'border-green-500 bg-green-50': selectedServices.includes(String(service.original_index))}">
                            <input type="checkbox" name="completed_indexes[]" :value="String(service.original_index)" x-model="selectedServices" class="hidden">
                            <div class="w-6 h-6 rounded-lg border-2 border-gray-200 flex items-center justify-center transition-all group-hover:border-green-400 shrink-0"
                                 :class="{'bg-green-500 border-green-500': selectedServices.includes(String(service.original_index))}">
                                <svg x-show="selectedServices.includes(String(service.original_index))" class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-black text-gray-900 uppercase" x-text="service.type"></p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5" x-text="`${service.date || 'No Date'} • ₱${parseFloat(service.cost).toLocaleString()}`"></p>
                            </div>
                        </label>
                    </template>
                </div>

                <!-- Verification Notes -->
                <div class="space-y-2 mb-8">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Internal Audit Notes</label>
                    <textarea name="notes" x-model="notes" rows="3" 
                              class="block w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl text-[11px] font-bold focus:bg-white focus:ring-2 focus:ring-autocheck-red/20 focus:border-autocheck-red transition-all"
                              placeholder="Explain the work done or any findings..."></textarea>
                </div>

                <!-- Footer -->
                <div class="flex items-center gap-3">
                    <button type="button" @click="showVerifyModal = false" 
                            class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit" :disabled="selectedServices.length === 0"
                            class="flex-1 px-6 py-4 bg-green-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-green-600 transition-all shadow-lg shadow-green-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        Verify Now
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quick Start Modal -->
<div x-show="showStartModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-cloak>
    
    <div @click.away="showStartModal = false" 
         class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl transform transition-all border border-gray-100"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 translate-y-4"
         x-transition:enter-end="scale-100 translate-y-0">
        
        <form :action="`/admin/vehicles/${currentVehicleId}/quick-start`" method="POST">
            @csrf
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Start Work <span class="text-autocheck-red italic" x-text="currentPlate"></span></h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Select services to move to 'In Progress'.</p>
                    </div>
                    <button type="button" @click="showStartModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Services List -->
                <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar pr-2 mb-8">
                    <template x-for="service in scheduledServices" :key="service.original_index">
                        <label class="flex items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent transition-all cursor-pointer hover:border-blue-100 group"
                               :class="{'border-blue-500 bg-blue-50': selectedServices.includes(String(service.original_index))}">
                            <input type="checkbox" name="start_indexes[]" :value="String(service.original_index)" x-model="selectedServices" class="hidden">
                            <div class="w-6 h-6 rounded-lg border-2 border-gray-200 flex items-center justify-center transition-all group-hover:border-blue-400 shrink-0"
                                 :class="{'bg-blue-500 border-blue-500': selectedServices.includes(String(service.original_index))}">
                                <svg x-show="selectedServices.includes(String(service.original_index))" class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs font-black text-gray-900 uppercase" x-text="service.type"></p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5" x-text="`${service.date || 'No Date'} • ${service.mode}`"></p>
                            </div>
                        </label>
                    </template>
                </div>

                <!-- Footer -->
                <div class="flex items-center gap-3">
                    <button type="button" @click="showStartModal = false" 
                            class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit" :disabled="selectedServices.length === 0"
                            class="flex-1 px-6 py-4 bg-blue-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        Start Selected
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
