<?php
declare(strict_types=1);

return [
    'default' => env('ELASTIC_CONNECTION', 'default'),
    'connections' => [
        'default' => [
            'hosts' => [
                env('ELASTIC_SEARCH_HOST', 'localhost') . ':' . env('ELASTIC_SEARCH_PORT', 9200),
            ],
        ],
    ],
];
