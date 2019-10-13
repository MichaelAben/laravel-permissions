<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Support\Collection;

class Permission extends PermissionModel
{
    protected $fillable = [
        'permission',
        'description',
    ];

    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'permissions';
    }

    public function permissionables()
    {
        return $this->hasMany(Permissionable::class);
    }

    public function items()
    {
        $collection = new Collection();
        foreach($this->permissionables as $permissionable) {
            $collection->add($permissionable->getObject());
        }
        return $collection;
    }
}
