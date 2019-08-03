<?php

namespace Maben\Permissions\Middlewares;

use Closure;
use Maben\Permissions\Traits\HasRoles;

class RolesMiddleware
{
    public function handle($request, Closure $next, $model, $roles, $requiresAll = TRUE)
    {
        if(!in_array(HasRoles::class, class_uses_recursive($model))) {
            throw new \Exception($model::class . ' does not use ' . HasRoles::class);
        }
        
        if(!is_array($roles)) {
            if(!$model->hasRole($roles)) {
                abort(403, 'You do not have the required role(s).');
            }
        }
        
        if($requiresAll) {
            if(!$model->hasAllroles($roles)) {
                abort(403, 'You do not have the required role(s).');
            }
        } else {
            if(!$model->hasAnyRole($roles)) {
                abort(403, 'You do not have the required role(s).');
            }
        }
        
        return $next($request);
    }
}