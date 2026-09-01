<?php

return [

    'razorpay' => [
        'key_id' => env('RAZORPAY_KEY_ID'),
        'key_secret' => env('RAZORPAY_KEY_SECRET'),
    ],

    'upi' => [
        'id' => env('UPI_ID'),
        'payee_name' => env('UPI_PAYEE_NAME', 'Pandiyan Store'),
    ],

    'whatsapp' => [
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'api_version' => env('WHATSAPP_API_VERSION', 'v25.0'),
        'admin_phone' => env('WHATSAPP_ADMIN_PHONE'),
        'customer_template' => env('WHATSAPP_CUSTOMER_TEMPLATE', 'order_confirmation'),
        'admin_template' => env('WHATSAPP_ADMIN_TEMPLATE', 'new_order_admin'),
        'template_language' => env('WHATSAPP_TEMPLATE_LANGUAGE', 'en_US'),
    ],

    'contact' => [
        'whatsapp' => env('CONTACT_WHATSAPP_URL'),
        'instagram' => env('CONTACT_INSTAGRAM_URL'),
        'facebook' => env('CONTACT_FACEBOOK_URL'),
        'youtube' => env('CONTACT_YOUTUBE_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
