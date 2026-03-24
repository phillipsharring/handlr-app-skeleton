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
use App\Profile\GetProfilePipe;
use App\Profile\PatchUpdateEmail;
use App\Profile\PatchUpdatePassword;
use App\Profile\PatchUpdateUsername;
use Handlr\Ab\Pipes\CaptureAbEvent;
use Handlr\Ab\Pipes\GetAbAssignments;
use Handlr\Ab\Pipes\GetAbTestResults;
use Handlr\Ab\Pipes\GetAbTests;
use Handlr\Ab\Pipes\PatchUpdateAbTest;
use Handlr\Ab\Pipes\PostCreateAbTest;
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

    // ── A/B Testing (session only — no CSRF, no auth) ──
    ->through([StartSessionPipe::class])
        ->get('/ab/assignments', [GetAbAssignments::class])
        ->post('/ab/capture', [CaptureAbEvent::class])
    ->end()

    ->through([StartSessionPipe::class, SessionAuthPipe::class, EnsureCsrfTokenPipe::class, VerifyCsrfTokenPipe::class])

        // ── Auth (public) ──
        ->group('/auth')
            ->get('/me', [GetAuthStatus::class])
            ->post('/login', [PostLoginAttempt::class])
            ->post('/signup', [PostSignup::class])
            ->get('/logout', [GetLogout::class])
        ->end()

        // ── A/B Testing (public — needs session, not auth) ──
        ->get('/ab/assignments', [GetAbAssignments::class])
        ->post('/ab/capture', [CaptureAbEvent::class])

        // ── Authenticated routes ──
        ->through([RequireAuthPipe::class])

            // ── Profile ──
            ->group('/profile')
                ->get('', [GetProfilePipe::class])
                ->patch('/username', [PatchUpdateUsername::class])
                ->patch('/email', [PatchUpdateEmail::class])
                ->patch('/password', [PatchUpdatePassword::class])
            ->end()

            // Add your authenticated API routes here

            // ── Admin: A/B Tests ──
            // ->through([new RequirePermissionPipe('admin.access')])
            //     ->group('/admin/ab')
            //         ->get('/', [GetAbTests::class])
            //         ->post('/', [PostCreateAbTest::class])
            //         ->get('/{id}', [GetAbTestResults::class])
            //         ->patch('/{id}', [PatchUpdateAbTest::class])
            //     ->end()
            // ->end()

        ->end()

    ->end()
->end();
