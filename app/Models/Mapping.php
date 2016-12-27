<?php

namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\System;
use App\Models\Language;

class Mapping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'mappable_type', 'mappable_id', 'project_id', 'is_default'
    ];

    /**
     * Get model instance from class name and value.
     *
     * @param $class (eq. \App\Models\Project::class)
     * @param $value
     * @return mixed
     */
    public static function resolve($class, $value, $column)
    {
        $basicSearch = $class::where($column, $value)->first();
        if (!empty($basicSearch)) {
            return $basicSearch;
        }
        $mappable_id = Mapping::where('value', $value)
            ->where('mappable_type', strtolower(class_basename($class)))        // assuming mappable types are only one-word
            ->firstOrFail(['mappable_id'])
            ->mappable_id;

        return $class::findOrFail($mappable_id);
    }

    /**
     * The project that this mapping refers to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all of the owning mappable models.
     */
    public function mappable()
    {
        return $this->morphTo();
    }

}
