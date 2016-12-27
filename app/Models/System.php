<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class System
 *
 * @package App\Models\Project
 * @mixin \Eloquent
 */
class System extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The errors that occurred in the operating system.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errors()
    {
        return $this->hasMany(Error::class);
    }

    /**
     * Get all of the system's mappings.
     */
    public function mappings()
    {
        return $this->morphMany(Mapping::class, 'mappable');
    }
}
