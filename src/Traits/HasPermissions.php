<?php
namespace Maben\Permissions\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Models\Permission;

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
     *
     * @var Collection
     */
    protected $allPermissions;

    /**
     * Delete all permissions of deleting model
     */
    public static function bootHasPermissions(): void
    {
        static::deleting(function ($model) {
            // Detach any permission from the model that is gona be deleted
            $model->permissions()->detach();
        });
    }

    /**
     *
     * @return MorphToMany
     */
    public function permissions(): MorphToMany
    {
        // We let elequent know how the permissions are linked to a model
        // We use the config file to know wich table has the permissions
        return $this->morphToMany(Permission::class, 'model', config('permissions.tables.model_permissions'), 'model_id', 'permission_id');
    }

    /**
     * Get all permissions of model by it's roles
     *
     * @return Collection
     */
    protected function getPermissionsViaRoles(): Collection
    {
        $permissions = new Collection();

        // If model has no roles, just return empty collection
        if (! method_exists($this, 'roles')) {
            return $permissions;
        }

        // If it has roles, loop roles and merge permissions collection into ours
        /** @var Role $role */
        foreach ($this->load('roles')
            ->roles()
            ->get() as $role) {
            $permissions = $permissions->merge($role->permissions()
                ->get());
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
        /**
         * If not loaded in already
         *
         * We do this so we don't load permissions all the time, it's like a cash
         */
        if (is_null($this->allPermissions)) {
            // Get own permissions
            $permissions = $this->permissions()->get();
            // Get role permissions and merge them into our own collection
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
        // If you gave a permission model, we want to take the ID
        if ($permission instanceof Permission) {
            $permission = $permission->id;
        }

        // If given permission is a int we gona search for it's ID
        if (is_int($permission)) {
            // If permission is found
            if (! $this->getAllPermissions()
                ->where('id', '=', $permission)
                ->isEmpty()) {
                return true;
            }
            return false;
        }

        // If permission is a string we need to search for that string
        if (is_string($permission)) {
            // If we have the exact permissions return true
            if (! $this->getAllPermissions()
                ->where('permission', '=', $permission)
                ->isEmpty())
                return true;

            // The direct permission is not found, let's check for wildcards
            // Explode permissions into it's part components
            $permissionParts = explode('.', $permission);
            // if no permissions component found, in instance of a single domain permission check we return false, cause it can't have a wildcard
            if (empty($permissionParts))
                return false;

            // A cash string to build our to check permission in
            $permissionPartBuild = '';

            // For each permission component
            foreach ($permissionParts as $permissionPart) {
                // We check if current build permission has a wildcard
                // First loop checks for '*' and nothing more, so if the model we check on has a wildcard on everything it returns true
                if (! empty($permissionPart) && ! $this->getAllPermissions()
                    ->where('permission', '=', $permissionPartBuild . '*')
                    ->isEmpty()) {
                    return true;
                }
                // Add next permission component to our to check permission variable
                $permissionPartBuild .= $permissionPart . '.';
            }
        }

        // Permission is given incorrectly and could not be checked
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
        // For each permission in array we call hasPermission function, if it returns false, we return false
        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission))
                return false;
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
        // For each permission in array we call hasPermission function, if it returns true, we return true
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }

        return false;
    }

    /**
     * Throw away the permissions cache
     */
    public function trowAwayPermissionsCache()
    {
        $this->allPermissions = null;
    }
}