<?php

return [
    'queue' => env("HERMES_QUEUE", "Hermes-Queue-Dev"),
    'onPremise' => [
        'queue' => env("HERMES_ONPREMISE_QUEUE", "Hermes-Queue-OnPremise"),
    ],
    'elasticsearch' => [
        'enabled' => env('HERMES_ELASTICSEARCH_ENABLED', false)
    ]
];