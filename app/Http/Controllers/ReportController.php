<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->getBusinessId();
        $date = $request->get('date', today()->format('Y-m-d'));

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);

        return view('reports.index', compact('orders', 'summary', 'date'));
    }

    public function weekly(Request $request)
    {
        $businessId = $this->getBusinessId();
        
        $weekStart = $request->get('week_start') 
            ? Carbon::parse($request->get('week_start'))->startOfWeek()
            : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);
        $business = auth()->user()->business;

        return view('reports.weekly', compact('orders', 'summary', 'weekStart', 'weekEnd', 'business'));
    }

    public function weeklyPdf(Request $request)
    {
        $businessId = $this->getBusinessId();
        
        $weekStart = $request->get('week_start') 
            ? Carbon::parse($request->get('week_start'))->startOfWeek()
            : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);
        $business = auth()->user()->business;

        $pdf = Pdf::loadView('reports.weekly-pdf', compact('orders', 'summary', 'weekStart', 'weekEnd', 'business'));
        
        return $pdf->download("weekly-report-{$weekStart->format('Y-m-d')}.pdf");
    }

    public function monthly(Request $request)
    {
        $businessId = $this->getBusinessId();
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);
        $business = auth()->user()->business;

        // Daily breakdown
        $dailyBreakdown = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders) {
            return [
                'count' => $dayOrders->count(),
                'amount' => $dayOrders->sum('total_amount'),
                'paid' => $dayOrders->sum('amount_paid'),
            ];
        });

        return view('reports.monthly', compact('orders', 'summary', 'monthStart', 'monthEnd', 'business', 'dailyBreakdown', 'month', 'year'));
    }

    public function monthlyPdf(Request $request)
    {
        $businessId = $this->getBusinessId();
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);
        $business = auth()->user()->business;

        $pdf = Pdf::loadView('reports.monthly-pdf', compact('orders', 'summary', 'monthStart', 'monthEnd', 'business'));
        
        return $pdf->download("monthly-report-{$monthStart->format('Y-m')}.pdf");
    }

    public function exportPdf(Request $request)
    {
        $businessId = $this->getBusinessId();
        $date = $request->get('date', today()->format('Y-m-d'));

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        $summary = $this->calculateSummary($orders);
        $business = auth()->user()->business;

        $pdf = Pdf::loadView('reports.pdf', compact('orders', 'summary', 'date', 'business'));
        
        return $pdf->download("daily-report-{$date}.pdf");
    }

    protected function calculateSummary($orders)
    {
        return [
            'total_orders' => $orders->count(),
            'total_loads' => $orders->sum('total_loads'),
            'total_amount' => $orders->sum('total_amount'),
            'total_paid' => $orders->sum('amount_paid'),
            'total_balance' => $orders->sum('total_amount') - $orders->sum('amount_paid'),
            'by_status' => $orders->groupBy('status')->map->count(),
        ];
    }
}
