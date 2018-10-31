<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapChamberRoutes();
        $this->mapImportRoutes();
        $this->mapExportRoutes();
        $this->mapFunRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "chambers" routes for MAGMA dashboard.
     * 
     * @return void
     */
    protected function mapChamberRoutes()
    {
        Route::prefix('chambers')
             ->name('chambers.')
             ->middleware(['web','auth','revalidate'])
             ->namespace($this->namespace)
             ->group(base_path('routes/chambers.php'));
    }

    /**
     * Define the "import" routes for MAGMA dashboard.
     *
     * @return void
     */
    protected function mapImportRoutes()
    {
        Route::prefix('chambers/import')
             ->name('chambers.import.')
             ->middleware(['web','auth','revalidate'])
             ->namespace($this->namespace)
             ->group(base_path('routes/import.php'));
    }

    /**
     * Define the "export" routes for MAGMA dashboard.
     *
     * @return void
     */
    protected function mapExportRoutes()
    {
        Route::prefix('chambers/v1/export')
             ->name('chambers.v1.export.')
             ->middleware(['web','auth','revalidate'])
             ->namespace($this->namespace)
             ->group(base_path('routes/export.php'));
    }

    /**
     * Define the "fun for FPL" routes for MAGMA dashboard.
     *
     * @return void
     */
    protected function mapFunRoutes()
    {
        Route::prefix('chambers/fun')
             ->name('chambers.fun.')
             ->middleware(['web','auth','revalidate'])
             ->namespace($this->namespace)
             ->group(base_path('routes/fun.php'));
    }
}
