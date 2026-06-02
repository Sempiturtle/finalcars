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
                font-size: 11px !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
            }
        }
        body {
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="antialiased min-h-screen text-gray-800 py-8 px-4 sm:px-6">

    <div class="max-w-md mx-auto space-y-6">
        
        <!-- Navigation / Action Bar (no-print) -->
        <div class="flex items-center justify-between no-print bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <button onclick="window.history.back()" class="inline-flex items-center px-3 py-1.5 text-[10px] font-bold text-gray-600 hover:text-autocheck-red transition-all uppercase tracking-widest">
                <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </button>
            <div class="flex items-center space-x-2">
                <button onclick="downloadPDF(event)" class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white text-[10px] font-black rounded-lg hover:bg-black transition-all shadow-sm uppercase tracking-widest">
                    <svg class="h-3.5 w-3.5 mr-1 text-autocheck-red animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    PDF
                </button>
                <button onclick="window.print()" class="inline-flex items-center px-3 py-1.5 bg-autocheck-red text-white text-[10px] font-black rounded-lg hover:bg-red-700 transition-all shadow-sm uppercase tracking-widest">
                    <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <!-- Official Receipt Card (Supermarket Style) -->
        <div id="receipt-card" class="bg-white border border-gray-200 shadow-xl p-6 pt-8 pb-8 relative overflow-hidden print-card font-mono text-[11px] text-gray-800 tracking-tight leading-relaxed">
            
            <!-- Top tear edge simulation -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-repeat-x no-print" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%228%22 viewBox=%220 0 16 8%22><polygon fill=%22%23f8fafc%22 points=%220,0 8,8 16,0 16,8 0,8%22/></svg>');"></div>

            <!-- Header Section -->
            <div class="text-center space-y-1 pb-4">
                <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">AUTO<span class="text-autocheck-red italic">CHECK</span></h1>
                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">Premium Vehicle Service Center</p>
                <p class="text-[9px] text-gray-400">123 Service Rd, Metro Manila</p>
                <p class="text-[9px] text-gray-400">Tel: +63 2 8888 8888</p>
            </div>

            <!-- Receipt Metadata -->
            <div class="border-t border-b border-dashed border-gray-300 py-2.5 my-3 space-y-1">
                <div class="flex justify-between">
                    <span>INVOICE NO:</span>
                    <span class="font-bold text-gray-900">REC-{{ $vehicle->plate_number }}-{{ date('Ymd') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>DATE &amp; TIME:</span>
                    <span>{{ date('Y-m-d H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>CASHIER/MECH:</span>
                    <span class="font-bold">{{ $vehicle->mechanic_name ?? 'Unassigned' }}</span>
                </div>
            </div>

            <!-- Customer & Vehicle Info -->
            <div class="space-y-1 mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">CUSTOMER:</span>
                    <span class="font-bold text-gray-900 truncate max-w-[200px]">{{ $vehicle->owner_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">PLATE NO:</span>
                    <span class="font-bold text-gray-900 italic">{{ $vehicle->plate_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">VEHICLE:</span>
                    <span class="text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->color ?? 'N/A' }})</span>
                </div>
            </div>

            <!-- Items Divider -->
            <div class="text-center font-bold text-gray-400 tracking-[0.2em] my-2">------------------------------</div>

            <!-- Service Items -->
            <div class="space-y-3">
                @php $hasCompleted = false; @endphp
                @foreach($vehicle->services ?? [] as $service)
                    @if(($service['status'] ?? '') === 'completed')
                        @php $hasCompleted = true; @endphp
                        <div>
                            <div class="flex justify-between items-start gap-4">
                                <span class="font-bold text-gray-900 uppercase leading-none">{{ $service['type'] }}</span>
                                <span class="text-gray-900 font-bold shrink-0">₱{{ number_format($service['cost'] ?? 0, 2) }}</span>
                            </div>
                            <div class="text-[10px] text-gray-500 flex justify-between mt-0.5">
                                <span>Mode: {{ $service['mode'] ?? 'Walk-in' }}</span>
                                <span class="italic max-w-[180px] truncate">{{ $service['notes'] ?? 'Diagnostics OK' }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$hasCompleted)
                    <div class="text-center py-2 text-gray-400 italic">No completed services.</div>
                @endif
            </div>

            <!-- Totals Divider -->
            <div class="text-center font-bold text-gray-400 tracking-[0.2em] my-2">------------------------------</div>

            <!-- Financial Summary -->
            <div class="space-y-1.5">
                <div class="flex justify-between">
                    <span>SUBTOTAL:</span>
                    <span>₱{{ number_format($subtotal, 2) }}</span>
                </div>

                @if($discountTotal > 0)
                    <div class="flex justify-between text-green-600 font-bold">
                        <span>REWARDS DISCOUNT:</span>
                        <span>-₱{{ number_format($discountTotal, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-300">
                    <span class="text-xs font-black text-gray-950">TOTAL TO PAY:</span>
                    <span class="text-base font-black text-autocheck-red">₱{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>

            <!-- Loyalty Section -->
            @if($pointsEarned > 0 || $claimedRewards->isNotEmpty())
                <div class="my-4 p-2 bg-gray-50 border border-dashed border-gray-200 space-y-1.5 text-[10px]">
                    @if($pointsEarned > 0)
                        <div class="flex justify-between font-bold">
                            <span class="text-gray-500">POINTS EARNED:</span>
                            <span class="text-gray-900">+{{ $pointsEarned }} pts</span>
                        </div>
                    @endif
                    @if($claimedRewards->isNotEmpty())
                        <div>
                            <span class="text-gray-500 font-bold block">REDEEMED REWARDS:</span>
                            @foreach($claimedRewards as $reward)
                                <div class="text-green-600 font-medium">• Free {{ $reward->serviceType->name }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Signatures for POS validation -->
            <div class="pt-6 pb-2 grid grid-cols-2 gap-4 text-center text-[9px] text-gray-400">
                <div class="space-y-6">
                    <div class="border-b border-gray-300 mx-2"></div>
                    <span>CUSTOMER SIGNATURE</span>
                </div>
                <div class="space-y-6">
                    <div class="border-b border-gray-300 mx-2"></div>
                    <span>AUTHORIZED SIGNATURE</span>
                </div>
            </div>

            <!-- Barcode & Footer -->
            <div class="pt-6 text-center space-y-2">
                <!-- Simple SVG Barcode for Authentic Thermal Receipt look -->
                <div class="inline-block no-print opacity-80 hover:opacity-100 transition-opacity">
                    <svg class="mx-auto" width="160" height="36" viewBox="0 0 160 36" xmlns="http://www.w3.org/2000/svg">
                        <!-- Barcode bars pattern -->
                        <rect x="0" y="0" width="4" height="30" fill="black" />
                        <rect x="6" y="0" width="2" height="30" fill="black" />
                        <rect x="10" y="0" width="6" height="30" fill="black" />
                        <rect x="18" y="0" width="2" height="30" fill="black" />
                        <rect x="22" y="0" width="4" height="30" fill="black" />
                        <rect x="28" y="0" width="8" height="30" fill="black" />
                        <rect x="38" y="0" width="2" height="30" fill="black" />
                        <rect x="42" y="0" width="4" height="30" fill="black" />
                        <rect x="48" y="0" width="6" height="30" fill="black" />
                        <rect x="56" y="0" width="2" height="30" fill="black" />
                        <rect x="60" y="0" width="4" height="30" fill="black" />
                        <rect x="66" y="0" width="2" height="30" fill="black" />
                        <rect x="70" y="0" width="8" height="30" fill="black" />
                        <rect x="80" y="0" width="4" height="30" fill="black" />
                        <rect x="86" y="0" width="2" height="30" fill="black" />
                        <rect x="90" y="0" width="6" height="30" fill="black" />
                        <rect x="98" y="0" width="2" height="30" fill="black" />
                        <rect x="102" y="0" width="4" height="30" fill="black" />
                        <rect x="108" y="0" width="8" height="30" fill="black" />
                        <rect x="118" y="0" width="2" height="30" fill="black" />
                        <rect x="122" y="0" width="4" height="30" fill="black" />
                        <rect x="128" y="0" width="6" height="30" fill="black" />
                        <rect x="136" y="0" width="2" height="30" fill="black" />
                        <rect x="140" y="0" width="4" height="30" fill="black" />
                        <rect x="146" y="0" width="2" height="30" fill="black" />
                        <rect x="150" y="0" width="8" height="30" fill="black" />
                        <rect x="160" y="0" width="2" height="30" fill="black" />
                        <!-- Barcode label -->
                        <text x="80" y="36" font-family="monospace" font-size="8" text-anchor="middle" fill="#9ca3af">REC-{{ $vehicle->plate_number }}</text>
                    </svg>
                </div>
                <div class="text-[9px] text-gray-500 font-bold">
                    <p>THANK YOU FOR YOUR VISIT!</p>
                    <p class="text-gray-400">PLEASE COME AGAIN</p>
                </div>
            </div>

            <!-- Bottom tear edge simulation -->
            <div class="absolute bottom-0 left-0 right-0 h-2 bg-repeat-x no-print" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%228%22 viewBox=%220 0 16 8%22><polygon fill=%22%23f8fafc%22 points=%220,8 8,0 16,8 16,0 0,0%22/></svg>');"></div>

        </div>

        <!-- Print-Ready Notice (no-print) -->
        <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest no-print italic">
            Press "Print" to print directly or download PDF copy.
        </p>

    </div>

    <script>
        function downloadPDF(event) {
            if (event) event.preventDefault();

            const element = document.getElementById('receipt-card');
            const plateNumber = '{{ $vehicle->plate_number }}';
            const date = new Date().toISOString().slice(0, 10);

            const opt = {
                margin:       [0.2, 0.2, 0.2, 0.2],
                filename:     `AutoCheck_Receipt_${plateNumber}_${date}.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'in', format: [4.25, 8.5], orientation: 'portrait' } // Customized to feel like a real long POS receipt slip
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
