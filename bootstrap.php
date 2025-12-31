<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('HANDLR_APP_ROOT')) {
    define('HANDLR_APP_ROOT', __DIR__);
}

const HANDLR_APP_APP_PATH = __DIR__ . '/app';

use Handlr\Config\Loader;
use Handlr\Core\Container\Container;

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
    $configPath = HANDLR_APP_APP_PATH . '/config.php';
    $config = Loader::load($configPath, $container);

    $app = [
        'container' => $container,
        'config' => $config,
    ];

    return $app;
}
