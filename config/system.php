<?php

return [
    'app' => [
        'name' => 'Royal SaaS Starter',
        'env' => 'local',
        'debug' => true,
    ],
    'addons' => [
        'enabled' => true,
        'statuses' => [
            'image-converter' => false,
            'pdf-converter' => true,
            'mp3-converter' => false,
            'invoice-generator' => false,
            'id-card-generator' => false,
            'letterhead-generator' => false,
            'qr-generator' => false,
        ],
    ],
    'license' => [
        'server_url' => env('LICENSE_SERVER_URL', 'https://server.4amtech.in/api/license'),
        'require_online' => env('LICENSE_REQUIRE_ONLINE', true),
    ],
];
