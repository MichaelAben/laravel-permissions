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
            $collection->add($permissionable->getModel());
        }
        return $collection;
    }

    public static function findOrCreate(string $permission, string $description)
    {
        $newPermission = Permission::where('permission', $permission)->first();
        if(!empty($newPermission)) return $newPermission;

        $newPermission = Permission::create([
            'permission' => $permission,
            'description' => $description,
        ]);

        return $newPermission;
    }
}
