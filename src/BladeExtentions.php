<?php


namespace MabenDev\Permissions;


use Illuminate\Support\Facades\Blade;

/**
 * Class BladeExtentions
 * @package MabenDev\Permissions
 *
 * @author Michael Aben
 */
class BladeExtentions
{
    /**
     *
     */
    public static function register()
    {
        Blade::if('hasPermission', function ($permission) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasPermission($permission);
        });

        Blade::if('hasAnyPermission', function ($permissions) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasAnyPermission($permissions);
        });

        Blade::if('hasAllPermissions', function ($permissions) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasAllPermissions($permissions);
        });

        Blade::if('hasPermissionIn', function ($permission) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasPermissionIn($permission);
        });

        Blade::if('hasRole', function ($role) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasRole($role);
        });

        Blade::if('hasAnyRole', function ($roles) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasAnyRole($roles);
        });

        Blade::if('hasAllRoles', function ($roles) {
            $user = config('MabenDevPermissions.user')();
            return $user->hasAllRoles($roles);
        });
    }
}
