<?php


namespace MabenDev\Permissions\Traits;


use Exception;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use MabenDev\Permissions\Models\Permission;

/**
 * Trait Permissionable
 * @package MabenDev\Permissions\Traits
 *
 * @author Michael Aben
 */
trait Permissionable
{
    /**
     * @return MorphToMany
     */
    public function permissions(): mixed
    {
        return $this->morphToMany(Permission::class, 'permissionable', config('MabenDevPermissions.database.prefix') . 'permissionable')->withTimestamps();
    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     * @throws Exception
     */
    public function givePermission(Permission|string $permission): bool
    {
        $tempPermission = $permission;
        if(!$permission instanceof Permission) $permission = Permission::where('permission', $tempPermission)->first();
        if(empty($permission)) throw new Exception('Could not find permission (' . $tempPermission . ')');

        if($this->hasPermission($tempPermission)) return true;

        return $this->permissions()->save($permission);

    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     * @throws Exception
     */
    public function hasPermission(Permission|string $permission): bool
    {
        $permission = $this->handleGivenPermission($permission);

        if($this->hasDirectPermission($permission)) return true;
        if($this->hasPermissionWildCard($permission)) return true;
        return false;
    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     * @throws Exception
     */
    public function hasPermissionIn(Permission|string $permission): bool
    {
        $permission = $this->handleGivenPermission($permission);
        if($this->hasPermissionWildCard($permission)) return true;
        return $this->permissions()->where('permission', 'LIKE', $permission . '%')->exists();
    }

    /**
     * @param  array  $permissions
     *
     * @return bool
     * @throws Exception
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach($permissions as $permission) {
            if($this->hasPermission($permission)) return true;
        }
        return false;
    }

    /**
     * @param  array  $permissions
     *
     * @return bool
     * @throws Exception
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach($permissions as $permission) {
            if(!$this->hasPermission($permission)) return false;
        }
        return true;
    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     * @throws Exception
     */
    protected function hasDirectPermission(Permission|string $permission): bool
    {
        $permission = $this->handleGivenPermission($permission);

        if($this->permissions()->where('permission', $permission)->exists()) return true;
        return false;
    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     * @throws Exception
     */
    protected function hasPermissionWildCard(Permission|string $permission): bool
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

    /**
     * @param Permission|string $permission
     *
     * @return string
     * @throws Exception
     */
    protected function handleGivenPermission(Permission|string $permission): string
    {
        if(!$permission instanceof Permission && !is_string($permission)) throw new Exception('Given $permission must be string or instance of ' . Permission::class);
        if($permission instanceof Permission) $permission = $permission->getAttribute('permission');
        return strtolower($permission);
    }
}
