<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Version
 *
 * @package App\Models\Project
 * @mixin \Eloquent
 */
class Version extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'project_id',
    ];
    
    /**
     * The reports that occurred in the apps in this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * The projects released in the this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
