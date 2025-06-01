<?php

namespace Sensy\UpdatePatches;

use Sensy\UpdatePatches\Commands\CreatePatchCommand;
use Sensy\UpdatePatches\Commands\DeployPatchUpdateCommand;
use Sensy\UpdatePatches\Commands\ReRunPatch;
use Illuminate\Support\ServiceProvider;

class UpdatePatchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        // Load migrations from the package
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        // Register package commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePatchCommand::class,
                DeployPatchUpdateCommand::class,
                ReRunPatch::class
            ]);

            // Publish configuration file
            $this->publishes([
                __DIR__ . '/config/update-patch.php' => config_path('update-patch.php'),
            ], 'update-patch-config');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Merge package configuration with app config
        $this->mergeConfigFrom(__DIR__ . '/config/update-patch.php', 'update-patch.php');
    }
}
