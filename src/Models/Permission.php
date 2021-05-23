<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function getTable(): string
    {
        return config('MabenDevPermissions.database.prefix') . 'permissions';
    }

    /**
     * @return HasMany
     */
    public function permissionables(): HasMany
    {
        return $this->hasMany(Permissionable::class);
    }

    /**
     * @return Collection
     */
    public function items(): Collection
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
     * @return Permission
     */
    public static function findOrCreate(string $permission, string $description): Permission
    {
        $newPermission = Permission::where('permission', $permission)->first();
        if(!empty($newPermission)) return $newPermission;

        return Permission::create([
            'permission' => $permission,
            'description' => $description,
        ]);
    }
}
