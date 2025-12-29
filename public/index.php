<?php

require_once __DIR__ . '/../bootstrap.php';

use Handlr\Core\Container\Container;
use Handlr\Core\Kernel;
use Handlr\Core\Request;
use Handlr\Core\Response;
use Handlr\Core\Router;

$container = new Container();
$router = new Router($container);
$kernel = Kernel::getInstance($container, $router, HANDLR_APP_ROOT);

$request = Request::fromGlobals();
$response = new Response;

$response = $kernel->getRouter()->dispatch($request, $response);
$response->send();
