<?php

/**
 * Application Routes
 *
 * @var Router $router The router instance provided by the Kernel
 */

use App\Auth\GetAuthStatus;
use App\Auth\Login\PostLoginAttempt;
use App\Auth\Logout\GetLogout;
use App\Auth\Signup\PostSignup;
use Handlr\Auth\Pipes\RequireAuthPipe;
use Handlr\Auth\Pipes\RequirePermissionPipe;
use Handlr\Auth\Pipes\SessionAuthPipe;
use Handlr\Auth\Pipes\StartSessionPipe;
use Handlr\Core\Routes\Router;
use Handlr\Csrf\CorsPipe;
use Handlr\Csrf\EnsureCsrfTokenPipe;
use Handlr\Csrf\VerifyCsrfTokenPipe;
use Handlr\Csrf\VerifyOriginPipe;
use Handlr\Pipes\ViewPipe;

/** @var Router $router */

// ── Public pages ──
$router->get('/', [new ViewPipe('home')]);

// ── API ──
$router->group('/api', [CorsPipe::class, VerifyOriginPipe::class])
    ->through([StartSessionPipe::class, SessionAuthPipe::class, EnsureCsrfTokenPipe::class, VerifyCsrfTokenPipe::class])

        // ── Auth (public) ──
        ->group('/auth')
            ->get('/me', [GetAuthStatus::class])
            ->post('/login', [PostLoginAttempt::class])
            ->post('/signup', [PostSignup::class])
            ->get('/logout', [GetLogout::class])
        ->end()

        // ── Authenticated routes ──
        ->through([RequireAuthPipe::class])

            // Add your authenticated API routes here

        ->end()

    ->end()
->end();
