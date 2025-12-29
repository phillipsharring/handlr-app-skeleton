<?php

require_once __DIR__ . '/../bootstrap.php';

use Handlr\Config\Loader;
use Handlr\Core\Container\Container;
use Handlr\Core\Kernel;
use Handlr\Core\Request;
use Handlr\Core\Response;
use Handlr\Core\Router;

$container = new Container();
$configPath = HANDLR_APP_APP_PATH . '/config.php';
Loader::load($configPath, $container);

$router = new Router($container);
$kernel = Kernel::getInstance($container, $router, HANDLR_APP_ROOT);

$request = Request::fromGlobals();
$response = new Response;

$response = $kernel->getRouter()->dispatch($request, $response);
$response->send();
