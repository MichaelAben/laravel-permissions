<?php


namespace MabenDev\Permissions\Traits;


use MabenDev\Permissions\Models\Permission;

trait Permissionable
{
    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable', config('MabenDevPermissions.database.prefix') . 'permissionable');
    }

    public function hasPermission($permission)
    {
        $permission = $this->handleGivenPermission($permission);

        if($this->hasDirectPermission($permission)) return true;
        if($this->hasPermissionWildCard($permission)) return true;
        return false;
    }

    public function hasPermissionIn($permission)
    {
        $permission = $this->handleGivenPermission($permission);
        return $this->permissions()->where('permission', 'LIKE', $permission . '%')->exists();
    }

    public function hasAnyPermission(array $permissions)
    {
        foreach($permissions as $permission) {
            if($this->hasPermission($permission)) return true;
        }
        return false;
    }

    public function hasAllPermissions(array $permissions)
    {
        foreach($permissions as $permission) {
            if(!$this->hasPermission($permission)) return false;
        }
        return true;
    }

    public function hasDirectPermission($permission)
    {
        $permission = $this->handleGivenPermission($permission);

        if($this->permissions()->where('permission', $permission)->exists()) return true;
        return false;
    }

    public function hasPermissionWildCard($permission)
    {
        $permission = $this->handleGivenPermission($permission);

        if($this->hasDirectPermission('*')) return true;

        $permissionParts = explode('.', $permission);
        array_pop($permissionParts);
        $permissionBuilder = '';

        foreach($permissionParts as $part) {
            $permissionBuilder .= $part . '.';
            if($this->hasDirectPermission($permissionBuilder . '*')) return true;
        }

        return false;
    }

    protected function handleGivenPermission($permission)
    {
        if(!$permission instanceof Permission && !is_string($permission)) throw new \Exception('Given $permission must be string or instance of ' . Permission::class);
        if($permission instanceof Permission) $permission = $permission->getAttribute('permission');
        return strtolower($permission);
    }
}
