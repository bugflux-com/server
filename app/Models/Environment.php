<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Environment
 *
 * @package App\Models\Project
 * @mixin \Eloquent
 */
class Environment extends Model
{
    /**
     * The errors that occurred in the environment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errors()
    {
        return $this->hasMany(Error::class);
    }
}
