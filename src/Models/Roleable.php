<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class Roleable
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Roleable extends Model
{
    use QueryCacheable;

    public $cacheFor = 60*60*24; // in seconds
    protected static $flushCacheOnUpdate = true;

    /**
     * @return string
     */
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'roleable';
    }

    /**
     *
     */
    public function roles()
    {
        $this->belongsTo(Role::class);
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->attributes['roleable_type']::find($this->attributes['roleable_id']);
    }
}
