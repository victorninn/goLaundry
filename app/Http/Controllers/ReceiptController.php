<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function show(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        if ($order->status !== LaundryOrder::STATUS_CLAIMED) {
            return back()->with('error', 'Receipt is only available for claimed orders.');
        }

        $order->load(['customer', 'items.service', 'orderProducts.product', 'business']);

        return view('receipts.show', compact('order'));
    }

    public function pdf(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        if ($order->status !== LaundryOrder::STATUS_CLAIMED) {
            return back()->with('error', 'Receipt is only available for claimed orders.');
        }

        $order->load(['customer', 'items.service', 'orderProducts.product', 'business']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('order'));
        
        return $pdf->download("receipt-{$order->order_number}.pdf");
    }

    protected function authorizeBusinessAccess($order)
    {
        if ($order->business_id !== $this->getBusinessId()) {
            abort(403);
        }
    }
}
