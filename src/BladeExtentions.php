<?php


namespace MabenDev\Permissions;


use Illuminate\Support\Facades\Auth;
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
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasPermission($permission);
        });

        Blade::if('hasAnyPermission', function ($permissions) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasAnyPermission($permissions);
        });

        Blade::if('hasAllPermissions', function ($permissions) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasAllPermissions($permissions);
        });

        Blade::if('hasPermissionIn', function ($permission) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasPermissionIn($permission);
        });

        Blade::if('hasRole', function ($role) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasRole($role);
        });

        Blade::if('hasAnyRole', function ($roles) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasAnyRole($roles);
        });

        Blade::if('hasAllRoles', function ($roles) {
            if(!Auth::check()) return false;
            $user = config('MabenDevPermissions.user')();
            return $user->hasAllRoles($roles);
        });
    }
}
