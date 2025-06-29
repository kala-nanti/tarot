<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers'; // <<< PENTING: PASTIKAN BARIS INI ADA!

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Pastikan RateLimiter juga ada di boot() jika Anda menggunakannya
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Rute API bawaan Laravel
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rute web bawaan Laravel
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // INI BAGIAN PENTING UNTUK ADMIN
            Route::middleware('web')
                ->namespace($this->namespace . '\Admin') // Mengacu ke App\Http\Controllers\Admin
                ->prefix('admin') // Awalan URL /admin
                ->as('admin.') // Awalan nama route admin.
                ->group(base_path('routes/admin.php'));
        });
    }
}