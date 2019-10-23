<?php


namespace MabenDev\Permissions\Traits;


use MabenDev\Permissions\Models\Permission;
use MabenDev\Permissions\Models\Role;

trait Roleable
{
    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable', config('MabenDevPermissions.database.prefix') . 'roleable');
    }

    public function hasRole($role)
    {
        $role = $this->handleGivenRole($role);

        if($this->roles()->where('name', $role)->exists()) return true;
        return false;
    }

    public function hasAnyRole(array $roles)
    {
        foreach($roles as $role) {
            $role = $this->handleGivenRole($role);
            if ($this->hasRole($role)) return true;
        }
        return false;
    }

    public function hasAllRoles(array $roles)
    {
        foreach($roles as $role) {
            $role = $this->handleGivenRole($role);
            if (!$this->hasRole($role)) return false;
        }
        return true;
    }

    protected function handleGivenRole($role)
    {
        if(!$role instanceof Role && !is_string($role)) throw new \Exception('Given $role must be string or instance of ' . Role::class);
        if($role instanceof Role) $role = $role->getAttribute('name');
        return strtolower($role);
    }

    public function hasPermission($permission)
    {
        foreach($this->roles as $role) {
            if($role->hasPermission($permission)) return true;
        }
        return false;
    }

    public function hasPermissionIn($permission)
    {
        foreach($this->roles as $role) {
            if($role->hasPermissionIn($permission)) return true;
        }
        return false;
    }

    public function hasAnyPermission(array $permissions)
    {
        foreach($this->roles as $role) {
            if($role->hasAnyPermission($permissions)) return true;
        }
        return false;
    }

    public function hasAllPermissions(array $permissions)
    {
        $permissionsCheck = [];
        foreach($permissions as $permission) {
            if($permission instanceof Permission) $permission = $permission->getAttribute('permission');
            $check = false;
            foreach($this->roles as $role) {
                if($role->hasPermission($permission)) {
                    $check = true;
                    break;
                }
            }
            $permissionsCheck[$permission] = $check;
        }

        return !in_array(false, $permissionsCheck);
    }
}
