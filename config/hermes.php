<?php

return [
    'url' => env("HERMES_API_URL", "https://hermes.helte.com.br"),
    'queue' => env("HERMES_QUEUE", "Hermes-Queue-Dev"),
    'onPremise' => [
        'queue' => env("HERMES_ONPREMISE_QUEUE", "Hermes-Queue-OnPremise"),
    ],
    'elasticsearch' => [
        'enabled' => env('HERMES_ELASTICSEARCH_ENABLED', false)
    ],
    'authentication' => [
        'client_credentials' => [
            'client_id' => env("HERMES_CLIENT_ID"),
            'client_secret' => env("HERMES_CLIENT_SECRET"),
        ],
        'token_settings' =>[
            'token_index' => 'token_hermes',
            'token_until_index' => 'token_until_hermes'
        ]
    ]
];