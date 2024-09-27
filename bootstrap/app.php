<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (Throwable $exception, $request) {
            // Laravel'in varsayılan istisna işleyicilerini koru
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return redirect()->guest(route('login'));
            }

            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->view('errors.403', [], 403);
            }

            if ($exception instanceof \App\Exceptions\ModelNotFoundException || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }
            if ($exception instanceof \App\Exceptions\CustomException) {
                return (new \App\Http\Responses\ErrorResponse($exception));
            }

            //return parent::render($request, $exception);

            // Diğer tüm durumlarda genel bir hata sayfası döndür
            return response()->view('errors.error', ['exception' => $exception]);
        });

    })->create();
