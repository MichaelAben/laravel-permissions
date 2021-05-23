<?php


namespace MabenDev\Permissions\Traits;


use Exception;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use MabenDev\Permissions\Models\Permission;
use MabenDev\Permissions\Models\Role;

/**
 * Trait Roleable
 * @package MabenDev\Permissions\Traits
 *
 * @author Michael Aben
 */
trait Roleable
{
    /**
     * @return MorphToMany
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'roleable', config('MabenDevPermissions.database.prefix') . 'roleable')->withTimestamps();
    }

    /**
     * @param $role
     *
     * @return bool
     * @throws Exception
     */
    public function giveRole($role): bool
    {
        $tempRole = $role;
        if(!$role instanceof Role) $role = Role::where('name', $tempRole)->first();
        if(empty($role)) throw new Exception('Could not find role (' . $tempRole . ')');

        if($this->hasRole($tempRole)) return true;

        return $this->roles()->save($role);
    }

    /**
     * @param $role
     *
     * @return bool
     * @throws Exception
     */
    public function hasRole($role): bool
    {
        $role = $this->handleGivenRole($role);

        if($this->roles()->where('name', $role)->exists()) return true;
        return false;
    }

    /**
     * @param  array  $roles
     *
     * @return bool
     * @throws Exception
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach($roles as $role) {
            $role = $this->handleGivenRole($role);
            if ($this->hasRole($role)) return true;
        }
        return false;
    }

    /**
     * @param  array  $roles
     *
     * @return bool
     * @throws Exception
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach($roles as $role) {
            $role = $this->handleGivenRole($role);
            if (!$this->hasRole($role)) return false;
        }
        return true;
    }

    /**
     * @param $role
     *
     * @return string
     * @throws Exception
     */
    protected function handleGivenRole($role): string
    {
        if(!$role instanceof Role && !is_string($role)) throw new Exception('Given $role must be string or instance of ' . Role::class);
        if($role instanceof Role) $role = $role->getAttribute('name');
        return strtolower($role);
    }

    /**
     * @param $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        foreach($this->roles as $role) {
            if($role->hasPermission($permission)) return true;
        }
        return false;
    }

    /**
     * @param $permission
     *
     * @return bool
     */
    public function hasPermissionIn($permission): bool
    {
        foreach($this->roles as $role) {
            if($role->hasPermissionIn($permission)) return true;
        }
        return false;
    }

    /**
     * @param  array  $permissions
     *
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach($this->roles as $role) {
            if($role->hasAnyPermission($permissions)) return true;
        }
        return false;
    }

    /**
     * @param  array  $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
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
