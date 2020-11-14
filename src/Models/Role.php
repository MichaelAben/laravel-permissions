<?php


namespace MabenDev\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use \MabenDev\Permissions\Traits\Permissionable;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class Role
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Role extends Model
{
    use Permissionable,
        QueryCacheable;

    public $cacheFor = 60*60*24; // in seconds
    protected static $flushCacheOnUpdate = true;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Role constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::deleting(function (Role $role) {
            /** @var Roleable $roleable */
            foreach($role->roleables as $roleable) {
                $roleable->delete();
            }
            $role->permissions()->detach();
        });
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix').'roles';
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
        foreach ($this->roleables as $roleable) {
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
        if (!empty($newRole)) {
            return $newRole;
        }

        $newRole = Role::create([
            'name' => $name,
            'description' => $description,
        ]);

        return $newRole;
    }
}
