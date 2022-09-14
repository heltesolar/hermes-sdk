<?php

return [
    'queue' => env("HERMES_QUEUE", "Hermes-Queue-Dev"),
    'elasticsearch' => [
        'enabled' => env('HERMES_ELASTICSEARCH_ENABLED', false)
    ]
];