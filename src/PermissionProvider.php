<?php

namespace MabenDev\Permissions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MabenDev\Permissions\Middleware\CheckPermission;

/**
 * Class PermissionProvider
 * @package MabenDev\Permissions
 *
 * @author Michael Aben
 */
class PermissionProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/MabenDevPermissionConfig.php', 'MabenDevPermissions');
    }

    /**
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/MabenDevPermissionConfig.php' => config_path('MabenDevPermissions.php'),
        ], 'config');

        $this->loadMigrationsFrom( __DIR__.'/Migrations/');

        $this->app['router']->aliasMiddleware('CheckPermission', CheckPermission::class);

        if($this->app->runningInConsole()) {
            $this->commands([
                \MabenDev\Permissions\Commands\Role\Make::class,
                \MabenDev\Permissions\Commands\Role\Give::class,
                \MabenDev\Permissions\Commands\Permission\Make::class,
                \MabenDev\Permissions\Commands\Permission\Give::class,
            ]);
        } else {
            BladeExtentions::register();
        }

        if(config('MabenDevPermissions.override_gate')) {
            Gate::before(function ($user, $ability) {
                if(method_exists($user, 'hasPermission')) {
                    return Auth::user()->hasPermission($ability) ?: null;
                }
                return true;
            });
        }
    }
}
