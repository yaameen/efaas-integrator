<?php

return [
    'client_id' => env('EFAAS_CLIENT_ID', '0ac9b753-e2a9-4d35-86ba-242f3fe2f02b'),
    'client_secret' => env('EFAAS_CLIENT_SECRET', 'e29329bc-ecf3-4782-acdd-d48f32d28d5e'),
    'post_logout_url' => env('EFAAS_POST_LOGOUT_URI', 'https://online-services.test/oauth/logout'),
    'redirect_uri' => env('EFAAS_REDIRECT_URI', 'https://online-services.test/oauth/callback'),
    'response_type' => env('EFAAS_RESPONSE_TYPE', 'id_token token'),
    'grant_type' =>  env('EFAAS_GRANT_TYPE', 'authorization_code'),
    'scope' =>  env('EFAAS_SCOPE', 'openid profile'),

    'oauth_route' =>  env('EFAAS_OAUTH_ROUTE', 'https://developer.egov.mv/efaas/connect'),
    'token_route' =>  env('EFAAS_TOKEN_ROUTE', 'https://developer.egov.mv/efaas/connect/token'),

    'session_name' =>  env('EFAAS_SESSION_NAME', 'openid profile'),

    'end_session_uri' => env('EFAAS_END_SESSION_URI', 'https://developer.egov.mv/efaas/connect/endsession'),

    'app' => [
        'home' => '/',
        'login_url' => env('EFAAS_LOGIN_URI', '/login'),
        'callback_url' => env('EFAAS_CALLBACK_URL', '/oauth/callback'),
    ],

    'user_model' => App\User::class,

];
