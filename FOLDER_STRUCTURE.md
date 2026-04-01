# Laravel Laundry Shop Management System - Folder Structure

Place each file in your Laravel project according to this structure:

```
your-laravel-project/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Business.php
│   │   ├── Customer.php
│   │   ├── Service.php
│   │   ├── Product.php
│   │   ├── LaundryOrder.php
│   │   └── LaundryOrderItem.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── PortalLoginController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── ProductController.php
│   │   │   ├── LaundryOrderController.php
│   │   │   ├── ReportController.php
│   │   │   ├── PortalController.php
│   │   │   ├── BusinessController.php
│   │   │   └── SuperAdminController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   └── CheckBusinessAccess.php
│   │   │
│   │   └── Requests/
│   │       ├── CustomerRequest.php
│   │       ├── ServiceRequest.php
│   │       ├── ProductRequest.php
│   │       └── LaundryOrderRequest.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php (update existing)
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_businesses_table.php
│   │   ├── 2024_01_01_000002_add_business_id_to_users_table.php
│   │   ├── 2024_01_01_000003_create_services_table.php
│   │   ├── 2024_01_01_000004_create_products_table.php
│   │   ├── 2024_01_01_000005_create_customers_table.php
│   │   ├── 2024_01_01_000006_create_laundry_orders_table.php
│   │   ├── 2024_01_01_000007_create_laundry_order_items_table.php
│   │   └── 2024_01_01_000008_create_service_product_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       └── DemoDataSeeder.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── portal.blade.php
│       │   └── guest.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── portal-login.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── customers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── services/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── products/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── orders/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── reports/
│       │   ├── index.blade.php
│       │   └── pdf.blade.php
│       ├── portal/
│       │   ├── track.blade.php
│       │   └── status.blade.php
│       ├── admin/
│       │   ├── businesses.blade.php
│       │   └── users.blade.php
│       └── components/
│           ├── navbar.blade.php
│           ├── sidebar.blade.php
│           └── status-badge.blade.php
│
├── routes/
│   └── web.php
│
├── config/
│   └── laundry.php
│
└── public/
    └── css/
        └── custom.css (optional)
```

## Installation Steps

1. Copy all files to their respective locations
2. Run migrations: `php artisan migrate`
3. Run seeders: `php artisan db:seed`
4. Install Tailwind CSS if not already installed
5. Compile assets: `npm run dev`

## Required Packages

Add to composer.json:
```json
"require": {
    "barryvdh/laravel-dompdf": "^2.0"
}
```

Then run: `composer require barryvdh/laravel-dompdf`

## Default Credentials (after seeding)

**Super Admin:**
- Email: superadmin@laundry.com
- Password: password123

**Admin (Shop Owner):**
- Email: admin@freshclean.com
- Password: password123

**Customer Portal:**
- Use phone number to track orders
