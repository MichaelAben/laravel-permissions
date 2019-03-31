<?php

namespace Maben\Permissions\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Models\Permission;
use Maben\Permissions\Models\Role;

/**
 * Trait HasPermissions
 *
 * Use this trait if you want a model to have only permissions
 *
 * @package Maben\Permissions\Traits
 *
 * @author Michael Aben <m.aben@live.nl>
 */
trait HasPermissions
{
    /**
     * @var Collection
     */
    protected $allPermissions;

    /**
     * Delete all permissions of deleting model
     */
    public static function bootHasPermissions(): void
    {
        static::deleting(function ($model) {
            $model->permissions()->detach();
        });
    }

    /**
     * @return MorphToMany
     */
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(
            Permission::class,
            'model',
            config('permissions.tables.model_permissions'),
            'model_id',
            'permission_id'
        );
    }

    /**
     * Get all permissions of model by it's roles
     *
     * @return Collection
     */
    protected function getPermissionsViaRoles(): Collection
    {
        $permissions = new Collection();

        if(!method_exists($this, 'roles')) {
            return $permissions;
        }

        /** @var Role $role */
        foreach($this->load('roles')->roles()->get() as $role) {
            $permissions = $permissions->merge($role->permissions()->get());
        }

        return $permissions;
    }

    /**
     * Get all permissions of this model
     *
     * @return Collection
     */
    public function getAllPermissions(): Collection
    {
        if(is_null($this->allPermissions)) {
            $permissions = $this->permissions()->get();
            $this->allPermissions = $permissions->merge($this->getPermissionsViaRoles());
        }
        return $this->allPermissions;
    }

    /**
     * Check if model has a permission
     *
     * @param Permission|int|string $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if($permission instanceof Permission) {
            $permission = $permission->id;
        }

        if(is_int($permission)) {
            if (is_int($permission) && !$this->getAllPermissions()->where('id', '=', $permission)->isEmpty()) {
                return true;
            }
            return false;
        }

        if(is_string($permission)) {
            if(!$this->getAllPermissions()->where('permission', '=', $permission)->isEmpty()) return true;

            $permissionParts = explode('.', $permission);
            if (empty($permissionParts)) return false;

            $permissionPartBuild = '';
            foreach ($permissionParts as $permissionPart) {
                if (!empty($permissionPart) && !$this->getAllPermissions()->where('permission', '=', $permissionPartBuild . '*')->isEmpty()) {
                    return true;
                }
                $permissionPartBuild .= $permissionPart . '.';
            }
        }

        return false;
    }

    /**
     * Check if model has all given permissions
     *
     * Array can contain Permission objects, integers and strings
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach($permissions as $permission) {
            if(!$this->hasPermission($permission)) return false;
        }

        return true;
    }

    /**
     * Check if model has any of given permissions
     *
     * Array can contain Permission objects, integers and strings
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach($permissions as $permission) {
            if($this->hasPermission($permission)) return true;
        }

        return false;
    }
}