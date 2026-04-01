<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laundry Application Settings
    |--------------------------------------------------------------------------
    */

    'statuses' => [
        'pending' => 'Pending',
        'washing' => 'Washing',
        'drying' => 'Drying',
        'folding' => 'Folding',
        'ready' => 'Ready for Pickup',
        'claimed' => 'Claimed',
        'cancelled' => 'Cancelled',
    ],

    'status_colors' => [
        'pending' => 'gray',
        'washing' => 'blue',
        'drying' => 'yellow',
        'folding' => 'purple',
        'ready' => 'green',
        'claimed' => 'teal',
        'cancelled' => 'red',
    ],

    'units' => [
        'ml' => 'Milliliters (ml)',
        'l' => 'Liters (L)',
        'g' => 'Grams (g)',
        'kg' => 'Kilograms (kg)',
        'pcs' => 'Pieces (pcs)',
    ],

    'currency' => 'PHP',
    'currency_symbol' => '₱',
];
