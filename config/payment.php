<?php

return [
    'qris_image' => env('PAYMENT_QRIS_IMAGE', 'images/qris-placeholder.svg'),
    'monthly_price' => (int) env('PAYMENT_MONTHLY_PRICE', 49000),
    'annual_price' => (int) env('PAYMENT_ANNUAL_PRICE', 490000),
    'unique_code_min' => (int) env('PAYMENT_UNIQUE_CODE_MIN', 100),
    'unique_code_max' => (int) env('PAYMENT_UNIQUE_CODE_MAX', 999),
    'instructions' => env('PAYMENT_INSTRUCTIONS', 'Pindai kode QRIS, transfer sesuai nominal total, lalu unggah bukti pembayaran.'),
];
