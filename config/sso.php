<?php

return [
    'key' => env('SSO_PUBLIC_KEY'),
    'realm-url' => env('SSO_REALM_URL'),
    'token-url' => env('SSO_TOKEN_URL'),
    'client-id' => env('SSO_CLIENT_ID'),
    'client-secret' => env('SSO_CLIENT_SECRET'),
    'jwt-leeway' => env('SSO_LEEWAY_SECONDS', 1800),
];
