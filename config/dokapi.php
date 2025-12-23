<?php

return [
    'api_endpoint' => env('DOKAPI_API_ENDPOINT', 'https://peppol-api.dokapi-stg.io'),
    'oauth2_api_endpoint' => env('DOKAPI_OAUTH2_API_ENDPOINT', 'https://ixordocs.apiable.io/api/oauth2/token'),
    'client_id' => env('DOKAPI_CLIENT_ID'),
    'client_secret' => env('DOKAPI_CLIENT_SECRET'),
];
