<?php

namespace Maben\Permissions\Middlewares;

use Closure;
use Maben\Permissions\Traits\HasPermissions;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $model, $permission, $requiresAll = TRUE)
    {
        if(!in_array(HasPermissions::class, class_uses_recursive($model))) {
            throw new \Exception($model::class . ' does not use ' . HasPermissions::class);
        }
        
        if(!is_array($permission)) {
            if(!$model->hasPermission($permission)) {
                abort(403, 'You do not have the required permission(s).');
            }
        }
        
        if($requiresAll) {
            if(!$model->hasAllPermissions($permission)) {
                abort(403, 'You do not have the required permission(s).');
            }
        } else {
            if(!$model->hasAnyPermission($permission)) {
                abort(403, 'You do not have the required permission(s).');
            }
        }
        
        return $next($request);
    }
}