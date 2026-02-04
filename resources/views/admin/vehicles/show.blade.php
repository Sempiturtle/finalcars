<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Vehicle Details</h1>
                <p class="text-gray-500 font-medium mt-1">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl text-white bg-autocheck-red hover:bg-red-700 transition-all shadow-lg shadow-red-500/30">
                    Edit Vehicle
                </a>
                <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-bold text-gray-600 hover:text-autocheck-red transition-all">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Fleet
                </a>
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- General Info -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">General Information</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Make</p>
                                    <p class="text-lg font-black text-gray-900">{{ $vehicle->make }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Model</p>
                                    <p class="text-lg font-black text-gray-900">{{ $vehicle->model }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Year</p>
                                    <p class="text-lg font-black text-gray-900">{{ $vehicle->year }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Color</p>
                                    <p class="text-lg font-black text-gray-900">{{ $vehicle->color ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Ownership</h3>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Owner Name</p>
                                <p class="text-lg font-black text-gray-900">{{ $vehicle->owner_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Dates -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Status & Important Dates</h3>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Current Status</p>
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] {{ 
                                        match($vehicle->status) {
                                            'active' => 'bg-green-50 text-green-600',
                                            'maintenance' => 'bg-blue-50 text-blue-600',
                                            'overdue' => 'bg-red-50 text-autocheck-red',
                                            default => 'bg-gray-50 text-gray-600',
                                        }
                                    }}">
                                        {{ $vehicle->status }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Registration</p>
                                        <p class="text-lg font-black text-gray-900">{{ $vehicle->registration_date?->format('m/d/Y') ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Next Service</p>
                                        <p class="text-lg font-black text-autocheck-red">{{ $vehicle->next_service_date?->format('m/d/Y') ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Identificaton</h3>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Plate Number</p>
                                <div class="inline-block px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl">
                                    <p class="text-xl font-black text-gray-900 italic tracking-widest">{{ $vehicle->plate_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Service Records Section -->
                @if(!empty($vehicle->services) && count($vehicle->services) > 0)
                <div class="mt-12 pt-12 border-t border-gray-100">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight mb-6">Service Records</h3>
                    <div class="bg-gray-50 rounded-3xl overflow-hidden border border-gray-100">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-8 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Service Type</th>
                                    <th class="px-8 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($vehicle->services as $service)
                                <tr>
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $service['type'] }}</p>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <p class="text-sm font-bold text-gray-600">₱{{ number_format($service['cost'], 2) }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100/50">
                                <tr>
                                    <td class="px-8 py-4 text-right">
                                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Cost</span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span class="text-lg font-black text-autocheck-red">₱{{ number_format($vehicle->total_cost ?? 0, 2) }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif
            </div>
</x-admin-layout>
