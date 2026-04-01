<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'business_id' => null,
        ]);

        // Create Demo Business
        $business = Business::create([
            'name' => 'Fresh & Clean Laundry',
            'address' => '123 Main Street, City Center',
            'phone' => '09123456789',
            'email' => 'freshclean@laundry.com',
            'is_active' => true,
        ]);

        // Create Admin for the business
        User::create([
            'name' => 'John Admin',
            'email' => 'admin@freshclean.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'business_id' => $business->id,
        ]);

        // Create Products (Inventory)
        $products = [
            [
                'name' => 'Laundry Detergent',
                'description' => 'Premium washing detergent',
                'unit' => 'ml',
                'quantity' => 5000,
                'price' => 0.10,
                'low_stock_threshold' => 1000,
            ],
            [
                'name' => 'Fabric Conditioner',
                'description' => 'Softens clothes and adds fragrance',
                'unit' => 'ml',
                'quantity' => 3000,
                'price' => 0.15,
                'low_stock_threshold' => 500,
            ],
            [
                'name' => 'Bleach',
                'description' => 'For white clothes',
                'unit' => 'ml',
                'quantity' => 2000,
                'price' => 0.08,
                'low_stock_threshold' => 300,
            ],
            [
                'name' => 'Stain Remover',
                'description' => 'Pre-treatment for tough stains',
                'unit' => 'ml',
                'quantity' => 1500,
                'price' => 0.20,
                'low_stock_threshold' => 200,
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $productData['business_id'] = $business->id;
            $createdProducts[] = Product::create($productData);
        }

        // Create Services
        $services = [
            [
                'name' => 'Regular Wash',
                'description' => 'Standard washing service',
                'price_per_kilo' => 35.00,
                'products' => [
                    ['index' => 0, 'qty' => 30], // Detergent
                    ['index' => 1, 'qty' => 15], // Conditioner
                ],
            ],
            [
                'name' => 'Premium Wash',
                'description' => 'Premium washing with fabric care',
                'price_per_kilo' => 50.00,
                'products' => [
                    ['index' => 0, 'qty' => 40],
                    ['index' => 1, 'qty' => 25],
                    ['index' => 3, 'qty' => 10], // Stain remover
                ],
            ],
            [
                'name' => 'Whites Only',
                'description' => 'Specialized service for white clothes',
                'price_per_kilo' => 45.00,
                'products' => [
                    ['index' => 0, 'qty' => 35],
                    ['index' => 2, 'qty' => 20], // Bleach
                    ['index' => 1, 'qty' => 15],
                ],
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Professional dry cleaning',
                'price_per_kilo' => 80.00,
                'products' => [],
            ],
        ];

        $createdServices = [];
        foreach ($services as $serviceData) {
            $productAssignments = $serviceData['products'];
            unset($serviceData['products']);
            
            $serviceData['business_id'] = $business->id;
            $service = Service::create($serviceData);
            $createdServices[] = $service;

            // Attach products
            foreach ($productAssignments as $assignment) {
                $service->products()->attach($createdProducts[$assignment['index']]->id, [
                    'quantity_per_kilo' => $assignment['qty']
                ]);
            }
        }

        // Create Sample Customers
        $customers = [
            ['name' => 'Maria Santos', 'phone' => '09171234567', 'email' => 'maria@email.com', 'address' => '456 Oak Street'],
            ['name' => 'Juan Dela Cruz', 'phone' => '09181234567', 'email' => 'juan@email.com', 'address' => '789 Pine Avenue'],
            ['name' => 'Ana Reyes', 'phone' => '09191234567', 'email' => 'ana@email.com', 'address' => '321 Maple Drive'],
            ['name' => 'Pedro Garcia', 'phone' => '09201234567', 'email' => 'pedro@email.com', 'address' => '654 Elm Boulevard'],
            ['name' => 'Rosa Martinez', 'phone' => '09211234567', 'email' => 'rosa@email.com', 'address' => '987 Cedar Lane'],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customerData['business_id'] = $business->id;
            $createdCustomers[] = Customer::create($customerData);
        }

        // Create Sample Orders
        $statuses = ['pending', 'washing', 'drying', 'ready', 'claimed'];
        
        for ($i = 0; $i < 15; $i++) {
            $customer = $createdCustomers[array_rand($createdCustomers)];
            $service = $createdServices[array_rand($createdServices)];
            $kilos = rand(2, 10) + (rand(0, 9) / 10);
            $status = $statuses[array_rand($statuses)];
            
            $dateReceived = now()->subDays(rand(0, 7));
            $dateRelease = $dateReceived->copy()->addDays(rand(1, 3));

            $order = LaundryOrder::create([
                'business_id' => $business->id,
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'total_kilos' => $kilos,
                'total_amount' => $kilos * $service->price_per_kilo,
                'amount_paid' => $status === 'claimed' ? $kilos * $service->price_per_kilo : rand(0, 1) * ($kilos * $service->price_per_kilo),
                'status' => $status,
                'date_received' => $dateReceived,
                'date_release' => $dateRelease,
                'notes' => null,
            ]);

            LaundryOrderItem::create([
                'laundry_order_id' => $order->id,
                'service_id' => $service->id,
                'kilos' => $kilos,
                'price_per_kilo' => $service->price_per_kilo,
                'subtotal' => $kilos * $service->price_per_kilo,
            ]);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Super Admin: superadmin@laundry.com / password123');
        $this->command->info('Admin: admin@freshclean.com / password123');
        $this->command->info('Portal: Use customer phone (e.g., 09171234567)');
    }
}
