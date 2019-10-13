<?php

/**
 * This is the config file for the permissions package, all default values should work fine.
 *
 * @author Michael Aben <m.aben@live.nl>
 */

return [
    // Config for database tables
    'database' => [
        // Tables prefix, so no other package or table gets in the way
        'prefix' => 'MabenDev_',
    ],

    // Automatically listen to model events?
    'autoPermissionCheck' => true,

    // User model
    'user' => function () {
        return Illuminate\Support\Facades\Auth::user();
    },
];
