<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;

class Permissionable extends Model
{
    public function getTable()
    {
        return config('MabenDevPermissions.database.prefix') . 'permissionable';
    }

    public function permission()
    {
        $this->belongsTo(Permission::class);
    }

    public function getModel()
    {
        return $this->attributes['permissionable_type']::find($this->attributes['permissionable_id']);
    }
}
