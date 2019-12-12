<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Permission
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
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
     * Permission constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::deleting(function (Permission $permission) {
            /** @var Permissionable $permissionable */
            foreach($permission->permissionables as $permissionable) {
                $permissionable->delete();
            }
        });
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'permissions';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissionables()
    {
        return $this->hasMany(Permissionable::class);
    }

    /**
     * @return Collection
     */
    public function items()
    {
        $collection = new Collection();
        foreach($this->permissionables as $permissionable) {
            $collection->add($permissionable->getModel());
        }
        return $collection;
    }

    /**
     * @param  string  $permission
     * @param  string  $description
     *
     * @return mixed
     */
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
