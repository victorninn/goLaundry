<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Report - {{ $weekStart->format('M d') }} to {{ $weekEnd->format('M d, Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0284c7; padding-bottom: 20px; }
        .header h1 { font-size: 24px; color: #0284c7; margin-bottom: 5px; }
        .header p { color: #666; }
        .summary { display: table; width: 100%; margin-bottom: 30px; }
        .summary-item { display: table-cell; text-align: center; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .summary-item .label { font-size: 10px; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .summary-item .value { font-size: 18px; font-weight: bold; color: #1e293b; }
        .summary-item.paid .value { color: #059669; }
        .summary-item.balance .value { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #0284c7; color: white; padding: 10px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .status { padding: 3px 8px; border-radius: 10px; font-size: 10px; font-weight: 500; }
        .status-pending { background: #f1f5f9; color: #475569; }
        .status-washing { background: #dbeafe; color: #1d4ed8; }
        .status-drying { background: #fef3c7; color: #d97706; }
        .status-ready { background: #d1fae5; color: #059669; }
        .status-claimed { background: #ccfbf1; color: #0d9488; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business->name }}</h1>
        <p>{{ $business->address }}</p>
        @if($business->tin)
            <p>TIN: {{ $business->tin }}</p>
        @endif
        <p>Weekly Report: {{ $weekStart->format('F d') }} - {{ $weekEnd->format('F d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Orders</div>
            <div class="value">{{ $summary['total_orders'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Loads</div>
            <div class="value">{{ $summary['total_loads'] ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Amount</div>
            <div class="value">₱{{ number_format($summary['total_amount'], 2) }}</div>
        </div>
        <div class="summary-item paid">
            <div class="label">Collected</div>
            <div class="value">₱{{ number_format($summary['total_paid'], 2) }}</div>
        </div>
        <div class="summary-item balance">
            <div class="label">Balance Due</div>
            <div class="value">₱{{ number_format($summary['total_balance'], 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Services</th>
                <th class="text-right">Loads</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Paid</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->items->pluck('service.name')->join(', ') }}</td>
                    <td class="text-right">{{ $order->total_loads ?? 0 }}</td>
                    <td class="text-right">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-right">₱{{ number_format($order->amount_paid, 2) }}</td>
                    <td><span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->created_at->format('M d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">No orders for this week</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
