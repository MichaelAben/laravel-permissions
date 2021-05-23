<?php


namespace MabenDev\Permissions\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rennokki\QueryCache\Traits\QueryCacheable;

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
    public function getTable(): string
    {
        return config('MabenDevPermissions.database.prefix') . 'roleable';
    }

    /**
     * @return BelongsTo
     */
    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return mixed
     */
    public function getModel(): mixed
    {
        return $this->attributes['roleable_type']::find($this->attributes['roleable_id']);
    }
}
