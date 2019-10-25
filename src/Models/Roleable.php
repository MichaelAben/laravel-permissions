<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Roleable
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Roleable extends Model
{
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
