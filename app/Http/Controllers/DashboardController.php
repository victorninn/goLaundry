<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        // For super admin, show overview of all businesses
        if ($user->isSuperAdmin()) {
            return $this->superAdminDashboard();
        }

        // Today's stats
        $todayOrders = LaundryOrder::byBusiness($businessId)->today()->count();
        $todayRevenue = LaundryOrder::byBusiness($businessId)->today()->sum('amount_paid');
        
        // Pending orders by status
        $ordersByStatus = LaundryOrder::byBusiness($businessId)
            ->whereNotIn('status', ['claimed', 'cancelled'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Recent orders
        $recentOrders = LaundryOrder::byBusiness($businessId)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::byBusiness($businessId)
            ->lowStock()
            ->get();

        // Monthly stats
        $monthlyOrders = LaundryOrder::byBusiness($businessId)
            ->whereMonth('created_at', now()->month)
            ->count();
        $monthlyRevenue = LaundryOrder::byBusiness($businessId)
            ->whereMonth('created_at', now()->month)
            ->sum('amount_paid');

        // Customers count
        $customersCount = Customer::byBusiness($businessId)->count();
        $servicesCount = Service::byBusiness($businessId)->active()->count();

        return view('dashboard.index', compact(
            'todayOrders',
            'todayRevenue',
            'ordersByStatus',
            'recentOrders',
            'lowStockProducts',
            'monthlyOrders',
            'monthlyRevenue',
            'customersCount',
            'servicesCount'
        ));
    }

    private function superAdminDashboard()
    {
        $totalBusinesses = \App\Models\Business::count();
        $totalUsers = \App\Models\User::count();
        $totalOrders = LaundryOrder::count();
        $totalRevenue = LaundryOrder::sum('amount_paid');

        $recentBusinesses = \App\Models\Business::with('owner')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.super-admin', compact(
            'totalBusinesses',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'recentBusinesses'
        ));
    }
}
