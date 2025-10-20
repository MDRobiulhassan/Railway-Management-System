<?php

return [
    'store_id' => env('SSLCOMMERZ_STORE_ID', 'bangl68ef9ee22c083'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', 'bangl68ef9ee22c083@ssl'),
    'mode' => env('SSLCOMMERZ_MODE', 'sandbox'), // 'sandbox' or 'live'
    
    'sandbox_url' => 'https://sandbox.sslcommerz.com',
    'live_url' => 'https://securepay.sslcommerz.com',
    
    'success_url' => env('APP_URL') . '/payment/success',
    'fail_url' => env('APP_URL') . '/payment/fail',
    'cancel_url' => env('APP_URL') . '/payment/cancel',
    'ipn_url' => env('APP_URL') . '/payment/ipn',
];
