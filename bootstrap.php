<?php

/**
 * Application Bootstrap
 *
 * Initializes the Handlr application by loading dependencies, environment variables,
 * and configuring the DI container. This file is shared by both web entrypoints
 * (public/index.php) and CLI scripts (migrations, seeders, etc.).
 *
 * @example Bootstrapping the app in a web entrypoint
 * ```php
 * require_once __DIR__ . '/../bootstrap.php';
 *
 * $app = handlr_app();
 * $container = $app['container'];
 * $config = $app['config'];
 * ```
 *
 * @example Using the bootstrap in a CLI script
 * ```php
 * require_once __DIR__ . '/bootstrap.php';
 *
 * $app = handlr_app();
 * $db = $app['container']->make(DbInterface::class);
 * ```
 *
 * @example Accessing the config after bootstrap
 * ```php
 * $app = handlr_app();
 * $dsn = $app['config']['database']['dsn'];
 * $isDebug = $app['config']['app']['debug'];
 * ```
 */

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('HANDLR_APP_ROOT')) {
    define('HANDLR_APP_ROOT', __DIR__);
}

const HANDLR_APP_APP_PATH = __DIR__ . '/app';

use App\Auth\AuthContext;
use App\Events\EventServiceProvider;
use Handlr\Config\Loader;
use Handlr\Core\Container\Container;
use Handlr\Core\EventManager;
use Handlr\Database\Db;
use Handlr\Database\DbInterface;
use Handlr\Log\Logger;
use Psr\Log\LoggerInterface;

/**
 * App bootstrap used by both web entrypoints and CLI scripts.
 *
 * - Loads Composer autoload (above)
 * - Defines app path constants
 * - Loads .env into $_ENV
 * - Loads config into the DI container
 *
 * IMPORTANT: This must NOT create a Request or dispatch the router/kernel.
 *
 * @return array{container: Container, config: array}
 */
function handlr_app(): array
{
    static $app = null;
    if (is_array($app)) {
        return $app;
    }

    // Load environment variables for both web + CLI usage.
    if (class_exists(\Dotenv\Dotenv::class)) {
        \Dotenv\Dotenv::createImmutable(HANDLR_APP_ROOT)->safeLoad();
    }

    $container = new Container();
    $container->bind(DbInterface::class, Db::class);
    $container->singleton(AuthContext::class);
    $container->singleton(EventManager::class);
    $container->singleton(LoggerInterface::class, new Logger(HANDLR_APP_ROOT . '/logs/app.log'));

    $configPath = HANDLR_APP_APP_PATH . '/config.php';
    $config = Loader::load($configPath, $container);

    // Register event listeners
    EventServiceProvider::register($container, $container->get(EventManager::class));

    $app = [
        'container' => $container,
        'config' => $config,
    ];

    return $app;
}
