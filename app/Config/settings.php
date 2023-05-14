<?php
return [
    'settings' => [
        'db' => [
            'host' => getenv('DB_HOST'),
            'user' => getenv('DB_USER'),
            'database' => getenv('DB_NAME'),
            'password' => getenv('DB_PASSWORD')
        ]
    ]
];