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
    'weather' => [
    'key' => env('WEATHER_API_KEY'),
],
    'windy' => [
    'key' => env('WINDY_API_KEY'),
],
'groq' => [
    'key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama-3.2-11b-vision-instruct'),
],


'gemini_summary' => [
    'key'   => env('GEMINI_SUMMARY_API_KEY'),
    'model' => env('GEMINI_SUMMARY_MODEL', 'gemini-3.6-flash'), // Digunakan khusus Admin Summary
],

    'gemini' => [
    'key'   => env('GEMINI_API_KEY'),
    'keys'  => env('GEMINI_API_KEYS'),
    'model' => env('GEMINI_MODEL', 'gemini-3.6-flash'),
],

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
