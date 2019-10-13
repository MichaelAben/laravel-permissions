<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $permissionCheckExempt = [];

    protected static function boot()
    {
        parent::boot();

        $user = config('MabenDevPermissions.user')();

        self::creating(function ($model) use ($user) {
            $modelRC = new \ReflectionClass($model);
            if(in_array('store', $model->permissionCheckExempt)) return;
            if(!$user->hasPermission($modelRC->getShortName() . '.store')) abort(203, 'Insufficient permissions, cant create ' . get_class($model));
        });

        self::updating(function ($model) use ($user) {
            $modelRC = new \ReflectionClass($model);
            if(in_array('update', $model->permissionCheckExempt)) return;
            if(!$user->hasPermission($modelRC->getShortName() . '.update')) abort(203, 'Insufficient permissions, cant update ' . get_class($model));
        });

        self::deleting(function ($model) use ($user) {
            $modelRC = new \ReflectionClass($model);
            if(in_array('destroy', $model->permissionCheckExempt)) return;
            if(!$user->hasPermission($modelRC->getShortName() . '.destroy')) abort(203, 'Insufficient permissions, cant delete ' . get_class($model));
        });
    }
}
