<?php

namespace Maben\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Maben\Permissions\Traits\HasPermissions;

/**
 * Class Role
 * @package Maben\Permissions\Models
 *
 * @author Michael Aben <m.aben@live.nl>
 */
class Role extends Model
{
    use HasPermissions;

    /**
     * @var array
     */
    protected $fillable = [
        'permission',
        'description',
    ];

    /**
     * @param string $model
     * @return MorphToMany
     */
    public function models(string $model): MorphToMany
    {
        return $this->morphedByMany(
            $model,
            'model',
            config('permissions.tables.model_roles'),
            'role_id',
            'model_id'
        );
    }
}
