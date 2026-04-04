<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PortalLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessLicenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaundryOrderController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Customer Portal Routes (No auth required, session-based)
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [PortalLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PortalLoginController::class, 'login']);
    Route::post('/logout', [PortalLoginController::class, 'logout'])->name('logout');
    Route::get('/track', [PortalController::class, 'track'])->name('track');
    Route::get('/order/{id}', [PortalController::class, 'showOrder'])->name('order');
    Route::post('/quick-track', [PortalController::class, 'quickTrack'])->name('quick-track');
});

// Authenticated Routes
Route::middleware(['auth', 'business.access', 'license.check'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers (will be disabled if license expired - handled in view)
    Route::resource('customers', CustomerController::class);

    // Services
    Route::resource('services', ServiceController::class)->except(['show']);

    // Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('/products/{product}/add-stock', [ProductController::class, 'addStock'])->name('products.add-stock');

    // Laundry Orders
    Route::resource('orders', LaundryOrderController::class);
    Route::patch('/orders/{order}/status', [LaundryOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/payment', [LaundryOrderController::class, 'recordPayment'])->name('orders.payment');

    // Receipts
    Route::get('/orders/{order}/receipt', [ReceiptController::class, 'show'])->name('orders.receipt');
    Route::get('/orders/{order}/receipt/pdf', [ReceiptController::class, 'pdf'])->name('orders.receipt.pdf');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/weekly/pdf', [ReportController::class, 'weeklyPdf'])->name('reports.weekly.pdf');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/monthly/pdf', [ReportController::class, 'monthlyPdf'])->name('reports.monthly.pdf');

    // License (Business side)
    Route::get('/license', [BusinessLicenseController::class, 'index'])->name('license.index');
    Route::post('/license/activate', [BusinessLicenseController::class, 'activate'])->name('license.activate');
    Route::get('/license/status', [BusinessLicenseController::class, 'checkStatus'])->name('license.status');

    // Business Settings (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/business/edit', [BusinessController::class, 'edit'])->name('business.edit');
        Route::put('/business', [BusinessController::class, 'update'])->name('business.update');
    });
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Businesses
    Route::get('/businesses', [SuperAdminController::class, 'businesses'])->name('businesses');
    Route::get('/businesses/create', [SuperAdminController::class, 'createBusiness'])->name('businesses.create');
    Route::post('/businesses', [SuperAdminController::class, 'storeBusiness'])->name('businesses.store');
    Route::get('/businesses/{business}/edit', [SuperAdminController::class, 'editBusiness'])->name('businesses.edit');
    Route::put('/businesses/{business}', [SuperAdminController::class, 'updateBusiness'])->name('businesses.update');
    Route::patch('/businesses/{business}/toggle', [SuperAdminController::class, 'toggleBusinessStatus'])->name('businesses.toggle');

    // Users
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create', [SuperAdminController::class, 'createAdmin'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeAdmin'])->name('users.store');
    Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');

    // Licenses
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::get('/licenses/create', [LicenseController::class, 'create'])->name('licenses.create');
    Route::post('/licenses', [LicenseController::class, 'store'])->name('licenses.store');
    Route::post('/licenses/generate', [LicenseController::class, 'generateKey'])->name('licenses.generate');
    Route::post('/licenses/{license}/renew', [LicenseController::class, 'renew'])->name('licenses.renew');
    Route::delete('/licenses/{license}', [LicenseController::class, 'destroy'])->name('licenses.destroy');
});
