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
        // DO NOT CHANGE after you have run the migration!!!
        'prefix' => 'MabenDev_',
    ],
    // This will override the gate of laravel, when using the can methods it will return Response::allow or deny
    'override_gates' => true,
];
