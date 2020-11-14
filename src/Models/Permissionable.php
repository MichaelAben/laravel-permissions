<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class Permissionable
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Permissionable extends Model
{
    use QueryCacheable;

    public $cacheFor = 60*60*24; // in seconds
    protected static $flushCacheOnUpdate = true;

    /**
     * @return string
     */
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'permissionable';
    }

    /**
     *
     */
    public function permission()
    {
        $this->belongsTo(Permission::class);
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->attributes['permissionable_type']::find($this->attributes['permissionable_id']);
    }
}
