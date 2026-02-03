<?php

/**
 * Application Configuration
 *
 * Returns the configuration array for the Handlr application. Configuration values
 * are loaded from environment variables with sensible defaults for local development.
 *
 * This file is loaded by the bootstrap via Handlr\Config\Loader and the values
 * are made available through the DI container.
 *
 * @example Loading config through the Loader
 * ```php
 * use Handlr\Config\Loader;
 *
 * $config = Loader::load(HANDLR_APP_APP_PATH . '/config.php', $container);
 * $dsn = $config['database']['dsn'];
 * ```
 *
 * @example Accessing config values from the app
 * ```php
 * $app = handlr_app();
 * $isDebug = $app['config']['app']['debug'];
 * ```
 *
 * @return array{
 *     app: array{env: string, debug: bool},
 *     database: array{dsn: string, user: string, password: string, options: array}
 * }
 */
return [
    'app' => [
        'env' => $_ENV['APP_ENV'] ?? 'local',
        'debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    ],
    'database' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
    ],
];
