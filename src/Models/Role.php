<?php
namespace Maben\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Traits\HasPermissions;

/**
 * Class Role
 *
 * @package Maben\Permissions\Models
 *         
 * @author Michael Aben <m.aben@live.nl>
 */
class Role extends Model
{
    use HasPermissions;

    /**
     *
     * @var array
     */
    protected $fillable = [
        'role',
        'description'
    ];

    /**
     *
     * @param string $model
     * @return MorphToMany
     */
    public function models(string $model): MorphToMany
    {
        return $this->morphedByMany($model, 'model', config('permissions.tables.model_roles'), 'role_id', 'model_id');
    }

    public function givePermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            if (! $permission instanceof Permission) {
                throw new \Exception('$permissions array must contain only ' . Permission::class . ' models');
            }

            $this->permissions()->save($permission);
        }

        return true;
    }

    public function syncPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            if (! $permission instanceof Permission) {
                throw new \Exception('$permissions array must contain only ' . Permission::class . ' models');
            }
        }
        $this->permissions()->sync($permissions);
        $this->trowAwayPermissionsCache();

        return true;
    }
}
