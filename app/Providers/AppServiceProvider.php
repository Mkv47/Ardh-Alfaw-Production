<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTP scheme so route() always generates http:// URLs.
        // This prevents scheme mismatch (http page / https form action)
        // when accessed through a tunnel like Tunnelmole.
        URL::forceScheme('http');
    }
}
