<?php


namespace MabenDev\Permissions\Models;

use Illuminate\Support\Collection;
use \MabenDev\Permissions\Traits\Permissionable;

class Role extends PermissionModel
{
    use Permissionable;
    
    protected $fillable = [
        'name',
        'description',
    ];

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

    public static function findOrCreate(string $name, string $description)
    {
        $newRole = Role::where('name', $name)->first();
        if(!empty($newRole)) return $newRole;

        $newRole = Role::create([
            'name' => $name,
            'description' => $description,
        ]);

        return $newRole;
    }
}
