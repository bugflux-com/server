<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','red', 'green', 'blue', 'project_id'];

    /**
     * The project for given tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Errors assigned to the tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function errors()
    {
        return $this->belongsToMany(Error::class);
    }

    /**
     * Convert RGB to HEX format (include hash symbol).
     *
     * @return string
     */
    public function getHexAttribute()
    {
        return sprintf('#%02x%02x%02x', $this->red, $this->green, $this->blue);
    }
}
