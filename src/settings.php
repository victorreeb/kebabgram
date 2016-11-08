<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'db' => [
          'driver' => 'mysql',
          'host' => 'localhost',
          'database' => 'kebabgram',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'prefix' => '',
        ],
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../ressources/views/templates',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
