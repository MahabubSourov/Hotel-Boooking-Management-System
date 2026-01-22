<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel_booking');

// Application Configuration
define('SITE_URL', 'http://localhost/hotel_booking');
define('SITE_NAME', 'N3XTSTAY');
define('ADMIN_EMAIL', 'admin@hotelbooking.com');

// Payment Gateway Configuration
// bKash Configuration
define('BKASH_APP_KEY', 'your_bkash_app_key');
define('BKASH_APP_SECRET', 'your_bkash_app_secret');
define('BKASH_USERNAME', 'your_bkash_username');
define('BKASH_PASSWORD', 'your_bkash_password');
define('BKASH_BASE_URL', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'); // Change to production URL when live

// Nagad Configuration
define('NAGAD_MERCHANT_ID', 'your_nagad_merchant_id');
define('NAGAD_MERCHANT_NUMBER', 'your_nagad_merchant_number');
define('NAGAD_PUBLIC_KEY', 'your_nagad_public_key');
define('NAGAD_PRIVATE_KEY', 'your_nagad_private_key');
define('NAGAD_BASE_URL', 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs'); // Change to production URL when live

// Card Payment Configuration (SSLCommerz)
define('SSLC_STORE_ID', 'your_sslcommerz_store_id');
define('SSLC_STORE_PASSWORD', 'your_sslcommerz_store_password');
define('SSLC_TESTMODE', true); // Set to false for production

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
}

// Timezone
date_default_timezone_set('Asia/Dhaka');

// Error Reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
