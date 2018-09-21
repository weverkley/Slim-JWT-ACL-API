<?php

return [
    'settings' => [
        'displayErrorDetails' => (bool)getenv('API_DEBUG'), // set to false in production

        'addContentLengthHeader' => true, // Allow the web server to send the content-length header

        'determineRouteBeforeAppMiddleware' => true,

        'renderer' => [
            'template_path' => __DIR__ . '/../Siit/View/',
        ],

        'logger' => [
            'name' => 'slim-app',
            'level' => (int)getenv('LOG_LEVEL') ?: 400,
            'path' => __DIR__ . '/../logs/app.log',
        ],

        'db' => [
            'host' => (string)getenv('DB_HOST'),
            'user' => (string)getenv('DB_USER'),
            'pass' => (string)getenv('DB_PASS'),
            'dbname' => (string)getenv('DB_NAME'),
        ]
    ],
];
