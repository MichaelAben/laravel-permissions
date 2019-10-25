<?php


namespace MabenDev\Permissions\Models;

use Illuminate\Support\Collection;
use \MabenDev\Permissions\Traits\Permissionable;

/**
 * Class Role
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Role extends PermissionModel
{
    use Permissionable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return string
     */
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'roles';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleables()
    {
        return $this->hasMany(Roleable::class);
    }

    /**
     * @return Collection
     */
    public function items()
    {
        $collection = new Collection();
        foreach($this->roleables as $roleable) {
            $collection->add($roleable->getModel());
        }
        return $collection;
    }

    /**
     * @param  string  $name
     * @param  string  $description
     *
     * @return mixed
     */
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
