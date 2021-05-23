<?php


namespace MabenDev\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MabenDev\Permissions\Traits\Permissionable;

/**
 * Class Role
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Role extends Model
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
    public function getTable(): string
    {
        return config('MabenDevPermissions.database.prefix').'roles';
    }

    /**
     * @return HasMany
     */
    public function roleables(): HasMany
    {
        return $this->hasMany(Roleable::class);
    }

    /**
     * @return Collection
     */
    public function items(): Collection
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
     * @return Role
     */
    public static function findOrCreate(string $name, string $description): Role
    {
        $newRole = Role::where('name', $name)->first();
        if (!empty($newRole)) {
            return $newRole;
        }

        return Role::create([
            'name' => $name,
            'description' => $description,
        ]);
    }
}
