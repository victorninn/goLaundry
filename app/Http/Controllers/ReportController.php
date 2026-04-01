<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $summary = [
            'total_orders' => $orders->count(),
            'total_kilos' => $orders->sum('total_kilos'),
            'total_amount' => $orders->sum('total_amount'),
            'total_paid' => $orders->sum('amount_paid'),
            'total_balance' => $orders->sum('total_amount') - $orders->sum('amount_paid'),
            'by_status' => $orders->groupBy('status')->map->count(),
        ];

        return view('reports.index', compact('orders', 'summary', 'date'));
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

        $summary = [
            'total_orders' => $orders->count(),
            'total_kilos' => $orders->sum('total_kilos'),
            'total_amount' => $orders->sum('total_amount'),
            'total_paid' => $orders->sum('amount_paid'),
            'total_balance' => $orders->sum('total_amount') - $orders->sum('amount_paid'),
            'by_status' => $orders->groupBy('status')->map->count(),
        ];

        $business = auth()->user()->business;

        $pdf = Pdf::loadView('reports.pdf', compact('orders', 'summary', 'date', 'business'));
        
        return $pdf->download("daily-report-{$date}.pdf");
    }

    public function dateRange(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $businessId = $this->getBusinessId();

        $orders = LaundryOrder::byBusiness($businessId)
            ->with(['customer', 'items.service'])
            ->whereBetween('created_at', [$validated['date_from'], $validated['date_to'] . ' 23:59:59'])
            ->latest()
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_kilos' => $orders->sum('total_kilos'),
            'total_amount' => $orders->sum('total_amount'),
            'total_paid' => $orders->sum('amount_paid'),
            'total_balance' => $orders->sum('total_amount') - $orders->sum('amount_paid'),
            'by_status' => $orders->groupBy('status')->map->count(),
        ];

        return view('reports.range', compact('orders', 'summary', 'validated'));
    }
}
