<?php

namespace App\Models\Traits;


trait HasManyCountRelation
{
    /**
     * Get relation that count rows of hasMany relation.
     *
     * @param $model
     * @param string $count Statement inside count() aggregate function.
     * @param null $foreignKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function hasManyCount($model, $count = '*', $foreignKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        return $this->hasOne($model)
            ->selectRaw("$foreignKey, count($count) as aggregate")
            ->groupBy($foreignKey);
    }

    /**
     * Wrap count relation to attribute.
     *
     * @param $relation
     * @return int
     */
    protected function hasManyCountAttribute($relation)
    {
        if (!$this->relationLoaded($relation)) {
            $this->load($relation);
        }

        $related = $this->getRelation($relation);
        return ($related) ? (int)$related->aggregate : 0;
    }
}