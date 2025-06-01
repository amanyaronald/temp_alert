<?php

namespace Sensy\Scrud\Providers;

use Sensy\Scrud\Commands\CreateUser;
use Sensy\Scrud\Commands\CrudScaffold;
use Sensy\Scrud\Commands\Deploy;
use Sensy\Scrud\Commands\Extractor;
use Sensy\Scrud\Commands\Install;
use Sensy\Scrud\Commands\ModuleScaffold;
use Sensy\Scrud\Commands\PermissionSync;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sensy\Scrud\Commands\CreateApiDocumentation;

class ScrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Use Bootstrap for pagination
        \Illuminate\Pagination\Paginator::useBootstrap();

        // Register Blade components
        Blade::component('scrud::guest-layout', \Sensy\Scrud\View\Components\GuestLayout::class);
        Blade::component('scrud::admin-layout', \Sensy\Scrud\View\Components\AdminLayout::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only register package routes after Laravel routes are loaded
        $this->app->booted(function () {
            // Add Scrud details to Laravel's "About" command
            AboutCommand::add('Scrud by Sensy', fn() => ['Version' => '1.0.7', 'Author' => 'Sensy']);

            // Load helper file
            require_once __DIR__ . '/../app/Http/Helpers/helper.php';

            // Merge configuration
            $this->mergeConfigFrom(__DIR__ . '/../configs/scrud.php', 'scrud');

            // Load routes
            $this->loadRoutes();

            // Load views
            $this->loadViews();

            // Load migrations
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            // Load publishable assets
            $this->loadAssets(app_path('/Scrud/View'));

            // Load middlewares
            $this->loadMiddlewares();

            // Register commands
            $this->registerCommands();
        });

    }

    /**
     * Load application routes.
     */
    protected function loadRoutes(): void
    {


        // Check if the main routes file exists
        if (file_exists(base_path('routes/api.php'))) {
            // Load the main routes file first
            require base_path('routes/api.php');
        }


        // Load custom scrud routes if they exist in the base directory
        if (file_exists(base_path('routes/scrud.php'))) {
            $this->loadRoutesFrom(base_path('routes/scrud.php'));
        }
        if (file_exists(base_path('routes/scrud-api.php'))) {
            $this->loadRoutesFrom(base_path('routes/scrud-api.php'));
        }

        // Load default scrud routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/scrud.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/scrud-api.php');
    }

    /**
     * Load application views.
     */
    protected function loadViews(): void
    {
        if (file_exists(base_path('resources/views/scrud'))) {
            // Load views from the app's resources directory
            $this->loadViewsFrom(base_path('resources/views/scrud'), 'scrud');
            Blade::componentNamespace('App\\Scrud\\View', 'scrud');
        } else {
            // Load default scrud views
            Blade::componentNamespace('Sensy\\Scrud\\View\\Components', 'scrud');
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'scrud');
        }
    }

    /**
     * Load publishable assets.
     *
     * @param string $layoutPath
     */
    protected function loadAssets(string $layoutPath): void
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('/'),
            __DIR__ . '/../routes/scrud.php' => base_path('/routes/scrud.php'),
            __DIR__ . '/../resources/views' => base_path('/resources/views/scrud/'),
            __DIR__ . '/../View/Components' => $layoutPath,
            __DIR__ . '/../resources/views/auth' => resource_path('views/auth'),
        ], 'scrud');
    }

    /**
     * Register custom Artisan commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            CrudScaffold::class,
            CreateUser::class,
            Deploy::class,
            Extractor::class,
            ModuleScaffold::class,
            PermissionSync::class,
            Install::class,
            CreateApiDocumentation::class,
        ]);
    }

    /**
     * Recursively delete a directory.
     *
     * @param string $dir
     * @return bool
     */
    public function deleteDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Dynamically load and register middlewares.
     */
    protected function loadMiddlewares(): void
    {
        // Define an array of middleware classes and their aliases
        $middlewares = [
            'resolve.service' => \Sensy\Scrud\app\Middleware\ServiceResolutionMiddleware::class,
//            'auth.custom' => \App\Http\Middleware\CustomAuthMiddleware::class,
//            'role.check' => \App\Http\Middleware\RoleCheckMiddleware::class,
        ];

        // Register each middleware with its alias
        foreach ($middlewares as $alias => $middlewareClass) {
            Route::aliasMiddleware($alias, $middlewareClass);
        }
    }

}
