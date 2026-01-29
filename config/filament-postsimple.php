<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PostSimple API Key
    |--------------------------------------------------------------------------
    |
    | Your PostSimple API key. You can request one at api@postsimple.nl
    | (Pro subscription required).
    |
    */
    'api_key' => env('POSTSIMPLE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | PostSimple API Endpoint
    |--------------------------------------------------------------------------
    |
    | The API endpoint for sending content to PostSimple.
    | You should not need to change this unless instructed by PostSimple.
    |
    */
    'api_endpoint' => env('POSTSIMPLE_API_ENDPOINT', 'https://postsimple.link/api/plugins/create-post'),

    /*
    |--------------------------------------------------------------------------
    | PostSimple App URL
    |--------------------------------------------------------------------------
    |
    | The URL where users will be redirected after sending content.
    |
    */
    'app_url' => env('POSTSIMPLE_APP_URL', 'https://my.postsimple.app/lab'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum time (in seconds) to wait for a response from PostSimple API.
    |
    */
    'timeout' => env('POSTSIMPLE_TIMEOUT', 30),
];
