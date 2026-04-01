# Laravel Laundry Shop Management System

A complete multi-tenant laundry shop management system built with Laravel and Tailwind CSS.

## Features

### Admin (Shop Owner)
- Dashboard with daily stats, revenue, and order overview
- Customer management (CRUD)
- Service types with pricing per kilo
- Product inventory with low stock alerts
- Laundry order management with status tracking
- Daily reports exportable to PDF
- Business settings

### Super Admin
- View all registered businesses
- Activate/deactivate businesses
- Manage all users
- Create new admin users

### Customer Portal
- Track laundry orders by phone number
- View order status with visual progress
- Quick track by order number

## Installation

1. **Create a new Laravel project** (or use existing):
   ```bash
   composer create-project laravel/laravel laundry-shop
   cd laundry-shop
   ```

2. **Copy the files** from this package to your Laravel project according to the folder structure in `FOLDER_STRUCTURE.md`

3. **Install required packages**:
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

4. **Setup Tailwind CSS** (if not already):
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
   ```

5. **Configure database** in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laundry_shop
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Register middleware** in `app/Http/Kernel.php`:
   ```php
   protected $middlewareAliases = [
       // ... existing middleware
       'role' => \App\Http\Middleware\CheckRole::class,
       'business.access' => \App\Http\Middleware\CheckBusinessAccess::class,
   ];
   ```

7. **Run migrations and seeders**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

8. **Build assets**:
   ```bash
   npm install
   npm run dev
   ```

9. **Start the server**:
   ```bash
   php artisan serve
   ```

## Default Credentials

After running seeders:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@laundry.com | password123 |
| Admin | admin@freshclean.com | password123 |

For Customer Portal, use phone number: `09171234567`

## Multi-Tenancy

The system uses a single database with `business_id` columns for multi-tenant support:
- Each business has its own customers, services, products, and orders
- Super Admin can see all businesses
- Admin users can only access their assigned business

## Routes Overview

| Route | Description |
|-------|-------------|
| `/login` | Admin login |
| `/register` | Business registration |
| `/dashboard` | Main dashboard |
| `/customers` | Customer management |
| `/services` | Service types |
| `/products` | Product inventory |
| `/orders` | Laundry orders |
| `/reports` | Daily reports with PDF export |
| `/portal/login` | Customer tracking portal |
| `/portal/track` | View customer orders |

## License

MIT License
