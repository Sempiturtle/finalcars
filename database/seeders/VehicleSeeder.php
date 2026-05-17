<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\User;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customer user to assign vehicles to
        $customer = User::where('role', 'customer')->first() ?? User::first();
        $customerId = $customer ? $customer->id : 1;
        $customerName = $customer ? $customer->name : 'Nathaniel Amistoso';

        $vehicles = [
            [
                'plate_number' => 'LMN-9012',
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => '2023',
                'color' => 'Crystal Black',
                'user_id' => $customerId,
                'owner_name' => $customerName,
                'mechanic_name' => 'Master Tech - Dave',
                'registration_date' => now()->subMonths(6),
                'next_service_date' => now()->addDays(2),
                'status' => 'completed',
                'total_cost' => 4500.00,
                'services' => [
                    ['type' => 'Full Synthetic Oil Change', 'cost' => 2500.00, 'status' => 'completed', 'date' => now()->subMonths(3)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Replaced oil filter and performed 21-point inspection.'],
                    ['type' => 'Brake Pad Replacement', 'cost' => 2000.00, 'status' => 'completed', 'date' => now()->subMonths(1)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Installed premium ceramic front brake pads.'],
                ]
            ],
            [
                'plate_number' => 'XYZ-1234',
                'make' => 'Toyota',
                'model' => 'Camry Hybrid',
                'year' => '2024',
                'color' => 'Platinum White Pearl',
                'user_id' => $customerId,
                'owner_name' => 'Maria Santos',
                'mechanic_name' => 'Senior Tech - Alex',
                'registration_date' => now()->subMonths(8),
                'next_service_date' => now()->subDays(1),
                'status' => 'overdue',
                'total_cost' => 12500.00,
                'services' => [
                    ['type' => 'Hybrid Battery Diagnostics', 'cost' => 3500.00, 'status' => 'completed', 'date' => now()->subMonths(4)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Battery health at 98%, excellent cell balancing.'],
                    ['type' => 'Tire Rotation & Wheel Alignment', 'cost' => 1800.00, 'status' => 'completed', 'date' => now()->subMonths(2)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Adjusted camber and toe to factory specs.'],
                    ['type' => 'Transmission Fluid Flush', 'cost' => 7200.00, 'status' => 'scheduled', 'date' => now()->subDays(1)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Customer requested CVT fluid flush.'],
                ]
            ],
            [
                'plate_number' => 'ABC-5678',
                'make' => 'Ford',
                'model' => 'Mustang GT',
                'year' => '2022',
                'color' => 'Rapid Red',
                'user_id' => $customerId,
                'owner_name' => 'Carlos Mendoza',
                'mechanic_name' => 'Performance Specialist - Ryan',
                'registration_date' => now()->subMonths(14),
                'next_service_date' => now()->addDays(15),
                'status' => 'in progress',
                'total_cost' => 28400.00,
                'services' => [
                    ['type' => 'V8 Supercharger Belt Inspection', 'cost' => 4500.00, 'status' => 'completed', 'date' => now()->subMonths(6)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Belt tension optimal.'],
                    ['type' => 'Exhaust Valve Cleaning', 'cost' => 8900.00, 'status' => 'completed', 'date' => now()->subMonths(3)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Cleaned active exhaust butterfly valves.'],
                    ['type' => 'Performance Spark Plug Upgrade', 'cost' => 15000.00, 'status' => 'in progress', 'date' => now()->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Installing Iridium high-performance spark plugs.'],
                ]
            ],
            [
                'plate_number' => 'DEF-9012',
                'make' => 'Tesla',
                'model' => 'Model 3 Dual Motor',
                'year' => '2023',
                'color' => 'Deep Blue Metallic',
                'user_id' => $customerId,
                'owner_name' => 'Elena Cruz',
                'mechanic_name' => 'EV Tech - Marcus',
                'registration_date' => now()->subMonths(10),
                'next_service_date' => now()->addDays(30),
                'status' => 'scheduled',
                'total_cost' => 6200.00,
                'services' => [
                    ['type' => 'Cabin Air Filter & Bioweapon Defense Calibration', 'cost' => 4200.00, 'status' => 'completed', 'date' => now()->subMonths(5)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Replaced HEPA filters.'],
                    ['type' => 'Brake Caliper Servicing', 'cost' => 2000.00, 'status' => 'scheduled', 'date' => now()->addDays(30)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Lubricating slide pins due to low friction braking usage.'],
                ]
            ],
            [
                'plate_number' => 'GHI-3456',
                'make' => 'BMW',
                'model' => 'M340i xDrive',
                'year' => '2023',
                'color' => 'Dravit Grey',
                'user_id' => $customerId,
                'owner_name' => 'Arthur Reyes',
                'mechanic_name' => 'Euro Specialist - Christian',
                'registration_date' => now()->subMonths(12),
                'next_service_date' => now(),
                'status' => 'in progress',
                'total_cost' => 34500.00,
                'services' => [
                    ['type' => 'Differential Fluid Service', 'cost' => 12500.00, 'status' => 'completed', 'date' => now()->subMonths(7)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'M-Sport rear differential service.'],
                    ['type' => 'B58 Engine Walnut Blasting', 'cost' => 22000.00, 'status' => 'in progress', 'date' => now()->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Intake valve carbon cleaning.'],
                ]
            ],
            [
                'plate_number' => 'JKL-7890',
                'make' => 'Hyundai',
                'model' => 'Tucson N-Line',
                'year' => '2024',
                'color' => 'Titan Gray',
                'user_id' => $customerId,
                'owner_name' => 'Sofia Tan',
                'mechanic_name' => 'Master Tech - Dave',
                'registration_date' => now()->subMonths(3),
                'next_service_date' => now()->addDays(45),
                'status' => 'completed',
                'total_cost' => 3800.00,
                'services' => [
                    ['type' => '1,000 KM Break-In Inspection', 'cost' => 3800.00, 'status' => 'completed', 'date' => now()->subMonths(1)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'All fluid levels and torque specs within normal limits.'],
                ]
            ],
            [
                'plate_number' => 'MNO-1122',
                'make' => 'Nissan',
                'model' => 'Navara Pro-4X',
                'year' => '2023',
                'color' => 'Stealth Pearl Gray',
                'user_id' => $customerId,
                'owner_name' => 'Ramon Bautista',
                'mechanic_name' => 'Senior Tech - Alex',
                'registration_date' => now()->subMonths(16),
                'next_service_date' => now()->addDays(5),
                'status' => 'scheduled',
                'total_cost' => 18900.00,
                'services' => [
                    ['type' => '4WD Transfer Case Flush', 'cost' => 8500.00, 'status' => 'completed', 'date' => now()->subMonths(9)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Replaced front and rear gear oils.'],
                    ['type' => 'Heavy Duty Shock Absorber Test', 'cost' => 10400.00, 'status' => 'completed', 'date' => now()->subMonths(3)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Inspected Bilstein dampers after off-road use.'],
                    ['type' => 'Diesel Fuel Filter Replacement', 'cost' => 4500.00, 'status' => 'scheduled', 'date' => now()->addDays(5)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Regular 20,000 km fuel filter replacement.'],
                ]
            ],
            [
                'plate_number' => 'PQR-3344',
                'make' => 'Mercedes-Benz',
                'model' => 'C300 AMG Line',
                'year' => '2022',
                'color' => 'Obsidian Black',
                'user_id' => $customerId,
                'owner_name' => 'Beatrice Gomez',
                'mechanic_name' => 'Euro Specialist - Christian',
                'registration_date' => now()->subMonths(20),
                'next_service_date' => now()->subDays(3),
                'status' => 'overdue',
                'total_cost' => 45200.00,
                'services' => [
                    ['type' => 'Service A Basic Maintenance', 'cost' => 18500.00, 'status' => 'completed', 'date' => now()->subMonths(12)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Completed synthetic oil change and checks.'],
                    ['type' => 'Auxiliary Battery Replacement', 'cost' => 26700.00, 'status' => 'completed', 'date' => now()->subMonths(5)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Replaced start-stop auxiliary battery.'],
                    ['type' => 'Service B Comprehensive Inspection', 'cost' => 32000.00, 'status' => 'scheduled', 'date' => now()->subDays(3)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Overdue Service B.'],
                ]
            ],
            [
                'plate_number' => 'STU-5566',
                'make' => 'Mitsubishi',
                'model' => 'Montero Sport GT',
                'year' => '2023',
                'color' => 'White Diamond',
                'user_id' => $customerId,
                'owner_name' => 'Miguel de Leon',
                'mechanic_name' => 'Master Tech - Dave',
                'registration_date' => now()->subMonths(11),
                'next_service_date' => now()->addDays(20),
                'status' => 'completed',
                'total_cost' => 14200.00,
                'services' => [
                    ['type' => 'EGR Valve & Intake Manifold Cleaning', 'cost' => 9500.00, 'status' => 'completed', 'date' => now()->subMonths(4)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Removed carbon soot buildup.'],
                    ['type' => 'Air Conditioning Cabin Sanitization', 'cost' => 4700.00, 'status' => 'completed', 'date' => now()->subMonths(2)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Performed antibacterial ozone mist treatment.'],
                ]
            ],
            [
                'plate_number' => 'VWX-7788',
                'make' => 'Suzuki',
                'model' => 'Jimny GLX AllGrip',
                'year' => '2024',
                'color' => 'Kinetic Yellow',
                'user_id' => $customerId,
                'owner_name' => 'Patricia Villanueva',
                'mechanic_name' => 'Performance Specialist - Ryan',
                'registration_date' => now()->subMonths(5),
                'next_service_date' => now()->addDays(12),
                'status' => 'scheduled',
                'total_cost' => 5500.00,
                'services' => [
                    ['type' => 'Front Knuckle Wiper Seal Service', 'cost' => 5500.00, 'status' => 'completed', 'date' => now()->subMonths(2)->format('Y-m-d'), 'mode' => 'Walk-in', 'notes' => 'Replaced felt seals after water crossing.'],
                    ['type' => 'Steering Damper Upgrade Installation', 'cost' => 8500.00, 'status' => 'scheduled', 'date' => now()->addDays(12)->format('Y-m-d'), 'mode' => 'Online Booking', 'notes' => 'Customer upgrading to Old Man Emu steering stabilizer.'],
                ]
            ],
        ];

        foreach ($vehicles as $data) {
            Vehicle::updateOrCreate(
                ['plate_number' => $data['plate_number']],
                $data
            );
        }
    }
}
