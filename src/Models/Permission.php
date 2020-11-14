<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class Permission
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Permission extends Model
{
    use QueryCacheable;

    public $cacheFor = 60*60*24; // in seconds
    protected static $flushCacheOnUpdate = true;

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
