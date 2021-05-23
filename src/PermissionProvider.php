<?php

namespace MabenDev\Permissions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MabenDev\Permissions\Commands\Permission\Give;
use MabenDev\Permissions\Commands\Permission\Make;
use MabenDev\Permissions\Middleware\CheckPermission;
use MabenDev\Permissions\Models\Permission;

/**
 * Class PermissionProvider
 * @package MabenDev\Permissions
 *
 * @author Michael Aben
 */
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
        ], 'config');

        $this->loadMigrationsFrom( __DIR__.'/Migrations/');

        $this->app['router']->aliasMiddleware('CheckPermission', CheckPermission::class);

        if($this->app->runningInConsole()) {
            $this->commands([
                \MabenDev\Permissions\Commands\Role\Make::class,
                \MabenDev\Permissions\Commands\Role\Give::class,
                Make::class,
                Give::class,
            ]);
        } else {
            BladeExtensions::register();
        }

        if(config('MabenDevPermissions.override_gate')) {
            Gate::before(function (Authenticatable|null $user, Permission|string $ability) {
                if(method_exists($user, 'hasPermission')) {
                    return $user->hasPermission($ability) ?: null;
                }
                return true;
            });
        }
    }
}
