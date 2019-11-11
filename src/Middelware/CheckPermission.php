<?php

namespace MabenDev\Permissions\Middleware;

use Closure;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MabenDev\Permissions\Models\Permission;

/**
 * Class CheckPermission
 * @package MabenDev\Permissions\Middleware
 *
 * @author Michael Aben <michael@atention.nl>
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $permission)
    {
        if(!Permission::where('permission', $permission)->exists()) {
            $permissionModel = Permission::create([
                'permission' => $permission,
                'description' => 'This permission was missing in the table and is generated automaticly.',
            ]);
        }

        if(!Auth::user()->hasPermission($permission)) {
            abort(403, 'You don\'t have the required permission');
        }

        return $next($request);
    }
}
