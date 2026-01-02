<?php

use Handlr\Core\Routes\Router;
use Handlr\Pipes\ViewPipe;

/** @var Router $router */
$router->get('/', [
    new ViewPipe('home'),
]);
