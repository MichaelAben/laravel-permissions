<?php


namespace MabenDev\Permissions\Traits;


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

    protected function handleGivenRole($role)
    {
        if(!$role instanceof Role && !is_string($role)) throw new \Exception('Given $role must be string or instance of ' . Role::class);
        if($role instanceof Role) $role = $role->getAttribute('name');
        return strtolower($role);
    }

    public function hasPermission($permission)
    {
        foreach($this->roles()->get() as $role) {
            if($role->hasPermission($permission)) return true;
        }
        return false;
    }
}
