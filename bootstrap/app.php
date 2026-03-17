<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\App\Http\Middleware\TrustFrameworkRequests::class);
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: ['admin/login']);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // File too large → back with a readable error
        $exceptions->render(function (PostTooLargeException $e, $request) {
            $maxMb = round(ini_get('post_max_size') ?: '256');
            if ($request->expectsJson()) {
                return response()->json(['error' => "الملف كبير جداً. الحد الأقصى هو {$maxMb}MB."], 413);
            }
            return back()->withErrors(['file' => "الملف كبير جداً. الحد الأقصى المسموح به هو {$maxMb}MB."]);
        });

        // Too many requests → 429 page
        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'طلبات كثيرة جداً. يرجى الانتظار قليلاً.'], 429);
            }
            return response()->view('errors.429', [], 429);
        });

        // 404 → custom page
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'المورد غير موجود.'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // 403 → custom page
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'غير مصرح بالدخول.'], 403);
            }
            return response()->view('errors.403', [], 403);
        });

    })->create();
