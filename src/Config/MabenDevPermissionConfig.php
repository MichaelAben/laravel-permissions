<?php

/**
 * This is the config file for the permissions package, all default values should work fine.
 *
 * @author Michael Aben <m.aben@live.nl>
 */

use App\User;

return [
    // Config for database tables
    'database' => [
        // Tables prefix, so no other package or table gets in the way
        // DO NOT CHANGE after you have run the migration!!!
        'prefix' => 'MabenDev_',
    ],

    // Automatically listen to model events? If user does not have the permission it will abort the request with 203
    // Models using this require to extend PermissionModel
    // If you don't want a model to use this don't let it extend PermissionModel
    'autoPermissionCheck' => true,

    // Models using permissions only and no roles
    // This is used to identify Permissionable models
    'permissionModels' => [],

    // Models using roles, and therefor use permissions
    // This is used to identify Roleable models
    'rolesModels' => [
        User::class,
    ],

    // User model, used to retrive a instance of the current logged in user (or any model that is uses permissions/roles)
    // If you use laravel default authentication you probably don't want to change this
    'user' => function () {
        return Illuminate\Support\Facades\Auth::user();
    },
];
