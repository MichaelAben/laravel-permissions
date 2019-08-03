<?php

namespace Maben\Permissions;

use Illuminate\Support\ServiceProvider;
use Maben\Permissions\Commands\CreatePermission;

class PermissionsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/permissions.php', 'permissions');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/permissions.php' => config_path('permissions.php'),
        ], 'maben/permissions');

        $this->publishes([
            __DIR__.'/Migrations/maben_permissions.php' =>
            database_path('/migrations/' . date('Y_m_d_His') . '_maben_permissions.php'),
        ], 'maben/permissions');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePermission::class,
            ]);
        }
    }
}
