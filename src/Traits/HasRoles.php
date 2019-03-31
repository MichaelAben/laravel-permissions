<?php

namespace Maben\Permissions\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Models\Role;

/**
 * Trait HasRoles
 *
 * Use this trait if you want a model to have roles and permissions
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
            $model->roles()->detach();
        });
    }

    /**
     * @return MorphToMany
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permissions.tables.model_roles'),
            'model_id',
            'role_id'
        );
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if($role instanceof Role) {
            $role = $role->id;
        }

        if(is_int($role)) {
            if(!$this->roles()->where('id', '=', $role)->get()->isEmpty()) {
                return true;
            }
            return false;
        }

        if(is_string($role)) {
            if(!$this->roles()->where('role', '=', $role)->get()->isEmpty()) {
                return true;
            }
        }

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
        foreach($roles as $role) {
            if($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}