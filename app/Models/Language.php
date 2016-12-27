<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 *
 * @package App\Models\Project
 * @mixin \Eloquent
 */
class Language extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];
    
    /**
     * The reports that occurred in the app in this language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get all of the language's mappings.
     */
    public function mappings()
    {
        return $this->morphMany(Mapping::class, 'mappable');
    }
}
