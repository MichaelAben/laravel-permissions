<?php

namespace Maben\Permissions;

use Illuminate\Support\ServiceProvider;

class PermissionsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        dd('yay');
    }
}
