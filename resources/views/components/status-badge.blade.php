@props(['status'])

@php
    $colors = [
        'pending' => 'bg-slate-100 text-slate-700',
        'washing' => 'bg-blue-100 text-blue-700',
        'drying' => 'bg-amber-100 text-amber-700',
        'folding' => 'bg-purple-100 text-purple-700',
        'ready' => 'bg-emerald-100 text-emerald-700',
        'claimed' => 'bg-teal-100 text-teal-700',
        'cancelled' => 'bg-rose-100 text-rose-700',
    ];
    $colorClass = $colors[$status] ?? 'bg-slate-100 text-slate-700';
    $label = \App\Models\LaundryOrder::getStatuses()[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $label }}
</span>
