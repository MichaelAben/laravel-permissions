<?php

namespace MabenDev\Permissions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @param  Closure $next
     * @param  string  $permission
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        if(!Auth::user()->hasPermission($permission)) {
            abort(403, 'You don\'t have the required permission');
        }

        return $next($request);
    }
}
