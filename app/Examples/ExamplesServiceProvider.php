<?php

declare(strict_types=1);

namespace App\Examples;

use Handlr\Core\Routes\Router;
use Handlr\Core\ServiceProvider;

/**
 * Wires up a small set of demo endpoints under /api/examples.
 *
 * The pipes are intentionally trivial — they exist so a fresh app has
 * working endpoints to point HTMX, fetch, or curl at while exercising
 * the response/error/toast/field-error patterns the framework provides.
 *
 * Routes attach to the `api.public` junction declared in `app/routes.php`,
 * which gives them CORS + VerifyOrigin + StartSession. The session pipe
 * is harmless for these stateless endpoints; attaching to `api.public`
 * means dropping this provider into `config.php` is the only wiring step,
 * with no edits to `app/routes.php`.
 *
 * To remove the examples module: delete `app/Examples/` and remove
 * `App\Examples\ExamplesServiceProvider::class` from the providers list
 * in `app/config.php`. Nothing else references this module.
 */
class ExamplesServiceProvider extends ServiceProvider
{
    public function routes(Router $router): void
    {
        $router->intoJunction('api.public')
            ->group('/examples')
                ->get('/hello',           [HelloPipe::class])
                ->post('/echo',           [EchoPipe::class])
                ->get('/toast',           [ToastPipe::class])
                ->get('/detail',          [ExampleDetailPipe::class])
                ->post('/field-errors',   [FieldErrorsPipe::class])
                ->post('/invariant-error', [InvariantErrorPipe::class])
                ->post('/success',        [SuccessPipe::class])
            ->end();
    }
}
