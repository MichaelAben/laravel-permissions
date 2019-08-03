<?php

namespace Maben\Permissions\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Models\Role;

/**
 * Trait HasRoles
 *
 * Use this trait if you want a model to have roles, a role always has permissions to it!
 *
 * @package Maben\Permissions\Traits
 *
 * @author Michael Aben <m.aben@live.nl>
 */
trait HasRoles
{
    use HasPermissions;

    /**
     * Delete all roles of deleting model
     */
    public static function bootHasPRoles(): void
    {
        static::deleting(function ($model) {
            // Detach any roles from the model that is gona be deleted
            $model->roles()->detach();
        });
    }

    /**
     * @return MorphToMany
     */
    public function roles(): MorphToMany
    {
        // We let elequent know how the roles are linked to a model
        // We use the config file to know wich table has the roles
        return $this->morphToMany(
            Role::class,
            'model',
            config('permissions.tables.model_roles'),
            'model_id',
            'role_id'
        );
    }

    /**
     * Check if model has a given role
     * 
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        
        // If you gave a role model, we want to take the ID
        if($role instanceof Role) {
            $role = $role->id;
        }
        
        // If given role is a int we gona search for it's ID
        if(is_int($role)) {
            // if model has role linked to it we return true
            if(!$this->roles()->where('id', '=', $role)->get()->isEmpty()) {
                return true;
            }
            return false;
        }

        // if given role is a string we gona search for this
        if(is_string($role)) {
            // If model has role linked to it we return true
            if(!$this->roles()->where('role', '=', $role)->get()->isEmpty()) {
                return true;
            }
        }

        // Role is given incorrectly and could not be checked
        return false;
    }

    /**
     * Check if model has all given roles
     *
     * Array can contain Role objects, integers and strings
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles): bool
    {
        // For each role in array we call hasRole function, if it returns false, we return false
        foreach($roles as $role) {
            if(!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if model has any of given roles
     *
     * Array can contain Role objects, integers and strings
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        // For each role in array we call hasRole function, if it returns true, we return true
        foreach($roles as $role) {
            if($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}