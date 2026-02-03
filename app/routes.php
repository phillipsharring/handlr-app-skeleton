<?php

/**
 * Application Routes
 *
 * Defines the HTTP routes for the application using the Handlr Router.
 * Routes are defined as a pipeline of Pipe classes that handle the request/response cycle.
 *
 * The $router variable is injected by the Kernel when this file is loaded.
 *
 * @example Defining a simple GET route
 * ```php
 * $router->get('/hello', [HelloPipe::class]);
 * ```
 *
 * @example Defining a route with multiple pipes (middleware pattern)
 * ```php
 * $router->post('/api/users', [
 *     AuthMiddlewarePipe::class,
 *     ValidateInputPipe::class,
 *     CreateUserPipe::class,
 * ]);
 * ```
 *
 * @example Grouping routes with a shared prefix
 * ```php
 * $router->group('/api')
 *     ->get('/items', [ListItemsPipe::class])
 *     ->post('/items', [CreateItemPipe::class])
 *     ->end();
 * ```
 *
 * @example Grouping routes with shared middleware
 * ```php
 * $router->group('/admin', [AdminAuthPipe::class])
 *     ->get('/dashboard', [DashboardPipe::class])
 *     ->get('/users', [ListUsersPipe::class])
 *     ->end();
 * ```
 *
 * @example Route parameters with type constraints
 * ```php
 * $router->get('/users/{id:uuid}', [GetUserPipe::class]);
 * $router->get('/posts/{slug}', [GetPostPipe::class]);
 * ```
 *
 * @var Router $router The router instance provided by the Kernel
 */

use Handlr\Core\Routes\Router;
use Handlr\Pipes\ViewPipe;

/** @var Router $router */
$router->get('/', [
    new ViewPipe('home'),
]);
