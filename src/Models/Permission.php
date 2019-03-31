<?php

namespace Maben\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Permission
 * @package Maben\Permissions\Models
 *
 * @author Michael Aben <m.aben@live.nl>
 */
class Permission extends Model
{
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
            config('permissions.tables.model_permissions'),
            'permission_id',
            'model_id'
        );
    }
}
