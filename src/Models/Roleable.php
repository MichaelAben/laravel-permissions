<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;

class Roleable extends Model
{
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'roleable';
    }

    public function roles()
    {
        $this->belongsTo(Role::class);
    }

    public function getModel()
    {
        return $this->attributes['roleable_type']::find($this->attributes['roleable_id']);
    }
}
