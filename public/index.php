<?php

require_once __DIR__ . '/../bootstrap.php';

use Handlr\Core\Kernel;
use Handlr\Core\Request;
use Handlr\Core\Response;
use Handlr\Core\Routes\Router;

try {
    $app = handlr_app();
    $container = $app['container'];

    $router = new Router($container);
    $kernel = Kernel::getInstance($container, $router, HANDLR_APP_ROOT);

    $request = Request::fromGlobals();
    $response = new Response;

    $response = $kernel->getRouter()->dispatch($request, $response);
    $response->send();
} catch (Throwable $e) {
    $response = new Response;
    $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';

    $errorData = [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];

    if (!$isProduction) {
        $errorData['debug'] = [
            'type' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];
    }

    $response->withJson($errorData, Response::HTTP_SERVER_ERROR)->send();
}
