<?php

namespace MabenDev\Permissions;

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
            __DIR__.'/Config/MabenDevPermissionConfig.php' => config_path('MabenDevPermissionConfig.php'),
        ], 'MabenDev/permissions');

        $this->loadMigrationsFrom( __DIR__.'/Migrations/');
    }
}
