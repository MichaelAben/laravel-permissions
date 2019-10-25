<?php

namespace MabenDev\Permissions;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PermissionProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/MabenDevPermissionConfig.php', 'MabenDevPermissions');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/MabenDevPermissionConfig.php' => config_path('MabenDevPermissions.php'),
        ], 'MabenDev/permissions');

        $this->loadMigrationsFrom( __DIR__.'/Migrations/');

        if($this->app->runningInConsole()) {
            $this->commands([
                \MabenDev\Permissions\Commands\Role\Make::class,
                \MabenDev\Permissions\Commands\Role\Give::class,
                \MabenDev\Permissions\Commands\Permission\Make::class,
                \MabenDev\Permissions\Commands\Permission\Give::class,
            ]);
        }

        BladeExtentions::register();
    }
}
