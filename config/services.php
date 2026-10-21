<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'AVPS_ACCESS_TOKEN' => env('AVPS_ACCESS_TOKEN'),
    'AVPS_API_URL' => env('AVPS_API_URL'),
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    'bookfhr' => [
        'base_url' => env('BOOKFHR_BASE_URL', 'https://www.bookfhr.com/api'),
        'hotel_base_url' => env('BOOKFHR_HOTEL_BASE_URL'),
        'token' => env('BOOKFHR_TOKEN'),
        'hotel_token' => env('BOOKFHR_HOTEL_TOKEN'),
        'source' => env('BOOKFHR_SOURCE', 'FHR'),
        'partner_id' => env('BOOKFHR_PARTNER_ID', ''),
        'booking_secret' => env('BOOKFHR_BOOKING_SECRET', ''),
        'origin' => env('BOOKFHR_ORIGIN', env('APP_URL')),
    ],

    'enjoytravel' => [
        // Set CARHIRE_ENABLED=false to hide booking and show Coming Soon again.
        'enabled' => filter_var(env('CARHIRE_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'base_url' => env('ENJOYTRAVEL_BASE_URL', 'https://ettapi-test.azurewebsites.net'),
        'auth_url' => env('ENJOYTRAVEL_AUTH_URL', 'https://auth.enjoytravel.com'),
        'username' => env('ENJOYTRAVEL_USERNAME'),
        'password' => env('ENJOYTRAVEL_PASSWORD'),
        'client_id' => env('ENJOYTRAVEL_CLIENT_ID'),
        'client_secret' => env('ENJOYTRAVEL_CLIENT_SECRET'),
        'source' => env('ENJOYTRAVEL_SOURCE'),
        'scope' => env('ENJOYTRAVEL_SCOPE', 'Enjoy.API'),
        'lang' => env('ENJOYTRAVEL_LANG', 'en-gb'),
        'currency' => env('ENJOYTRAVEL_CURRENCY', 'GBP'),
        'location_id' => env('ENJOYTRAVEL_LOCATION_ID', 7857),
    ],

];
