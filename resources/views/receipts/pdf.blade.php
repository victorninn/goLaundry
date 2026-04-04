<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; padding: 20px; max-width: 400px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #0d9488; }
        .header h1 { font-size: 20px; color: #0d9488; margin-bottom: 3px; }
        .header p { color: #666; font-size: 11px; }
        .header .receipt-title { font-size: 14px; font-weight: bold; margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 10px; }
        .info-grid { width: 100%; margin-bottom: 15px; }
        .info-grid td { padding: 3px 0; font-size: 11px; }
        .info-grid .label { color: #666; }
        .info-grid .value { font-weight: bold; text-align: right; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.items th { background: #f1f5f9; padding: 6px 4px; text-align: left; font-size: 10px; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        table.items td { padding: 6px 4px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .section-title { font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin: 15px 0 8px; }
        .totals { border-top: 2px solid #e2e8f0; padding-top: 10px; margin-top: 10px; }
        .totals table { width: 100%; }
        .totals td { padding: 4px 0; font-size: 12px; }
        .totals .grand-total td { font-size: 16px; font-weight: bold; padding-top: 8px; border-top: 1px solid #e2e8f0; }
        .totals .paid td { color: #059669; }
        .totals .balance td { color: #dc2626; }
        .footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ccc; }
        .footer p { font-size: 11px; color: #666; }
        .footer .timestamp { font-size: 9px; color: #94a3b8; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $order->business->name }}</h1>
        @if($order->business->address)
            <p>{{ $order->business->address }}</p>
        @endif
        @if($order->business->phone)
            <p>Phone: {{ $order->business->phone }}</p>
        @endif
        @if($order->business->tin)
            <p>TIN: {{ $order->business->tin }}</p>
        @endif
        @if($order->business->business_registration_number)
            <p>Reg. No: {{ $order->business->business_registration_number }}</p>
        @endif
        <p class="receipt-title">OFFICIAL RECEIPT</p>
    </div>

    <table class="info-grid">
        <tr>
            <td class="label">Receipt #:</td>
            <td class="value">{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td class="label">Date:</td>
            <td class="value">{{ $order->date_received->format('F d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Customer:</td>
            <td class="value">{{ $order->customer->name }}</td>
        </tr>
        @if($order->customer->phone)
        <tr>
            <td class="label">Phone:</td>
            <td class="value">{{ $order->customer->phone }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">Services</div>
    <table class="items">
        <thead>
            <tr>
                <th>Service</th>
                <th class="text-center">Loads</th>
                <th class="text-right">Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->service->name }}</td>
                    <td class="text-center">{{ $item->num_loads }}</td>
                    <td class="text-right">₱{{ number_format($item->price_per_load, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($order->orderProducts->count() > 0)
        <div class="section-title">Products</div>
        <table class="items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $orderProduct)
                    <tr>
                        <td>{{ $orderProduct->product->name }}</td>
                        <td class="text-center">{{ $orderProduct->quantity }}</td>
                        <td class="text-right">₱{{ number_format($orderProduct->unit_price, 2) }}</td>
                        <td class="text-right">₱{{ number_format($orderProduct->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="totals">
        <table>
            @if($order->orderProducts->count() > 0)
                <tr>
                    <td>Services Total:</td>
                    <td class="text-right">₱{{ number_format($order->services_total ?? $order->items->sum('subtotal'), 2) }}</td>
                </tr>
                <tr>
                    <td>Products Total:</td>
                    <td class="text-right">₱{{ number_format($order->products_total ?? $order->orderProducts->sum('subtotal'), 2) }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>Total Amount:</td>
                <td class="text-right">₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr class="paid">
                <td>Amount Paid:</td>
                <td class="text-right">₱{{ number_format($order->amount_paid, 2) }}</td>
            </tr>
            @if($order->balance > 0)
                <tr class="balance">
                    <td>Balance:</td>
                    <td class="text-right">₱{{ number_format($order->balance, 2) }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p class="timestamp">Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
