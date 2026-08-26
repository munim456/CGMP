<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('admin*')) {
                return redirect()->guest(route('admin.login'));
            }

            return redirect()->guest(route('home'));
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->isMethod('GET') && ! $request->is('admin*', 'storage/*')) {
                $hit = \App\Models\Redirect::lookup($request->getPathInfo());

                if ($hit !== null) {
                    return redirect()->to($hit['destination'], $hit['status_code']);
                }
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found.'], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    })->create();
