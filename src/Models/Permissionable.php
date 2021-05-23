<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Permissionable
 * @package MabenDev\Permissions\Models
 *
 * @author Michael Aben
 */
class Permissionable extends Model
{
    /**
     * @return string
     */
    public function getTable(): string
    {
        return config('MabenDevPermissions.database.prefix') . 'permissionable';
    }

    /**
     * @return BelongsTo
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * @return mixed
     */
    public function getModel(): mixed
    {
        return $this->attributes['permissionable_type']::find($this->attributes['permissionable_id']);
    }
}
