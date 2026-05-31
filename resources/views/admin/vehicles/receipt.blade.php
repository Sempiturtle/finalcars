@php
    $displayStatus = $vehicle->calculated_status;
    $progress = $vehicle->service_progress;
    
    // Calculate Loyalty Points & Rewards Discounts
    $claimedRewards = collect();
    $discountTotal = 0;
    
    if ($vehicle->owner && is_array($vehicle->services)) {
        $completedServiceNames = collect($vehicle->services)
            ->where('status', 'completed')
            ->pluck('type')
            ->map(fn($t) => strtolower(trim($t)))
            ->toArray();

        // Get recent rewards claimed by the user that match completed service types
        $claimedRewards = $vehicle->owner->rewards()
            ->wherePivot('claimed_at', '>=', now()->subDays(7)) // claimed recently
            ->get()
            ->filter(function($reward) use ($completedServiceNames) {
                return $reward->serviceType && in_array(strtolower(trim($reward->serviceType->name)), $completedServiceNames);
            });

        // Deduct cost for each matching service reward
        foreach ($claimedRewards as $reward) {
            $service = collect($vehicle->services)
                ->where('status', 'completed')
                ->first(fn($s) => strtolower(trim($s['type'] ?? '')) === strtolower(trim($reward->serviceType->name)));
            
            if ($service) {
                $discountTotal += (float) ($service['cost'] ?? 0);
            }
        }
    }

    $subtotal = collect($vehicle->services ?? [])->where('status', 'completed')->sum(fn($s) => (float)($s['cost'] ?? 0));
    $grandTotal = max(0, $subtotal - $discountTotal);

    // Calculate loyalty points earned on this visit
    $pointsEarned = 0;
    foreach ($vehicle->services ?? [] as $service) {
        if (($service['status'] ?? '') === 'completed') {
            $serviceType = \App\Models\ServiceType::whereRaw('LOWER(name) = ?', [strtolower($service['type'] ?? '')])->first();
            if ($serviceType) {
                $pointsEarned += $serviceType->points_awarded;
            } else {
                $pointsEarned += floor(((float)($service['cost'] ?? 0)) / 10);
            }
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Receipt - {{ $vehicle->plate_number }}</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <!-- html2pdf.js for PDF Download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'autocheck-red': '#E11D48',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 12px !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .print-border {
                border-bottom: 2px solid #E5E7EB !important;
            }
        }
        body {
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="antialiased min-h-screen text-gray-800 py-8 px-4 sm:px-6">

    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Navigation / Action Bar (no-print) -->
        <div class="flex items-center justify-between no-print bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-600 hover:text-autocheck-red transition-all uppercase tracking-widest">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Fleet
            </button>
            <div class="flex items-center space-x-2">
                <button onclick="downloadPDF(event)" class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-xs font-black rounded-xl hover:bg-black transition-all shadow-md uppercase tracking-widest">
                    <svg class="h-4 w-4 mr-1.5 text-autocheck-red animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </button>
                <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 bg-autocheck-red text-white text-xs font-black rounded-xl hover:bg-red-700 transition-all shadow-md shadow-red-500/10 uppercase tracking-widest">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Invoice
                </button>
            </div>
        </div>

        <!-- Official Receipt Card -->
        <div id="receipt-card" class="bg-white rounded-[3rem] border border-gray-100 shadow-sm p-8 sm:p-12 relative overflow-hidden print-card">
            
            <!-- Diagonal Accent Line (no-print) -->
            <div class="absolute top-0 right-0 w-64 h-1.5 bg-autocheck-red no-print"></div>

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-b pb-8 print-border">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Auto<span class="text-autocheck-red italic">Check</span></h1>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider mt-1">Premium Vehicle Care &amp; Maintenance Service</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Invoice Receipt</p>
                    <p class="text-lg font-black text-gray-900 mt-0.5">REC-{{ $vehicle->plate_number }}-{{ date('Ymd') }}</p>
                    <p class="text-xs font-bold text-gray-400 mt-1">Date: {{ date('F j, Y') }}</p>
                </div>
            </div>

            <!-- Client & Vehicle Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-b print-border">
                <!-- Customer info -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Customer Information</h3>
                    <div class="space-y-1 ml-1">
                        <p class="text-sm font-black text-gray-900">{{ $vehicle->owner_name }}</p>
                        <p class="text-xs font-semibold text-gray-500">Contact: {{ $vehicle->owner?->phone ?? 'N/A' }}</p>
                        <p class="text-xs font-semibold text-gray-500">Email: {{ $vehicle->owner?->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Vehicle details -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Vehicle Specifications</h3>
                    <div class="grid grid-cols-2 gap-y-2 gap-x-4 ml-1">
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Plate Number</span>
                            <span class="text-xs font-black text-gray-900 italic tracking-wider">{{ $vehicle->plate_number }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Brand / Model</span>
                            <span class="text-xs font-black text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Color / Year</span>
                            <span class="text-xs font-bold text-gray-700">{{ $vehicle->color ?? 'Standard' }} • {{ $vehicle->year }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Assigned mechanic</span>
                            <span class="text-xs font-black text-autocheck-red">{{ $vehicle->mechanic_name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itemized Completed Services Table -->
            <div class="py-8">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Completed Repair &amp; Diagnostics</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Performed</th>
                                <th class="py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Mode</th>
                                <th class="py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Remarks</th>
                                <th class="py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $hasCompleted = false; @endphp
                            @foreach($vehicle->services ?? [] as $service)
                                @if(($service['status'] ?? '') === 'completed')
                                    @php $hasCompleted = true; @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 text-xs font-black text-gray-900 uppercase">{{ $service['type'] }}</td>
                                        <td class="py-4 text-xs font-semibold text-gray-500 uppercase">{{ $service['mode'] ?? 'Walk-in' }}</td>
                                        <td class="py-4 text-xs font-bold text-gray-400 italic">{{ $service['notes'] ?? 'Diagnostics passed successfully.' }}</td>
                                        <td class="py-4 text-xs font-black text-gray-900 text-right">₱{{ number_format($service['cost'] ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach

                            @if(!$hasCompleted)
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-xs font-bold text-gray-400 uppercase italic">No completed services recorded for this invoice.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Cost Breakdown Section -->
            <div class="border-t pt-8 print-border flex flex-col md:flex-row justify-between items-start gap-8">
                <!-- Loyalty Points Earned / Coupon details -->
                <div class="space-y-4 max-w-sm">
                    @if($pointsEarned > 0)
                        <div class="bg-red-50/50 border border-red-100/50 p-4 rounded-2xl flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center border border-red-100 shadow-sm">
                                <span class="text-xs font-black text-autocheck-red">⭐</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">AutoCheck Rewards Club</p>
                                <p class="text-xs font-black text-gray-950 mt-0.5">+{{ $pointsEarned }} Loyalty Points Earned!</p>
                            </div>
                        </div>
                    @endif

                    @if($claimedRewards->isNotEmpty())
                        <div class="bg-green-50/50 border border-green-100/50 p-4 rounded-2xl">
                            <p class="text-[9px] font-black text-green-600 uppercase tracking-widest">Coupons Redeemed</p>
                            <div class="space-y-1.5 mt-1.5">
                                @foreach($claimedRewards as $reward)
                                    <div class="flex items-center space-x-1.5 text-xs text-gray-600 font-bold">
                                        <span>🎟️</span>
                                        <span>Claimed: Free {{ $reward->serviceType->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Grand Total summary -->
                <div class="w-full md:w-80 space-y-3">
                    <div class="flex justify-between items-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <span>Services Subtotal</span>
                        <span class="text-gray-950 font-black">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discountTotal > 0)
                        <div class="flex justify-between items-center text-xs font-bold text-green-600 uppercase tracking-widest bg-green-50/50 py-1.5 px-3 rounded-lg border border-green-100/50">
                            <span>Rewards Discount</span>
                            <span class="font-black">-₱{{ number_format($discountTotal, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-150">
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Amount to Pay</span>
                        <span class="text-2xl font-black text-autocheck-red">₱{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer / Closing Signatures -->
            <div class="mt-16 grid grid-cols-2 gap-8 text-center pt-8 border-t border-dashed border-gray-150">
                <div class="space-y-12">
                    <div class="w-48 border-b mx-auto border-gray-300 h-8"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer Signature</p>
                </div>
                <div class="space-y-12">
                    <div class="w-48 border-b mx-auto border-gray-300 h-8"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Service Head Authorization</p>
                </div>
            </div>

        </div>

        <!-- Print-Ready Notice (no-print) -->
        <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest no-print italic">
            Invoice generated automatically. Press "Print Invoice" to proceed to physical printing.
        </p>

    </div>

    <script>
        function downloadPDF(event) {
            if (event) event.preventDefault();

            const element = document.getElementById('receipt-card');
            const plateNumber = '{{ $vehicle->plate_number }}';
            const date = new Date().toISOString().slice(0, 10);

            const opt = {
                margin:       0.5,
                filename:     `AutoCheck_Invoice_${plateNumber}_${date}.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Hide no-print elements inside the card during capture
            const noPrintEls = element.querySelectorAll('.no-print');
            noPrintEls.forEach(el => el.style.display = 'none');

            html2pdf().set(opt).from(element).save().then(() => {
                noPrintEls.forEach(el => el.style.display = '');
            });
        }
    </script>

</body>
</html>
