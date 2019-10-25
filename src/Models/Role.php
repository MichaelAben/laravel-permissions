<?php


namespace MabenDev\Permissions\Models;

use Illuminate\Support\Collection;
use \MabenDev\Permissions\Traits\Permissionable;

class Role extends PermissionModel
{
    use Permissionable;

    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'roles';
    }

    public function roleables()
    {
        return $this->hasMany(Roleable::class);
    }

    public function items()
    {
        $collection = new Collection();
        foreach($this->roleables as $roleable) {
            $collection->add($roleable->getModel());
        }
        return $collection;
    }
}
