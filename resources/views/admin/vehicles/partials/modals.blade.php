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

<!-- Full Vehicle Info Slide-Over Drawer -->
<div x-show="showViewModal" 
     class="fixed inset-0 z-[110] overflow-hidden" 
     @keydown.escape.window="showViewModal = false"
     x-cloak>
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showViewModal = false"></div>

    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <div class="w-screen max-w-2xl bg-white shadow-2xl flex flex-col border-l border-gray-100 overflow-hidden"
             x-transition:enter="transform transition ease-out duration-300 sm:duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200 sm:duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <template x-if="selectedVehicle">
                <div class="flex flex-col h-full bg-gray-50/50 divide-y divide-gray-100">
                    <!-- Drawer Header -->
                    <div class="p-6 sm:p-8 bg-white flex items-start justify-between gap-4 shrink-0 shadow-sm z-10">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <span class="px-3 py-1 bg-gray-100 text-gray-900 rounded-lg text-xs font-black italic tracking-widest border border-gray-200 shadow-sm" x-text="selectedVehicle.plate_number"></span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm"
                                      :class="{
                                          'bg-green-50 text-green-600 border border-green-100': selectedVehicle.status === 'completed',
                                          'bg-blue-50 text-blue-600 border border-blue-100': selectedVehicle.status === 'in progress',
                                          'bg-amber-50 text-amber-600 border border-amber-100': selectedVehicle.status === 'due today',
                                          'bg-yellow-50 text-yellow-600 border border-yellow-100': selectedVehicle.status === 'scheduled',
                                          'bg-red-50 text-autocheck-red border border-red-100': selectedVehicle.status === 'overdue',
                                          'bg-gray-50 text-gray-600 border border-gray-100': selectedVehicle.status === 'inactive'
                                      }" x-text="selectedVehicle.status"></span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-2 uppercase truncate block" x-text="`${selectedVehicle.make} ${selectedVehicle.model}`"></h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5 truncate block" x-text="`${selectedVehicle.year} • ${selectedVehicle.color}`"></p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a :href="selectedVehicle.edit_url" class="p-2.5 bg-gray-50 text-gray-400 hover:text-autocheck-red hover:bg-red-50 rounded-xl transition-all border border-gray-100 shadow-sm" title="Edit Vehicle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" @click="showViewModal = false" class="p-2.5 bg-gray-50 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all border border-gray-100 shadow-sm focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Drawer Body (Scrollable) -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-8">
                        <!-- Key Analytics Bento Box -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reliability Index</span>
                                <div class="mt-2 flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-gray-900 tracking-tight" x-text="`${selectedVehicle.reliability_index}%`"></span>
                                    <span class="text-[10px] font-bold text-green-500 uppercase">Score</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                                    <div class="bg-green-500 h-full transition-all duration-500" :style="`width: ${selectedVehicle.reliability_index}%`"></div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Health Trend</span>
                                <div class="mt-2 flex items-center gap-1.5 truncate">
                                    <div class="w-2.5 h-2.5 rounded-full shrink-0"
                                         :class="{
                                             'bg-green-500 animate-pulse': selectedVehicle.health_trend === 'Improving',
                                             'bg-blue-500': selectedVehicle.health_trend === 'Stable',
                                             'bg-autocheck-red': selectedVehicle.health_trend === 'Declining'
                                         }"></div>
                                    <span class="text-lg font-black text-gray-900 uppercase tracking-tight truncate" x-text="selectedVehicle.health_trend"></span>
                                </div>
                                <span class="text-[9px] font-bold text-gray-400 uppercase mt-2">AI Trajectory</span>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between truncate">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">Next Service</span>
                                <span class="text-sm font-black text-autocheck-red mt-2 tracking-tight block truncate" x-text="selectedVehicle.next_service_date"></span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase mt-2">Target Date</span>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between truncate">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">Total Cost</span>
                                <span class="text-lg font-black text-gray-900 mt-2 tracking-tight block truncate" x-text="`₱${selectedVehicle.total_cost}`"></span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase mt-2">Lifetime Spent</span>
                            </div>
                        </div>

                        <!-- Ownership & Registration -->
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">Personnel & Registration</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Owner</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-red-50 text-autocheck-red rounded-lg font-black text-xs flex items-center justify-center shrink-0 border border-red-100 shadow-sm" x-text="selectedVehicle.owner_name ? selectedVehicle.owner_name.charAt(0) : 'U'"></div>
                                        <span class="text-sm font-black text-gray-900 truncate block" x-text="selectedVehicle.owner_name"></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Assigned Mechanic</span>
                                    <span class="text-sm font-black text-autocheck-red block truncate" x-text="selectedVehicle.mechanic_name || 'Not Assigned'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Registration Date</span>
                                    <span class="text-sm font-black text-gray-900 block truncate" x-text="selectedVehicle.registration_date"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Next Service Date</span>
                                    <span class="text-sm font-black text-gray-900 block truncate" x-text="selectedVehicle.next_service_date"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Predictive AI & Frequency -->
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4 flex items-center justify-between">
                                <span>AI Predictive Diagnostics</span>
                                <span class="text-[9px] bg-red-50 text-autocheck-red px-2.5 py-0.5 rounded-full font-black tracking-widest border border-red-100">PRO MAX ACTIVE</span>
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Average Service Interval</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-black text-gray-900 tracking-tight" x-text="selectedVehicle.avg_interval"></span>
                                        <span class="text-xs font-bold text-gray-500">days between visits</span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-2">Calculated from historical completed service logs.</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Predicted Next Service</span>
                                    <span class="text-xl font-black text-autocheck-red tracking-tight block" x-text="selectedVehicle.predictive_date"></span>
                                    <p class="text-[11px] text-gray-400 mt-2">AI forecasted maintenance window based on frequency drift.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Service Records -->
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="p-6 sm:p-8 border-b border-gray-50 flex items-center justify-between">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Complete Service Log</h3>
                                <span class="text-xs font-black text-gray-900 tracking-tight" x-text="`${selectedVehicle.services.length} items logged`"></span>
                            </div>
                            <div x-show="selectedVehicle.services.length === 0" class="p-12 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">
                                No service records attached to this vehicle.
                            </div>
                            <div x-show="selectedVehicle.services.length > 0" class="divide-y divide-gray-50">
                                <template x-for="service in selectedVehicle.services" :key="service.type + service.date">
                                    <div class="p-6 hover:bg-gray-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-black text-gray-900 uppercase tracking-tight" x-text="service.type"></span>
                                                <span class="px-2 py-0.5 text-[9px] font-black rounded uppercase tracking-widest"
                                                      :class="{
                                                          'bg-green-50 text-green-600 border border-green-100': service.status === 'completed',
                                                          'bg-blue-50 text-blue-600 border border-blue-100': service.status === 'in progress',
                                                          'bg-yellow-50 text-yellow-600 border border-yellow-100': (!service.status || service.status === 'scheduled')
                                                      }" x-text="service.status || 'scheduled'"></span>
                                            </div>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest" x-text="`${service.date || 'No Date'} • Mode: ${service.mode || 'Walk-in'}`"></p>
                                            <p x-show="service.notes" class="text-xs text-gray-500 italic mt-1" x-text="`Notes: ${service.notes}`"></p>
                                        </div>
                                        <div class="flex items-center sm:flex-col sm:items-end justify-between sm:justify-center gap-1 shrink-0">
                                            <span class="text-sm font-black text-gray-900 tracking-tight" x-text="`₱${parseFloat(service.cost || 0).toLocaleString()}`"></span>
                                            <span x-show="service.points > 0" class="px-2 py-0.5 rounded-full text-[9px] font-black bg-green-50 text-green-600 uppercase tracking-widest border border-green-100" x-text="`+${service.points} pts`"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Drawer Footer -->
                    <div class="p-6 bg-white flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shrink-0 shadow-md z-10">
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                            <a :href="selectedVehicle.show_url" class="inline-flex items-center justify-center px-6 py-3.5 bg-gray-900 text-white hover:bg-autocheck-red rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-gray-900/20 w-full sm:w-auto text-center">
                                <span>Full Details</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <template x-if="selectedVehicle.status === 'completed'">
                                <a :href="`/admin/vehicles/${selectedVehicle.id}/receipt`" target="_blank" class="inline-flex items-center justify-center px-6 py-3.5 bg-amber-500 text-white hover:bg-amber-600 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-amber-500/20 w-full sm:w-auto text-center">
                                    <span>Print Receipt</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </template>
                        </div>
                        <button type="button" @click="showViewModal = false" class="px-6 py-3.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-2xl text-xs font-black uppercase tracking-widest transition-all focus:outline-none w-full sm:w-auto text-center justify-center">
                            Close
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
